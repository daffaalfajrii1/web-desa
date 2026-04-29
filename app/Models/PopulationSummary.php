<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopulationSummary extends Model
{
    protected $fillable = [
        'hamlet_id',
        'year',
        'total_kk',
        'male_count',
        'female_count',
    ];

    public function hamlet()
    {
        return $this->belongsTo(Hamlet::class);
    }

    public function getTotalPopulationAttribute(): int
    {
        return (int) $this->male_count + (int) $this->female_count;
    }
}