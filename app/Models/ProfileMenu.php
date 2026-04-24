<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMenu extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'page_id',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}