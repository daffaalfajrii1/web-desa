<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicInformation extends Model
{
    protected $table = 'public_informations';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'status',
        'created_by',
        'views',
        'published_date',
    ];

    protected function casts(): array
    {
        return [
            'published_date' => 'date',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}