<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\ProfileMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoProfileSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $pagesData = [
            [
                'title' => 'Visi Misi',
                'excerpt' => 'Arah kebijakan pembangunan dan komitmen pelayanan Desa Tanjung Beringin.',
                'content' => '<p>Visi dan misi desa menjadi pedoman perencanaan pembangunan, layanan sosial, dan pemberdayaan ekonomi masyarakat.</p>',
                'featured_image' => 'demo/profile/visi-misi.jpg',
            ],
            [
                'title' => 'Sejarah Desa',
                'excerpt' => 'Ringkasan perjalanan sejarah, pembentukan wilayah, dan perkembangan desa.',
                'content' => '<p>Desa Tanjung Beringin tumbuh dari kawasan pertanian dan perdagangan lokal yang berkembang menjadi pusat pelayanan masyarakat.</p>',
                'featured_image' => 'demo/profile/sejarah-desa.jpg',
            ],
            [
                'title' => 'Potensi Desa',
                'excerpt' => 'Potensi unggulan desa pada sektor pertanian, UMKM, dan wisata.',
                'content' => '<p>Komoditas pertanian, usaha rumahan, serta destinasi alam menjadi fokus penguatan ekonomi desa.</p>',
                'featured_image' => 'demo/profile/potensi-desa.jpg',
            ],
            [
                'title' => 'Lembaga Kemasyarakatan',
                'excerpt' => 'Struktur lembaga desa dan peran partisipatif warga.',
                'content' => '<p>LPM, PKK, Karang Taruna, dan lembaga lain berkolaborasi mendukung program pembangunan dan sosial.</p>',
                'featured_image' => 'demo/profile/lembaga.jpg',
            ],
        ];

        $pages = collect();
        foreach ($pagesData as $index => $data) {
            $slug = Str::slug($data['title']);
            $page = Page::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'featured_image' => $data['featured_image'],
                    'status' => 'published',
                    'published_at' => Carbon::now()->subDays(20 - $index),
                ]
            );
            $pages->push($page);
        }

        if (! Schema::hasTable('profile_menus')) {
            return;
        }

        $menuTitles = ['Visi Misi', 'Sejarah Desa', 'Potensi Desa', 'Lembaga Kemasyarakatan'];

        foreach ($menuTitles as $sortOrder => $menuTitle) {
            $page = $pages->firstWhere('title', $menuTitle);
            if (! $page) {
                continue;
            }

            ProfileMenu::query()->updateOrCreate(
                ['slug' => Str::slug($menuTitle)],
                [
                    'title' => $menuTitle,
                    'page_id' => $page->id,
                    'sort_order' => $sortOrder + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
