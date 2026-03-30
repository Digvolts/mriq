<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'name',
        'description',
        'exclusive_mercendise',
        'bahan',
        'style',
        'printing_design',
        'terjual',
        'keterangan_bestseller',
        'pengiriman',
        'price',
        'discount_price',
        'stock',
        'image',
        'size',
        'is_active',
    ];

      protected $casts = [
        'price' => 'integer',
        'discount_price' => 'integer',
        'stock' => 'integer',
        'terjual' => 'integer',
        'is_active' => 'boolean',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

}
