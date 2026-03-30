<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newArrivals extends Model
{
       use HasFactory;
        protected $table = 'new_arrivals'; // Eksplisit nama tabel


    protected $fillable = [
        'name',
        'image',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];}
