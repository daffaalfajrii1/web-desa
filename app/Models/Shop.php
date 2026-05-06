<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shop extends Model
{
    protected $fillable = [
        'shop_category_id',
        'title',
        'slug',
        'main_image',
        'excerpt',
        'description',
        'price',
        'stock',
        'status',
        'is_featured',
        'is_active',
        'whatsapp',
        'seller_name',
        'location',
        'views',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'shop_category_id');
    }

    public function images()
    {
        return $this->hasMany(ShopImage::class)->orderBy('sort_order');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->whatsapp);

        if ($phone === '') {
            return null;
        }

        if (Str::startsWith($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (Str::startsWith($phone, '8')) {
            $phone = '62'.$phone;
        }

        return 'https://wa.me/'.$phone;
    }
}
