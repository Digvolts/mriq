<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
   public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function generateMidtransOrderId(string $invoiceNumber): string
    {
        return $invoiceNumber . '-PAY-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));
    }

    public function createSnapTransaction(
        Order $order,
        ?string $midtransOrderId = null,
        ?string $preferredMethod = null
    ): array {
        $midtransOrderId = $midtransOrderId ?: $this->generateMidtransOrderId($order->invoice_number);

        $items = $order->items->map(function ($item) {
            return [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => Str::limit(
                    $item->product_name . ' (' . strtoupper($item->size) . ')',
                    50,
                    ''
                ),
            ];
        })->toArray();

        $payload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->email,
                'phone' => $order->phone,
            ],
            'callbacks' => [
                'finish' => route('order.show', $order->invoice_number),
                'pending' => route('order.show', $order->invoice_number),
                'error' => route('order.show', $order->invoice_number),
            ],
        ];

        $enabledPayments = $this->mapPreferredMethodToEnabledPayments($preferredMethod);

        if (!empty($enabledPayments)) {
            $payload['enabled_payments'] = $enabledPayments;
        }

        $snapToken = Snap::getSnapToken($payload);
\Log::info('Snap payload debug', [
    'invoice' => $order->invoice_number,
    'preferred_method' => $preferredMethod,
    'enabled_payments' => $payload['enabled_payments'] ?? null,
]);
        return [
            'transaction_id' => $midtransOrderId,
            'snap_token' => $snapToken,
            'token' => $snapToken,
            'enabled_payments' => $enabledPayments,
        ];
    }

protected function mapPreferredMethodToEnabledPayments(?string $preferredMethod): array
{
    return match ($preferredMethod) {
        'bank_transfer' => [
            'bca_va',
            'bni_va',
            'bri_va',
            'permata_va',
            'echannel',
            'other_va',
        ],
        'ewallet' => [
            'gopay',
            'shopeepay',
        ],
        'card' => [
            'credit_card',
        ],
        default => [],
    };
}

    public function getTransactionStatus(string $transactionId): object
    {
        return Transaction::status($transactionId);
    }

    public function getTransactionDetails(string $transactionId): ?object
    {
        try {
            $status = $this->getTransactionStatus($transactionId);

            return (object) [
                'id' => $transactionId,
                'status' => $status->transaction_status ?? 'unknown',
                'payment_type' => $status->payment_type ?? null,
                'gross_amount' => $status->gross_amount ?? 0,
                'settlement_time' => $status->settlement_time ?? null,
                'fraud_status' => $status->fraud_status ?? 'unknown',
                'raw' => (array) $status,
                'is_settled' => in_array($status->transaction_status ?? null, ['settlement', 'capture']),
                'is_expired' => ($status->transaction_status ?? null) === 'expire',
                'is_cancelled' => in_array($status->transaction_status ?? null, ['cancel', 'deny']),
                'is_pending' => ($status->transaction_status ?? null) === 'pending',
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting transaction details: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
            ]);

            return null;
        }
    }

    public function validateSignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return ($payload['signature_key'] ?? '') === $expected;
    }

    public function processNotification(array $payload): array
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                return ['status' => 'challenge', 'message' => 'Transaction challenged'];
            }

            return ['status' => 'success', 'message' => 'Payment success'];
        }

        if ($transactionStatus === 'settlement') {
            return ['status' => 'success', 'message' => 'Payment settled'];
        }

        if ($transactionStatus === 'pending') {
            return ['status' => 'pending', 'message' => 'Waiting for payment'];
        }

        if ($transactionStatus === 'deny') {
            return ['status' => 'failed', 'message' => 'Payment denied'];
        }

        if ($transactionStatus === 'expire') {
            return ['status' => 'expired', 'message' => 'Payment expired'];
        }

        if ($transactionStatus === 'cancel') {
            return ['status' => 'cancelled', 'message' => 'Payment cancelled'];
        }

        return ['status' => 'unknown', 'message' => 'Unknown transaction status'];
    }

    public function cancelTransaction(string $transactionId): bool
    {
        try {
            Transaction::cancel($transactionId);
            return true;
        } catch (\Exception $e) {
            \Log::warning('Cancel transaction failed: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
            ]);
            return false;
        }
    }

    public function cleanupTransaction(string $transactionId): bool
    {
        try {
            $status = $this->getTransactionStatus($transactionId);
            $currentStatus = $status->transaction_status ?? 'unknown';

            if ($currentStatus === 'pending') {
                return $this->cancelTransaction($transactionId);
            }

            return false;
        } catch (\Exception $e) {
            \Log::warning('Cleanup transaction failed: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
            ]);
            return false;
        }
    }
}