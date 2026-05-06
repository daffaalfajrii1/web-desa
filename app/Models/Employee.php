<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_position_id',
        'name',
        'position',
        'position_type',
        'photo',
        'nip',
        'email',
        'phone',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'whatsapp',
        'telegram',
        'sort_order',
        'is_active',
        'attendance_pin',
        'pin_absensi',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employeePosition()
    {
        return $this->belongsTo(EmployeePosition::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function scopeActiveWithAttendancePin($query)
    {
        return $query
            ->where('is_active', true)
            ->whereNotNull('pin_absensi')
            ->where('pin_absensi', '!=', '');
    }

    public function scopeOrderedForAttendance($query)
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
