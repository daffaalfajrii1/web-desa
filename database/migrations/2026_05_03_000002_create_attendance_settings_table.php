<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('check_in_start')->default('06:00:00');
            $table->time('check_in_end')->default('07:30:00');
            $table->time('check_out_start')->default('15:00:00');
            $table->time('check_out_end')->default('23:59:00');
            $table->decimal('office_latitude', 10, 7)->nullable();
            $table->decimal('office_longitude', 10, 7)->nullable();
            $table->unsignedInteger('allowed_radius_meter')->default(100);
            $table->boolean('validate_location')->default(false);
            $table->boolean('use_holiday_api')->default(true);
            $table->boolean('disable_sunday_attendance')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
