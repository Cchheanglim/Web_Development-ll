<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Card payment display fields — ONLY the last 4 digits and a guessed
 * brand, for the receipt to show something like "Visa •••• 4242". The
 * full card number and CVV are never stored anywhere (see CheckoutController's
 * card handling) — this mirrors how real payment processors work: your
 * own database should never hold a raw card number or CVV.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('card_brand')->nullable()->after('payment_method');
            $table->string('card_last4', 4)->nullable()->after('card_brand');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['card_brand', 'card_last4']);
        });
    }
};
