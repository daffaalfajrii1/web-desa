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

    /** Urutan tampilan jenis informasi di portal publik */
    public static function typeOrder(): array
    {
        return ['berkala', 'serta_merta', 'setiap_saat'];
    }

    public static function typeLabels(): array
    {
        return [
            'berkala' => 'Informasi Berkala',
            'serta_merta' => 'Informasi Serta Merta',
            'setiap_saat' => 'Informasi Setiap Saat',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return static::typeLabels()[$this->type] ?? ucfirst(str_replace('_', ' ', (string) $this->type));
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
