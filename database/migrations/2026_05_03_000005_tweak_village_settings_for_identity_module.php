<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('village_settings', 'village_head_employee_id')) {
                $table->unsignedBigInteger('village_head_employee_id')
                    ->nullable()
                    ->after('head_position')
                    ->index();
            }

            if (! Schema::hasColumn('village_settings', 'village_head_name_manual')) {
                $table->string('village_head_name_manual')
                    ->nullable()
                    ->after('village_head_employee_id');
            }

            if (! Schema::hasColumn('village_settings', 'logo_path')) {
                $table->string('logo_path')
                    ->nullable()
                    ->after('logo');
            }
        });

        if (
            Schema::hasColumn('village_settings', 'head_name')
            && Schema::hasColumn('village_settings', 'village_head_name_manual')
        ) {
            DB::table('village_settings')
                ->whereNull('village_head_name_manual')
                ->whereNotNull('head_name')
                ->orderBy('id')
                ->get(['id', 'head_name'])
                ->each(function ($setting) {
                    DB::table('village_settings')
                        ->where('id', $setting->id)
                        ->update(['village_head_name_manual' => $setting->head_name]);
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('village_settings', 'village_head_employee_id')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->dropIndex(['village_head_employee_id']);
                $table->dropColumn('village_head_employee_id');
            });
        }

        if (Schema::hasColumn('village_settings', 'village_head_name_manual')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->dropColumn('village_head_name_manual');
            });
        }

        if (Schema::hasColumn('village_settings', 'logo_path')) {
            Schema::table('village_settings', function (Blueprint $table) {
                $table->dropColumn('logo_path');
            });
        }
    }
};
