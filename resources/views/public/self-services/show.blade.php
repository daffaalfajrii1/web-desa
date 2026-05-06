@extends('public.layouts.app')

@section('title', $service->service_name)

@section('content')
@php
    $hasFileField = $service->fields->contains(fn ($f) => $f->field_type === 'file');
    $inp = 'block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25';
@endphp

<section class="relative overflow-hidden border-b border-emerald-800/40 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 py-10 text-white sm:py-12">
    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-amber-400/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <a href="{{ route('public.self-services.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-200/95 transition hover:text-white">
            <span aria-hidden="true">←</span> Kembali ke daftar layanan
        </a>
        <p class="mt-5 text-xs font-extrabold uppercase tracking-[0.2em] text-amber-200/90">{{ $service->service_code }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl lg:text-[2.35rem]">{{ $service->service_name }}</h1>
        @if ($service->description)
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-emerald-50/95">{{ $service->description }}</p>
        @endif
        <p class="mt-4 inline-flex flex-wrap items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-medium leading-relaxed text-emerald-50 backdrop-blur">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-300 shadow-[0_0_10px_rgba(251,191,36,0.8)]" aria-hidden="true"></span>
            <span>Pengajuan online resmi — isi data sesuai dokumen asli agar proses verifikasi berjalan lancar.</span>
        </p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:items-start">
        <aside class="frontend-card overflow-hidden p-0 shadow-md ring-1 ring-slate-200/70">
            <div class="border-b border-emerald-100 bg-gradient-to-br from-emerald-50 to-white px-5 py-4">
                <h2 class="text-base font-bold text-slate-950">Persyaratan</h2>
                <p class="mt-1 text-xs text-slate-600">Pastikan berkas yang diminta sudah siap sebelum mengirim formulir.</p>
            </div>
            <div class="prose prose-sm prose-slate max-w-none px-5 py-5">
                {!! $service->requirements ?: '<p class="text-sm text-slate-600">Persyaratan belum diisi.</p>' !!}
            </div>
        </aside>

        <div class="frontend-card overflow-hidden shadow-lg shadow-slate-900/[0.04] ring-1 ring-slate-200/90">
            <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4 sm:flex sm:items-start sm:justify-between sm:gap-4">
                <div class="space-y-2">
                    <h2 class="text-base font-bold text-slate-950">Formulir permohonan</h2>
                    <p class="max-w-2xl text-sm leading-relaxed text-slate-600">
                        Lengkapi setiap bagian di bawah ini dengan teliti. Gunakan identitas dan nomor kontak yang aktif,
                        karena kami memakainya untuk konfirmasi dan agar Anda dapat mengecek status permohonan kapan saja.
                    </p>
                </div>
                <span class="mt-3 inline-flex shrink-0 self-start rounded-md bg-emerald-700 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white sm:mt-0">Online</span>
            </div>

            <div class="px-5 py-6 sm:p-7">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">{{ session('success') }}</div>
                @endif

                @error('fields')
                    <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-950">{{ $message }}</div>
                @enderror

                @if ($service->fields->isEmpty())
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm leading-relaxed text-slate-600">
                        Pengajuan online untuk layanan ini belum dibuka. Silakan datang ke kantor desa atau hubungi petugas administrasi untuk bantuan.
                    </div>
                @else
                    <form
                        method="POST"
                        action="{{ route('public.self-services.store', $service) }}"
                        enctype="{{ $hasFileField ? 'multipart/form-data' : 'application/x-www-form-urlencoded' }}"
                        class="space-y-8"
                    >
                        @csrf

                        <div class="space-y-6">
                            @foreach ($service->fields as $field)
                                @php
                                    $fid = 'fld_'.$field->id;
                                    $fname = 'fields['.$field->field_name.']';
                                    $oldKey = 'fields.'.$field->field_name;
                                @endphp
                                <div class="pb-6 last:pb-0 last:border-0 @if(!$loop->last) border-b border-slate-100 @endif">
                                    <label for="{{ $fid }}" class="mb-1.5 block">
                                        <span class="flex flex-wrap items-baseline gap-2">
                                            <span class="text-sm font-bold text-slate-900">{{ $field->field_label }}</span>
                                            @if ($field->is_required)
                                                <span class="text-xs font-semibold text-rose-600">wajib</span>
                                            @endif
                                        </span>
                                    </label>

                                    @switch($field->field_type)
                                        @case('textarea')
                                            <textarea id="{{ $fid }}" name="{{ $fname }}" rows="4" placeholder="{{ $field->placeholder }}" class="{{ $inp }}"
                                                @if ($field->is_required) required @endif>{{ old($oldKey) }}</textarea>
                                            @break

                                        @case('number')
                                            <input id="{{ $fid }}" name="{{ $fname }}" type="number" value="{{ old($oldKey) }}" placeholder="{{ $field->placeholder }}" class="{{ $inp }}"
                                                @if ($field->is_required) required @endif>
                                            @break

                                        @case('date')
                                            <input id="{{ $fid }}" name="{{ $fname }}" type="date" value="{{ old($oldKey) }}" class="{{ $inp }}"
                                                @if ($field->is_required) required @endif>
                                            @break

                                        @case('select')
                                            <select id="{{ $fid }}" name="{{ $fname }}" class="{{ $inp }}" @if ($field->is_required) required @endif>
                                                <option value="">Pilih</option>
                                                @foreach ((array) $field->options as $option)
                                                    <option value="{{ $option }}" @selected(old($oldKey) == $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('radio')
                                            <div class="mt-2 space-y-2 rounded-lg border border-slate-100 bg-slate-50/60 p-3">
                                                @foreach ((array) $field->options as $option)
                                                    <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-800">
                                                        <input type="radio" name="{{ $fname }}" value="{{ $option }}" class="h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old($oldKey) == $option) @if ($field->is_required && $loop->first) required @endif>
                                                        <span>{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('checkbox')
                                            @php $oldArr = (array) old($oldKey, []); @endphp
                                            <div class="mt-2 space-y-2 rounded-lg border border-slate-100 bg-slate-50/60 p-3">
                                                @foreach ((array) $field->options as $option)
                                                    <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-800">
                                                        <input type="checkbox" name="{{ $fname }}[]" value="{{ $option }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(in_array($option, $oldArr, true))>
                                                        <span>{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('file')
                                            <input id="{{ $fid }}" name="{{ $fname }}" type="file"
                                                class="block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:bg-emerald-700" @if ($field->is_required) required @endif>
                                            @break

                                        @default
                                            <input id="{{ $fid }}" name="{{ $fname }}" type="text" value="{{ old($oldKey) }}" placeholder="{{ $field->placeholder }}" class="{{ $inp }}"
                                                @if ($field->is_required) required @endif>
                                    @endswitch

                                    @if ($field->help_text)
                                        <p class="mt-2 text-xs text-slate-500">{{ $field->help_text }}</p>
                                    @endif

                                    @error('fields.'.$field->field_name)<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                                    @error('fields.'.$field->field_name.'.*')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            @endforeach
                        </div>

                        @if (($useRecaptcha ?? false))
                            @include('public.partials.recaptcha')
                        @else
                            @include('public.partials.captcha', ['captcha' => $captcha])
                        @endif

                        <div class="flex flex-col gap-4 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs leading-relaxed text-slate-500">Setelah dikirim, permohonan Anda masuk ke antrean pelayanan. Simpan nomor registrasi yang muncul — Anda membutuhkannya untuk memantau atau melengkapi berkas jika diminta.</p>
                            <button type="submit" class="inline-flex min-h-[44px] shrink-0 items-center justify-center rounded-lg bg-emerald-700 px-6 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/35">
                                Kirim permohonan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
