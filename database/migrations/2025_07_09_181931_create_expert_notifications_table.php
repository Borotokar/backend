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
        Schema::create('expert_notifications', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('expert_id');
            $table->timestamps();
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_notifications');
    }
};
