@extends('public.layouts.app')

@section('title', $gallery->title)

@section('content')
@php
    $creator = $gallery->author?->name ?: 'Admin Desa';
    $dateLabel = $gallery->taken_at?->translatedFormat('d F Y')
        ?: $gallery->published_at?->translatedFormat('d F Y');
    $photoUrls = $gallery->photoUrls();
@endphp

<article>
    <header class="bg-emerald-950 py-7 text-white sm:py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
            <a href="{{ route('public.galleries.index') }}" class="inline-flex text-sm font-semibold text-amber-200">← Kembali ke galeri</a>
            <div class="mt-5 flex flex-wrap items-end justify-between gap-5">
                <div class="min-w-0 max-w-3xl">
                    @if ($gallery->is_video)
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wide text-emerald-100">Video desa</span>
                    @else
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wide text-emerald-100">
                            Album foto @if(count($photoUrls) > 1)<span class="normal-case opacity-90">· {{ count($photoUrls) }} gambar</span>@endif
                        </span>
                    @endif
                    <h1 class="mt-3 text-2xl font-extrabold leading-tight sm:text-4xl">{{ $gallery->title }}</h1>
                </div>
                <div class="flex flex-wrap gap-2 lg:justify-end">
                    @if ($dateLabel)
                        <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">{{ $dateLabel }}</span>
                    @endif
                    <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">Dilihat: {{ $viewsDisplay }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:px-6 lg:py-11">
        <div>
            <div class="mx-auto w-full max-w-2xl">
                <div class="overflow-hidden rounded-2xl bg-gradient-to-b from-slate-100 via-white to-slate-50/80 p-5 shadow-xl ring-1 ring-slate-200/90 sm:p-6">
                    @if ($gallery->is_video && $gallery->youtube_embed_url)
                        <div class="overflow-hidden rounded-xl bg-slate-950 shadow-lg ring-1 ring-slate-900/20">
                            <iframe
                                src="{{ $gallery->youtube_embed_url }}"
                                title="{{ $gallery->title }}"
                                class="aspect-video w-full max-h-[min(24rem,65vh)]"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        </div>
                    @elseif ($gallery->is_photo && $photoUrls !== [])
                        <div id="album_photo_viewer" class="space-y-4" data-photos='@json($photoUrls)'>
                            <div class="relative">
                                <div class="flex items-center gap-2">
                                    <button type="button" id="album_prev" class="hidden shrink-0 rounded-lg border border-slate-200 bg-white p-2.5 text-slate-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50 sm:inline-flex" aria-label="Foto sebelumnya">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    </button>
                                    <button type="button" id="album_main_btn" class="group relative flex min-h-[200px] flex-1 cursor-zoom-in items-center justify-center rounded-xl bg-white/70 p-3 ring-1 ring-slate-200/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 sm:min-h-[280px] sm:p-4">
                                        <img id="album_main_img" src="{{ $photoUrls[0] }}" alt="{{ $gallery->title }}" class="max-h-[min(22rem,62vh)] w-auto max-w-full rounded-lg object-contain shadow-[0_20px_50px_-12px_rgb(15_23_42/0.35)] ring-1 ring-slate-200/70 transition duration-300 group-hover:ring-emerald-200/80 sm:max-h-[min(26rem,68vh)]" fetchpriority="high" decoding="async">
                                        <span class="pointer-events-none absolute bottom-4 right-4 rounded-full bg-emerald-900/95 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-emerald-50 shadow-lg backdrop-blur-sm">Perbesar</span>
                                    </button>
                                    <button type="button" id="album_next" class="hidden shrink-0 rounded-lg border border-slate-200 bg-white p-2.5 text-slate-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50 sm:inline-flex" aria-label="Foto berikutnya">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </button>
                                </div>
                                @if (count($photoUrls) > 1)
                                    <p id="album_counter" class="mt-2 text-center text-xs font-bold text-slate-500">1 / {{ count($photoUrls) }}</p>
                                    <p class="mt-1 text-center text-xs text-slate-500 sm:hidden">Gunakan tombol samping foto atau thumbnail di bawah.</p>
                                @endif
                            </div>

                            @if (count($photoUrls) > 1)
                                <div class="flex gap-2 overflow-x-auto pb-1 pt-1 [-ms-overflow-style:none] [scrollbar-width:thin]" id="album_thumbs" role="tablist" aria-label="Thumbnail album">
                                    @foreach ($photoUrls as $idx => $url)
                                        <button type="button" data-album-index="{{ $idx }}" class="album-thumb shrink-0 overflow-hidden rounded-lg border-2 {{ $idx === 0 ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-slate-200 opacity-80 hover:opacity-100' }} bg-slate-100 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500" aria-label="Tampilkan foto {{ $idx + 1 }}">
                                            <img src="{{ $url }}" alt="" class="h-14 w-20 object-cover sm:h-16 sm:w-24" loading="lazy" decoding="async">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <dialog id="gallery_lightbox" class="public-media-lightbox" aria-labelledby="gallery-lightbox-title">
                            <div class="flex w-full max-w-[95vw] flex-col items-center gap-4 p-2">
                                <p id="gallery-lightbox-title" class="sr-only">{{ $gallery->title }}</p>
                                <div class="flex w-full items-center justify-center gap-2">
                                    <button type="button" id="lb_prev" class="rounded-full border border-white/30 bg-white/10 p-2 text-white backdrop-blur hover:bg-white/20 {{ count($photoUrls) <= 1 ? 'hidden' : '' }}" aria-label="Sebelumnya">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    </button>
                                    <img id="lb_img" src="{{ $photoUrls[0] }}" alt="" class="max-h-[min(78vh,520px)] w-auto max-w-[min(88vw,900px)] rounded-xl object-contain shadow-2xl ring-1 ring-white/20">
                                    <button type="button" id="lb_next" class="rounded-full border border-white/30 bg-white/10 p-2 text-white backdrop-blur hover:bg-white/20 {{ count($photoUrls) <= 1 ? 'hidden' : '' }}" aria-label="Berikutnya">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </button>
                                </div>
                                @if (count($photoUrls) > 1)
                                    <p id="lb_counter" class="text-sm font-bold text-white/90">1 / {{ count($photoUrls) }}</p>
                                @endif
                                <button type="button" id="gallery_lightbox_close" class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-slate-900 shadow-lg ring-1 ring-slate-200 transition hover:bg-emerald-50">Tutup</button>
                            </div>
                        </dialog>
                    @else
                        <div class="grid min-h-64 place-items-center rounded-xl bg-slate-100 text-sm font-bold text-slate-500">Media galeri belum tersedia.</div>
                    @endif
                </div>

                @if ($related->isNotEmpty())
                    <div class="mt-7">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Jelajahi galeri</p>
                            <div class="flex gap-1">
                                <button type="button" id="gallery_strip_prev" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-emerald-200 hover:bg-emerald-50" aria-label="Geser kiri">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                </button>
                                <button type="button" id="gallery_strip_next" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-emerald-200 hover:bg-emerald-50" aria-label="Geser kanan">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                </button>
                            </div>
                        </div>
                        <div id="gallery_related_strip" class="frontend-media-strip snap-x gap-3 px-0.5">
                            <div class="w-28 shrink-0 snap-start overflow-hidden rounded-xl border-2 border-emerald-500 bg-slate-100 shadow-sm ring-2 ring-emerald-100 sm:w-32">
                                @if ($gallery->media_url)
                                    <img src="{{ $gallery->media_url }}" alt="" class="aspect-[4/3] h-full w-full object-cover">
                                @endif
                                <span class="block truncate bg-white px-2 py-1 text-center text-[10px] font-bold text-emerald-800">Saat ini</span>
                            </div>
                            @foreach ($related as $item)
                                <a href="{{ route('public.galleries.show', $item->slug) }}" class="w-28 shrink-0 snap-start overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200/90 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:ring-emerald-200 sm:w-32">
                                    @if ($item->media_url)
                                        <img src="{{ $item->media_url }}" alt="{{ $item->title }}" class="aspect-[4/3] w-full object-cover">
                                    @endif
                                    <span class="line-clamp-2 block px-2 py-1.5 text-center text-[10px] font-semibold leading-tight text-slate-700">{{ $item->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="mx-auto mt-10 max-w-2xl lg:mx-0">
                <div class="rounded-xl bg-white p-6 shadow-lg ring-1 ring-slate-200/90">
                    <h2 class="text-lg font-bold text-slate-950">Cerita dokumentasi</h2>
                    <div class="prose prose-slate mt-4 max-w-none">
                        {!! $gallery->description ?: '<p>Deskripsi galeri belum tersedia.</p>' !!}
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-5">
            <div class="rounded-xl bg-white p-6 shadow-lg ring-1 ring-slate-200/90">
                <h2 class="text-lg font-bold text-slate-950">Detail galeri</h2>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div>
                        <dt class="font-semibold text-slate-500">Pembuat</dt>
                        <dd class="mt-1 font-bold text-slate-950">{{ $creator }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Tipe media</dt>
                        <dd class="mt-1 font-bold text-slate-950">
                            @if ($gallery->is_video)
                                Video YouTube
                            @else
                                Album foto {{ $gallery->photoCount() > 0 ? '('.$gallery->photoCount().' gambar)' : '' }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Lokasi</dt>
                        <dd class="mt-1 font-bold text-slate-950">{{ $gallery->location ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Tanggal dokumentasi</dt>
                        <dd class="mt-1 font-bold text-slate-950">{{ $dateLabel ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Jumlah dilihat</dt>
                        <dd class="mt-1 font-bold text-slate-950">{{ $viewsDisplay }} kali</dd>
                    </div>
                </dl>
            </div>

            @if ($related->isNotEmpty())
                <div class="rounded-xl bg-white p-6 shadow-lg ring-1 ring-slate-200/90">
                    <h2 class="text-lg font-bold text-slate-950">Galeri lainnya</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach ($related as $item)
                            <a href="{{ route('public.galleries.show', $item->slug) }}" class="flex gap-3 rounded-lg border border-slate-100 p-2 transition hover:border-emerald-200 hover:bg-emerald-50">
                                <span class="h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                    @if ($item->media_url)
                                        <img src="{{ $item->media_url }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    @endif
                                </span>
                                <span class="min-w-0">
                                    <span class="line-clamp-2 text-sm font-bold text-slate-950">{{ $item->title }}</span>
                                    <span class="mt-1 block text-xs font-semibold text-slate-500">{{ $item->is_video ? 'Video' : 'Foto' }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</article>
@endsection

@push('scripts')
    @if ($gallery->is_photo && count($photoUrls) > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var root = document.getElementById('album_photo_viewer');
                if (!root) return;
                var photos;
                try {
                    photos = JSON.parse(root.getAttribute('data-photos') || '[]');
                } catch (err) {
                    return;
                }
                if (!photos.length) return;

                var idx = 0;
                var mainImg = document.getElementById('album_main_img');
                var mainBtn = document.getElementById('album_main_btn');
                var albCounter = document.getElementById('album_counter');
                var albPrev = document.getElementById('album_prev');
                var albNext = document.getElementById('album_next');
                var thumbs = root.querySelectorAll('.album-thumb');
                var dialog = document.getElementById('gallery_lightbox');
                var lbImg = document.getElementById('lb_img');
                var lbCounter = document.getElementById('lb_counter');
                var lbPrev = document.getElementById('lb_prev');
                var lbNext = document.getElementById('lb_next');
                var closeBtn = document.getElementById('gallery_lightbox_close');

                function show(at) {
                    idx = (at % photos.length + photos.length) % photos.length;
                    if (mainImg) mainImg.src = photos[idx];
                    if (albCounter) albCounter.textContent = (idx + 1) + ' / ' + photos.length;
                    if (lbImg) lbImg.src = photos[idx];
                    if (lbCounter) lbCounter.textContent = (idx + 1) + ' / ' + photos.length;
                    thumbs.forEach(function (t, ti) {
                        var on = ti === idx;
                        t.classList.toggle('border-emerald-500', on);
                        t.classList.toggle('ring-2', on);
                        t.classList.toggle('ring-emerald-100', on);
                        t.classList.toggle('border-slate-200', !on);
                        t.classList.toggle('opacity-80', !on);
                    });
                }

                function step(delta) {
                    show(idx + delta);
                }

                if (photos.length <= 1) {
                    if (albPrev) albPrev.classList.add('hidden');
                    if (albNext) albNext.classList.add('hidden');
                }

                albPrev && albPrev.addEventListener('click', function (e) { e.preventDefault(); step(-1); });
                albNext && albNext.addEventListener('click', function (e) { e.preventDefault(); step(1); });
                lbPrev && lbPrev.addEventListener('click', function (e) { e.preventDefault(); step(-1); });
                lbNext && lbNext.addEventListener('click', function (e) { e.preventDefault(); step(1); });

                thumbs.forEach(function (t) {
                    t.addEventListener('click', function () {
                        show(parseInt(t.getAttribute('data-album-index'), 10));
                    });
                });

                function openLb() {
                    if (dialog && typeof dialog.showModal === 'function') {
                        show(idx);
                        dialog.showModal();
                    }
                }
                function closeLb() {
                    if (dialog) dialog.close();
                }

                mainBtn && mainBtn.addEventListener('click', openLb);
                closeBtn && closeBtn.addEventListener('click', closeLb);
                dialog && dialog.addEventListener('click', function (e) {
                    if (e.target === dialog) closeLb();
                });
                document.addEventListener('keydown', function (e) {
                    if (dialog && dialog.open) {
                        if (e.key === 'Escape') closeLb();
                        if (e.key === 'ArrowLeft') { e.preventDefault(); step(-1); }
                        if (e.key === 'ArrowRight') { e.preventDefault(); step(1); }
                    }
                });
            });
        </script>
    @endif
    @if ($related->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var strip = document.getElementById('gallery_related_strip');
                var prev = document.getElementById('gallery_strip_prev');
                var next = document.getElementById('gallery_strip_next');
                if (!strip || !prev || !next) {
                    return;
                }
                prev.addEventListener('click', function () {
                    strip.scrollBy({ left: -220, behavior: 'smooth' });
                });
                next.addEventListener('click', function () {
                    strip.scrollBy({ left: 220, behavior: 'smooth' });
                });
            });
        </script>
    @endif
@endpush
