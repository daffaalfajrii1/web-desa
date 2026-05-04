<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VillageSetting extends Model
{
    protected $fillable = [
        'village_name',
        'district_name',
        'regency_name',
        'province_name',
        'address',
        'postal_code',
        'email',
        'phone',
        'whatsapp',
        'logo',
        'logo_path',
        'favicon',
        'hero_image',
        'head_photo',
        'head_name',
        'head_position',
        'village_head_employee_id',
        'village_head_name_manual',
        'welcome_message',
        'vision',
        'mission',
        'map_embed',
        'latitude',
        'longitude',
        'active_theme',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function villageHeadEmployee()
    {
        return $this->belongsTo(Employee::class, 'village_head_employee_id');
    }

    public function getVillageHeadNameAttribute(): ?string
    {
        return $this->villageHeadEmployee?->name
            ?: $this->village_head_name_manual
            ?: $this->head_name;
    }

    public function getLogoUrlAttribute(): ?string
    {
        $path = $this->logo_path ?: $this->logo;

        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::url($path);
    }

    public function getEmbedMapAttribute(): ?string
    {
        return $this->map_embed;
    }

    public function setEmbedMapAttribute(?string $value): void
    {
        $this->attributes['map_embed'] = $value;
    }
}
