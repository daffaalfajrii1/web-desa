<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tourism extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'main_image',
        'excerpt',
        'description',
        'facilities',
        'map_embed',
        'address',
        'contact_person',
        'contact_phone',
        'open_days',
        'closed_days',
        'open_time',
        'close_time',
        'is_featured',
        'is_active',
        'views',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function images()
    {
        return $this->hasMany(TourismImage::class)->orderBy('sort_order');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}