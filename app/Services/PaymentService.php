<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;

class PaymentService
{
    public function create(Order $order, ?string $method = null): array
    {
        $midtrans = new MidtransService();

        $transaction = $midtrans->createSnapTransaction($order, null, $method);

        $order->update([
            'snap_token' => $transaction['snap_token'],
            'transaction_id' => $transaction['transaction_id'],
            'payment_type' => null,
        ]);

        OrderPayment::create([
            'order_id' => $order->id,
            'transaction_id' => $transaction['transaction_id'],
            'status' => 'pending',
            'amount' => $order->total_price,
        ]);

        return $transaction;
    }
}