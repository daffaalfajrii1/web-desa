<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_settings', function (Blueprint $table) {
            $table->id();
            $table->string('village_name')->nullable();
            $table->string('district_name')->nullable();
            $table->string('regency_name')->nullable();
            $table->string('province_name')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('head_photo')->nullable();

            $table->string('head_name')->nullable();
            $table->string('head_position')->nullable();

            $table->text('welcome_message')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();

            $table->longText('map_embed')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('active_theme')->default('default');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_settings');
    }
};