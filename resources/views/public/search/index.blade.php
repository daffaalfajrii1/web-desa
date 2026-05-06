@extends('public.layouts.app')

@section('title', 'Pencarian')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Cari menu & halaman</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Pencarian Website Desa</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Berita, pengumuman, agenda, galeri, lapak, wisata, layanan mandiri, produk hukum, infografis, dan halaman profil.</p>
    </div>
</section>

<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <form method="GET" action="{{ route('public.search') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 md:grid-cols-[1fr_auto]">
            <input
                type="search"
                name="q"
                value="{{ $q }}"
                placeholder="Cari layanan, menu, halaman, produk, wisata..."
                class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                autofocus
            >
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
        </div>
    </form>

    <div class="mt-8">
        @if ($q === '')
            <div class="frontend-empty">Ketik kata kunci untuk mulai mencari.</div>
        @elseif ($results->isEmpty())
            <div class="search-no-results frontend-empty relative overflow-hidden py-14 text-center" role="status">
                <div class="search-no-results-glow pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_30%,rgba(16,185,129,0.12),transparent_55%)]"></div>
                <div class="search-no-results-icon relative mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 ring-4 ring-emerald-100/80">
                    <svg class="h-10 w-10 text-emerald-700 search-no-results-mag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="M21 21l-4.2-4.2" stroke-linecap="round"/></svg>
                </div>
                <p class="relative mt-6 text-lg font-extrabold text-slate-800">Belum ada hasil untuk "{{ $q }}"</p>
                <p class="relative mt-2 max-w-md mx-auto text-sm leading-relaxed text-slate-600">Coba kata kunci lain, periksa ejaan, atau gunakan menu infografis &amp; layanan untuk menelusuri konten.</p>
                <a href="{{ route('public.infographics.index') }}" class="relative mt-6 inline-flex rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-800 transition hover:bg-emerald-100">Buka infografis</a>
            </div>
        @else
            <p class="mb-4 text-sm font-semibold text-slate-500">{{ number_format($results->count(), 0, ',', '.') }} hasil ditemukan untuk "{{ $q }}".</p>
            <div class="grid gap-3">
                @foreach ($results as $result)
                    <a href="{{ $result['href'] }}" class="frontend-list-item">
                        <span class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ $result['type'] }}</span>
                        <span class="text-base font-bold text-slate-950">{{ $result['title'] }}</span>
                        @if ($result['description'])
                            <span class="text-sm leading-6 text-slate-600">{{ $result['description'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
