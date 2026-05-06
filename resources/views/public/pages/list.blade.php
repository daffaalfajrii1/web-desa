@extends('public.layouts.app')

@section('title', $title)

@section('content')
@php
    $filters = $filters ?? [];
    $activeFilters = $activeFilters ?? [];
    $hasFilters = collect($filters)->filter(fn ($value) => filled($value) || $value === true)->isNotEmpty();
@endphp

<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Publikasi Desa</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">{{ $title }}</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">{{ $description }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($hasFilters)
        <form method="GET" action="{{ url()->current() }}" class="mb-8 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-3 lg:grid-cols-[minmax(220px,1fr)_auto_auto_auto_auto]">
                <label class="block">
                    <span class="sr-only">Pencarian</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $activeFilters['q'] ?? '' }}"
                        placeholder="{{ $filters['search_placeholder'] ?? 'Cari data...' }}"
                        class="block w-full rounded-lg border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </label>

                @if (! empty($filters['category_options']) && $filters['category_options']->isNotEmpty())
                    <select name="kategori" class="rounded-lg border-slate-200 px-3 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua kategori</option>
                        @foreach ($filters['category_options'] as $category)
                            <option value="{{ $category->id }}" @selected((string) ($activeFilters['kategori'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['document_type_options']) && $filters['document_type_options']->isNotEmpty())
                    <select name="jenis" class="rounded-lg border-slate-200 px-3 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua jenis</option>
                        @foreach ($filters['document_type_options'] as $type)
                            <option value="{{ $type }}" @selected(($activeFilters['jenis'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['year_options']) && $filters['year_options']->isNotEmpty())
                    <select name="tahun" class="rounded-lg border-slate-200 px-3 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua tahun</option>
                        @foreach ($filters['year_options'] as $year)
                            <option value="{{ $year }}" @selected((string) ($activeFilters['tahun'] ?? '') === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['featured_options']))
                    <select name="unggulan" class="rounded-lg border-slate-200 px-3 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua</option>
                        <option value="1" @selected(($activeFilters['unggulan'] ?? '') === '1')>Unggulan</option>
                    </select>
                @endif

                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
                    <a href="{{ url()->current() }}" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
                </div>
            </div>
        </form>
    @endif

    @if ($items && $items->count())
        <div class="mb-4 text-sm font-semibold text-slate-500">
            Menampilkan {{ number_format($items->total(), 0, ',', '.') }} data.
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $item)
                @php
                    $image = $item->media_url ?? $imageUrl($item->featured_image ?? $item->main_image ?? $item->image_path ?? null);
                    $routeKey = $item->slug ?? $item->getKey();
                    $summary = $item->excerpt ?? $item->description ?? 'Informasi selengkapnya tersedia pada halaman detail.';
                    $chips = collect([
                        $item->category?->name ?? null,
                        $item->document_type ?? null,
                        $item->number ?? null,
                        $item->published_at?->translatedFormat('d F Y') ?? $item->published_date?->translatedFormat('d F Y') ?? null,
                        $item->address ?? null,
                        ($item->is_featured ?? false) ? 'Unggulan' : null,
                    ])->filter()->take(3);
                @endphp
                <article class="frontend-card overflow-hidden transition hover:-translate-y-0.5 hover:shadow-md">
                    @if ($image)
                        <img src="{{ $image }}" alt="{{ $item->title }}" class="h-44 w-full object-cover">
                    @else
                        <div class="grid h-44 place-items-center bg-emerald-50 text-sm font-semibold text-emerald-700">{{ $title }}</div>
                    @endif
                    <div class="p-5">
                        @if ($chips->isNotEmpty())
                            <div class="mb-3 flex flex-wrap gap-1.5">
                                @foreach ($chips as $chip)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">{{ $chip }}</span>
                                @endforeach
                            </div>
                        @endif
                        <h2 class="line-clamp-2 text-lg font-bold text-slate-950">{{ $item->title }}</h2>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $summary), 150) }}
                        </p>
                        <a href="{{ route($showRoute, $routeKey) }}" class="mt-4 inline-flex text-sm font-bold text-emerald-700">Lihat Detail</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <div class="frontend-empty">Data belum tersedia atau tidak cocok dengan pencarian.</div>
    @endif
</section>
@endsection
