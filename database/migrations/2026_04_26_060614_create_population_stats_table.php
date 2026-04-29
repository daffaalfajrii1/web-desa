<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('population_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hamlet_id')->constrained('hamlets')->cascadeOnDelete();
            $table->year('year');
            $table->string('category'); // umur, pendidikan, wajib_pilih, perkawinan, agama
            $table->string('item_name');
            $table->integer('value')->default(0);
            $table->timestamps();

            $table->unique(['hamlet_id', 'year', 'category', 'item_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('population_stats');
    }
};