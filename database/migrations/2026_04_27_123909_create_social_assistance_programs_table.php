<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_assistance_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->year('year');
            $table->string('period')->nullable();
            $table->text('description')->nullable();
            $table->integer('quota')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_assistance_programs');
    }
};