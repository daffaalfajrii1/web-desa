@extends('public.layouts.app')

@section('title', 'Galeri Desa')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1fr_auto] lg:px-6">
        <div>
            <p class="text-sm font-semibold text-amber-200">Dokumentasi Desa</p>
            <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Galeri Desa</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">
                Kumpulan foto dan video kegiatan, potensi, dan momen penting desa.
            </p>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center sm:min-w-[360px]">
            <div class="rounded-lg bg-white/10 px-4 py-3 ring-1 ring-white/10">
                <p class="text-2xl font-extrabold">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs font-semibold text-emerald-100">Semua</p>
            </div>
            <div class="rounded-lg bg-white/10 px-4 py-3 ring-1 ring-white/10">
                <p class="text-2xl font-extrabold">{{ number_format($stats['photo'] ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs font-semibold text-emerald-100">Foto</p>
            </div>
            <div class="rounded-lg bg-white/10 px-4 py-3 ring-1 ring-white/10">
                <p class="text-2xl font-extrabold">{{ number_format($stats['video'] ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs font-semibold text-emerald-100">Video</p>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-6">
    <form method="GET" action="{{ route('public.galleries.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 md:grid-cols-[1fr_180px_auto_auto]">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari judul, lokasi, atau deskripsi..."
                class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            <select name="tipe" class="rounded-lg border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua media</option>
                <option value="photo" @selected($selectedType === 'photo')>Foto</option>
                <option value="video" @selected($selectedType === 'video')>Video</option>
            </select>
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
            <a href="{{ route('public.galleries.index') }}" class="rounded-lg bg-slate-100 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
        </div>
    </form>
</section>

<section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-6 lg:pb-16">
    @if ($items->count())
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Album</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-950">Dokumentasi terbaru</h2>
            </div>
            <span class="rounded-full bg-slate-100 px-4 py-1.5 text-xs font-bold text-slate-600">
                {{ number_format($items->total(), 0, ',', '.') }} item
            </span>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $gallery)
                @php
                    $creator = $gallery->author?->name ?: 'Admin Desa';
                    $dateLabel = $gallery->taken_at?->translatedFormat('d F Y')
                        ?: $gallery->published_at?->translatedFormat('d F Y');
                @endphp
                <article class="frontend-card group overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                    <a href="{{ route('public.galleries.show', $gallery->slug) }}" class="block">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            @if ($gallery->media_url)
                                <img src="{{ $gallery->media_url }}" alt="{{ $gallery->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="grid h-full place-items-center text-sm font-bold text-slate-500">Galeri</div>
                            @endif

                            <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-extrabold uppercase text-emerald-800 shadow-sm">
                                    {{ $gallery->is_video ? 'Video' : 'Album' }}
                                </span>
                                @if ($gallery->is_photo && $gallery->photoCount() > 1)
                                    <span class="rounded-full bg-slate-900/90 px-2.5 py-1 text-[11px] font-extrabold text-white shadow-sm">{{ $gallery->photoCount() }} foto</span>
                                @endif
                                @if ($gallery->is_featured)
                                    <span class="rounded-full bg-amber-300 px-2.5 py-1 text-[11px] font-extrabold uppercase text-slate-950 shadow-sm">Unggulan</span>
                                @endif
                            </div>

                            @if ($gallery->is_video)
                                <span class="absolute inset-0 grid place-items-center bg-slate-950/20 text-white">
                                    <span class="grid h-14 w-14 place-items-center rounded-full bg-white/20 backdrop-blur">
                                        <svg viewBox="0 0 24 24" class="h-8 w-8" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </span>
                            @endif
                        </div>
                    </a>

                    <div class="p-5">
                        <div class="mb-3 flex flex-wrap gap-2 text-[11px] font-bold text-slate-600">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1">Oleh {{ $creator }}</span>
                            @if ($dateLabel)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1">{{ $dateLabel }}</span>
                            @endif
                        </div>
                        <h3 class="line-clamp-2 text-lg font-bold text-slate-950">{{ $gallery->title }}</h3>
                        @if ($gallery->description)
                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags((string) $gallery->description), 120) }}
                            </p>
                        @endif
                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                            <span class="truncate text-xs font-semibold text-slate-500">{{ $gallery->location ?: 'Dokumentasi desa' }}</span>
                            <a href="{{ route('public.galleries.show', $gallery->slug) }}" class="shrink-0 text-sm font-bold text-emerald-700 hover:text-emerald-900">Lihat</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <div class="frontend-empty">Galeri belum tersedia atau tidak cocok dengan pencarian.</div>
    @endif
</section>
@endsection
