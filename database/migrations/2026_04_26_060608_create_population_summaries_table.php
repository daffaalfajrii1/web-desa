<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('population_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hamlet_id')->constrained('hamlets')->cascadeOnDelete();
            $table->year('year');
            $table->integer('total_kk')->default(0);
            $table->integer('male_count')->default(0);
            $table->integer('female_count')->default(0);
            $table->timestamps();

            $table->unique(['hamlet_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('population_summaries');
    }
};