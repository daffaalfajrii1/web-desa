<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'attachment',
        'status',
        'created_by',
        'views',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}