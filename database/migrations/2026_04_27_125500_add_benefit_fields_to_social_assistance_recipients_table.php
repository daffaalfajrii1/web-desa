<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_assistance_recipients', function (Blueprint $table) {
            $table->string('benefit_type')->default('cash')->after('amount');
            $table->text('item_description')->nullable()->after('benefit_type');
            $table->string('unit')->nullable()->after('item_description');
            $table->decimal('quantity', 12, 2)->nullable()->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('social_assistance_recipients', function (Blueprint $table) {
            $table->dropColumn([
                'benefit_type',
                'item_description',
                'unit',
                'quantity',
            ]);
        });
    }
};