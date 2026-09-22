<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel's default `laravel/laravel` skeleton already ships a
 * create_users_table migration (with name/email/password). This
 * migration adds the `role` column the ERD calls for, so we don't have
 * to fork/replace Laravel's own auth scaffolding.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // buyer | seller | admin — see App\Models\User role constants
            $table->string('role')->default('buyer')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
