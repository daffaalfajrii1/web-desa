@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')
@php
    $welcomeText = trim(strip_tags((string) ($village?->welcome_message ?: '')));
    $headName = $village?->village_head_name ?: 'Kepala Desa';
    $visionShort = $village?->vision ? \Illuminate\Support\Str::limit(trim(strip_tags((string) $village->vision)), 220) : null;
    $missionShort = $village?->mission ? \Illuminate\Support\Str::limit(trim(strip_tags((string) $village->mission)), 220) : null;
    $profileTeaser = $welcomeText ? \Illuminate\Support\Str::limit($welcomeText, 200) : ($village?->address ? \Illuminate\Support\Str::limit((string) $village->address, 200) : null);
@endphp

@include('public.partials.home-hero', ['banners' => $banners, 'village' => $village, 'carouselIntro' => $carouselIntro ?? null])

<div class="-mt-10 relative z-10 lg:-mt-12">
    <div class="home-quick-menu-panel">
        @include('public.partials.quick-menu')
    </div>
</div>

<section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_1.1fr] lg:px-6 lg:py-16">
    <div class="home-welcome-shell relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-emerald-50/30 to-slate-50 shadow-2xl shadow-emerald-950/[0.08] ring-1 ring-slate-200/80">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-amber-300/12 blur-3xl"></div>
        <div class="relative flex flex-col p-6 sm:p-8 lg:p-10">
                <span class="inline-flex max-w-fit items-center gap-2 rounded-full border border-emerald-200/80 bg-emerald-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wide text-emerald-800">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.7)]" aria-hidden="true"></span>
                    Tata kelola · transparansi
                </span>

                <div class="mt-5 flex items-center gap-4 rounded-2xl bg-white/80 p-4 ring-1 ring-emerald-100/80 backdrop-blur">
                    <span class="relative flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-emerald-100 to-slate-100 ring-4 ring-white shadow-md">
                        @if ($headPhotoUrl ?? null)
                            <img src="{{ $headPhotoUrl }}" alt="{{ $headName }}" class="h-full w-full object-cover object-top">
                        @else
                            <span class="text-2xl font-extrabold text-emerald-800">{{ \Illuminate\Support\Str::substr($headName, 0, 1) }}</span>
                        @endif
                    </span>
                    <span class="min-w-0">
                        <span class="block text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-500">Sambutan kepala desa</span>
                        <span class="mt-1 block truncate text-2xl font-extrabold tracking-tight text-slate-950">{{ $headName }}</span>
                        <span class="mt-1 block truncate text-sm font-semibold text-emerald-700">{{ $village?->villageHeadEmployee?->position ?: 'Kepala Desa' }}</span>
                    </span>
                </div>

                <blockquote class="welcome-quote mt-5 rounded-r-2xl border-l-[4px] border-emerald-500 bg-slate-50/70 p-5 text-base leading-8 text-slate-800 shadow-sm" aria-labelledby="welcome-heading">
                    <svg class="mb-2 h-7 w-7 text-emerald-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.17 6A4.17 4.17 0 003 10.17V18h7.83v-7.83H6.92v-.5A2.08 2.08 0 019 7.58h1V6H7.17zm10 0A4.17 4.17 0 0013 10.17V18h7.83v-7.83h-3.91v-.5A2.08 2.08 0 0119 7.58h1V6h-2.83z"/></svg>
                    <p id="welcome-heading" class="font-medium">
                        {{ $welcomeText ? \Illuminate\Support\Str::limit($welcomeText, 280) : 'Selamat datang di website resmi desa kami. Kami membuka kanal komunikasi yang transparan agar seluruh warga dapat ikut serta dalam pembangunan desa yang berkelanjutan.' }}
                    </p>
                </blockquote>
                @if ($visionShort || $missionShort)
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @if ($visionShort)
                            <div class="rounded-xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white px-4 py-3 shadow-sm ring-1 ring-emerald-100/60">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Visi</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $visionShort }}</p>
                            </div>
                        @endif
                        @if ($missionShort)
                            <div class="rounded-xl border border-slate-200 bg-slate-50/90 px-4 py-3 shadow-sm ring-1 ring-slate-200/80">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-600">Misi</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $missionShort }}</p>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('public.profile') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-700 to-emerald-600 px-5 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/20 transition hover:from-emerald-800 hover:to-emerald-700">
                        Profil desa lengkap
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('public.profile.structure') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50/50">
                        Struktur organisasi
                    </a>
                </div>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <div class="rounded-2xl border border-emerald-100/80 bg-gradient-to-br from-white via-white to-emerald-50/50 p-6 shadow-lg shadow-emerald-950/[0.04] ring-1 ring-slate-200/80">
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Ringkasan profil</p>
            <p class="mt-3 text-sm leading-7 text-slate-600 md:text-[15px]">
                {{ $profileTeaser ?: 'Profil desa akan ditampilkan di sini setelah data ringkasan tersedia.' }}
            </p>
            <a href="{{ route('public.profile.structure') }}" class="mt-3 inline-flex text-sm font-extrabold text-emerald-800 hover:text-emerald-950">Struktur organisasi →</a>
            <a href="{{ route('public.profile') }}" class="mt-2 block text-sm font-extrabold text-emerald-700 hover:text-emerald-900">Halaman profil →</a>
        </div>

    <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-inner ring-1 ring-slate-100 sm:p-5">
        <div class="mb-3 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-emerald-700">Infografis kilat</p>
                <p class="mt-1 text-sm font-semibold text-slate-600">Angka utama desa · ketuk kartu untuk detail</p>
            </div>
            <a href="{{ route('public.infographics.index') }}" class="text-xs font-extrabold text-emerald-700 underline-offset-4 hover:text-emerald-900 hover:underline">Semua data →</a>
        </div>
        <div class="home-infographic-stagger grid gap-4 sm:grid-cols-2">
        @forelse ($infographics as $info)
            @php $infoHref = $info['href'] ?? route('public.infographics.index'); @endphp
            <a href="{{ $infoHref }}" class="home-infographic-card group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-white to-emerald-50/40 p-5 shadow-md ring-1 ring-slate-200/90 transition hover:-translate-y-1 hover:shadow-xl hover:ring-emerald-300/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                <span class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 opacity-0 ring-1 ring-emerald-100 transition group-hover:opacity-100" aria-hidden="true">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 18l6-6-6-6"/></svg>
                </span>
                <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500 group-hover:text-emerald-700">{{ $info['label'] }}</p>
                <p class="home-infographic-value mt-2 text-2xl font-extrabold tracking-tight text-slate-950">{{ $info['value'] }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $info['meta'] }}</p>
                <span class="mt-4 inline-flex items-center gap-1 text-xs font-extrabold text-emerald-700 opacity-0 transition group-hover:opacity-100">Lihat infografis<span aria-hidden="true">→</span></span>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 p-8 text-center sm:col-span-2">
                <p class="text-sm font-semibold text-slate-500">Ringkasan data desa</p>
                <p class="mt-2 text-base font-bold text-slate-700">Data indikator akan tampil di sini.</p>
            </div>
        @endforelse
        </div>
    </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-6 lg:py-14">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Pemerintahan desa</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Aparatur Desa</h2>
            <p class="mt-2 text-sm text-slate-600">Kenali perangkat desa yang siap melayani warga.</p>
        </div>
        <a href="{{ route('public.profile.structure') }}" class="hidden rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-800 transition hover:bg-emerald-100 sm:inline-flex">Lihat struktur lengkap →</a>
    </div>

    @if (($employees ?? collect())->isNotEmpty())
        <div id="home_employee_strip" class="frontend-media-strip snap-x gap-3 px-0.5 pb-2">
            @foreach ($employees as $idx => $employee)
                @php
                    $empPhoto = $imageUrl($employee->photo ?? null);
                    $empJob = $employee->employeePosition?->name ?? $employee->position ?: 'Perangkat desa';
                    $empRank = $idx + 1;
                @endphp
                <article class="group h-[268px] w-36 shrink-0 snap-start overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm shadow-slate-900/[0.03] ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:shadow-md sm:h-[286px] sm:w-40">
                    <div class="relative">
                        <div class="absolute inset-x-0 top-0 h-10 bg-gradient-to-r from-emerald-700 to-teal-700"></div>
                        <div class="relative px-2.5 pt-3">
                            <div class="h-40 overflow-hidden rounded-xl bg-slate-100 ring-1 ring-white/60 sm:h-44">
                                @if ($empPhoto)
                                    <img src="{{ $empPhoto }}" alt="{{ $employee->name }}" class="h-full w-full object-cover object-top">
                                @else
                                    <div class="grid h-full w-full place-items-center bg-gradient-to-br from-emerald-100 to-slate-100 text-3xl font-extrabold text-emerald-800">{{ \Illuminate\Support\Str::substr($employee->name, 0, 1) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex h-[96px] flex-col items-center justify-start px-2.5 pb-3 pt-2 text-center sm:h-[104px]">
                        <p class="text-[11px] font-extrabold uppercase tracking-wide text-emerald-700">{{ $empRank }}</p>
                        <h3 class="mt-1 line-clamp-2 h-[2.4rem] text-[13px] font-extrabold leading-tight text-slate-950">{{ $employee->name }}</h3>
                        <p class="mt-1 line-clamp-2 h-[1.8rem] text-[11px] font-medium leading-snug text-slate-600">{{ $empJob }}</p>
                    </div>
                </article>
            @endforeach
        </div>
        <a href="{{ route('public.profile.structure') }}" class="mt-4 inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-800 transition hover:bg-emerald-100 sm:hidden">Lihat struktur lengkap →</a>
    @else
        <div class="frontend-empty">Data aparatur desa belum tersedia.</div>
    @endif
</section>

<section class="border-y border-slate-200/80 bg-gradient-to-b from-white to-slate-50/80 py-12 lg:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Kabar desa</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Berita terbaru</h2>
            </div>
            <a href="{{ route('public.posts.index') }}" class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-800 transition hover:bg-emerald-100">Semua berita →</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @forelse ($posts as $post)
                @php $postImage = $imageUrl($post->featured_image); @endphp
                <article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg shadow-slate-900/[0.05] ring-1 ring-slate-200/90 transition hover:-translate-y-1 hover:shadow-xl hover:ring-emerald-200/80">
                    <a href="{{ route('public.posts.show', $post->slug) }}" class="relative block shrink-0 overflow-hidden">
                        @if ($postImage)
                            <img src="{{ $postImage }}" alt="" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                        @else
                            <div class="grid aspect-[16/10] place-items-center bg-gradient-to-br from-emerald-100 to-teal-50 text-sm font-extrabold text-emerald-800">Berita</div>
                        @endif
                        <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-emerald-800 shadow-sm">Kabar Desa</span>
                    </a>
                    <div class="flex flex-1 flex-col p-5">
                        @if ($post->published_at)
                            <time class="inline-flex max-w-fit rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-slate-500" datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->translatedFormat('d M Y') }}</time>
                        @endif
                        <h3 class="mt-2 line-clamp-2 text-lg font-extrabold leading-snug text-slate-950">
                            <a href="{{ route('public.posts.show', $post->slug) }}" class="hover:text-emerald-800">{{ $post->title }}</a>
                        </h3>
                        <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->content), 130) }}</p>
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            <a href="{{ route('public.posts.show', $post->slug) }}" class="inline-flex text-sm font-extrabold text-emerald-700 hover:text-emerald-900">Baca selengkapnya →</a>
                            <span class="text-xs font-semibold text-slate-400">Artikel</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="frontend-empty md:col-span-3">Belum ada berita yang dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-6 lg:py-16">
    <div>
        <div class="mb-5 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Dokumen</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Produk hukum</h2>
            </div>
            <a href="{{ route('public.legal-products.index') }}" class="text-sm font-extrabold text-emerald-700 hover:text-emerald-900">Lihat semua →</a>
        </div>
        <div class="grid gap-3">
            @forelse ($legalProducts as $item)
                <a href="{{ route('public.legal-products.show', $item->slug) }}" class="frontend-list-item">
                    <span class="font-bold text-slate-950">{{ $item->title }}</span>
                    <span class="text-sm text-slate-500">{{ $item->document_type ?: 'Produk hukum' }}</span>
                </a>
            @empty
                <div class="frontend-empty">Produk hukum belum tersedia.</div>
            @endforelse
        </div>
    </div>

    <div>
        <div class="mb-5 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Informasi</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Informasi publik</h2>
            </div>
            <a href="{{ route('public.public-informations.index') }}" class="text-sm font-extrabold text-emerald-700 hover:text-emerald-900">Lihat semua →</a>
        </div>
        <div class="grid gap-3">
            @forelse ($publicInformations as $item)
                <a href="{{ route('public.public-informations.show', $item->slug) }}" class="frontend-list-item">
                    <span class="font-bold text-slate-950">{{ $item->title }}</span>
                    <span class="text-sm text-slate-500">{{ $item->published_date?->translatedFormat('d F Y') ?: 'Informasi publik' }}</span>
                </a>
            @empty
                <div class="frontend-empty">Informasi publik belum tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 py-12 text-white lg:py-16">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_50%_at_0%_100%,rgba(251,191,36,0.12),transparent_50%)]"></div>
    <div class="relative mx-auto grid max-w-7xl gap-5 px-4 sm:px-6 lg:grid-cols-3 lg:px-6">
        <a href="{{ route('public.services.index') }}" class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-lg shadow-black/20 backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/[0.18]">
            <p class="text-xs font-extrabold uppercase tracking-wide text-amber-200/95">{{ $serviceCount }} layanan</p>
            <h2 class="mt-2 text-xl font-extrabold sm:text-2xl">Layanan desa</h2>
            <p class="mt-3 text-sm leading-relaxed text-emerald-50/95">Administrasi dan permohonan dari satu pintu.</p>
        </a>
        <a href="{{ route('public.complaints.create') }}" class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-lg shadow-black/20 backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/[0.18]">
            <p class="text-xs font-extrabold uppercase tracking-wide text-amber-200/95">Partisipasi</p>
            <h2 class="mt-2 text-xl font-extrabold sm:text-2xl">Pengaduan warga</h2>
            <p class="mt-3 text-sm leading-relaxed text-emerald-50/95">Laporan dengan nomor tiket untuk ditindaklanjuti.</p>
        </a>
        <a href="{{ route('public.attendance.index') }}" class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-lg shadow-black/20 backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/[0.18]">
            <p class="text-xs font-extrabold uppercase tracking-wide text-amber-200/95">Perangkat</p>
            <h2 class="mt-2 text-xl font-extrabold sm:text-2xl">Absensi pegawai</h2>
            <p class="mt-3 text-sm leading-relaxed text-emerald-50/95">Presensi dengan PIN untuk perangkat desa.</p>
        </a>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-6 lg:py-16">
    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <div class="mb-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Potensi</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">Lapak dan wisata</h2>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($shops->take(2) as $shop)
                    @php $shopImage = $imageUrl($shop->main_image); @endphp
                    <a href="{{ route('public.shops.show', $shop->slug) }}" class="frontend-card overflow-hidden">
                        @if ($shopImage)
                            <img src="{{ $shopImage }}" alt="{{ $shop->title }}" class="h-36 w-full object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-bold text-slate-950">{{ $shop->title }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $shop->seller_name ?: 'Lapak warga' }}</p>
                        </div>
                    </a>
                @endforeach
                @foreach ($tourism->take(2) as $place)
                    @php $tourismImage = $imageUrl($place->main_image); @endphp
                    <a href="{{ route('public.tourism.show', $place->slug) }}" class="frontend-card overflow-hidden">
                        @if ($tourismImage)
                            <img src="{{ $tourismImage }}" alt="{{ $place->title }}" class="h-36 w-full object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="font-bold text-slate-950">{{ $place->title }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $place->address ?: 'Wisata desa' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="mb-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Dokumentasi</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">Galeri terbaru</h2>
                </div>
                <a href="{{ route('public.galleries.index') }}" class="text-sm font-bold text-emerald-700">Album Galeri</a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @forelse ($galleries as $gallery)
                    <a href="{{ route('public.galleries.show', $gallery->slug) }}" class="block overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-200">
                        @if ($gallery->media_url)
                            <img src="{{ $gallery->media_url }}" alt="{{ $gallery->title }}" class="aspect-square w-full object-cover transition duration-200 hover:scale-105">
                        @else
                            <div class="grid aspect-square place-items-center text-xs font-semibold text-slate-500">Galeri</div>
                        @endif
                    </a>
                @empty
                    <div class="frontend-empty col-span-2 sm:col-span-3">Galeri belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-12 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-6">
        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Lokasi</p>
        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Peta desa</h2>
        <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600">
            {{ $village?->address ?: 'Alamat desa akan ditampilkan bersama peta di bawah.' }}
        </p>
        <div class="mt-8 overflow-hidden rounded-2xl bg-slate-100 shadow-lg ring-1 ring-slate-200/90">
            @if ($village?->map_embed)
                <div class="public-map-embed">{!! $village->map_embed !!}</div>
            @else
                <div class="grid min-h-72 place-items-center px-6 text-center text-sm font-semibold text-slate-500">
                    Peta interaktif akan tersedia setelah konfigurasi selesai.
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
