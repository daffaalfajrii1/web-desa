<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('village_settings') && ! Schema::hasColumn('village_settings', 'marquee_text')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->text('marquee_text')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('village_settings') && Schema::hasColumn('village_settings', 'marquee_text')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->dropColumn('marquee_text');
            });
        }
    }
};
