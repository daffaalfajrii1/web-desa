@extends('public.layouts.app')

@section('title', 'Cek Progres Layanan')

@section('content')
@php
    $steps = $submission?->status === 'ditolak'
        ? ['masuk' => 'Masuk', 'diproses' => 'Diproses', 'ditolak' => 'Ditolak']
        : ['masuk' => 'Masuk', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
    $currentIndex = $submission ? array_search($submission->status, array_keys($steps), true) : false;
    $currentIndex = $currentIndex === false ? -1 : $currentIndex;
@endphp

<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <a href="{{ route('public.services.index') }}" class="inline-flex text-sm font-semibold text-amber-200">Kembali ke layanan</a>
        <h1 class="mt-4 text-3xl font-extrabold sm:text-4xl">Cek progres layanan mandiri</h1>
        <p class="mt-3 max-w-3xl text-sm leading-6 text-emerald-50">Gunakan nomor registrasi yang diterima setelah pengajuan, lalu cocokkan dengan No. HP atau NIK pemohon.</p>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-6 lg:py-14">
    <aside class="frontend-card p-6">
        <h2 class="text-xl font-bold text-slate-950">Cari registrasi</h2>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        @if ($notice)
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900">{{ $notice }}</div>
        @endif

        <form method="GET" action="{{ route('public.self-services.status') }}" class="mt-6 grid gap-4">
            <div>
                <label for="registration_number" class="block text-sm font-semibold text-slate-700">Nomor Registrasi</label>
                <input id="registration_number" name="registration_number" value="{{ $registrationNumber }}" placeholder="REG-20260504-LAY-0001" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
            </div>
            <div>
                <label for="contact" class="block text-sm font-semibold text-slate-700">No. HP / NIK Pemohon</label>
                <input id="contact" name="contact" value="{{ $contact }}" placeholder="Nomor yang dipakai saat pengajuan" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
            </div>
            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">Cek Progres</button>
        </form>
    </aside>

    <div class="frontend-card p-6">
        @if ($submission)
            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">{{ $submission->registration_number }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950">{{ $submission->service?->service_name ?: 'Layanan Mandiri' }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $submission->display_applicant_name }} - {{ $submission->display_applicant_contact }}</p>
                </div>
                <span class="self-start rounded-full bg-emerald-50 px-4 py-1.5 text-xs font-bold text-emerald-800">{{ $submission->status_label }}</span>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                @foreach ($steps as $value => $label)
                    @php $isDone = $loop->index <= $currentIndex; @endphp
                    <div class="rounded-lg border {{ $isDone ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-slate-50' }} p-4">
                        <span class="grid h-9 w-9 place-items-center rounded-full {{ $isDone ? 'bg-emerald-700 text-white' : 'bg-white text-slate-400 ring-1 ring-slate-200' }} text-sm font-extrabold">{{ $loop->iteration }}</span>
                        <h3 class="mt-3 font-bold text-slate-950">{{ $label }}</h3>
                        <p class="mt-1 text-xs font-semibold text-slate-500">
                            @if ($value === 'masuk')
                                {{ $submission->submitted_at?->translatedFormat('d F Y H:i') ?: '-' }}
                            @elseif ($value === 'diproses')
                                {{ $submission->processed_at?->translatedFormat('d F Y H:i') ?: 'Menunggu proses' }}
                            @else
                                {{ $submission->completed_at?->translatedFormat('d F Y H:i') ?: 'Belum selesai' }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Catatan admin</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $submission->admin_note ?: 'Belum ada catatan admin.' }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Hasil layanan</p>
                    <h3 class="mt-2 font-bold text-slate-950">{{ $submission->result_title ?: $submission->result_type_label }}</h3>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $submission->result_note ?: 'Hasil layanan belum tersedia.' }}</p>
                    @if ($submission->result_file)
                        <a href="{{ route('public.self-services.download-result', ['registrationNumber' => $submission->registration_number, 'contact' => $contact]) }}" class="mt-4 inline-flex rounded-lg bg-emerald-700 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-800">
                            Unduh hasil layanan
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="grid min-h-72 place-items-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-6 text-center">
                <div>
                    <p class="text-lg font-bold text-slate-950">Masukkan nomor registrasi</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Status pengajuan, catatan admin, dan file hasil akan tampil di sini setelah data ditemukan.</p>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
