<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('self_service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('self_service_id')->constrained('self_services')->cascadeOnDelete();
            $table->string('field_name');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'textarea', 'number', 'date', 'select', 'radio', 'checkbox', 'file']);
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['self_service_id', 'field_name']);
            $table->index(['self_service_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('self_service_fields');
    }
};
