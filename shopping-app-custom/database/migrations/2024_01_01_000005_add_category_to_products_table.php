<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a lightweight `category` field so products can be browsed by
 * category (Electronics, Fashion, etc.) — an Alibaba-style marketplace
 * leans heavily on category browsing, so this small addition on top of
 * the original ERD makes that possible without a separate categories
 * table (kept simple on purpose for a class project).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('Others')->after('condition');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
