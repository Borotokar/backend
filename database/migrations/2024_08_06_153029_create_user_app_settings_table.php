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
        Schema::create('user_app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('baneer1');
            $table->string('baneer2');
            $table->string('baneer3');
            $table->string('baneer4');
            $table->string('law');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_app_settings');
    }
};
