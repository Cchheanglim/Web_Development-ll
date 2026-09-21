<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a real checkout step's fields — shipping details and a chosen
 * payment method label — so "Buy Now" walks through an actual checkout
 * page instead of firing an order the moment you click a button. Still
 * no real payment processing (see README) — payment_method is just a
 * label the buyer picks (Cash on Delivery / Bank Transfer).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_name')->after('status');
            $table->string('shipping_phone')->after('shipping_name');
            $table->string('shipping_address')->after('shipping_phone');
            $table->string('payment_method')->default('cod')->after('shipping_address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_name', 'shipping_phone', 'shipping_address', 'payment_method']);
        });
    }
};
