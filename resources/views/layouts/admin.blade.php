<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Web Desa')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayScrollbars/css/OverlayScrollbars.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-bg: #2f3640;
            --sidebar-hover: rgba(255,255,255,0.08);
            --sidebar-active: #0d6efd;
            --sidebar-text: rgba(255,255,255,0.88);
            --sidebar-muted: rgba(255,255,255,0.55);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-size: 15px;
        }

        .main-sidebar {
            background: var(--sidebar-bg);
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.12);
        }

        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-top: .9rem;
            padding-bottom: .9rem;
            background: rgba(255,255,255,.02);
        }

        .brand-link .brand-text {
            font-weight: 600 !important;
            letter-spacing: .2px;
            color: #fff;
        }

        .brand-image {
            object-fit: cover;
        }

        .sidebar {
            height: calc(100vh - 57px);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.15);
            border-radius: 10px;
        }

        .user-panel {
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-bottom: 1rem !important;
        }

        .user-panel .info a {
            color: #fff !important;
            font-weight: 600;
        }

        .user-panel .info small {
            display: block;
            color: var(--sidebar-muted);
            margin-top: 2px;
            font-size: 13px;
        }

        .nav-sidebar .nav-header {
            color: var(--sidebar-muted) !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .9px;
            font-weight: 700;
            padding-top: .9rem;
            padding-bottom: .35rem;
            margin-left: .2rem;
        }

        .nav-sidebar .nav-item > .nav-link {
            color: var(--sidebar-text);
            border-radius: 10px;
            margin: 3px 10px;
            padding: .72rem .9rem;
            transition: all .15s ease;
        }

        .nav-sidebar .nav-item > .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-sidebar .nav-link.active {
            background: var(--sidebar-active) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(13,110,253,.25);
        }

        .nav-sidebar .nav-link .nav-icon {
            font-size: 1rem;
        }

        .nav-treeview {
            padding-bottom: .25rem;
        }

        .nav-treeview .nav-link {
            margin-left: 22px;
            margin-right: 10px;
            border-radius: 8px;
            color: rgba(255,255,255,.78) !important;
            padding-top: .58rem;
            padding-bottom: .58rem;
        }

        .nav-treeview .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff !important;
        }

        .nav-treeview .nav-link.active {
            background: rgba(13,110,253,.18) !important;
            color: #fff !important;
            box-shadow: none;
        }

        .main-header.navbar {
            border-bottom: 1px solid #dee2e6;
            background: #fff;
        }

        .content-wrapper {
            background: #f4f6f9;
            min-height: 100vh !important;
        }

        .content-header {
            padding: 18px 1rem 6px;
        }

        .content-header h1 {
            font-weight: 700;
            font-size: 2rem;
            color: #1f2937;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,.04);
        }

        .card-header {
            border-bottom: 1px solid #e5e7eb;
        }

        .btn {
            border-radius: 8px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .main-footer {
            font-weight: 600;
            color: #6b7280;
            background: #fff;
            border-top: 1px solid #e5e7eb;
        }

        .sidebar-mini.sidebar-collapse .brand-text,
        .sidebar-mini.sidebar-collapse .user-panel .info,
        .sidebar-mini.sidebar-collapse .nav-sidebar .nav-header {
            display: none !important;
        }

        .sidebar-mini.sidebar-collapse .nav-treeview {
            display: none !important;
        }

        .small-box {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .small-box .inner {
            position: relative;
            z-index: 2;
        }

        .small-box .icon {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 1;
            font-size: 56px;
            line-height: 1;
            opacity: 0.10;
            transition: transform .2s ease-in-out;
        }

        .small-box:hover .icon {
            transform: scale(1.05);
        }

        .small-box .icon i {
            color: rgba(0, 0, 0, 0.18);
        }

        .small-box h5,
        .small-box h3,
        .small-box p {
            position: relative;
            z-index: 2;
            margin-bottom: 0;
        }

        .small-box h5 {
            font-weight: 700;
            word-break: break-word;
        }

        .info-box .info-box-icon i {
            font-size: 28px;
        }

        @media (max-width: 991.98px) {
            .content-header h1 {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link" style="border:none;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img
                src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
                alt="Logo"
                class="brand-image img-circle elevation-3"
                style="opacity:.9"
            >
            <span class="brand-text">Admin Desa</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 mb-3 d-flex align-items-center">
                <div class="image">
                    <img
                        src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg"
                        class="img-circle elevation-2"
                        alt="User Image"
                    >
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                    <small>{{ implode(', ', auth()->user()->getRoleNames()->toArray()) ?: 'Tanpa Role' }}</small>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @canany(['manage settings', 'manage profil-desa'])
                        <li class="nav-header">Master Data</li>
                    @endcanany

                    @can('manage settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.desa.edit') }}"
                               class="nav-link {{ request()->routeIs('admin.settings.desa.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Identitas Desa</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage profil-desa')
                        <li class="nav-item has-treeview {{ request()->routeIs('admin.profil-desa.*') ? 'menu-open' : '' }}">
                            <a href="#"
                               class="nav-link {{ request()->routeIs('admin.profil-desa.*') ? 'active' : '' }}"
                               data-menu-key="profil-desa">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Profil Desa
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.profil-desa.halaman.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.profil-desa.halaman.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Halaman Profil</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.profil-desa.menu.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.profil-desa.menu.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Menu Profil</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @canany(['manage berita', 'manage pengumuman', 'manage agenda', 'manage produk-hukum', 'manage informasi-publik', 'manage ppid', 'manage lapak', 'manage wisata'])
                        <li class="nav-header">Informasi</li>
                    @endcanany

                    @can('manage berita')
                        <li class="nav-item">
                            <a href="{{ route('admin.berita.index') }}"
                               class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Berita</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.kategori-berita.index') }}"
                               class="nav-link {{ request()->routeIs('admin.kategori-berita.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori Berita</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage pengumuman')
                        <li class="nav-item">
                            <a href="{{ route('admin.pengumuman.index') }}"
                               class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>Pengumuman</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage agenda')
                        <li class="nav-item">
                            <a href="{{ route('admin.agenda.index') }}"
                               class="nav-link {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Agenda</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage produk-hukum')
                        <li class="nav-item">
                            <a href="{{ route('admin.produk-hukum.index') }}"
                               class="nav-link {{ request()->routeIs('admin.produk-hukum.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-balance-scale"></i>
                                <p>Produk Hukum</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.kategori-produk-hukum.index') }}"
                               class="nav-link {{ request()->routeIs('admin.kategori-produk-hukum.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori Produk Hukum</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage informasi-publik')
                        <li class="nav-item">
                            <a href="{{ route('admin.informasi-publik.index') }}"
                               class="nav-link {{ request()->routeIs('admin.informasi-publik.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-info-circle"></i>
                                <p>Informasi Publik</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage ppid')
                        <li class="nav-item has-treeview {{ request()->routeIs('admin.ppid-section.*') || request()->routeIs('admin.ppid-document.*') || request()->routeIs('admin.ppid-request.*') ? 'menu-open' : '' }}">
                            <a href="#"
                               class="nav-link {{ request()->routeIs('admin.ppid-section.*') || request()->routeIs('admin.ppid-document.*') || request()->routeIs('admin.ppid-request.*') ? 'active' : '' }}"
                               data-menu-key="ppid">
                                <i class="nav-icon fas fa-envelope-open-text"></i>
                                <p>
                                    PPID
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.ppid-section.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.ppid-section.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>FAQ / Section PPID</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.ppid-document.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.ppid-document.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dokumen PPID</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.ppid-request.index') }}"
                                       class="nav-link {{ request()->routeIs('admin.ppid-request.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Permohonan Informasi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('manage lapak')
                        <li class="nav-item">
                            <a href="{{ route('admin.lapak.index') }}"
                               class="nav-link {{ request()->routeIs('admin.lapak.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-store"></i>
                                <p>Lapak Desa</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.kategori-lapak.index') }}"
                               class="nav-link {{ request()->routeIs('admin.kategori-lapak.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori Lapak</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage wisata')
                        <li class="nav-item">
                            <a href="{{ route('admin.wisata.index') }}"
                               class="nav-link {{ request()->routeIs('admin.wisata.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marked-alt"></i>
                                <p>Wisata</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage infografis')
                        <li class="nav-item has-treeview {{ request()->routeIs('admin.hamlets.*') || request()->routeIs('admin.population-summaries.*') || request()->routeIs('admin.population-stats.*') || request()->routeIs('admin.apbdes.*') ? 'menu-open' : '' }}">
                            <a href="#"
                               class="nav-link {{ request()->routeIs('admin.hamlets.*') || request()->routeIs('admin.population-summaries.*') || request()->routeIs('admin.population-stats.*') || request()->routeIs('admin.apbdes.*') ? 'active' : '' }}"
                               data-menu-key="infografis">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    Infografis
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.hamlets.index') }}" class="nav-link {{ request()->routeIs('admin.hamlets.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Dusun</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.population-summaries.index') }}" class="nav-link {{ request()->routeIs('admin.population-summaries.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ringkasan Penduduk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.population-stats.index') }}" class="nav-link {{ request()->routeIs('admin.population-stats.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Statistik Penduduk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.apbdes.index') }}" class="nav-link {{ request()->routeIs('admin.apbdes.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>APBDes</p>
                                    </a>
                                </li>

                                <li class="nav-item">
    <a href="{{ route('admin.bansos-program.index') }}" class="nav-link {{ request()->routeIs('admin.bansos-program.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Program Bansos</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.bansos-recipient.index') }}" class="nav-link {{ request()->routeIs('admin.bansos-recipient.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Penerima Bansos</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.bansos-chart.index') }}" class="nav-link {{ request()->routeIs('admin.bansos-chart.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Chart Bansos</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.stunting-records.index') }}" class="nav-link {{ request()->routeIs('admin.stunting-records.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Stunting</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.idm-summaries.index') }}"
       class="nav-link {{ request()->routeIs('admin.idm-summaries.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>IDM</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.idm-indicators.index') }}"
       class="nav-link {{ request()->routeIs('admin.idm-indicators.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Indikator IDM</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.sdgs-summaries.index') }}" class="nav-link {{ request()->routeIs('admin.sdgs-summaries.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>SDGS Ringkasan</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.sdgs-goal-values.index') }}" class="nav-link {{ request()->routeIs('admin.sdgs-goal-values.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>SDGS Nilai Tujuan</p>
    </a>
</li>
                            </ul>
                        </li>
                    @endcan

                    @canany(['manage users', 'manage roles'])
                        <li class="nav-header">Manajemen Akses</li>
                    @endcanany

                    @can('manage users')
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage roles')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                               class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Role & Permission</p>
                            </a>
                        </li>
                    @endcan

                    @canany(['manage sotk', 'manage absensi'])
                        <li class="nav-header">Organisasi</li>
                    @endcanany

                    @can('manage sotk')
                        <li class="nav-item">
                            <a href="{{ route('admin.pegawai.index') }}"
                               class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pegawai / SOTK</p>
                            </a>
                        </li>
                    @endcan

                    @can('manage absensi')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-qrcode"></i>
                                <p>Absensi</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-1">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content pb-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        Web Desa Admin
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.main-sidebar .sidebar');
        if (sidebar) {
            const savedScroll = localStorage.getItem('admin_sidebar_scroll');
            if (savedScroll !== null) {
                sidebar.scrollTop = parseInt(savedScroll, 10);
            }

            sidebar.addEventListener('scroll', function () {
                localStorage.setItem('admin_sidebar_scroll', sidebar.scrollTop);
            });
        }

        const treeLinks = document.querySelectorAll('.nav-sidebar .nav-link[data-menu-key]');
        treeLinks.forEach(function(link) {
            const key = 'menu_open_' + link.dataset.menuKey;
            const parent = link.closest('.has-treeview');

            if (localStorage.getItem(key) === '1' && parent) {
                parent.classList.add('menu-open');
            }

            link.addEventListener('click', function() {
                if (!parent) return;
                const willOpen = !parent.classList.contains('menu-open');
                localStorage.setItem(key, willOpen ? '1' : '0');
            });
        });
    });
</script>
</body>
</html>