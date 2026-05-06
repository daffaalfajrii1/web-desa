@extends('public.layouts.app')

@section('title', 'Infografis')

@section('content')
@php
    $search = trim((string) request('q', ''));
    $hub = collect([
        ['title' => 'Data Dusun', 'desc' => 'Wilayah administratif & ringkasan jiwa/kk.', 'href' => route('public.infographics.hamlets'), 'icon' => 'M12 2L3 7v10l9 5 9-5V7l-9-5z'],
        ['title' => 'Ringkasan Penduduk', 'desc' => 'Total, L/P, KK per dusun.', 'href' => route('public.infographics.population-summary'), 'icon' => 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0'],
        ['title' => 'Statistik Penduduk', 'desc' => 'Umur, pendidikan, agama, dan kategori lain.', 'href' => route('public.infographics.population-stats'), 'icon' => 'M4 19V5m16 14V5M9 9h6v10H9z'],
        ['title' => 'APBDes', 'desc' => 'Pendapatan, belanja, pembiayaan, SILPA.', 'href' => route('public.infographics.apbdes'), 'icon' => 'M4 6h16v4H4V6zm0 8h16v4H4v-4z'],
        ['title' => 'Program Bansos', 'desc' => 'Daftar program & kuota.', 'href' => route('public.infographics.bansos-program'), 'icon' => 'M5 12l5 5L20 7'],
        ['title' => 'Penerima Bansos', 'desc' => 'Daftar penerima & filter program.', 'href' => route('public.infographics.bansos-recipients'), 'icon' => 'M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2'],
        ['title' => 'Chart Bansos', 'desc' => 'Visual program & status penyaluran.', 'href' => route('public.infographics.bansos-chart'), 'icon' => 'M4 18h16M7 14l3-3 3 2 4-5'],
        ['title' => 'Cek Bansos', 'desc' => 'Cari berdasarkan NIK atau nama.', 'href' => route('public.infographics.bansos-check'), 'icon' => 'M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z'],
        ['title' => 'Stunting', 'desc' => 'Monitoring gizi & status anak.', 'href' => route('public.infographics.stunting'), 'icon' => 'M12 3v18M8 12h8'],
        ['title' => 'Indeks Desa Membangun', 'desc' => 'Skor IKS, IKE, IKL & indikator.', 'href' => route('public.infographics.idm'), 'icon' => 'M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4z'],
        ['title' => 'SDGs Desa', 'desc' => 'Nilai per tujuan pembangunan.', 'href' => route('public.infographics.sdgs'), 'icon' => 'M12 8c-2 3-6 4-6 8a6 6 0 1012 0c0-4-4-5-6-8z'],
    ]);

    if ($search !== '') {
        $needle = \Illuminate\Support\Str::lower($search);
        $hub = $hub->filter(fn ($card) => \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($card['title'].' '.$card['desc']), $needle))->values();
    }
@endphp

<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Transparansi data</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Pusat Infografis Desa</h1>
        <p class="mt-3 max-w-3xl text-sm leading-6 text-emerald-50">Data kependudukan, keuangan desa, IDM, SDGs, bantuan sosial, dan indikator pembangunan diambil dari modul admin.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <form method="GET" action="{{ route('public.infographics.index') }}" class="mb-8 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 md:grid-cols-[1fr_auto_auto]">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari APBDes, bansos, penduduk, SDGs, IDM..."
                class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
            <a href="{{ route('public.infographics.index') }}" class="rounded-lg bg-slate-100 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
        </div>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Penduduk</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-950">{{ number_format($populationTotal, 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $populationYear ? 'Tahun ' . $populationYear : 'Data belum lengkap' }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">APBDes</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-950">{{ $apbdes ? 'Rp ' . number_format((float) $apbdes->pendapatan, 0, ',', '.') : '-' }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $apbdes?->year ? 'Tahun ' . $apbdes->year : 'Pendapatan desa' }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Status IDM</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-950">{{ $idm?->idm_status ?: '-' }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $idm?->year ? 'Tahun ' . $idm->year : 'Data IDM' }}</p>
        </div>
        <div class="frontend-card p-5">
            <p class="text-sm font-semibold text-slate-500">Bansos</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-950">{{ number_format($bansosCount, 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-slate-500">Program aktif</p>
        </div>
    </div>

    <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($hub as $card)
            <a href="{{ $card['href'] }}" class="frontend-card group block p-6 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-emerald-200">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $card['icon'] }}"/></svg>
                </span>
                <h2 class="mt-4 text-lg font-bold text-slate-950 group-hover:text-emerald-800">{{ $card['title'] }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $card['desc'] }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-emerald-700">Buka data -></span>
            </a>
        @empty
            <div class="frontend-empty sm:col-span-2 lg:col-span-3">Modul infografis tidak cocok dengan pencarian.</div>
        @endforelse
    </div>

    <div class="mt-10 frontend-card p-6">
        <h2 class="text-xl font-bold text-slate-950">SDGs ringkas</h2>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            {{ $sdgs ? 'Nilai rata-rata SDGs tahun ' . $sdgs->year . ': ' . $sdgs->average_score : 'Ringkasan SDGs akan tampil setelah data diisi dari admin.' }}
        </p>
        <a href="{{ route('public.infographics.sdgs') }}" class="mt-4 inline-flex text-sm font-bold text-emerald-700">Detail SDGs -></a>
    </div>
</section>
@endsection
