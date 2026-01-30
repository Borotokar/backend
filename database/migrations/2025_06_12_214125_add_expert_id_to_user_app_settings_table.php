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
        Schema::table('user_app_settings', function (Blueprint $table) {
            $table->foreignId('expert_id1')->nullable()->constrained('experts')->nullOnDelete();
            $table->foreignId('expert_id2')->nullable()->constrained('experts')->nullOnDelete();
            $table->foreignId('expert_id3')->nullable()->constrained('experts')->nullOnDelete();
            $table->foreignId('expert_id4')->nullable()->constrained('experts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_app_settings', function (Blueprint $table) {
            //
        });
    }
};
