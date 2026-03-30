<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
     protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'status',
        'amount',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'midtrans_response' => 'array',

    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
