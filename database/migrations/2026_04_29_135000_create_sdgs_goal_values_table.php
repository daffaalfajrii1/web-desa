<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdgs_goal_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sdgs_summary_id')
                ->constrained('sdgs_summaries')
                ->cascadeOnDelete();

            $table->foreignId('sdgs_goal_id')
                ->constrained('sdgs_goals')
                ->cascadeOnDelete();

            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('achievement_percent', 8, 2)->nullable();
            $table->enum('status', ['baik', 'berkembang', 'prioritas'])->default('prioritas');
            $table->text('short_description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['sdgs_summary_id', 'sdgs_goal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdgs_goal_values');
    }
};