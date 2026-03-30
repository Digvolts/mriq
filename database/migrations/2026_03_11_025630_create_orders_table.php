<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Random string 16 char
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            
            // Lokasi
            $table->string('province_id');
            $table->string('province_name');
            $table->string('regency_id');
            $table->string('regency_name');
            $table->string('district_id');
            $table->string('district_name');
            $table->text('address');
            
            // Harga
            $table->integer('subtotal');
            $table->integer('shipping_cost')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total_price');
            
            // Pembayaran
            $table->string('payment_method'); 
            $table->string('payment_status')->default('pending'); // pending, success, failed, expired
            $table->string('snap_token')->nullable(); // Midtrans token
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            
            // Status order
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending');
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('invoice_number');
            $table->index('email');
            $table->index('phone');
            $table->index('payment_status');

            $table->boolean('is_terjual_recorded')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
