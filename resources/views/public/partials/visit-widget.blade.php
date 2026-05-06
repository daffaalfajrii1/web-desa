@php
    $todayVisits = app(\App\Services\Public\VisitCounterService::class)->todayCount();
@endphp

<div class="fixed bottom-28 right-4 z-40 lg:bottom-6 lg:right-6">
    <div class="visit-widget rounded-lg border border-emerald-100 bg-white px-4 py-3 shadow-lg shadow-slate-900/10">
        <p class="text-xs font-semibold text-slate-500">Kunjungan Hari Ini</p>
        <p class="mt-1 text-xl font-bold text-emerald-700">{{ number_format($todayVisits, 0, ',', '.') }}</p>
    </div>
</div>

<a href="{{ route('public.complaints.create') }}" class="fixed bottom-28 left-4 z-40 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-rose-600 text-white shadow-lg shadow-rose-900/20 transition hover:bg-rose-700 lg:bottom-6 lg:left-auto lg:right-48" aria-label="Pengaduan">
    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M21 12a8.5 8.5 0 01-12.5 7.5L3 21l1.5-5.5A8.5 8.5 0 1121 12z" />
        <path d="M8 10h8M8 14h5" />
    </svg>
</a>
