@extends('public.layouts.app')

@section('title', 'Lapak Desa')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Ekonomi lokal</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Lapak Produk Desa</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Produk unggulan, terbaru, dan katalog lengkap dari pelaku usaha desa.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-6">
    <form method="GET" action="{{ route('public.shops.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="grid gap-3 lg:grid-cols-[1fr_220px_auto_auto]">
            <input
                type="search"
                name="q"
                value="{{ $search ?? '' }}"
                placeholder="Cari produk, penjual, lokasi, atau kategori..."
                class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
            @if ($categories->isNotEmpty())
                <select name="kategori" class="rounded-lg border-slate-200 px-3 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $selectedCategory === (string) $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
            <a href="{{ route('public.shops.index') }}" class="rounded-lg bg-slate-100 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
        </div>
    </form>

    @if ($categories->isNotEmpty())
        <div class="mt-5 flex flex-wrap gap-2">
            <a href="{{ route('public.shops.index', array_filter(['q' => $search ?: null])) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ ! $selectedCategory ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Semua kategori</a>
            @foreach ($categories as $cat)
                <a href="{{ route('public.shops.index', array_filter(['kategori' => $cat->id, 'q' => $search ?: null])) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ (string) $selectedCategory === (string) $cat->id ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
    @endif
</section>

@if ($featured->isNotEmpty())
    <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-6">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Unggulan</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-950">Produk unggulan</h2>
            </div>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($featured as $shop)
                @include('public.shops._card', ['shop' => $shop, 'img' => $imageUrl($shop->main_image)])
            @endforeach
        </div>
    </section>
@endif

@if ($latest->isNotEmpty())
    <section class="bg-slate-50 py-12 lg:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
            <div class="mb-6 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">Terbaru</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-950">Produk terbaru</h2>
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($latest as $shop)
                    @include('public.shops._card', ['shop' => $shop, 'img' => $imageUrl($shop->main_image)])
                @endforeach
            </div>
        </div>
    </section>
@endif

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-6 lg:py-16">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Katalog</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950">Semua produk</h2>
        </div>
        @if ($all)
            <span class="rounded-full bg-slate-100 px-4 py-1.5 text-xs font-bold text-slate-600">{{ number_format($all->total(), 0, ',', '.') }} produk</span>
        @endif
    </div>

    @if ($all && $all->count())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($all as $shop)
                @include('public.shops._card', ['shop' => $shop, 'img' => $imageUrl($shop->main_image)])
            @endforeach
        </div>
        <div class="mt-8">{{ $all->links() }}</div>
    @else
        <div class="frontend-empty">Belum ada produk lapak yang cocok dengan pencarian.</div>
    @endif
</section>
@endsection
