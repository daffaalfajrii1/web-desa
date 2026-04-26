<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidSection extends Model
{
    protected $fillable = [
        'title',
        'type',
        'sort_order',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function documents()
    {
        return $this->hasMany(PpidDocument::class)->orderBy('sort_order');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}