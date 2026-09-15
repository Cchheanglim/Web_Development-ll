<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Dog', 'Cat', 'Bird', 'Rabbit', 'Other']);
            $table->string('breed')->nullable();
            $table->string('age');
            $table->enum('gender', ['Male', 'Female', 'Unknown']);
            $table->text('description');
            $table->enum('status', ['Available', 'Pending', 'Adopted'])->default('Available');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
