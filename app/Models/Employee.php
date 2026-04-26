<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
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
}