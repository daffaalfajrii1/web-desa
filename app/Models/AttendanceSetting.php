<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
        'office_latitude',
        'office_longitude',
        'allowed_radius_meter',
        'validate_location',
        'use_holiday_api',
        'disable_saturday_attendance',
        'disable_sunday_attendance',
    ];

    protected function casts(): array
    {
        return [
            'office_latitude' => 'float',
            'office_longitude' => 'float',
            'allowed_radius_meter' => 'integer',
            'validate_location' => 'boolean',
            'use_holiday_api' => 'boolean',
            'disable_saturday_attendance' => 'boolean',
            'disable_sunday_attendance' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'check_in_start' => '06:00:00',
            'check_in_end' => '07:30:00',
            'check_out_start' => '15:00:00',
            'check_out_end' => '23:59:00',
            'allowed_radius_meter' => 100,
            'validate_location' => false,
            'use_holiday_api' => true,
            'disable_saturday_attendance' => true,
            'disable_sunday_attendance' => true,
        ]);
    }
}
