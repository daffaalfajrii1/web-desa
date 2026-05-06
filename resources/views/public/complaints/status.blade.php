@extends('public.layouts.app')

@section('title', 'Cek status pengaduan')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Pengaduan</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Cek status tiket</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Masukkan nomor tiket persis seperti pada bukti pengiriman (contoh: ADU-2026-0001).</p>
    </div>
</section>

<section class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="frontend-card p-6">
        <form method="GET" action="{{ route('public.complaints.status') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="ticket" class="block text-sm font-semibold text-slate-700">Nomor tiket</label>
                <input id="ticket" name="ticket" value="{{ old('ticket', $ticketQuery) }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm uppercase shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="ADU-2026-0001" autocomplete="off">
            </div>
            <button type="submit" class="rounded-lg bg-emerald-700 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-800">Cari</button>
        </form>

        @if ($ticketQuery !== '')
            <div class="mt-8 border-t border-slate-100 pt-8">
                @if ($statusComplaint)
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-4">
                        <p class="text-sm font-semibold text-emerald-900">Tiket ditemukan</p>
                        <dl class="mt-3 grid gap-2 text-sm text-emerald-950">
                            <div class="flex justify-between gap-4"><dt class="text-emerald-800">Perihal</dt><dd class="font-semibold text-right">{{ $statusComplaint->subject }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-emerald-800">Status</dt><dd class="font-bold text-right">{{ $statusComplaint->status_label }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-emerald-800">Dikirim</dt><dd class="text-right">{{ $statusComplaint->submitted_at?->translatedFormat('d M Y H:i') ?: '-' }}</dd></div>
                        </dl>
                    </div>
                @else
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-4 text-sm font-semibold text-amber-900">
                        Tiket tidak ditemukan. Periksa penulisan atau gunakan halaman pengaduan untuk mengajukan laporan baru.
                    </div>
                @endif
            </div>
        @endif

        <a href="{{ route('public.complaints.create') }}" class="mt-8 inline-flex text-sm font-bold text-emerald-700 hover:text-emerald-900">← Kembali ke form pengaduan</a>
    </div>
</section>
@endsection
