@extends('public.layouts.app')

@section('title', 'Profil Desa')

@section('content')
@php
    $headPhoto = $village?->resolvePublicHeadPhotoUrl();
@endphp

<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Profil Desa</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">{{ $village?->village_name ?: 'Profil Desa' }}</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">{{ $village?->address ?: 'Informasi profil desa.' }}</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-white via-emerald-50/35 to-slate-50 shadow-xl shadow-emerald-950/[0.06] ring-1 ring-slate-200/80">
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-emerald-300/15 blur-3xl"></div>
        <div class="relative grid gap-6 p-6 sm:p-8 lg:grid-cols-[300px_1fr] lg:gap-8 lg:p-10">
            <div class="rounded-2xl bg-white/90 p-5 text-center ring-1 ring-emerald-100/80">
                <div class="mx-auto h-40 w-40 overflow-hidden rounded-full bg-gradient-to-br from-emerald-100 to-slate-100 ring-4 ring-white shadow-md">
                    @if ($headPhoto)
                        <img src="{{ $headPhoto }}" alt="{{ $village?->village_head_name }}" class="h-full w-full object-cover object-top">
                    @else
                        <div class="grid h-full place-items-center text-4xl font-extrabold text-emerald-700">{{ \Illuminate\Support\Str::substr((string) ($village?->village_head_name ?: 'K'), 0, 1) }}</div>
                    @endif
                </div>
                <p class="mt-4 text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Kepala Desa</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-950">{{ $village?->village_head_name ?: 'Belum diatur' }}</h2>
                @if ($village?->villageHeadEmployee?->position)
                    <p class="mt-1 text-sm font-semibold text-slate-600">{{ $village->villageHeadEmployee->position }}</p>
                @endif
            </div>

            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700">Profil & sambutan</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Visi Misi Desa</h2>
                <blockquote class="mt-5 rounded-r-2xl border-l-4 border-emerald-500 bg-white/80 p-5 text-[15px] leading-8 text-slate-800 shadow-sm">
                    <svg class="mb-2 h-6 w-6 text-emerald-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.17 6A4.17 4.17 0 003 10.17V18h7.83v-7.83H6.92v-.5A2.08 2.08 0 019 7.58h1V6H7.17zm10 0A4.17 4.17 0 0013 10.17V18h7.83v-7.83h-3.91v-.5A2.08 2.08 0 0119 7.58h1V6h-2.83z"/></svg>
                    {!! $village?->welcome_message ?: '<p>Selamat datang di website resmi desa.</p>' !!}
                </blockquote>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @if ($village?->vision)
                        <div class="rounded-xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white px-4 py-3 shadow-sm ring-1 ring-emerald-100/60">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Visi</p>
                            <p class="mt-2 text-sm leading-6 text-slate-800">{{ \Illuminate\Support\Str::limit(strip_tags((string) $village->vision), 400) }}</p>
                        </div>
                    @endif
                    @if ($village?->mission)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/90 px-4 py-3 shadow-sm ring-1 ring-slate-200/80">
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-600">Misi</p>
                            <p class="mt-2 text-sm leading-6 text-slate-800">{{ \Illuminate\Support\Str::limit(strip_tags((string) $village->mission), 400) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('public.profile.structure') }}" class="group flex flex-col justify-between rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-md ring-1 ring-emerald-100/80 transition hover:-translate-y-0.5 hover:shadow-lg">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-emerald-800">Perangkat desa</p>
                <h2 class="mt-2 text-xl font-extrabold text-slate-950">Struktur organisasi</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Nama dan jabatan mengikuti urutan yang ditetapkan untuk SOTK.</p>
            </div>
            <span class="mt-4 inline-flex text-sm font-extrabold text-emerald-700 group-hover:text-emerald-900">Lihat struktur →</span>
        </a>
        <a href="{{ route('public.map') }}" class="group flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-md ring-1 ring-slate-200/90 transition hover:-translate-y-0.5 hover:border-sky-200 hover:ring-sky-100">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wide text-sky-700">Wilayah</p>
                <h2 class="mt-2 text-xl font-extrabold text-slate-950">Peta desa</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Lihat batas wilayah dan titik penting desa.</p>
            </div>
            <span class="mt-4 inline-flex text-sm font-extrabold text-sky-700 group-hover:text-sky-900">Buka peta →</span>
        </a>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-12 pt-10 sm:px-6 lg:px-6 lg:pb-16">
    <h2 class="text-lg font-extrabold text-slate-950">Halaman profil</h2>
    <p class="mt-2 text-sm text-slate-600">Topik tambahan tentang desa.</p>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse (($pages ?? collect()) as $page)
            <a href="{{ route('public.profile.page', $page->slug) }}" class="frontend-card block p-5 transition hover:ring-2 hover:ring-emerald-200">
                <h3 class="font-bold text-slate-950">{{ $page->title }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ \Illuminate\Support\Str::limit(strip_tags((string) $page->excerpt ?: (string) $page->content), 140) ?: 'Ringkasan halaman akan tampil di sini.' }}
                </p>
                <span class="mt-4 inline-flex text-sm font-bold text-emerald-700">Buka halaman -></span>
            </a>
        @empty
            @forelse ($menus as $menu)
                <a href="{{ $menu->page ? route('public.profile.menu', $menu->slug) : '#' }}" class="frontend-card block p-5 transition hover:ring-2 hover:ring-emerald-200 {{ $menu->page ? '' : 'pointer-events-none opacity-60' }}">
                    <h3 class="font-bold text-slate-950">{{ $menu->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) $menu->page?->excerpt ?: (string) $menu->page?->content), 140) ?: 'Ringkasan halaman akan tampil di sini.' }}
                    </p>
                    @if ($menu->page)
                        <span class="mt-4 inline-flex text-sm font-bold text-emerald-700">Buka halaman -></span>
                    @else
                        <span class="mt-4 inline-flex text-sm font-semibold text-amber-700">Belum terhubung ke halaman</span>
                    @endif
                </a>
            @empty
                <div class="frontend-empty sm:col-span-2 lg:col-span-3">Halaman profil belum tersedia.</div>
            @endforelse
        @endforelse
    </div>
</section>
@endsection
