<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdgs_summaries', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();
            $table->decimal('average_score', 8, 2)->default(0);
            $table->unsignedInteger('total_good')->default(0);
            $table->unsignedInteger('total_medium')->default(0);
            $table->unsignedInteger('total_priority')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdgs_summaries');
    }
};