<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalProduct extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'number',
        'document_type',
        'published_date',
        'description',
        'file_path',
        'status',
        'created_by',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'published_date' => 'date',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}