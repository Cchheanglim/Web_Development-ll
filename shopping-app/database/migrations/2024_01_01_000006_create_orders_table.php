<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A lightweight order placement table — not in the original ERD, added so
 * buyers have an actual "Buy Now" action instead of only chat. This is a
 * REQUEST TO BUY that the seller confirms, not a real payment/checkout —
 * there's no payment gateway wired in here (see README for why, and how
 * to add real payments later with Stripe/PayPal).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            // pending: buyer just placed it -> confirmed: seller accepted
            // -> completed: handed over/picked up -> cancelled: either side backed out
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
