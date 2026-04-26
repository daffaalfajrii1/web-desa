<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}