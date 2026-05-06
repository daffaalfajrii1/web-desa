@extends('public.layouts.app')

@section('title', $post->title)

@section('content')
@php
    $heroImage = $imageUrl($post->featured_image);
    $dateLabel = $post->published_at?->translatedFormat('l, d F Y');
    $authorName = $post->author?->name;
@endphp

<article>
    <header class="border-b border-emerald-900/15 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 py-8 text-white sm:py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-6">
            <a href="{{ route('public.posts.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-amber-200 transition hover:text-amber-100">
                <span aria-hidden="true">←</span> Kembali ke berita
            </a>
            <div class="mt-5 flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wide text-emerald-100/95">
                @if ($post->category)
                    <span class="rounded-full bg-white/15 px-3 py-1">{{ $post->category->name }}</span>
                @endif
                @if ($dateLabel)
                    <span class="rounded-full bg-white/10 px-3 py-1 text-emerald-50">{{ $dateLabel }}</span>
                @endif
                <span class="rounded-full bg-white/10 px-3 py-1 text-emerald-50">Dilihat {{ $viewsDisplay }}</span>
            </div>
            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl lg:text-[2.35rem]">{{ $post->title }}</h1>
            @if ($authorName)
                <p class="mt-4 text-sm font-semibold text-emerald-100/90">Oleh {{ $authorName }}</p>
            @endif
        </div>
    </header>

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-6 lg:py-12">
        @if ($heroImage)
            <figure class="mb-10 overflow-hidden rounded-2xl bg-slate-100 shadow-lg ring-1 ring-slate-200/90">
                <div class="flex justify-center bg-gradient-to-b from-slate-50 to-white p-4 sm:p-6">
                    <button
                        type="button"
                        id="post_lightbox_open"
                        class="group relative max-w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                    >
                        <img
                            src="{{ $heroImage }}"
                            alt="{{ $post->title }}"
                            class="max-h-[min(22rem,58vh)] w-auto max-w-full rounded-xl object-contain shadow-md ring-1 ring-slate-200/80 transition group-hover:ring-emerald-200/90 sm:max-h-[min(26rem,62vh)]"
                            fetchpriority="high"
                        >
                        <figcaption class="sr-only">Gambar utama berita</figcaption>
                    </button>
                </div>
                <p class="border-t border-slate-100 px-4 py-2 text-center text-[11px] font-semibold text-slate-500 sm:hidden">Ketuk gambar untuk memperbesar</p>
            </figure>

            <dialog id="post_lightbox" class="public-media-lightbox" aria-label="Gambar berita">
                <div class="flex max-h-[95vh] flex-col items-center gap-4">
                    <img src="{{ $heroImage }}" alt="{{ $post->title }}" class="max-h-[88vh] w-auto max-w-[92vw] rounded-xl object-contain shadow-2xl ring-1 ring-white/20">
                    <button type="button" id="post_lightbox_close" class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-slate-900 shadow-lg ring-1 ring-slate-200 transition hover:bg-emerald-50">Tutup</button>
                </div>
            </dialog>
        @endif

        @if ($post->excerpt)
            <p class="mb-8 border-l-4 border-emerald-500/80 pl-5 text-lg font-medium leading-relaxed text-slate-700">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="prose prose-slate prose-lg max-w-none prose-headings:font-extrabold prose-a:text-emerald-700 prose-img:rounded-xl">
            {!! $post->content ?: '<p>Isi berita belum tersedia.</p>' !!}
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-14 border-t border-slate-200 pt-10">
                <h2 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Berita terkait</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    @foreach ($related as $item)
                        @php $thumb = $imageUrl($item->featured_image); @endphp
                        <a href="{{ route('public.posts.show', $item->slug) }}" class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/80 p-3 ring-1 ring-slate-100 transition hover:border-emerald-200 hover:bg-white hover:ring-emerald-100">
                            <span class="h-20 w-24 shrink-0 overflow-hidden rounded-lg bg-slate-200">
                                @if ($thumb)
                                    <img src="{{ $thumb }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </span>
                            <span class="min-w-0">
                                <span class="line-clamp-2 text-sm font-extrabold text-slate-950">{{ $item->title }}</span>
                                @if ($item->published_at)
                                    <time class="mt-1 block text-xs font-semibold text-slate-500" datetime="{{ $item->published_at->toIso8601String() }}">{{ $item->published_at->translatedFormat('d M Y') }}</time>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection

@push('scripts')
    @if ($heroImage)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var openBtn = document.getElementById('post_lightbox_open');
                var dialog = document.getElementById('post_lightbox');
                var closeBtn = document.getElementById('post_lightbox_close');
                openBtn?.addEventListener('click', function () {
                    if (dialog && typeof dialog.showModal === 'function') {
                        dialog.showModal();
                    }
                });
                closeBtn?.addEventListener('click', function () {
                    dialog?.close();
                });
                dialog?.addEventListener('click', function (e) {
                    if (e.target === dialog) {
                        dialog.close();
                    }
                });
            });
        </script>
    @endif
@endpush
