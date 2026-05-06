<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('village_settings') && ! Schema::hasColumn('village_settings', 'theme_active')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->string('theme_active')->nullable()->index();
            });

            if (Schema::hasColumn('village_settings', 'active_theme')) {
                DB::table('village_settings')
                    ->whereNull('theme_active')
                    ->update(['theme_active' => DB::raw('active_theme')]);
            }
        }

        if (Schema::hasTable('galleries') && ! Schema::hasColumn('galleries', 'views')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->unsignedBigInteger('views')->default(0);
            });
        }

        if (! Schema::hasTable('public_visits')) {
            Schema::create('public_visits', function (Blueprint $table) {
                $table->id();
                $table->date('visit_date')->index();
                $table->string('session_id', 120);
                $table->string('ip_hash', 64)->nullable();
                $table->string('user_agent_hash', 64)->nullable();
                $table->timestamps();

                $table->unique(['visit_date', 'session_id'], 'public_visits_date_session_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('public_visits');

        if (Schema::hasTable('galleries') && Schema::hasColumn('galleries', 'views')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('views');
            });
        }

        if (Schema::hasTable('village_settings') && Schema::hasColumn('village_settings', 'theme_active')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->dropColumn('theme_active');
            });
        }
    }
};
