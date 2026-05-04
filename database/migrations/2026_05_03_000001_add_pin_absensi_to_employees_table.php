<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('employees', 'pin_absensi')) {
            Schema::table('employees', function (Blueprint $table) {
                $afterColumn = Schema::hasColumn('employees', 'attendance_pin') ? 'attendance_pin' : 'is_active';

                $table->string('pin_absensi', 20)->nullable()->after($afterColumn);
            });
        }

        if (Schema::hasColumn('employees', 'attendance_pin') && Schema::hasColumn('employees', 'pin_absensi')) {
            DB::table('employees')
                ->whereNull('pin_absensi')
                ->whereNotNull('attendance_pin')
                ->update(['pin_absensi' => DB::raw('attendance_pin')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('employees', 'pin_absensi')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('pin_absensi');
            });
        }
    }
};
