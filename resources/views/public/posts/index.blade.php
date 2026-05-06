@extends('public.layouts.app')

@section('title', $title)

@section('content')
@php
    $filters = $filters ?? [];
    $activeFilters = $activeFilters ?? [];
    $hasFilters = collect($filters)->filter(fn ($value) => filled($value) || $value === true)->isNotEmpty();
    $plainListing = filled($activeFilters['q'] ?? null) || filled($activeFilters['kategori'] ?? null);
@endphp

<section class="relative overflow-hidden border-b border-emerald-900/20 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 py-10 text-white sm:py-12">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_100%_0%,rgba(251,191,36,0.14),transparent_55%)]"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-200/95">Publikasi</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">{{ $title }}</h1>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-emerald-100/95 sm:text-base">{{ $description }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($hasFilters)
        <form method="GET" action="{{ url()->current() }}" class="mb-10 rounded-2xl border border-slate-200/90 bg-white p-5 shadow-lg shadow-slate-900/[0.04] ring-1 ring-slate-100">
            <div class="grid gap-3 lg:grid-cols-[minmax(220px,1fr)_auto_auto_auto_auto]">
                <label class="block">
                    <span class="sr-only">Pencarian</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $activeFilters['q'] ?? '' }}"
                        placeholder="{{ $filters['search_placeholder'] ?? 'Cari berita…' }}"
                        class="block w-full rounded-xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </label>

                @if (! empty($filters['category_options']) && $filters['category_options']->isNotEmpty())
                    <select name="kategori" class="rounded-xl border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua kategori</option>
                        @foreach ($filters['category_options'] as $category)
                            <option value="{{ $category->id }}" @selected((string) ($activeFilters['kategori'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['document_type_options']) && $filters['document_type_options']->isNotEmpty())
                    <select name="jenis" class="rounded-xl border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua jenis</option>
                        @foreach ($filters['document_type_options'] as $type)
                            <option value="{{ $type }}" @selected(($activeFilters['jenis'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['year_options']) && $filters['year_options']->isNotEmpty())
                    <select name="tahun" class="rounded-xl border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua tahun</option>
                        @foreach ($filters['year_options'] as $year)
                            <option value="{{ $year }}" @selected((string) ($activeFilters['tahun'] ?? '') === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                @endif

                @if (! empty($filters['featured_options']))
                    <select name="unggulan" class="rounded-xl border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua</option>
                        <option value="1" @selected(($activeFilters['unggulan'] ?? '') === '1')>Unggulan</option>
                    </select>
                @endif

                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">Cari</button>
                    <a href="{{ url()->current() }}" class="rounded-xl bg-slate-100 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
                </div>
            </div>
        </form>
    @endif

    @if ($items && $items->count())
        <div class="mb-6 flex flex-wrap items-baseline justify-between gap-2 border-b border-slate-200/90 pb-4">
            <p class="text-sm font-semibold text-slate-600">
                <span class="font-extrabold text-slate-950">{{ number_format($items->total(), 0, ',', '.') }}</span> entri
            </p>
        </div>

        @if ($items->onFirstPage() && ! $plainListing)
            @php
                $lead = $items->first();
                $leadImage = $lead->media_url ?? $imageUrl($lead->featured_image ?? null);
                $leadDate = $lead->published_at?->translatedFormat('d M Y');
                $leadExcerpt = $lead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $lead->content), 200);
            @endphp
            <a href="{{ route($showRoute, $lead->slug) }}" class="group mb-8 flex max-w-4xl flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-md ring-1 ring-slate-100 transition hover:border-emerald-200/80 hover:shadow-lg sm:mb-10 sm:max-w-none sm:flex-row">
                <div class="relative h-44 w-full shrink-0 overflow-hidden bg-slate-100 sm:h-52 sm:w-56 md:w-64">
                    @if ($leadImage)
                        <img src="{{ $leadImage }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:opacity-95">
                    @else
                        <div class="grid h-full min-h-[11rem] place-items-center bg-gradient-to-br from-emerald-50 to-slate-100 text-xs font-bold text-emerald-800">Berita</div>
                    @endif
                    <span class="absolute left-3 top-3 rounded-full bg-emerald-950/90 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-amber-200">Sorotan</span>
                </div>
                <div class="flex min-w-0 flex-1 flex-col justify-center p-5 sm:p-6 md:p-7">
                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                        @if ($lead->category)
                            <span class="text-emerald-700">{{ $lead->category->name }}</span>
                        @endif
                        @if ($leadDate)
                            <span class="text-slate-300">·</span>
                            <time datetime="{{ $lead->published_at?->toIso8601String() }}">{{ $leadDate }}</time>
                        @endif
                    </div>
                    <h2 class="mt-2 text-lg font-extrabold leading-snug text-slate-950 sm:text-xl md:text-2xl">{{ $lead->title }}</h2>
                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-600">{{ strip_tags((string) $leadExcerpt) }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-extrabold text-emerald-700">
                        Baca selengkapnya
                        <span aria-hidden="true" class="transition group-hover:translate-x-0.5">→</span>
                    </span>
                </div>
            </a>
        @endif

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $loopIndex => $item)
                @if ($items->onFirstPage() && ! $plainListing && $loopIndex === 0)
                    @continue
                @endif
                @php
                    $image = $item->media_url ?? $imageUrl($item->featured_image ?? $item->main_image ?? $item->image_path ?? null);
                    $routeKey = $item->slug ?? $item->getKey();
                    $summary = $item->excerpt ?? $item->description ?? '';
                    $dateLabel = $item->published_at?->translatedFormat('d M Y');
                @endphp
                <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-md shadow-slate-900/[0.04] ring-1 ring-slate-200/90 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-emerald-200/70">
                    <a href="{{ route($showRoute, $routeKey) }}" class="block shrink-0">
                        @if ($image)
                            <img src="{{ $image }}" alt="" class="aspect-[5/3] max-h-40 w-full object-cover sm:max-h-44">
                        @else
                            <div class="grid aspect-[5/3] max-h-40 place-items-center bg-gradient-to-br from-slate-100 to-emerald-50/50 text-xs font-bold text-slate-500 sm:max-h-44">Berita</div>
                        @endif
                    </a>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="mb-2 flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                            @if ($item->category)
                                <span class="text-emerald-700">{{ $item->category->name }}</span>
                            @endif
                            @if ($dateLabel)
                                <span class="text-slate-400">·</span>
                                <time datetime="{{ $item->published_at?->toIso8601String() }}">{{ $dateLabel }}</time>
                            @endif
                        </div>
                        <h2 class="line-clamp-2 text-lg font-extrabold leading-snug text-slate-950">
                            <a href="{{ route($showRoute, $routeKey) }}" class="hover:text-emerald-800">{{ $item->title }}</a>
                        </h2>
                        <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $summary), 160) }}
                        </p>
                        <a href="{{ route($showRoute, $routeKey) }}" class="mt-4 inline-flex text-sm font-extrabold text-emerald-700 hover:text-emerald-900">Selengkapnya</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $items->links() }}
        </div>
    @else
        <div class="frontend-empty">Belum ada berita atau tidak ada hasil yang cocok.</div>
    @endif
</section>
@endsection
