<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('self_service_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('self_service_id')->constrained('self_services')->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('applicant_name')->nullable();
            $table->string('applicant_nik', 32)->nullable();
            $table->string('applicant_phone', 50)->nullable();
            $table->string('applicant_email')->nullable();
            $table->text('applicant_address')->nullable();
            $table->json('form_data')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', ['masuk', 'diproses', 'selesai', 'ditolak'])->default('masuk');
            $table->text('admin_note')->nullable();
            $table->enum('result_type', ['surat', 'pemberitahuan', 'ditolak', 'lainnya'])->nullable();
            $table->string('result_title')->nullable();
            $table->text('result_note')->nullable();
            $table->string('result_file')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['self_service_id', 'status', 'submitted_at']);
            $table->index(['applicant_name', 'applicant_phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('self_service_submissions');
    }
};
