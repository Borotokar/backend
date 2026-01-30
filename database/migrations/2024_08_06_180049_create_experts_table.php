<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_id')->unique();
            $table->date('birth_date');
            $table->enum('type', ['business_unit', 'self_employed', 'company']);
            $table->string('telegram_link')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('eitaa_link')->nullable();
            $table->text('address');
            $table->string('province');
            $table->string('city');
            $table->decimal('lat', 9, 6);
            $table->decimal('log', 9, 6);
            $table->boolean('is_active')->default(false);
            $table->string('profile_image')->default('img/default.png');
            $table->string('company_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->timestamps();
        });

        Schema::create('expert_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        });

        Schema::create('expert_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade');
            $table->string('type'); // e.g., national_id_photo, personal_photo, business_license_photo, etc.
            $table->string('path');
        });

        Schema::create('expert_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade');
            $table->string('path');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expert_gallery');
        Schema::dropIfExists('expert_documents');
        Schema::dropIfExists('expert_services');
        Schema::dropIfExists('experts');
    }
};
