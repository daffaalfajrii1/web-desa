<?php

namespace App\Http\Controllers\Public;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\LegalProduct;
use App\Models\Page;
use App\Models\Post;
use App\Models\ProfileMenu;
use App\Models\PublicInformation;
use App\Models\SelfService;
use App\Models\Shop;
use App\Models\Tourism;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SearchController extends BasePublicController
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = collect();

        if ($q !== '') {
            $results = $results
                ->merge($this->menuResults($q))
                ->merge($this->profileMenuResults($q))
                ->merge($this->pageResults($q))
                ->merge($this->serviceResults($q))
                ->merge($this->modelResults(Post::class, 'posts', $q, 'Berita', 'public.posts.show', ['title', 'excerpt', 'content']))
                ->merge($this->modelResults(LegalProduct::class, 'legal_products', $q, 'Produk Hukum', 'public.legal-products.show', ['title', 'number', 'document_type', 'description']))
                ->merge($this->modelResults(PublicInformation::class, 'public_informations', $q, 'Informasi Publik', 'public.public-informations.show', ['title', 'description']))
                ->merge($this->modelResults(Shop::class, 'shops', $q, 'Lapak', 'public.shops.show', ['title', 'excerpt', 'description', 'seller_name', 'location']))
                ->merge($this->modelResults(Tourism::class, 'tourisms', $q, 'Wisata', 'public.tourism.show', ['title', 'excerpt', 'description', 'address', 'facilities']))
                ->merge($this->modelResults(Gallery::class, 'galleries', $q, 'Galeri', 'public.galleries.show', ['title', 'description', 'location']))
                ->merge($this->modelResults(Agenda::class, 'agendas', $q, 'Agenda', 'public.agendas.show', ['title', 'description', 'location', 'organizer']))
                ->merge($this->modelResults(Announcement::class, 'announcements', $q, 'Pengumuman', 'public.announcements.show', ['title', 'excerpt', 'content']))
                ->take(96)
                ->values();
        }

        return view('public.search.index', [
            'q' => $q,
            'results' => $results,
        ]);
    }

    private function menuResults(string $q)
    {
        $menus = collect([
            ['title' => 'Beranda', 'type' => 'Menu', 'href' => route('home'), 'description' => 'Halaman utama website desa.'],
            ['title' => 'Layanan Desa', 'type' => 'Menu', 'href' => route('public.services.index'), 'description' => 'Layanan mandiri, pengaduan, dan dokumen publik.'],
            ['title' => 'Cek Progres Layanan', 'type' => 'Layanan', 'href' => route('public.self-services.status'), 'description' => 'Cek status layanan mandiri dengan nomor registrasi.'],
            ['title' => 'Infografis', 'type' => 'Menu', 'href' => route('public.infographics.index'), 'description' => 'Pusat data statistik desa.'],
            ['title' => 'Lapak Desa', 'type' => 'Menu', 'href' => route('public.shops.index'), 'description' => 'Produk warga dan UMKM desa.'],
            ['title' => 'Wisata Desa', 'type' => 'Menu', 'href' => route('public.tourism.index'), 'description' => 'Destinasi dan potensi wisata desa.'],
            ['title' => 'Produk Hukum', 'type' => 'Menu', 'href' => route('public.legal-products.index'), 'description' => 'Regulasi dan dokumen hukum desa.'],
            ['title' => 'Informasi Publik', 'type' => 'Menu', 'href' => route('public.public-informations.index'), 'description' => 'Dokumen dan informasi yang dapat diakses masyarakat.'],
            ['title' => 'Pengaduan', 'type' => 'Menu', 'href' => route('public.complaints.create'), 'description' => 'Form pengaduan warga.'],
            ['title' => 'Peta Desa', 'type' => 'Menu', 'href' => route('public.map'), 'description' => 'Lokasi dan peta wilayah desa.'],
            ['title' => 'Struktur organisasi', 'type' => 'Menu', 'href' => route('public.profile.structure'), 'description' => 'Perangkat desa dan jabatan SOTK.'],
            ['title' => 'Profil desa', 'type' => 'Menu', 'href' => route('public.profile'), 'description' => 'Ringkasan profil dan pemerintahan desa.'],
            ['title' => 'Berita desa', 'type' => 'Menu', 'href' => route('public.posts.index'), 'description' => 'Berita dan artikel pemerintahan desa.'],
            ['title' => 'Galeri', 'type' => 'Menu', 'href' => route('public.galleries.index'), 'description' => 'Foto dan video kegiatan desa.'],
            ['title' => 'Agenda', 'type' => 'Menu', 'href' => route('public.agendas.index'), 'description' => 'Jadwal kegiatan dan acara desa.'],
            ['title' => 'Pengumuman', 'type' => 'Menu', 'href' => route('public.announcements.index'), 'description' => 'Pengumuman resmi bagi warga.'],
            ['title' => 'Data penduduk ringkas', 'type' => 'Infografis', 'href' => route('public.infographics.population-summary'), 'description' => 'Ringkasan statistik kependudukan.'],
            ['title' => 'APBDes', 'type' => 'Infografis', 'href' => route('public.infographics.apbdes'), 'description' => 'Anggaran pendapatan dan belanja desa.'],
            ['title' => 'IDM Desa', 'type' => 'Infografis', 'href' => route('public.infographics.idm'), 'description' => 'Indeks desa membangun.'],
            ['title' => 'Bansos', 'type' => 'Infografis', 'href' => route('public.infographics.bansos-program'), 'description' => 'Program bantuan sosial desa.'],
            ['title' => 'PPID', 'type' => 'Menu', 'href' => route('public.ppid.index'), 'description' => 'Informasi publik sesuai UU KIP.'],
        ]);

        $needle = Str::lower($q);

        return $menus
            ->filter(fn ($item) => Str::contains(Str::lower($item['title'].' '.$item['description'].' '.$item['type']), $needle))
            ->values();
    }

    private function profileMenuResults(string $q)
    {
        return $this->fromTable('profile_menus', function () use ($q) {
            return ProfileMenu::query()
                ->with('page')
                ->where('is_active', true)
                ->whereHas('page', fn (Builder $query) => $query->where('status', 'published'))
                ->where(function (Builder $query) use ($q) {
                    $query->where('title', 'like', '%'.$q.'%')
                        ->orWhere('slug', 'like', '%'.$q.'%');
                })
                ->orderBy('sort_order')
                ->limit(12)
                ->get()
                ->map(fn (ProfileMenu $menu) => [
                    'title' => $menu->title,
                    'type' => 'Menu profil',
                    'href' => route('public.profile.menu', $menu->slug),
                    'description' => Str::limit(strip_tags((string) ($menu->page?->excerpt ?? $menu->page?->content ?? '')), 150),
                ]);
        }, collect());
    }

    private function pageResults(string $q)
    {
        return $this->fromTable('pages', function () use ($q) {
            return Page::query()
                ->where('status', 'published')
                ->where(function (Builder $query) use ($q) {
                    $query->where('title', 'like', '%'.$q.'%')
                        ->orWhere('excerpt', 'like', '%'.$q.'%')
                        ->orWhere('content', 'like', '%'.$q.'%');
                })
                ->limit(12)
                ->get()
                ->map(fn (Page $page) => [
                    'title' => $page->title,
                    'type' => 'Halaman Profil',
                    'href' => route('public.profile.page', $page->slug),
                    'description' => Str::limit(strip_tags((string) ($page->excerpt ?: $page->content)), 150),
                ]);
        }, collect());
    }

    private function serviceResults(string $q)
    {
        return $this->fromTable('self_services', function () use ($q) {
            return SelfService::query()
                ->where('is_active', true)
                ->where(function (Builder $query) use ($q) {
                    $query->where('service_name', 'like', '%'.$q.'%')
                        ->orWhere('service_code', 'like', '%'.$q.'%')
                        ->orWhere('description', 'like', '%'.$q.'%')
                        ->orWhere('requirements', 'like', '%'.$q.'%');
                })
                ->limit(12)
                ->get()
                ->map(fn (SelfService $service) => [
                    'title' => $service->service_name,
                    'type' => 'Layanan Mandiri',
                    'href' => route('public.self-services.show', $service),
                    'description' => Str::limit(strip_tags((string) $service->description), 150),
                ]);
        }, collect());
    }

    private function modelResults(string $model, string $table, string $q, string $type, string $route, array $columns)
    {
        return $this->fromTable($table, function () use ($model, $table, $q, $type, $route, $columns) {
            $searchColumns = collect($columns)
                ->filter(fn (string $column) => Schema::hasColumn($table, $column))
                ->values();

            if ($searchColumns->isEmpty()) {
                return collect();
            }

            return $model::query()
                ->when(Schema::hasColumn($table, 'status') && $table !== 'shops', fn (Builder $query) => $query->where('status', 'published'))
                ->when(Schema::hasColumn($table, 'is_active'), fn (Builder $query) => $query->where('is_active', true))
                ->where(function (Builder $query) use ($q, $searchColumns) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', '%'.$q.'%');
                    }
                })
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn ($item) => [
                    'title' => $item->title,
                    'type' => $type,
                    'href' => route($route, $item->slug ?? $item->getKey()),
                    'description' => Str::limit(strip_tags((string) ($item->excerpt ?? $item->description ?? $item->content ?? '')), 150),
                ]);
        }, collect());
    }
}
