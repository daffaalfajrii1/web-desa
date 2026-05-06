@extends('public.layouts.app')

@section('title', 'Peta Desa')

@section('content')
<section class="border-b border-emerald-900/20 bg-emerald-950 py-8 text-white sm:py-10">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-6">
        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-amber-200">Lokasi</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Peta desa</h1>
    </div>
</section>

<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-6 lg:py-12">
    <p class="max-w-3xl text-sm leading-relaxed text-slate-600">
        {{ $village?->address ?: 'Alamat desa akan ditampilkan bersama peta di bawah.' }}
    </p>
    <div class="mt-8 overflow-hidden rounded-2xl bg-slate-100 shadow-lg ring-1 ring-slate-200/90">
        @if ($village?->map_embed)
            <div class="public-map-embed">{!! $village->map_embed !!}</div>
        @else
            <div class="grid min-h-[420px] place-items-center px-6 text-center text-sm font-semibold text-slate-500">
                Peta interaktif belum tersedia.
            </div>
        @endif
    </div>
</section>
@endsection
