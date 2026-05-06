<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopImage;
use App\Models\Tourism;
use App\Models\TourismImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoCommerceTourismSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('email', 'admin@desa.test')->value('id');
        $shopCategories = [];

        if (Schema::hasTable('shop_categories')) {
            foreach (['Kuliner', 'Kerajinan', 'Pertanian', 'Jasa'] as $name) {
                $category = ShopCategory::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'is_active' => true]
                );
                $shopCategories[] = $category;
            }
        }

        if (Schema::hasTable('shops')) {
            $shops = [
                ['title' => 'Kopi Bubuk Tanjung', 'seller' => 'UMKM Beringin Jaya', 'price' => 45000, 'stock' => 56, 'location' => 'Dusun 1'],
                ['title' => 'Keripik Pisang Kriuk', 'seller' => 'Kelompok PKK Melati', 'price' => 28000, 'stock' => 120, 'location' => 'Dusun 2'],
                ['title' => 'Madu Hutan Asli', 'seller' => 'Bumdes Sejahtera', 'price' => 85000, 'stock' => 30, 'location' => 'Dusun 3'],
                ['title' => 'Anyaman Bambu Mini', 'seller' => 'Karang Taruna Kreatif', 'price' => 65000, 'stock' => 24, 'location' => 'Dusun 2'],
                ['title' => 'Paket Sayur Segar', 'seller' => 'Gapoktan Maju Bersama', 'price' => 35000, 'stock' => 80, 'location' => 'Dusun 4'],
                ['title' => 'Jasa Foto Kegiatan Desa', 'seller' => 'Pemuda Digital Desa', 'price' => 250000, 'stock' => null, 'location' => 'Balai Desa'],
            ];

            foreach ($shops as $i => $shopData) {
                $category = $shopCategories[$i % max(count($shopCategories), 1)] ?? null;
                $shop = Shop::query()->updateOrCreate(
                    ['slug' => Str::slug($shopData['title'])],
                    [
                        'shop_category_id' => $category?->id,
                        'title' => $shopData['title'],
                        'main_image' => 'demo/shops/shop-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                        'excerpt' => 'Produk unggulan warga desa dengan kualitas terjaga.',
                        'description' => 'Produk lokal hasil karya masyarakat Desa Tanjung Beringin. Pemesanan dapat dilakukan melalui WhatsApp penjual.',
                        'price' => $shopData['price'],
                        'stock' => $shopData['stock'],
                        'status' => 'available',
                        'is_featured' => $i < 3,
                        'is_active' => true,
                        'whatsapp' => '0812733300'.str_pad((string) ($i + 11), 2, '0', STR_PAD_LEFT),
                        'seller_name' => $shopData['seller'],
                        'location' => $shopData['location'],
                        'views' => 70 + ($i * 10),
                        'created_by' => $authorId,
                    ]
                );

                if (Schema::hasTable('shop_images')) {
                    for ($j = 1; $j <= 2; $j++) {
                        ShopImage::query()->updateOrCreate(
                            ['shop_id' => $shop->id, 'sort_order' => $j],
                            ['image_path' => 'demo/shops/shop-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'-'.$j.'.jpg']
                        );
                    }
                }
            }
        }

        if (Schema::hasTable('tourisms')) {
            $tourisms = [
                ['title' => 'Air Terjun Batu Rajo', 'address' => 'Dusun 5, Tanjung Beringin'],
                ['title' => 'Bukit Panorama Desa', 'address' => 'Dusun 4, Tanjung Beringin'],
                ['title' => 'Embung Lestari', 'address' => 'Dusun 2, Tanjung Beringin'],
                ['title' => 'Kampung Adat Beringin', 'address' => 'Dusun 1, Tanjung Beringin'],
            ];

            foreach ($tourisms as $i => $tourismData) {
                $tourism = Tourism::query()->updateOrCreate(
                    ['slug' => Str::slug($tourismData['title'])],
                    [
                        'title' => $tourismData['title'],
                        'main_image' => 'demo/tourism/tourism-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                        'excerpt' => 'Destinasi wisata lokal dengan panorama alam dan kearifan budaya.',
                        'description' => 'Lokasi wisata desa dengan fasilitas dasar, area foto, dan dukungan UMKM lokal.',
                        'facilities' => 'Area parkir, toilet umum, mushola, warung UMKM, spot foto',
                        'map_embed' => '<iframe src="https://www.google.com/maps?q=-3.466667,102.533333&z=13&output=embed" loading="lazy"></iframe>',
                        'address' => $tourismData['address'],
                        'contact_person' => 'Pokdarwis Desa',
                        'contact_phone' => '0812734400'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                        'open_days' => 'Senin-Minggu',
                        'closed_days' => null,
                        'open_time' => '08:00:00',
                        'close_time' => '17:00:00',
                        'is_featured' => $i < 2,
                        'is_active' => true,
                        'views' => 40 + ($i * 12),
                        'created_by' => $authorId,
                    ]
                );

                if (Schema::hasTable('tourism_images')) {
                    for ($j = 1; $j <= 2; $j++) {
                        TourismImage::query()->updateOrCreate(
                            ['tourism_id' => $tourism->id, 'sort_order' => $j],
                            ['image_path' => 'demo/tourism/tourism-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'-'.$j.'.jpg']
                        );
                    }
                }
            }
        }

        if (Schema::hasTable('galleries')) {
            for ($i = 1; $i <= 8; $i++) {
                $isVideo = $i % 4 === 0;
                $title = $isVideo ? 'Dokumentasi Video Kegiatan Desa '.$i : 'Foto Kegiatan Desa '.$i;
                $photoRelative = 'demo/galleries/gallery-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT).'.jpg';
                Gallery::query()->updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'media_type' => $isVideo ? Gallery::TYPE_VIDEO : Gallery::TYPE_PHOTO,
                        'image_path' => $isVideo ? null : $photoRelative,
                        'photo_paths' => $isVideo ? null : [$photoRelative],
                        'youtube_url' => $isVideo ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
                        'youtube_id' => $isVideo ? 'dQw4w9WgXcQ' : null,
                        'description' => 'Dokumentasi kegiatan pemerintahan, layanan publik, dan partisipasi warga.',
                        'location' => 'Desa Tanjung Beringin',
                        'taken_at' => Carbon::now()->subDays(60 - $i)->toDateString(),
                        'is_featured' => $i <= 3,
                        'status' => Gallery::STATUS_PUBLISHED,
                        'views' => 25 + ($i * 7),
                        'published_at' => Carbon::now()->subDays(30 - $i),
                        'created_by' => $authorId,
                    ]
                );
            }
        }
    }
}
