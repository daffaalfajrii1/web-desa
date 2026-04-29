<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tourisms', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('main_image')->nullable();

            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->text('facilities')->nullable();
            $table->text('map_embed')->nullable();

            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('open_days')->nullable();
            $table->string('closed_days')->nullable();

            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourisms');
    }
};