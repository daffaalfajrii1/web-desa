<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAssistanceProgram extends Model
{
    protected $fillable = [
        'name',
        'year',
        'period',
        'description',
        'quota',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function recipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }
}