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
        Schema::table('bids', function (Blueprint $table) {
            $table->unsignedBigInteger('proposal_type_id')->nullable(); // اضافه کردن فیلد نوع پیشنهاد
            $table->foreign('proposal_type_id')->references('id')->on('proposal_types')->onDelete('set null'); // رابطه با جدول proposal_types
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign(['proposal_type_id']);
            $table->dropColumn('proposal_type_id');
        });
    }
};
