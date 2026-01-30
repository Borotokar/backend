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
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
	    $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade'); // گزارش‌دهنده (متخصص)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // کاربر موردنظر
            $table->enum('violation_type', ['chat', 'profile', 'order'])->comment('تخلف در چت، پروفایل، سفارش');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'rejected'])->default('pending'); 
	    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
