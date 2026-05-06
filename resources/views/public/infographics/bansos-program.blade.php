@extends('public.layouts.app')

@section('title', 'Program Bansos')

@section('content')
@include('public.infographics._sub-hero', ['title' => 'Program Bansos', 'subtitle' => 'Program aktif dan kuota dari admin.', 'kicker' => 'Bansos'])

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if ($programs->isEmpty())
        <div class="frontend-empty">Belum ada program bantuan sosial.</div>
    @else
        <div class="grid gap-5 md:grid-cols-2">
            @foreach ($programs as $p)
                <div class="frontend-card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-950">{{ $p->name }}</h2>
                            <p class="mt-2 text-sm text-slate-600">{{ $p->description ? \Illuminate\Support\Str::limit(strip_tags((string) $p->description), 180) : '—' }}</p>
                        </div>
                        @if ($p->is_active)
                            <span class="shrink-0 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900">Aktif</span>
                        @else
                            <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                        @endif
                    </div>
                    <dl class="mt-5 grid gap-2 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Tahun</dt><dd class="font-semibold">{{ $p->year ?: '—' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Periode</dt><dd>{{ $p->period ?: '—' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Target / kuota</dt><dd>{{ $p->quota !== null ? number_format((int) $p->quota, 0, ',', '.') : '—' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Penerima (data)</dt><dd class="font-bold text-emerald-800">{{ number_format((int) $p->recipients_count, 0, ',', '.') }}</dd></div>
                    </dl>
                    <a href="{{ route('public.infographics.bansos-recipients', ['program' => $p->id]) }}" class="mt-5 inline-flex text-sm font-bold text-emerald-700">Lihat penerima →</a>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
