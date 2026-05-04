<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public const STATUS_HADIR = 'hadir';
    public const STATUS_TELAT = 'telat';
    public const STATUS_IZIN = 'izin';
    public const STATUS_SAKIT = 'sakit';
    public const STATUS_ALPA = 'alpa';
    public const STATUS_LIBUR = 'libur';

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'note',
        'latitude',
        'longitude',
        'distance_meter',
        'is_holiday',
        'holiday_name',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
            'distance_meter' => 'float',
            'is_holiday' => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_HADIR,
            self::STATUS_TELAT,
            self::STATUS_IZIN,
            self::STATUS_SAKIT,
            self::STATUS_ALPA,
            self::STATUS_LIBUR,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_TELAT => 'Telat',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_ALPA => 'Alpa',
            self::STATUS_LIBUR => 'Libur',
        ];
    }

    public static function statusBadgeClasses(): array
    {
        return [
            self::STATUS_HADIR => 'badge-success',
            self::STATUS_TELAT => 'badge-warning',
            self::STATUS_IZIN => 'badge-primary',
            self::STATUS_SAKIT => 'badge-info',
            self::STATUS_ALPA => 'badge-danger',
            self::STATUS_LIBUR => 'badge-secondary',
        ];
    }
}
