@extends('public.layouts.app')

@section('title', 'Layanan Mandiri')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Layanan</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Layanan Mandiri</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Pilih jenis layanan administrasi yang tersedia atau cek progres permohonan yang sudah dikirim.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="mb-8 grid gap-4 lg:grid-cols-[1fr_360px]">
        <form method="GET" action="{{ route('public.self-services.index') }}" class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-3 md:grid-cols-[1fr_auto_auto]">
                <input
                    type="search"
                    name="q"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama layanan, kode, atau persyaratan..."
                    class="block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
                <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cari</button>
                <a href="{{ route('public.self-services.index') }}" class="rounded-lg bg-slate-100 px-5 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-200">Reset</a>
            </div>
        </form>

        <a href="{{ route('public.self-services.status') }}" class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 shadow-sm transition hover:bg-emerald-100">
            <p class="text-sm font-bold text-emerald-900">Sudah mengajukan?</p>
            <p class="mt-1 text-sm leading-6 text-emerald-800">Cek progres dan unduh hasil layanan dengan nomor registrasi.</p>
            <span class="mt-3 inline-flex text-sm font-bold text-emerald-900">Cek registrasi -></span>
        </a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($services as $service)
            <a href="{{ route('public.self-services.show', $service) }}" class="frontend-card p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm font-semibold text-emerald-700">{{ $service->service_code }}</p>
                <h2 class="mt-2 text-xl font-bold text-slate-950">{{ $service->service_name }}</h2>
                @if ($service->slug)
                    <p class="mt-1 font-mono text-[11px] font-semibold text-slate-400">/layanan-mandiri/{{ $service->slug }}</p>
                @endif
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $service->description ?: 'Layanan administrasi desa.' }}</p>
            </a>
        @empty
            <div class="frontend-empty sm:col-span-2 lg:col-span-3">Layanan mandiri belum tersedia atau tidak cocok dengan pencarian.</div>
        @endforelse
    </div>
</section>
@endsection
