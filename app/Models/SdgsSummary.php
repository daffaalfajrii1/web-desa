<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdgsSummary extends Model
{
    protected $fillable = [
        'year',
        'average_score',
        'total_good',
        'total_medium',
        'total_priority',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'average_score' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function goalValues()
    {
        return $this->hasMany(SdgsGoalValue::class)->with('goal')->orderBy('sort_order')->orderBy('sdgs_goal_id');
    }

    public function recalculate(): void
    {
        $values = $this->goalValues()->get();

        $this->update([
            'average_score' => round((float) $values->avg('score'), 2),
            'total_good' => $values->where('status', 'baik')->count(),
            'total_medium' => $values->where('status', 'berkembang')->count(),
            'total_priority' => $values->where('status', 'prioritas')->count(),
        ]);
    }
}