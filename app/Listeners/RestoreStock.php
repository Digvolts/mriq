<?php

namespace App\Listeners;

use App\Events\OrderExpired;
use App\Models\Product;

class RestoreStock
{
    public function handle(OrderExpired $event)
    {
        $order = $event->order;

        if ($order->stock_restored_at) return;

        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)
                ->increment('stock', $item->quantity);
        }

        $order->update([
            'stock_restored_at' => now()
        ]);
    }
}