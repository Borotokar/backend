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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->uniqid()->nullable();
            $table->string('phone_number')->uniqid()->nullable();
            $table->string('picture')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['pending', 'active'])->default('pending');
            $table->enum('sex', ['Male', 'Fmale']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
