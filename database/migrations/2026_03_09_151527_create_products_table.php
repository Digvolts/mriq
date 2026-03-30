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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->nullable()->constrained('collections')->onDelete('set null');
            $table->string('name');
            $table->text('description');
            $table->text('exclusive_mercendise');
            $table->text('bahan');
            $table->text('style');
            $table->text('printing_design');
            $table->integer('terjual');
            $table->string('keterangan_bestseller');
            $table->string('pengiriman');
            $table->integer('price');
            $table->integer('discount_price')->nullable();
            $table->integer('stock')->default(0);
            $table->string('image');
            $table->string('size');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
