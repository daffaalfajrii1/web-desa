<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();

            $table->decimal('pendapatan', 18, 2)->default(0);
            $table->decimal('belanja', 18, 2)->default(0);
            $table->decimal('pembiayaan_penerimaan', 18, 2)->default(0);
            $table->decimal('pembiayaan_pengeluaran', 18, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes');
    }
};