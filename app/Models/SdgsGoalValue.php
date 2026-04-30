<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdgsGoalValue extends Model
{
    protected $fillable = [
        'sdgs_summary_id',
        'sdgs_goal_id',
        'score',
        'achievement_percent',
        'status',
        'short_description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'achievement_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function summary()
    {
        return $this->belongsTo(SdgsSummary::class, 'sdgs_summary_id');
    }

    public function goal()
    {
        return $this->belongsTo(SdgsGoal::class, 'sdgs_goal_id');
    }

    public static function resolveStatus($score): string
    {
        $score = (float) $score;

        if ($score >= 80) {
            return 'baik';
        }

        if ($score >= 60) {
            return 'berkembang';
        }

        return 'prioritas';
    }
}