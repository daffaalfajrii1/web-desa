@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php
    $roles = auth()->user()->getRoleNames()->implode(', ') ?: 'Tanpa Role';
    $villageName = $village?->village_name ?: 'Web Desa';
    $logoUrl = $village?->logo_url ?: 'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png';
    $location = collect([$village?->district_name, $village?->regency_name, $village?->province_name])
        ->filter()
        ->implode(', ');
    $contactItems = collect([
        ['icon' => 'fas fa-phone', 'text' => $village?->phone],
        ['icon' => 'fas fa-envelope', 'text' => $village?->email],
        ['icon' => 'fab fa-whatsapp', 'text' => $village?->whatsapp],
    ])->filter(fn ($item) => filled($item['text']));
    $attendanceLabels = \App\Models\Attendance::statusLabels();
    $attendanceBadges = \App\Models\Attendance::statusBadgeClasses();
@endphp

@once
    <style>
        .dashboard-hero {
            min-height: 220px;
            border: 0;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(135deg, #0f766e 0%, #2563eb 100%);
            color: #fff;
        }

        .dashboard-hero .card-body {
            position: relative;
            z-index: 2;
        }

        .dashboard-hero:after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -58px;
            bottom: -70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
        }

        .dashboard-logo {
            width: 86px;
            height: 86px;
            border-radius: 18px;
            background: #fff;
            object-fit: contain;
            padding: 8px;
            box-shadow: 0 14px 28px rgba(15, 23, 42, .18);
        }

        .dashboard-contact-chip {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            margin: 4px 6px 0 0;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
            color: #fff;
            font-size: 13px;
            word-break: break-word;
        }

        .dashboard-contact-chip i {
            margin-right: 6px;
        }

        .welcome-card {
            min-height: 220px;
            border: 0;
            border-radius: 14px;
        }

        .welcome-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e8f1ff;
            color: #0d6efd;
            font-size: 26px;
        }

        .dashboard-small-box {
            min-height: 138px;
        }

        .dashboard-small-box .inner h3 {
            font-size: 2rem;
            font-weight: 800;
        }

        .dashboard-small-box .inner p {
            min-height: 38px;
            font-weight: 600;
        }

        .dashboard-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 14px;
        }

        .dashboard-section-title h2 {
            margin: 0;
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .metric-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 16px;
            height: 100%;
        }

        .metric-card .metric-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #2563eb;
            font-size: 18px;
        }

        .metric-card .metric-value {
            margin-top: 12px;
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
        }

        .metric-card .metric-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
        }

        .attendance-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
        }

        .latest-list .list-group-item {
            border-left: 0;
            border-right: 0;
        }

        .latest-list .list-group-item:first-child {
            border-top: 0;
        }

        .latest-list .list-group-item:last-child {
            border-bottom: 0;
        }

        .shortcut-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .shortcut-card {
            display: flex;
            align-items: center;
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            color: #1f2937;
            font-weight: 700;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .shortcut-card:hover {
            color: #1f2937;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .10);
        }

        .shortcut-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: #fff;
        }

        .system-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 12px;
        }

        .system-item {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        .system-item span {
            display: block;
            color: #6b7280;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .system-item strong {
            display: block;
            margin-top: 4px;
            color: #111827;
            font-size: 18px;
        }
    </style>
@endonce

<div class="row">
    <div class="col-xl-8 mb-3">
        <div class="card dashboard-hero position-relative h-100">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center">
                <img src="{{ $logoUrl }}" alt="Logo {{ $villageName }}" class="dashboard-logo mb-3 mb-md-0 mr-md-4">
                <div>
                    <div class="text-uppercase font-weight-bold mb-1" style="opacity:.78;">Ringkasan Desa</div>
                    <h2 class="mb-1 font-weight-bold">{{ $villageName }}</h2>
                    <div class="mb-2">
                        {{ $location ?: 'Kecamatan, kabupaten, dan provinsi belum dilengkapi.' }}
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-user-tie mr-1"></i>
                        Kepala Desa: <strong>{{ $village?->village_head_name ?: 'Belum diatur' }}</strong>
                    </div>
                    <div>
                        @forelse($contactItems as $contact)
                            <span class="dashboard-contact-chip">
                                <i class="{{ $contact['icon'] }}"></i> {{ $contact['text'] }}
                            </span>
                        @empty
                            <span class="dashboard-contact-chip">
                                <i class="fas fa-info-circle"></i> Kontak desa belum dilengkapi
                            </span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 mb-3">
        <div class="card welcome-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="welcome-icon"><i class="fas fa-user-shield"></i></span>
                    <span class="badge badge-primary">{{ $roles }}</span>
                </div>
                <h4 class="font-weight-bold mb-1">Halo, {{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">Selamat datang di panel admin website desa.</p>
                <div class="d-flex justify-content-between border-top pt-3">
                    <div>
                        <div class="text-muted small font-weight-bold">Tanggal</div>
                        <div>{{ $today->locale('id')->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-muted small font-weight-bold">Jam</div>
                        <div id="dashboard_clock">{{ $today->format('H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-section-title">
    <h2>Statistik Cepat</h2>
</div>

<div class="row">
    @foreach($summaryCards as $card)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <div class="small-box dashboard-small-box {{ $card['color'] }}">
                <div class="inner">
                    <h3>{{ number_format($card['value'], 0, ',', '.') }}</h3>
                    <p>{{ $card['label'] }}</p>
                </div>
                <div class="icon"><i class="{{ $card['icon'] }}"></i></div>
                <a href="{{ $card['url'] }}" class="small-box-footer">
                    Lihat detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Ringkasan Layanan</h3>
                <div>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-outline-danger btn-sm">Pengaduan</a>
                    <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-outline-primary btn-sm">Layanan</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach([
                        ['label' => 'Pengaduan Total', 'value' => $serviceSummary['complaints_total'], 'icon' => 'fas fa-comments'],
                        ['label' => 'Masuk', 'value' => $serviceSummary['complaints_incoming'], 'icon' => 'fas fa-inbox'],
                        ['label' => 'Diproses', 'value' => $serviceSummary['complaints_processing'], 'icon' => 'fas fa-spinner'],
                        ['label' => 'Selesai', 'value' => $serviceSummary['complaints_done'], 'icon' => 'fas fa-check-circle'],
                        ['label' => 'Layanan Aktif', 'value' => $serviceSummary['active_services'], 'icon' => 'fas fa-concierge-bell'],
                        ['label' => 'Pengajuan 7 Hari', 'value' => $serviceSummary['latest_submissions'], 'icon' => 'fas fa-file-signature'],
                    ] as $metric)
                        <div class="col-md-4 col-6 mb-3">
                            <div class="metric-card">
                                <span class="metric-icon"><i class="{{ $metric['icon'] }}"></i></span>
                                <div class="metric-value">{{ number_format($metric['value'], 0, ',', '.') }}</div>
                                <div class="metric-label">{{ $metric['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Absensi Hari Ini</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($attendanceToday as $status => $total)
                        <div class="col-md-6 mb-3">
                            <div class="attendance-pill">
                                <span>
                                    <span class="badge {{ $attendanceBadges[$status] ?? 'badge-secondary' }} mr-2">
                                        {{ $attendanceLabels[$status] ?? ucfirst($status) }}
                                    </span>
                                </span>
                                <strong>{{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-qrcode mr-1"></i> Lihat Rekap Absensi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title mb-0">Ringkasan Infografis / Data Desa</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($infographicSummary as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                    <a href="{{ $item['url'] }}" class="info-box mb-0 text-decoration-none">
                        <span class="info-box-icon bg-light">
                            <i class="{{ $item['icon'] }} text-primary"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted">{{ $item['label'] }}</span>
                            <span class="info-box-number text-dark">{{ number_format($item['value'], 0, ',', '.') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="dashboard-section-title">
    <h2>Data Terbaru</h2>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Berita Terbaru</h3>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-link btn-sm">Lihat semua</a>
            </div>
            <div class="list-group list-group-flush latest-list">
                @forelse($latestPosts as $post)
                    <div class="list-group-item">
                        <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($post->title, 52) }}</div>
                        <small class="text-muted">{{ $post->created_at?->format('d-m-Y H:i') ?: '-' }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Belum ada berita.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Pengaduan Terbaru</h3>
                <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-link btn-sm">Lihat semua</a>
            </div>
            <div class="list-group list-group-flush latest-list">
                @forelse($latestComplaints as $complaint)
                    <div class="list-group-item">
                        <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($complaint->subject, 52) }}</div>
                        <small class="text-muted">{{ $complaint->name ?: '-' }} · {{ $complaint->submitted_at?->format('d-m-Y H:i') ?: '-' }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Belum ada pengaduan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Pengajuan Layanan</h3>
                <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-link btn-sm">Lihat semua</a>
            </div>
            <div class="list-group list-group-flush latest-list">
                @forelse($latestSubmissions as $submission)
                    <div class="list-group-item">
                        <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($submission->display_applicant_name, 52) }}</div>
                        <small class="text-muted">{{ $submission->service?->service_name ?: 'Layanan' }} · {{ $submission->submitted_at?->format('d-m-Y H:i') ?: '-' }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Belum ada pengajuan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Agenda Terbaru</h3>
                <a href="{{ route('admin.agenda.index') }}" class="btn btn-link btn-sm">Lihat semua</a>
            </div>
            <div class="list-group list-group-flush latest-list">
                @forelse($latestAgendas as $agenda)
                    <div class="list-group-item">
                        <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($agenda->title, 52) }}</div>
                        <small class="text-muted">{{ $agenda->start_date?->format('d-m-Y') ?: '-' }} · {{ $agenda->location ?: '-' }}</small>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Belum ada agenda.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Shortcut Menu</h3>
            </div>
            <div class="card-body">
                <div class="shortcut-grid">
                    @foreach($shortcuts as $shortcut)
                        <a href="{{ $shortcut['url'] }}" class="shortcut-card">
                            <span class="shortcut-icon bg-{{ $shortcut['color'] }}">
                                <i class="{{ $shortcut['icon'] }}"></i>
                            </span>
                            <span>{{ $shortcut['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Informasi Sistem</h3>
            </div>
            <div class="card-body">
                <div class="system-row">
                    <div class="system-item">
                        <span>User Admin</span>
                        <strong>{{ number_format($systemInfo['users'], 0, ',', '.') }}</strong>
                    </div>
                    <div class="system-item">
                        <span>Role</span>
                        <strong>{{ number_format($systemInfo['roles'], 0, ',', '.') }}</strong>
                    </div>
                    <div class="system-item">
                        <span>Tema Aktif</span>
                        <strong>{{ ucfirst($systemInfo['active_theme']) }}</strong>
                    </div>
                    <div class="system-item">
                        <span>Identitas Desa</span>
                        <strong>{{ $systemInfo['identity_status'] }}</strong>
                    </div>
                    <div class="system-item">
                        <span>Logo Desa</span>
                        <strong>{{ $systemInfo['has_logo'] ? 'Sudah ada' : 'Belum ada' }}</strong>
                    </div>
                    <div class="system-item">
                        <span>Banner Desa</span>
                        <strong>{{ $systemInfo['has_banner'] ? $systemInfo['banner_total'] . ' banner' : 'Belum ada' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clock = document.getElementById('dashboard_clock');

        function updateClock() {
            const now = new Date();
            clock.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        if (clock) {
            updateClock();
            setInterval(updateClock, 30000);
        }
    });
</script>
@endpush
