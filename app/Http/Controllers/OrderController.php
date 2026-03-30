<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderPayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
     public function trackPage()
    {
        return view('order.track');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:50',
            'email' => 'required|email|max:100',
        ]);

        $order = Order::where('invoice_number', $validated['invoice_number'])
            ->where('email', $validated['email'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        return redirect()->route('order.show', $order->invoice_number);
    }

public function show($invoiceNumber)
{
    $order = Order::with(['items', 'payments'])
        ->where('invoice_number', $invoiceNumber)
        ->firstOrFail();

    try {
        if ($order->transaction_id) {
            $midtrans = new MidtransService();
            $transactionDetails = $midtrans->getTransactionDetails($order->transaction_id);

            if ($transactionDetails) {
                if (in_array($transactionDetails->status, ['settlement', 'capture'])) {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_type' => $transactionDetails->payment_type ?? $order->payment_type,
                        'status' => 'processing',
                        'paid_at' => $order->paid_at ?? now(),
                    ]);
                } elseif ($transactionDetails->status === 'pending') {
                    $order->update([
                        'payment_status' => 'pending',
                        'payment_type' => $transactionDetails->payment_type ?? $order->payment_type,
                    ]);
                } elseif ($transactionDetails->status === 'expire') {
                    $order->update([
                        'payment_status' => 'expired',
                    ]);
                } elseif ($transactionDetails->status === 'cancel') {
                    $order->update([
                        'payment_status' => 'cancelled',
                        'status' => 'cancelled',
                    ]);
                } elseif ($transactionDetails->status === 'deny') {
                    $order->update([
                        'payment_status' => 'failed',
                    ]);
                }

                $latestPayment = $order->payments()
                    ->where('transaction_id', $order->transaction_id)
                    ->latest('id')
                    ->first();

                if ($latestPayment) {
                    $latestPayment->update([
                        'payment_type' => $transactionDetails->payment_type ?? $latestPayment->payment_type,
                        'midtrans_response' => $transactionDetails->raw ?? null,
                    ]);
                }
            }
        }
    } catch (\Exception $e) {
        \Log::warning('Failed to sync Midtrans status', [
            'invoice' => $invoiceNumber,
            'message' => $e->getMessage(),
        ]);
    }

    $order->refresh();

    return view('order.show', compact('order'));
}

    public function refreshPayment(Request $request, $invoiceNumber)
    {
        $validated = $request->validate([
            'preferred_method' => 'nullable|in:bank_transfer,ewallet,qris,card',
        ]);

        try {
            $order = Order::where('invoice_number', $invoiceNumber)
                ->where('payment_status', 'pending')
                ->with(['items', 'payments'])
                ->firstOrFail();

            $midtransService = new MidtransService();

            if ($order->transaction_id) {
                $midtransService->cleanupTransaction($order->transaction_id);

                $oldPayment = $order->payments()
                    ->where('transaction_id', $order->transaction_id)
                    ->latest('id')
                    ->first();

                if ($oldPayment && $oldPayment->status === 'pending') {
                    $oldPayment->update([
                        'status' => 'failed',
                    ]);
                }
            }

            $transaction = $midtransService->createSnapTransaction(
                $order,
                null,
                $validated['preferred_method'] ?? null
            );

            $order->update([
                'snap_token' => $transaction['snap_token'],
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'paid_at' => null,
            ]);

            OrderPayment::create([
                'order_id' => $order->id,
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'status' => 'pending',
                'amount' => $order->total_price,
                'midtrans_response' => [
                    'preferred_method' => $validated['preferred_method'] ?? null,
                    'enabled_payments' => $transaction['enabled_payments'] ?? [],
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pilihan pembayaran berhasil diperbarui.',
                'snap_token' => $transaction['snap_token'],
                'preferred_method' => $validated['preferred_method'] ?? null,
                'enabled_payments' => $transaction['enabled_payments'] ?? [],
            ]);
        } catch (\Exception $e) {
            \Log::error('Refresh payment error', [
                'invoice' => $invoiceNumber,
                'preferred_method' => $request->input('preferred_method'),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui pembayaran.',
            ], 500);
        }
    }

    public function regeneratePayment(Request $request, $invoiceNumber)
    {
        $validated = $request->validate([
            'preferred_method' => 'nullable|in:bank_transfer,ewallet,qris,card',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::where('invoice_number', $invoiceNumber)
                ->whereIn('payment_status', ['expired', 'cancelled', 'failed'])
                ->with(['items', 'payments'])
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($order->items as $item) {
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Produk {$item->product_name} tidak ditemukan.");
                }

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok {$item->product_name} tidak cukup untuk pembayaran ulang.");
                }
            }

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->decrement('stock', $item->quantity);
            }

            $midtransService = new MidtransService();

            $transaction = $midtransService->createSnapTransaction(
                $order,
                null,
                $validated['preferred_method'] ?? null
            );

            $order->update([
                'payment_status' => 'pending',
                'status' => 'pending',
                'payment_type' => null,
                'snap_token' => $transaction['snap_token'],
                'transaction_id' => $transaction['transaction_id'],
                'paid_at' => null,
                'stock_restored_at' => null,
            ]);

            OrderPayment::create([
                'order_id' => $order->id,
                'transaction_id' => $transaction['transaction_id'],
                'payment_type' => null,
                'status' => 'pending',
                'amount' => $order->total_price,
                'midtrans_response' => [
                    'preferred_method' => $validated['preferred_method'] ?? null,
                    'enabled_payments' => $transaction['enabled_payments'] ?? [],
                ],
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil dibuat ulang.',
                'snap_token' => $transaction['snap_token'],
                'redirect' => route('order.show', $order->invoice_number),
                'preferred_method' => $validated['preferred_method'] ?? null,
                'enabled_payments' => $transaction['enabled_payments'] ?? [],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Regenerate payment error', [
                'invoice' => $invoiceNumber,
                'preferred_method' => $request->input('preferred_method'),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

public function handleNotification(Request $request)
{
    $payload = $request->all();

    DB::beginTransaction();

    try {
        $midtrans = new MidtransService();

        if (!$midtrans->validateSignature($payload)) {
            DB::rollBack();
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionId = $payload['order_id'];
        $status = $payload['transaction_status'];
        $paymentType = $payload['payment_type'] ?? null;

        $order = Order::with(['items'])
            ->where('transaction_id', $transactionId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            DB::commit();
            return response()->json(['message' => 'OK']);
        }

        switch ($status) {

            case 'settlement':
            case 'capture':
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_type' => $paymentType,
                    'paid_at' => now(),
                ]);

                event(new \App\Events\OrderPaid($order));
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);

                event(new \App\Events\OrderExpired($order));
                break;

            case 'pending':
                $order->update([
                    'payment_status' => 'pending',
                    'payment_type' => $paymentType,
                ]);
                break;
        }

        DB::commit();

        return response()->json(['message' => 'OK']);

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Webhook error', [
            'message' => $e->getMessage()
        ]);

        return response()->json(['message' => 'Error'], 500);
    }
}
    public function status($invoiceNumber)
{
    $order = Order::where('invoice_number', $invoiceNumber)->firstOrFail();

    return response()->json([
        'payment_status' => $order->payment_status,
        'status' => $order->status,
        'payment_type' => $order->payment_type,
    ]);
}
}
