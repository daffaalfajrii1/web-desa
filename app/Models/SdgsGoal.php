<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdgsGoal extends Model
{
    protected $fillable = [
        'goal_number',
        'goal_name',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function values()
    {
        return $this->hasMany(SdgsGoalValue::class);
    }
}