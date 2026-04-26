<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidDocument extends Model
{
    protected $fillable = [
        'ppid_section_id',
        'title',
        'file_path',
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

    public function section()
    {
        return $this->belongsTo(PpidSection::class, 'ppid_section_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}