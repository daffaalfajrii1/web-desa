<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hamlet extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function populationSummaries()
    {
        return $this->hasMany(PopulationSummary::class);
    }

    public function populationStats()
    {
        return $this->hasMany(PopulationStat::class);
    }
}