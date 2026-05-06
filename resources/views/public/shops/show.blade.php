@extends('public.layouts.app')

@section('title', $shop->title)

@section('content')
@php
    $priceLabel = $shop->price !== null && (float) $shop->price > 0
        ? 'Rp '.number_format((float) $shop->price, 0, ',', '.')
        : 'Hubungi penjual';
    $statusLabel = $shop->status === 'out_of_stock' ? 'Stok habis' : 'Tersedia';
    $statusClass = $shop->status === 'out_of_stock' ? 'bg-rose-50 text-rose-700 ring-rose-100' : 'bg-emerald-50 text-emerald-700 ring-emerald-100';
    $seller = $shop->seller_name ?: 'Pelaku usaha desa';
    $images = collect([$shop->main_image])
        ->merge($shop->images->pluck('image_path'))
        ->filter()
        ->map(fn ($path) => $imageUrl($path))
        ->filter()
        ->unique()
        ->values();
    $mainImage = $images->first();
    $detailUrl = route('public.shops.show', $shop->slug);
    $waTemplate = 'Halo, aku melihat di lapak ini aku tertarik dengan produk "'.$shop->title.'".';
    $waTemplate .= ' Boleh minta info detail harga dan ketersediaan?';
    $waTemplate .= ' Link produk: '.$detailUrl;
    $waUrl = $shop->whatsapp_url
        ? $shop->whatsapp_url.(str_contains($shop->whatsapp_url, '?') ? '&' : '?').'text='.rawurlencode($waTemplate)
        : null;
@endphp

