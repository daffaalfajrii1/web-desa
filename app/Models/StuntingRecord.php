<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StuntingRecord extends Model
{
    protected $fillable = [
        'year',
        'hamlet_id',
        'child_name',
        'child_nik',
        'parent_name',
        'gender',
        'birth_date',
        'age_in_months',
        'height_cm',
        'weight_kg',
        'stunting_status',
        'nutrition_status',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function hamlet()
    {
        return $this->belongsTo(Hamlet::class);
    }
}
