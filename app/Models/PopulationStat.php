<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopulationStat extends Model
{
    protected $fillable = [
        'hamlet_id',
        'year',
        'category',
        'item_name',
        'value',
    ];

    public function hamlet()
    {
        return $this->belongsTo(Hamlet::class);
    }
}