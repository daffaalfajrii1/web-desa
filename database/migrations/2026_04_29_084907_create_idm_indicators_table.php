<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idm_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idm_summary_id')->constrained('idm_summaries')->cascadeOnDelete();

            $table->enum('category', ['IKS', 'IKE', 'IKL']);
            $table->unsignedInteger('indicator_no')->default(1);

            $table->string('indicator_name');
            $table->unsignedInteger('score')->default(0);
            $table->text('description')->nullable();
            $table->text('activity')->nullable();

            $table->decimal('value', 8, 4)->default(0);

            $table->string('executor_central')->nullable();
            $table->string('executor_province')->nullable();
            $table->string('executor_regency')->nullable();
            $table->string('executor_village')->nullable();
            $table->string('executor_csr')->nullable();
            $table->string('executor_other')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idm_indicators');
    }
};