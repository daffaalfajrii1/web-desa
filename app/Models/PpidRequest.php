<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidRequest extends Model
{
    protected $fillable = [
        'name',
        'institution',
        'phone',
        'email',
        'request_content',
        'status',
        'admin_note',
        'responded_at',
        'handled_by',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}