<article>
    <header class="bg-emerald-950 py-7 text-white sm:py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
            <a href="{{ route('public.shops.index') }}" class="inline-flex text-sm font-semibold text-amber-200">Kembali ke lapak</a>
            <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_auto] lg:items-end">
                <div>
                    <div class="flex flex-wrap gap-2">
                        @if ($shop->category)
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-emerald-50">{{ $shop->category->name }}</span>
                        @endif
                        @if ($shop->is_featured)
                            <span class="rounded-full bg-amber-300 px-3 py-1 text-xs font-bold text-slate-950">Produk unggulan</span>
                        @endif
                    </div>
                    <h1 class="mt-4 max-w-4xl text-3xl font-extrabold leading-tight sm:text-4xl">{{ $shop->title }}</h1>
                    @if ($shop->excerpt)
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">{{ $shop->excerpt }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2 lg:justify-end">
                    <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">Penjual: {{ $seller }}</span>
                    @if ($shop->location)
                        <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">Lokasi: {{ $shop->location }}</span>
                    @endif
                    <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">Dilihat: {{ $viewsDisplay }}</span>
                </div>
            </div>
        </div>
    </header>

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_380px] lg:px-6 lg:py-14">
        <div class="min-w-0">
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-slate-200/90">
                <div class="bg-gradient-to-b from-slate-100 to-white p-5 sm:p-6">
                    <div class="mx-auto w-full max-w-md lg:max-w-lg">
                        <div class="overflow-hidden rounded-xl bg-white shadow-inner ring-1 ring-slate-200/90">
                            @if ($mainImage)
                                <div class="aspect-[4/3] w-full">
                                    <img id="shop_main_image" src="{{ $mainImage }}" alt="{{ $shop->title }}" class="h-full w-full object-cover">
                                </div>
                            @else
                                <div class="grid aspect-[4/3] place-items-center text-sm font-bold text-slate-500">Foto produk belum tersedia.</div>
                            @endif
                        </div>
                        @if ($images->count() > 1)
                            <div class="mt-4 flex items-center gap-2">
                                <button type="button" id="shop_thumbs_prev" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-900" aria-label="Thumbnail sebelumnya">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                </button>
                                <div id="shop_thumb_strip" class="frontend-media-strip min-h-[4.5rem] flex-1 px-1">
                                    @foreach ($images as $index => $img)
                                        <button
                                            type="button"
                                            class="shop-thumb size-16 shrink-0 snap-start overflow-hidden rounded-lg border-2 shadow-sm transition sm:size-[4.75rem] {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-slate-200 hover:border-emerald-200' }}"
                                            data-shop-image="{{ $img }}"
                                            aria-label="Lihat foto produk {{ $index + 1 }}"
                                        >
                                            <img src="{{ $img }}" alt="" class="h-full w-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                                <button type="button" id="shop_thumbs_next" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-900" aria-label="Thumbnail berikutnya">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-bold text-slate-950">Deskripsi produk</h2>
                <div class="prose prose-slate mt-4 max-w-none">
                    {!! $shop->description ?: '<p>Deskripsi produk belum tersedia. Hubungi penjual untuk informasi lebih lengkap.</p>' !!}
                </div>
            </div>
        </div>

        <aside class="space-y-5">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Harga</p>
                        <p class="mt-1 text-3xl font-extrabold text-emerald-800">{{ $priceLabel }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-extrabold ring-1 {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-lg bg-slate-50 p-3">
                        <p class="font-semibold text-slate-500">Stok</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $shop->stock !== null ? number_format($shop->stock, 0, ',', '.') : 'Tanyakan' }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3">
                        <p class="font-semibold text-slate-500">Kategori</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $shop->category?->name ?: '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3">
                        <p class="font-semibold text-slate-500">Lokasi</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $shop->location ?: '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3">
                        <p class="font-semibold text-slate-500">Dilihat</p>
                        <p class="mt-1 font-bold text-slate-950">{{ $viewsDisplay }} kali</p>
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-800">Penjual</p>
                    <p class="mt-1 text-lg font-extrabold text-slate-950">{{ $seller }}</p>
                    @if ($shop->whatsapp)
                        <p class="mt-1 text-sm font-semibold text-slate-600">{{ $shop->whatsapp }}</p>
                    @endif
                </div>

                @if ($waUrl)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-5 flex w-full items-center justify-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">
                        Chat WhatsApp Penjual
                    </a>
                @else
                    <span class="mt-5 flex w-full items-center justify-center rounded-lg bg-slate-100 px-5 py-3 text-sm font-bold text-slate-500">
                        Kontak penjual belum tersedia
                    </span>
                @endif
            </div>

            @if ($related->isNotEmpty())
                <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-bold text-slate-950">Produk terkait</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach ($related as $item)
                            @php
                                $relatedImage = $imageUrl($item->main_image);
                                $relatedPrice = $item->price !== null && (float) $item->price > 0
                                    ? 'Rp '.number_format((float) $item->price, 0, ',', '.')
                                    : 'Hubungi penjual';
                            @endphp
                            <a href="{{ route('public.shops.show', $item->slug) }}" class="flex gap-3 rounded-lg border border-slate-100 p-2 transition hover:border-emerald-200 hover:bg-emerald-50">
                                <span class="h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                    @if ($relatedImage)
                                        <img src="{{ $relatedImage }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    @endif
                                </span>
                                <span class="min-w-0">
                                    <span class="line-clamp-2 text-sm font-bold text-slate-950">{{ $item->title }}</span>
                                    <span class="mt-1 block text-xs font-extrabold text-emerald-800">{{ $relatedPrice }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </section>
</article>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var mainImage = document.getElementById('shop_main_image');
            var thumbs = document.querySelectorAll('[data-shop-image]');

            if (!mainImage || !thumbs.length) {
                return;
            }

            thumbs.forEach(function (button) {
                button.addEventListener('click', function () {
                    mainImage.src = button.dataset.shopImage;
                    thumbs.forEach(function (thumb) {
                        thumb.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-100');
                        thumb.classList.add('border-slate-200', 'hover:border-emerald-200');
                    });
                    button.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-100');
                    button.classList.remove('border-slate-200');
                });
            });

            var strip = document.getElementById('shop_thumb_strip');
            var prev = document.getElementById('shop_thumbs_prev');
            var next = document.getElementById('shop_thumbs_next');
            if (strip && prev && next) {
                prev.addEventListener('click', function () {
                    strip.scrollBy({ left: -180, behavior: 'smooth' });
                });
                next.addEventListener('click', function () {
                    strip.scrollBy({ left: 180, behavior: 'smooth' });
                });
            }
        });
    </script>
@endpush
