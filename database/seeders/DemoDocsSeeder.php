<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LegalProduct;
use App\Models\PpidDocument;
use App\Models\PpidSection;
use App\Models\PublicInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoDocsSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('email', 'admin@desa.test')->value('id');
        $legalCategories = Category::query()
            ->where('type', 'legal_product')
            ->pluck('id', 'slug');

        if (Schema::hasTable('legal_products')) {
            $items = [
                ['title' => 'Perdes RPJMDes 2025-2030', 'number' => '01/2026', 'type' => 'Peraturan Desa', 'cat' => 'peraturan-desa'],
                ['title' => 'Perdes APBDes Tahun 2026', 'number' => '02/2026', 'type' => 'Peraturan Desa', 'cat' => 'peraturan-desa'],
                ['title' => 'Keputusan Kades Tim Verifikasi Bansos', 'number' => '03/2026', 'type' => 'Keputusan Kepala Desa', 'cat' => 'keputusan-kepala-desa'],
                ['title' => 'Perdes Tata Ruang Permukiman', 'number' => '04/2026', 'type' => 'Peraturan Desa', 'cat' => 'peraturan-desa'],
                ['title' => 'Keputusan Kades Pengelola Website Desa', 'number' => '05/2026', 'type' => 'Keputusan Kepala Desa', 'cat' => 'keputusan-kepala-desa'],
            ];

            foreach ($items as $i => $item) {
                LegalProduct::query()->updateOrCreate(
                    ['slug' => Str::slug($item['title'])],
                    [
                        'title' => $item['title'],
                        'category_id' => $legalCategories[$item['cat']] ?? null,
                        'number' => $item['number'],
                        'document_type' => $item['type'],
                        'published_date' => Carbon::now()->subMonths(6 - $i)->toDateString(),
                        'description' => 'Dokumen hukum desa untuk mendukung tata kelola dan kepastian regulasi.',
                        'file_path' => 'demo/docs/legal-product-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.pdf',
                        'status' => 'published',
                        'created_by' => $authorId,
                        'views' => 60 + ($i * 13),
                    ]
                );
            }
        }

        if (Schema::hasTable('public_informations')) {
            $infoTitles = [
                'Laporan Realisasi APBDes Semester I',
                'Data Penerima Bantuan Sosial Tahun 2026',
                'Daftar Program Prioritas Pembangunan Desa',
                'Informasi Standar Pelayanan Administrasi',
                'Laporan Kinerja Pemerintah Desa',
            ];

            foreach ($infoTitles as $i => $title) {
                PublicInformation::query()->updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'description' => 'Informasi publik yang dapat diakses warga sesuai prinsip keterbukaan informasi.',
                        'file_path' => 'demo/docs/public-information-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.pdf',
                        'status' => 'published',
                        'created_by' => $authorId,
                        'views' => 35 + ($i * 8),
                        'published_date' => Carbon::now()->subWeeks(8 - $i)->toDateString(),
                    ]
                );
            }
        }

        if (! Schema::hasTable('ppid_sections')) {
            return;
        }

        $sections = [
            ['title' => 'Informasi Berkala', 'type' => 'berkala', 'sort_order' => 1],
            ['title' => 'Informasi Serta Merta', 'type' => 'serta_merta', 'sort_order' => 2],
            ['title' => 'Informasi Setiap Saat', 'type' => 'setiap_saat', 'sort_order' => 3],
        ];

        $sectionModels = [];
        foreach ($sections as $section) {
            $model = PpidSection::query()->updateOrCreate(
                ['type' => $section['type'], 'title' => $section['title']],
                [
                    'sort_order' => $section['sort_order'],
                    'is_active' => true,
                    'created_by' => $authorId,
                ]
            );
            $sectionModels[$section['type']] = $model;
        }

        if (! Schema::hasTable('ppid_documents')) {
            return;
        }

        $docs = [
            ['type' => 'berkala', 'title' => 'Laporan Keuangan Triwulan I', 'file' => 'demo/docs/ppid-berkala-01.pdf'],
            ['type' => 'berkala', 'title' => 'Profil Singkat Pemerintah Desa', 'file' => 'demo/docs/ppid-berkala-02.pdf'],
            ['type' => 'serta_merta', 'title' => 'Informasi Tanggap Bencana Banjir', 'file' => 'demo/docs/ppid-serta-merta-01.pdf'],
            ['type' => 'setiap_saat', 'title' => 'Daftar Informasi Publik Desa', 'file' => 'demo/docs/ppid-setiap-saat-01.pdf'],
            ['type' => 'setiap_saat', 'title' => 'SOP Permohonan Informasi PPID', 'file' => 'demo/docs/ppid-setiap-saat-02.pdf'],
        ];

        foreach ($docs as $i => $doc) {
            $section = $sectionModels[$doc['type']] ?? null;
            if (! $section) {
                continue;
            }

            PpidDocument::query()->updateOrCreate(
                ['ppid_section_id' => $section->id, 'title' => $doc['title']],
                [
                    'file_path' => $doc['file'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                    'created_by' => $authorId,
                ]
            );
        }
    }
}
