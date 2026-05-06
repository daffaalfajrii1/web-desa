<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoContentPostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('email', 'admin@desa.test')->value('id');

        $postCategoryIds = [];
        if (Schema::hasTable('categories')) {
            foreach (['Pemerintahan', 'Pembangunan', 'Kesehatan', 'Pendidikan', 'Ekonomi Desa'] as $name) {
                $category = Category::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'type' => 'post']
                );
                $postCategoryIds[] = $category->id;
            }

            foreach (['Peraturan Desa', 'Keputusan Kepala Desa'] as $name) {
                Category::query()->updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'type' => 'legal_product']
                );
            }
        }

        if (Schema::hasTable('posts')) {
            $postTitles = [
                'Musyawarah Desa Penetapan Prioritas Pembangunan 2026',
                'Gerakan Jumat Bersih Libatkan Seluruh RT',
                'Posyandu Balita Bulan Ini Catat Cakupan 96 Persen',
                'Pelatihan UMKM Digital Marketing untuk Warga',
                'Perbaikan Jalan Lingkungan Tahap II Dimulai',
                'Program Ketahanan Pangan Keluarga Diperluas',
                'Monitoring Dana Desa Semester I Tahun 2026',
                'Kolaborasi Karang Taruna dan PKK untuk Bank Sampah',
            ];

            foreach ($postTitles as $i => $title) {
                $categoryId = $postCategoryIds[$i % max(count($postCategoryIds), 1)] ?? null;
                Post::query()->updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'category_id' => $categoryId,
                        'excerpt' => 'Informasi ringkas terkait kegiatan dan program pembangunan desa.',
                        'content' => '<p>Pemerintah desa menyampaikan perkembangan kegiatan kepada masyarakat sebagai bentuk transparansi dan akuntabilitas publik.</p>',
                        'featured_image' => 'demo/posts/post-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                        'status' => 'published',
                        'created_by' => $authorId,
                        'views' => 120 + ($i * 17),
                        'published_at' => Carbon::now()->subDays(30 - $i),
                    ]
                );
            }
        }

        if (Schema::hasTable('announcements')) {
            $titles = [
                'Pemberitahuan Jadwal Pelayanan Administrasi',
                'Pengumuman Pendaftaran Kader Posyandu',
                'Jadwal Vaksinasi Ternak Tahap Mei 2026',
                'Imbauan Kesiapsiagaan Musim Hujan',
                'Informasi Pemadaman Listrik Terjadwal',
            ];

            foreach ($titles as $i => $title) {
                Announcement::query()->updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'excerpt' => 'Pengumuman resmi pemerintah desa untuk diketahui seluruh warga.',
                        'content' => '<p>Mohon perhatian dan partisipasi warga sesuai pengumuman yang disampaikan.</p>',
                        'featured_image' => 'demo/announcements/announcement-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                        'attachment' => 'demo/docs/pengumuman-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.pdf',
                        'status' => 'published',
                        'created_by' => $authorId,
                        'views' => 80 + ($i * 11),
                    ]
                );
            }
        }

        if (Schema::hasTable('agendas')) {
            $baseDate = Carbon::now()->startOfMonth();
            $agendas = [
                ['name' => 'Rembuk Stunting Desa', 'days' => 3],
                ['name' => 'Pelatihan Administrasi RT/RW', 'days' => 7],
                ['name' => 'Kerja Bakti Lingkungan Dusun 1', 'days' => 12],
                ['name' => 'Sosialisasi APBDes Perubahan', 'days' => 18],
                ['name' => 'Festival Produk UMKM Desa', 'days' => 24],
            ];

            foreach ($agendas as $i => $agenda) {
                $startDate = $baseDate->copy()->addDays($agenda['days']);
                Agenda::query()->updateOrCreate(
                    ['slug' => Str::slug($agenda['name'])],
                    [
                        'title' => $agenda['name'],
                        'featured_image' => 'demo/agendas/agenda-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                        'description' => 'Agenda kegiatan resmi desa untuk memperkuat partisipasi masyarakat.',
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $startDate->copy()->addDay()->toDateString(),
                        'start_time' => '08:00:00',
                        'end_time' => '11:30:00',
                        'location' => 'Balai Desa Tanjung Beringin',
                        'organizer' => 'Pemerintah Desa',
                        'contact_person' => 'Sekretariat Desa',
                        'status' => 'published',
                        'created_by' => $authorId,
                        'views' => 45 + ($i * 9),
                    ]
                );
            }
        }
    }
}
