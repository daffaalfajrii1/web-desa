@extends('public.layouts.app')

@section('title', $title)

@section('content')
@php
    $image = $item->media_url ?? $imageUrl($item->featured_image ?? $item->main_image ?? $item->image_path ?? null);
@endphp

<article>
    <header class="bg-emerald-950 py-12 text-white">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-6">
            <a href="{{ route($backRoute) }}" class="inline-flex text-sm font-semibold text-amber-200">Kembali</a>
            <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $title }}</h1>
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($meta as $label => $value)
                    @if ($value)
                        <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-emerald-50">{{ $label }}: {{ $value }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="mb-8 max-h-[460px] w-full rounded-lg object-cover shadow-sm ring-1 ring-slate-200">
        @endif

        <div class="prose prose-slate max-w-none">
            {!! $body ?: '<p>Konten detail belum tersedia.</p>' !!}
        </div>

        @if (! empty($item->whatsapp_url))
            <a href="{{ $item->whatsapp_url }}" class="mt-8 inline-flex rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800" target="_blank" rel="noopener">
                Chat WhatsApp Penjual
            </a>
        @endif

        @if (! empty($item->file_path))
            <a href="{{ $imageUrl($item->file_path) }}" class="mt-8 inline-flex rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-800" target="_blank" rel="noopener">
                Unduh Dokumen
            </a>
        @endif
    </div>
</article>
@endsection
