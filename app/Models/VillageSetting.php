<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'favicon',
        'hero_image',
        'head_photo',
        'head_name',
        'head_position',
        'welcome_message',
        'vision',
        'mission',
        'map_embed',
        'latitude',
        'longitude',
        'active_theme',
    ];
}