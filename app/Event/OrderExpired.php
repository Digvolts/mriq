<?php

namespace App\Events;

use App\Models\Order;

class OrderExpired
{
    public function __construct(public Order $order) {}
}