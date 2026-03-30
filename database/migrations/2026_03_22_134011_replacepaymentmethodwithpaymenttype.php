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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('payment_status');
            }

            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });

        Schema::table('order_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('order_payments', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('transaction_id');
            }

            if (!Schema::hasColumn('order_payments', 'payload')) {
                $table->json('payload')->nullable()->after('amount');
            }

            if (Schema::hasColumn('order_payments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_price');
            }

            if (Schema::hasColumn('orders', 'payment_type')) {
                $table->dropColumn('payment_type');
            }
        });

        Schema::table('order_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('order_payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('transaction_id');
            }

            if (Schema::hasColumn('order_payments', 'payment_type')) {
                $table->dropColumn('payment_type');
            }

            if (Schema::hasColumn('order_payments', 'payload')) {
                $table->dropColumn('payload');
            }
        });
    }

};
