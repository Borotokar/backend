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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // نوع رویداد (ایجاد، حذف، ویرایش)
            $table->string('model'); // مدل مربوطه (سرویس، سفارش، متخصص و ...)
            $table->unsignedBigInteger('model_id')->nullable(); // آیدی مدل مربوطه
            $table->text('message'); // توضیح نوتیفیکیشن
            $table->boolean('read')->default(false); // خوانده شده یا نه
            $table->timestamps();	
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
