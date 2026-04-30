<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stunting_records', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->foreignId('hamlet_id')->nullable()->constrained('hamlets')->nullOnDelete();

            $table->string('child_name');
            $table->string('child_nik')->nullable();
            $table->string('parent_name')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->date('birth_date')->nullable();
            $table->integer('age_in_months')->nullable();

            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();

            $table->enum('stunting_status', ['normal', 'stunting', 'berisiko'])->default('normal');
            $table->enum('nutrition_status', ['baik', 'kurang', 'buruk'])->default('baik');

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stunting_records');
    }
};