<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Apbdes;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\IdmSummary;
use App\Models\LegalProduct;
use App\Models\PopulationSummary;
use App\Models\Post;
use App\Models\ProfileMenu;
use App\Models\PublicInformation;
use App\Models\SelfService;
use App\Models\Shop;
use App\Models\SocialAssistanceProgram;
use App\Models\Tourism;
use App\Models\VillageBanner;
use App\Services\Public\ThemeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class HomeController extends Controller
{
    public function __invoke(ThemeService $themeService)
    {
        $village = $themeService->villageSetting();
        if ($village) {
            $village->load('villageHeadEmployee');
        }

        $welcomePlain = trim(strip_tags((string) ($village?->welcome_message ?? '')));

        return view('public.home', [
            'village' => $village,
            'carouselIntro' => $welcomePlain !== ''
                ? Str::limit($welcomePlain, 240)
                : 'Portal informasi dan layanan digital desa - transparan, mudah diakses, dan mendukung partisipasi warga.',
            'headPhotoUrl' => $village?->resolvePublicHeadPhotoUrl(),
            'theme' => $themeService->resolve($village),
            'banners' => $this->banners(),
            'posts' => $this->latest(Post::class, 'posts', 'published_at', 3),
            'legalProducts' => $this->latest(LegalProduct::class, 'legal_products', 'published_date', 3),
            'publicInformations' => $this->latest(PublicInformation::class, 'public_informations', 'published_date', 3),
            'announcements' => $this->latest(Announcement::class, 'announcements', 'created_at', 2),
            'agendas' => $this->latest(Agenda::class, 'agendas', 'start_date', 3),
            'shops' => $this->featured(Shop::class, 'shops', 4),
            'tourism' => $this->featured(Tourism::class, 'tourisms', 3),
            'galleries' => $this->galleries(),
            'employees' => $this->employees(),
            'profileMenus' => $this->profileMenus(),
            'infographics' => $this->infographics(),
            'serviceCount' => $this->activeCount(SelfService::class, 'self_services'),
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    private function banners()
    {
        return $this->fromTable('village_banners', fn () => VillageBanner::query()
            ->active()
            ->ordered()
            ->limit(4)
            ->get(), collect());
    }

    private function latest(string $model, string $table, string $dateColumn, int $limit)
    {
        return $this->fromTable($table, function () use ($model, $table, $dateColumn, $limit) {
            return $model::query()
                ->when(Schema::hasColumn($table, 'status') && $table !== 'shops', fn (Builder $query) => $query->where('status', 'published'))
                ->when(Schema::hasColumn($table, $dateColumn), fn (Builder $query) => $query->orderByDesc($dateColumn))
                ->latest('created_at')
                ->limit($limit)
                ->get();
        }, collect());
    }

    private function featured(string $model, string $table, int $limit)
    {
        return $this->fromTable($table, function () use ($model, $table, $limit) {
            return $model::query()
                ->when(Schema::hasColumn($table, 'is_active'), fn (Builder $query) => $query->where('is_active', true))
                ->when(Schema::hasColumn($table, 'status') && $table !== 'shops', fn (Builder $query) => $query->where('status', 'published'))
                ->when(Schema::hasColumn($table, 'is_featured'), fn (Builder $query) => $query->orderByDesc('is_featured'))
                ->latest()
                ->limit($limit)
                ->get();
        }, collect());
    }

    private function galleries()
    {
        return $this->fromTable('galleries', fn () => Gallery::query()
            ->where('status', Gallery::STATUS_PUBLISHED)
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get(), collect());
    }

    private function employees()
    {
        return $this->fromTable('employees', fn () => Employee::query()
            ->with('employeePosition')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(12)
            ->get(), collect());
    }

    private function profileMenus()
    {
        return $this->fromTable('profile_menus', fn () => ProfileMenu::query()
            ->with('page')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get(), collect());
    }

    private function infographics(): array
    {
        $population = $this->fromTable('population_summaries', function () {
            $year = PopulationSummary::query()->max('year');

            if (! $year) {
                return null;
            }

            $query = PopulationSummary::query()->where('year', $year);

            return [
                'label' => 'Penduduk',
                'value' => number_format((int) $query->sum('male_count') + (int) $query->sum('female_count'), 0, ',', '.'),
                'meta' => 'Tahun '.$year,
                'href' => route('public.infographics.population-summary'),
            ];
        });

        $apbdes = $this->fromTable('apbdes', function () {
            $summary = Apbdes::query()
                ->where('is_active', true)
                ->latest('year')
                ->first();

            return $summary ? [
                'label' => 'APBDes',
                'value' => 'Rp '.number_format((float) $summary->pendapatan, 0, ',', '.'),
                'meta' => 'Pendapatan '.$summary->year,
                'href' => route('public.infographics.apbdes'),
            ] : null;
        });

        $idm = $this->fromTable('idm_summaries', function () {
            $summary = IdmSummary::query()
                ->where('is_active', true)
                ->latest('year')
                ->first();

            return $summary ? [
                'label' => 'Status IDM',
                'value' => $summary->idm_status ?: 'Belum diisi',
                'meta' => $summary->year ? 'Tahun '.$summary->year : 'Data terbaru',
                'href' => route('public.infographics.idm'),
            ] : null;
        });

        $bansos = $this->fromTable('social_assistance_programs', function () {
            $count = SocialAssistanceProgram::query()
                ->where('is_active', true)
                ->count();

            return [
                'label' => 'Bansos',
                'value' => number_format($count, 0, ',', '.'),
                'meta' => 'Program aktif',
                'href' => route('public.infographics.bansos-program'),
            ];
        });

        return collect([$population, $apbdes, $idm, $bansos])->filter()->values()->all();
    }

    private function activeCount(string $model, string $table): int
    {
        return $this->fromTable($table, fn () => (int) $model::query()
            ->when(Schema::hasColumn($table, 'is_active'), fn (Builder $query) => $query->where('is_active', true))
            ->count(), 0);
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::url($path);
    }

    private function fromTable(string $table, callable $callback, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable($table)) {
                return $default;
            }

            return $callback();
        } catch (Throwable) {
            return $default;
        }
    }
}
