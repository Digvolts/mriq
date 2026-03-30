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
        Schema::create('order_payments', function (Blueprint $table) {
              $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            $table->string('transaction_id')->unique();
            $table->string('payment_method');
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            
            $table->integer('amount');
            $table->json('midtrans_response')->nullable();
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
