<?php

namespace Database\Seeders;

use App\Models\VillageBanner;
use App\Models\VillageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoVillageIdentitySeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('village_settings')) {
            VillageSetting::query()->updateOrCreate(
                ['id' => 1],
                [
                    'village_name' => 'Desa Tanjung Beringin',
                    'district_name' => 'Kecamatan Curup Utara',
                    'regency_name' => 'Kabupaten Rejang Lebong',
                    'province_name' => 'Bengkulu',
                    'address' => 'Jl. Raya Desa Tanjung Beringin No. 01, Curup Utara, Rejang Lebong',
                    'postal_code' => '39119',
                    'email' => 'pemdes@tanjungberingin.desa.id',
                    'phone' => '0732-123456',
                    'whatsapp' => '081273330001',
                    'logo' => 'demo/identity/logo-desa.png',
                    'logo_path' => 'demo/identity/logo-desa.png',
                    'favicon' => 'demo/identity/favicon-desa.png',
                    'hero_image' => 'demo/identity/hero-desa.jpg',
                    'head_photo' => 'demo/identity/kades.jpg',
                    'head_name' => 'Andi Saputra',
                    'head_position' => 'Kepala Desa',
                    'village_head_name_manual' => 'Andi Saputra',
                    'welcome_message' => 'Selamat datang di website resmi Desa Tanjung Beringin. Portal ini menjadi ruang informasi publik, transparansi pembangunan, dan pelayanan masyarakat berbasis digital.',
                    'vision' => 'Terwujudnya Desa Tanjung Beringin yang maju, transparan, berdaya saing, dan sejahtera berbasis gotong royong.',
                    'mission' => 'Meningkatkan kualitas layanan publik, memperkuat ekonomi lokal, mempercepat pembangunan infrastruktur desa, serta mendorong partisipasi aktif warga dalam tata kelola pemerintahan desa.',
                    'marquee_text' => "Informasi pelayanan desa aktif setiap hari kerja\nPantau infografis APBDes, IDM, SDGs, dan data penduduk\nGunakan layanan mandiri untuk administrasi tanpa antre",
                    'map_embed' => '<iframe src="https://www.google.com/maps?q=-3.466667,102.533333&z=14&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                    'latitude' => -3.4666670,
                    'longitude' => 102.5333330,
                    'active_theme' => 'default',
                    'theme_active' => 'default',
                ]
            );
        }

        if (Schema::hasTable('village_banners')) {
            $banners = [
                ['title' => 'Selamat Datang di Portal Desa', 'subtitle' => 'Akses layanan dan informasi publik dalam satu pintu.', 'image_path' => 'demo/banners/banner-01.jpg', 'sort_order' => 1],
                ['title' => 'Transparansi Anggaran Desa', 'subtitle' => 'APBDes, infografis, dan data pembangunan terbuka untuk warga.', 'image_path' => 'demo/banners/banner-02.jpg', 'sort_order' => 2],
                ['title' => 'Layanan Mandiri Warga', 'subtitle' => 'Ajukan administrasi desa secara digital dan pantau prosesnya.', 'image_path' => 'demo/banners/banner-03.jpg', 'sort_order' => 3],
                ['title' => 'Potensi UMKM dan Lapak Desa', 'subtitle' => 'Dukung produk lokal dan ekonomi kreatif warga.', 'image_path' => 'demo/banners/banner-04.jpg', 'sort_order' => 4],
                ['title' => 'Wisata dan Budaya Lokal', 'subtitle' => 'Eksplor destinasi dan kegiatan budaya desa.', 'image_path' => 'demo/banners/banner-05.jpg', 'sort_order' => 5],
            ];

            foreach ($banners as $banner) {
                VillageBanner::query()->updateOrCreate(
                    ['image_path' => $banner['image_path']],
                    [
                        'title' => $banner['title'],
                        'subtitle' => $banner['subtitle'],
                        'is_active' => true,
                        'sort_order' => $banner['sort_order'],
                    ]
                );
            }
        }
    }
}
