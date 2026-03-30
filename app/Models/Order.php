<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;

   protected $fillable = [
        'invoice_number',
        'customer_name',
        'email',
        'phone',
        'province_id',
        'province_name',
        'regency_id',
        'regency_name',
        'district_id',
        'district_name',
        'address',
        'subtotal',
        'shipping_cost',
        'tax',
        'total_price',
        'payment_status',
        'payment_type',
        'snap_token',
        'transaction_id',
        'status',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'stock_restored_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'tax' => 'integer',
        'total_price' => 'integer',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'stock_restored_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public static function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'INV-' . strtoupper(\Illuminate\Support\Str::random(12));
        } while (self::where('invoice_number', $invoice)->exists());

        return $invoice;
    }

    public function restoreStockIfNeeded(): void
    {
        if ($this->stock_restored_at) {
            return;
        }

        $this->loadMissing('items');

        foreach ($this->items as $item) {
            if (!$item->product_id || (int) $item->quantity < 1) {
                continue;
            }

            Product::where('id', $item->product_id)
                ->increment('stock', (int) $item->quantity);
        }

        $this->forceFill([
            'stock_restored_at' => now(),
        ])->save();
    }

    public function increaseTerjualIfNeeded(): void
    {
        if ($this->payment_status === 'paid') {
            return;
        }

        $this->loadMissing('items');

        foreach ($this->items as $item) {
            if (!$item->product_id || (int) $item->quantity < 1) {
                continue;
            }

            Product::where('id', $item->product_id)
                ->increment('terjual', (int) $item->quantity);
        }
    }

    public function getPaymentTypeLabelAttribute(): string
    {
        return match ($this->payment_type) {
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'echannel' => 'Mandiri Bill',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'other_va' => 'Virtual Account',
            null => 'Belum ditentukan',
            default => ucwords(str_replace('_', ' ', $this->payment_type)),
        };
    }
    
}
