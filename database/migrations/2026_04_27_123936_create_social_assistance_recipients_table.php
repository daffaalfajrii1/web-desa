<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_assistance_recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('social_assistance_program_id')
                ->constrained('social_assistance_programs')
                ->cascadeOnDelete();

            $table->foreignId('hamlet_id')
                ->nullable()
                ->constrained('hamlets')
                ->nullOnDelete();

            $table->string('name');
            $table->string('nik', 30);
            $table->string('kk_number', 30)->nullable();
            $table->text('address')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('phone')->nullable();

            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->enum('distribution_status', ['pending', 'ready', 'distributed', 'rejected'])->default('pending');

            $table->date('distributed_at')->nullable();
            $table->string('receiver_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['social_assistance_program_id', 'nik']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_assistance_recipients');
    }
};