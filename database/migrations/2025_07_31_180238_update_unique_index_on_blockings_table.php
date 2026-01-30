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
        Schema::table('blockings', function (Blueprint $table) {
            // حذف foreign key ها اول
            $table->dropForeign(['user_id']);
            $table->dropForeign(['expert_id']);

            // حذف unique قدیمی
            $table->dropUnique('blockings_user_id_expert_id_unique');

            // اضافه کردن unique جدید با block_type
            $table->unique(['user_id', 'expert_id', 'block_type']);

            // دوباره foreign key ها رو اضافه کن
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('blockings', function (Blueprint $table) {
            // حذف foreign key ها اول
            $table->dropForeign(['user_id']);
            $table->dropForeign(['expert_id']);

            // حذف unique جدید
            $table->dropUnique(['user_id', 'expert_id', 'block_type']);

            // اضافه کردن unique قبلی
            $table->unique(['user_id', 'expert_id']);

            // دوباره foreign key ها رو اضافه کن
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
        });
    }
};
