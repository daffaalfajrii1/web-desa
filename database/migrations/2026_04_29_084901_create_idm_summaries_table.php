<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idm_summaries', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();

            $table->decimal('iks_score', 8, 4)->default(0);
            $table->decimal('ike_score', 8, 4)->default(0);
            $table->decimal('ikl_score', 8, 4)->default(0);
            $table->decimal('idm_score', 8, 4)->default(0);

            $table->string('idm_status')->nullable();
            $table->string('target_status')->default('Mandiri');
            $table->decimal('minimal_target_score', 8, 4)->default(0.8156);
            $table->decimal('additional_score_needed', 8, 4)->default(0);

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idm_summaries');
    }
};