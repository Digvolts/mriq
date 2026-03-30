<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Product;

class UpdateTerjual
{
    public function handle(OrderPaid $event)
    {
        $order = $event->order;

        if ($order->is_terjual_recorded) return;

        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)
                ->increment('terjual', $item->quantity);
        }

        $order->update(['is_terjual_recorded' => true]);
    }
}