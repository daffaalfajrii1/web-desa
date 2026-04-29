<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourismImage extends Model
{
    protected $fillable = [
        'tourism_id',
        'image_path',
        'sort_order',
    ];

    public function tourism()
    {
        return $this->belongsTo(Tourism::class);
    }
}