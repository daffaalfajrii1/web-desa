@extends('public.layouts.app')

@section('title', $page->title)

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <nav class="text-xs font-semibold text-amber-200/90">
            <a href="{{ route('public.profile') }}" class="hover:text-amber-100">Profil desa</a>
            <span class="mx-2 opacity-60">/</span>
            <span>{{ $profileLabel ?? $page->title }}</span>
        </nav>
        <h1 class="mt-5 text-3xl font-extrabold sm:text-4xl">{{ $page->title }}</h1>
        @if ($page->excerpt)
            <p class="mt-4 max-w-3xl text-sm leading-6 text-emerald-50">{{ $page->excerpt }}</p>
        @endif
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($featuredImage)
        <div class="mb-10 overflow-hidden rounded-2xl bg-gradient-to-b from-slate-50 to-white p-4 shadow-lg ring-1 ring-slate-200/90 sm:p-6">
            <div class="flex justify-center">
                <img src="{{ $featuredImage }}" alt="" class="max-h-[min(26rem,62vh)] w-auto max-w-full rounded-xl object-contain shadow-sm ring-1 ring-slate-200">
            </div>
        </div>
    @endif

    <article class="prose prose-slate max-w-none">
        @if ($page->content)
            {!! $page->content !!}
        @else
            <p class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm font-semibold text-slate-600">Konten halaman ini belum tersedia.</p>
        @endif
    </article>

    <a href="{{ route('public.profile') }}" class="mt-10 inline-flex text-sm font-extrabold text-emerald-700 hover:text-emerald-900">← Kembali ke profil desa</a>
</section>
@endsection
