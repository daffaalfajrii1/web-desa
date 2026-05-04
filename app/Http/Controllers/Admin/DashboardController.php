<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Apbdes;
use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\Employee;
use App\Models\Hamlet;
use App\Models\IdmSummary;
use App\Models\LegalProduct;
use App\Models\Post;
use App\Models\PopulationStat;
use App\Models\PublicInformation;
use App\Models\SdgsSummary;
use App\Models\SelfService;
use App\Models\SelfServiceSubmission;
use App\Models\SocialAssistanceProgram;
use App\Models\StuntingRecord;
use App\Models\User;
use App\Models\VillageBanner;
use App\Models\VillageSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now();
        $village = $this->tableExistsFor(VillageSetting::class)
            ? VillageSetting::with('villageHeadEmployee')->first()
            : null;

        $attendanceToday = $this->attendanceTodaySummary();
        $latestSubmissionsCount = $this->countFor(SelfServiceSubmission::class, function (Builder $query) use ($today) {
            $query->where('submitted_at', '>=', $today->copy()->subDays(7));
        });

        return view('admin.dashboard', [
            'village' => $village,
            'today' => $today,
            'summaryCards' => $this->summaryCards($attendanceToday),
            'serviceSummary' => $this->serviceSummary($latestSubmissionsCount),
            'infographicSummary' => $this->infographicSummary(),
            'attendanceToday' => $attendanceToday,
            'latestPosts' => $this->latestFor(Post::class, 5),
            'latestComplaints' => $this->latestComplaints(),
            'latestSubmissions' => $this->latestSubmissions(),
            'latestAgendas' => $this->latestFor(Agenda::class, 5, fn (Builder $query) => $query->latest('start_date')),
            'shortcuts' => $this->shortcuts(),
            'systemInfo' => $this->systemInfo($village),
        ]);
    }

    private function summaryCards(array $attendanceToday): array
    {
        return [
            [
                'label' => 'Berita',
                'value' => $this->countFor(Post::class),
                'icon' => 'fas fa-newspaper',
                'color' => 'bg-info',
                'url' => route('admin.berita.index'),
            ],
            [
                'label' => 'Produk Hukum',
                'value' => $this->countFor(LegalProduct::class),
                'icon' => 'fas fa-balance-scale',
                'color' => 'bg-warning',
                'url' => route('admin.produk-hukum.index'),
            ],
            [
                'label' => 'Informasi Publik',
                'value' => $this->countFor(PublicInformation::class),
                'icon' => 'fas fa-info-circle',
                'color' => 'bg-primary',
                'url' => route('admin.informasi-publik.index'),
            ],
            [
                'label' => 'Pengumuman',
                'value' => $this->countFor(Announcement::class),
                'icon' => 'fas fa-bullhorn',
                'color' => 'bg-success',
                'url' => route('admin.pengumuman.index'),
            ],
            [
                'label' => 'Agenda',
                'value' => $this->countFor(Agenda::class),
                'icon' => 'fas fa-calendar-alt',
                'color' => 'bg-teal',
                'url' => route('admin.agenda.index'),
            ],
            [
                'label' => 'Layanan Aktif',
                'value' => $this->countFor(SelfService::class, fn (Builder $query) => $query->where('is_active', true)),
                'icon' => 'fas fa-concierge-bell',
                'color' => 'bg-indigo',
                'url' => route('admin.layanan-mandiri.index'),
            ],
            [
                'label' => 'Pengaduan Masuk',
                'value' => $this->countFor(Complaint::class, fn (Builder $query) => $query->where('status', 'masuk')),
                'icon' => 'fas fa-inbox',
                'color' => 'bg-danger',
                'url' => route('admin.pengaduan.index'),
            ],
            [
                'label' => 'Pegawai Aktif',
                'value' => $this->countFor(Employee::class, fn (Builder $query) => $query->where('is_active', true)),
                'icon' => 'fas fa-users',
                'color' => 'bg-secondary',
                'url' => route('admin.pegawai.index'),
            ],
            [
                'label' => 'Banner Aktif',
                'value' => $this->countFor(VillageBanner::class, fn (Builder $query) => $query->where('is_active', true)),
                'icon' => 'fas fa-images',
                'color' => 'bg-purple',
                'url' => route('admin.settings.desa-banners.index'),
            ],
            [
                'label' => 'Hadir Hari Ini',
                'value' => $attendanceToday[Attendance::STATUS_HADIR] ?? 0,
                'icon' => 'fas fa-user-check',
                'color' => 'bg-success',
                'url' => route('admin.absensi.index'),
            ],
            [
                'label' => 'Pengaduan Diproses',
                'value' => $this->countFor(Complaint::class, fn (Builder $query) => $query->where('status', 'diproses')),
                'icon' => 'fas fa-spinner',
                'color' => 'bg-orange',
                'url' => route('admin.pengaduan.index'),
            ],
            [
                'label' => 'Pengajuan 7 Hari',
                'value' => $this->countFor(SelfServiceSubmission::class, fn (Builder $query) => $query->where('submitted_at', '>=', now()->subDays(7))),
                'icon' => 'fas fa-file-signature',
                'color' => 'bg-cyan',
                'url' => route('admin.layanan-mandiri.index'),
            ],
        ];
    }

    private function serviceSummary(int $latestSubmissionsCount): array
    {
        return [
            'complaints_total' => $this->countFor(Complaint::class),
            'complaints_incoming' => $this->countFor(Complaint::class, fn (Builder $query) => $query->where('status', 'masuk')),
            'complaints_processing' => $this->countFor(Complaint::class, fn (Builder $query) => $query->where('status', 'diproses')),
            'complaints_done' => $this->countFor(Complaint::class, fn (Builder $query) => $query->where('status', 'selesai')),
            'active_services' => $this->countFor(SelfService::class, fn (Builder $query) => $query->where('is_active', true)),
            'latest_submissions' => $latestSubmissionsCount,
        ];
    }

    private function infographicSummary(): array
    {
        return [
            [
                'label' => 'Dusun',
                'value' => $this->countFor(Hamlet::class),
                'icon' => 'fas fa-map',
                'url' => route('admin.hamlets.index'),
            ],
            [
                'label' => 'Statistik Penduduk',
                'value' => $this->countFor(PopulationStat::class),
                'icon' => 'fas fa-chart-bar',
                'url' => route('admin.population-stats.index'),
            ],
            [
                'label' => 'APBDes',
                'value' => $this->countFor(Apbdes::class),
                'icon' => 'fas fa-wallet',
                'url' => route('admin.apbdes.index'),
            ],
            [
                'label' => 'Program Bansos',
                'value' => $this->countFor(SocialAssistanceProgram::class),
                'icon' => 'fas fa-hands-helping',
                'url' => route('admin.bansos-program.index'),
            ],
            [
                'label' => 'Stunting',
                'value' => $this->countFor(StuntingRecord::class),
                'icon' => 'fas fa-child',
                'url' => route('admin.stunting-records.index'),
            ],
            [
                'label' => 'IDM',
                'value' => $this->countFor(IdmSummary::class),
                'icon' => 'fas fa-layer-group',
                'url' => route('admin.idm-summaries.index'),
            ],
            [
                'label' => 'SDGS',
                'value' => $this->countFor(SdgsSummary::class),
                'icon' => 'fas fa-bullseye',
                'url' => route('admin.sdgs-summaries.index'),
            ],
        ];
    }

    private function attendanceTodaySummary(): array
    {
        $summary = array_fill_keys(Attendance::statuses(), 0);

        if (! $this->tableExistsFor(Attendance::class)) {
            return $summary;
        }

        $counts = Attendance::query()
            ->whereDate('attendance_date', now()->toDateString())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        foreach (Attendance::statuses() as $status) {
            $summary[$status] = (int) ($counts[$status] ?? 0);
        }

        return $summary;
    }

    private function latestComplaints(): Collection
    {
        if (! $this->tableExistsFor(Complaint::class)) {
            return collect();
        }

        return Complaint::query()
            ->latest('submitted_at')
            ->limit(5)
            ->get();
    }

    private function latestSubmissions(): Collection
    {
        if (! $this->tableExistsFor(SelfServiceSubmission::class)) {
            return collect();
        }

        return SelfServiceSubmission::query()
            ->with('service')
            ->latest('submitted_at')
            ->limit(5)
            ->get();
    }

    private function shortcuts(): array
    {
        return [
            ['label' => 'Identitas Desa', 'icon' => 'fas fa-building', 'url' => route('admin.settings.desa.edit'), 'color' => 'primary'],
            ['label' => 'Berita', 'icon' => 'fas fa-newspaper', 'url' => route('admin.berita.index'), 'color' => 'info'],
            ['label' => 'Produk Hukum', 'icon' => 'fas fa-balance-scale', 'url' => route('admin.produk-hukum.index'), 'color' => 'warning'],
            ['label' => 'Pengaduan', 'icon' => 'fas fa-comments', 'url' => route('admin.pengaduan.index'), 'color' => 'danger'],
            ['label' => 'Layanan Mandiri', 'icon' => 'fas fa-concierge-bell', 'url' => route('admin.layanan-mandiri.index'), 'color' => 'success'],
            ['label' => 'Pegawai / SOTK', 'icon' => 'fas fa-users', 'url' => route('admin.pegawai.index'), 'color' => 'secondary'],
            ['label' => 'Absensi', 'icon' => 'fas fa-qrcode', 'url' => route('admin.absensi.index'), 'color' => 'dark'],
            ['label' => 'Carousel Desa', 'icon' => 'fas fa-images', 'url' => route('admin.settings.desa-banners.index'), 'color' => 'purple'],
        ];
    }

    private function systemInfo(?VillageSetting $village): array
    {
        $identityChecks = [
            filled($village?->village_name),
            filled($village?->district_name),
            filled($village?->regency_name),
            filled($village?->province_name),
            filled($village?->address),
            filled($village?->village_head_name),
        ];

        $completedIdentity = collect($identityChecks)->filter()->count();

        return [
            'users' => $this->countFor(User::class),
            'roles' => $this->countFor(Role::class),
            'active_theme' => $village?->active_theme ?: 'default',
            'identity_status' => $completedIdentity . '/' . count($identityChecks) . ' data utama',
            'identity_complete' => $completedIdentity === count($identityChecks),
            'has_logo' => filled($village?->logo_url),
            'banner_total' => $this->countFor(VillageBanner::class),
            'has_banner' => $this->countFor(VillageBanner::class) > 0,
        ];
    }

    private function latestFor(string $modelClass, int $limit = 5, ?\Closure $callback = null): Collection
    {
        if (! $this->tableExistsFor($modelClass)) {
            return collect();
        }

        $query = $modelClass::query();

        if ($callback) {
            $callback($query);
        } else {
            $query->latest();
        }

        return $query->limit($limit)->get();
    }

    private function countFor(string $modelClass, ?\Closure $callback = null): int
    {
        if (! $this->tableExistsFor($modelClass)) {
            return 0;
        }

        $query = $modelClass::query();

        if ($callback) {
            $callback($query);
        }

        return (int) $query->count();
    }

    private function tableExistsFor(string $modelClass): bool
    {
        return Schema::hasTable((new $modelClass)->getTable());
    }
}
