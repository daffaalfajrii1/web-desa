<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('attendance_settings', 'disable_saturday_attendance')) {
            Schema::table('attendance_settings', function (Blueprint $table) {
                $table->boolean('disable_saturday_attendance')
                    ->default(true)
                    ->after('use_holiday_api');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendance_settings', 'disable_saturday_attendance')) {
            Schema::table('attendance_settings', function (Blueprint $table) {
                $table->dropColumn('disable_saturday_attendance');
            });
        }
    }
};
