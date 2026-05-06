@extends('public.layouts.app')

@section('title', 'Halaman tidak ditemukan')

@section('content')
<div class="relative min-h-[calc(100vh-12rem)] overflow-hidden bg-gradient-to-b from-emerald-950 via-emerald-900 to-slate-900 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(251,191,36,0.18),transparent_55%),radial-gradient(ellipse_50%_40%_at_100%_80%,rgba(16,185,129,0.12),transparent_45%)]"></div>
    <div class="relative mx-auto flex max-w-3xl flex-col items-center px-4 py-20 text-center sm:py-24">
        <p class="text-xs font-extrabold uppercase tracking-[0.35em] text-amber-200/95">Error 404</p>
        <h1 class="mt-6 text-4xl font-extrabold tracking-tight sm:text-5xl">Halaman ini tidak ada</h1>
        <p class="mt-5 max-w-lg text-base leading-relaxed text-emerald-100/92">
            Tautan mungkin sudah diubah atau alamat salah ketik. Gunakan pencarian atau kembali ke beranda untuk melanjutkan.
        </p>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-amber-300 px-6 py-3 text-sm font-extrabold text-slate-950 shadow-lg shadow-black/20 transition hover:bg-amber-200">
                ← Beranda
            </a>
            <a href="{{ route('public.search') }}" class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/10 px-6 py-3 text-sm font-extrabold text-white backdrop-blur transition hover:bg-white/15">
                Buka pencarian
            </a>
            <button type="button" onclick="window.history.back()" class="inline-flex cursor-pointer items-center justify-center rounded-xl px-6 py-3 text-sm font-bold text-emerald-100 underline decoration-emerald-300/80 underline-offset-4 hover:text-white">
                Kembali ke halaman sebelumnya
            </button>
        </div>
        <div class="mt-14 grid w-full gap-4 text-left sm:grid-cols-2">
            <a href="{{ route('public.infographics.index') }}" class="rounded-2xl border border-white/10 bg-white/[0.07] px-5 py-4 text-sm font-semibold text-emerald-50 backdrop-blur transition hover:border-emerald-300/30 hover:bg-white/10">
                <span class="font-extrabold text-white">Infografis</span>
                <span class="mt-1 block text-xs font-medium text-emerald-100/80">Data APBDes, penduduk, bansos…</span>
            </a>
            <a href="{{ route('public.services.index') }}" class="rounded-2xl border border-white/10 bg-white/[0.07] px-5 py-4 text-sm font-semibold text-emerald-50 backdrop-blur transition hover:border-emerald-300/30 hover:bg-white/10">
                <span class="font-extrabold text-white">Layanan</span>
                <span class="mt-1 block text-xs font-medium text-emerald-100/80">Form mandiri dan kanal pengaduan</span>
            </a>
        </div>
        <span class="not-found-code mt-14 select-none font-mono text-[9rem] font-black leading-none text-white/[0.06] sm:text-[11rem]" aria-hidden="true">404</span>
    </div>
</div>
@endsection

@push('head')
    <meta name="robots" content="noindex">
@endpush
