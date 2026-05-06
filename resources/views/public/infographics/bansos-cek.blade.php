@extends('public.layouts.app')

@section('title', 'Cek Bansos')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Cek Data Bansos', 'subtitle' => 'Pencarian berdasarkan NIK, KK, atau nama sesuai data admin.', 'kicker' => 'Bansos'])

<section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="frontend-card p-6">
        <form method="GET" action="{{ route('public.infographics.bansos-check') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="q" class="block text-sm font-semibold text-slate-700">NIK / KK / nama</label>
                <input id="q" name="q" value="{{ old('q', $q ?? '') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm" placeholder="Minimal 3 karakter nama atau NIK lengkap">
            </div>
            <button type="submit" class="rounded-lg bg-emerald-700 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-800">Cari</button>
        </form>

        @if (filled($q ?? null))
            <div class="mt-8 border-t border-slate-100 pt-8">
                @if (($result ?? collect())->isEmpty())
                    <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900">Data tidak ditemukan atau kata kunci terlalu umum.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($result as $r)
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                <p class="font-bold text-slate-950">{{ $r->name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $r->program?->name ?: 'Program' }} · {{ $r->hamlet?->name ?: 'Dusun' }}</p>
                                <dl class="mt-3 grid gap-1 text-sm">
                                    <div class="flex justify-between gap-4"><dt class="text-slate-500">NIK</dt><dd>{{ $r->nik ?: '—' }}</dd></div>
                                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Status salur</dt><dd class="font-semibold">{{ $r->distribution_status ?: '—' }}</dd></div>
                                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Verifikasi</dt><dd>{{ $r->verification_status ?: '—' }}</dd></div>
                                </dl>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
