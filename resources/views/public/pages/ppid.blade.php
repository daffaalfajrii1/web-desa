@extends('public.layouts.app')

@section('title', 'PPID')

@section('content')
<section class="bg-emerald-950 py-12 text-white lg:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Pejabat Pengelola Informasi dan Dokumentasi</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Transparansi Informasi Publik</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-emerald-50">
            Dokumen di bawah mengikuti klasifikasi Undang-Undang Keterbukaan Informasi Publik: <strong>Informasi Berkala</strong> (diperbarui secara berkala),
            <strong>Informasi Serta Merta</strong> (wajib diumumkan segera), dan <strong>Informasi Setiap Saat</strong> (tersedia untuk pemohon).
            Dokumen dikelompokkan per jenis informasi berikut.
        </p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    @if (session('success'))
        <div class="mb-8 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-8 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
    @endif

    <div class="space-y-14">
        @foreach ($typeOrder as $typeKey)
            @php
                $bucket = $grouped->get($typeKey, collect());
                $label = $typeLabels[$typeKey] ?? ucfirst($typeKey);
            @endphp

            <div class="scroll-mt-24">
                <div class="flex flex-wrap items-end justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-950">{{ $label }}</h2>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                            @if ($typeKey === 'berkala')
                                Informasi yang wajib diperbarui secara berkala (misalnya laporan keuangan, rencana pembangunan ringkas). Tiap judul section dapat berisi satu atau lebih dokumen unduhan.
                            @elseif ($typeKey === 'serta_merta')
                                Informasi yang harus diumumkan tanpa diminta, bersifat mendesak bagi publik.
                            @else
                                Informasi yang dapat diminta setiap saat oleh pemohon sesuai prosedur — dokumen terkait dipublikasikan di bawah untuk kemudahan akses.
                            @endif
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-4 py-1.5 text-xs font-bold text-emerald-900">{{ $bucket->count() }} section</span>
                </div>

                @if ($bucket->isEmpty())
                    <div class="frontend-empty mt-6">Belum ada section aktif untuk kategori ini.</div>
                @else
                    <div class="mt-6 space-y-3">
                        @foreach ($bucket as $section)
                            <details class="ppid-details frontend-card overflow-hidden p-0">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 bg-slate-50 px-5 py-4 transition hover:bg-emerald-50/80 [&::-webkit-details-marker]:hidden">
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-bold text-slate-950">{{ $section->title }}</h3>
                                        <p class="mt-0.5 text-xs font-semibold text-emerald-700">Klik untuk melihat dokumen</p>
                                    </div>
                                    <span class="ppid-accordion-chevron grid h-9 w-9 shrink-0 place-items-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm" aria-hidden="true">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                    </span>
                                </summary>
                                <div class="border-t border-slate-100 px-5 py-5">
                                    @if ($section->documents->isEmpty())
                                        <p class="text-sm text-slate-500">Belum ada dokumen pada section ini.</p>
                                    @else
                                        <ul class="divide-y divide-slate-100">
                                            @foreach ($section->documents as $doc)
                                                <li class="flex flex-wrap items-center justify-between gap-4 py-4 first:pt-0">
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-950">{{ $doc->title }}</p>
                                                    </div>
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($doc->file_path) }}" target="_blank" rel="noopener" class="inline-flex shrink-0 items-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-800">
                                                        Unduh dokumen
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div id="permohonan" class="mt-16 scroll-mt-24 border-t border-slate-200 pt-14">
        <div class="mx-auto max-w-3xl">
            <h2 class="text-2xl font-bold text-slate-950">Permohonan informasi publik</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Ajukan permintaan data atau dokumen yang belum tersedia di daftar di atas. Petugas PPID akan menanggapi sesuai jadwal dan ketentuan yang berlaku.
                Lengkapi captcha untuk melindungi formulir dari spam otomatis.
            </p>

            <div class="frontend-card mt-8 p-6">
                <form method="POST" action="{{ route('public.ppid.store-request') }}" class="grid gap-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="ppid_name" class="block text-sm font-semibold text-slate-700">Nama lengkap <span class="text-rose-600">*</span></label>
                            <input id="ppid_name" name="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('name')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="ppid_institution" class="block text-sm font-semibold text-slate-700">Instansi / organisasi</label>
                            <input id="ppid_institution" name="institution" value="{{ old('institution') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('institution')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="ppid_phone" class="block text-sm font-semibold text-slate-700">Telepon / WhatsApp <span class="text-rose-600">*</span></label>
                            <input id="ppid_phone" name="phone" value="{{ old('phone') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('phone')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="ppid_email" class="block text-sm font-semibold text-slate-700">Email <span class="text-rose-600">*</span></label>
                            <input id="ppid_email" name="email" type="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('email')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="ppid_content" class="block text-sm font-semibold text-slate-700">Rincian permohonan <span class="text-rose-600">*</span></label>
                        <textarea id="ppid_content" name="request_content" rows="6" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required placeholder="Sebutkan judul informasi, periode, dan tujuan penggunaan (jika perlu).">{{ old('request_content') }}</textarea>
                        @error('request_content')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    @include('public.partials.captcha', ['captcha' => $captcha])

                    <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">
                        Kirim permohonan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
