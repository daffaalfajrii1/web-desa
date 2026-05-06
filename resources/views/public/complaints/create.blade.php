@extends('public.layouts.app')

@section('title', 'Pengaduan')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Pengaduan</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Pengaduan Warga</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">Kirim laporan atau aspirasi kepada pemerintah desa. Anda akan memperoleh nomor tiket untuk memantau progres.</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('public.complaints.status') }}" class="inline-flex rounded-lg border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/15">Cek status tiket</a>
            <a href="{{ route('public.services.index') }}" class="inline-flex rounded-lg border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/15">Daftar layanan</a>
        </div>
    </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-6 lg:py-14">
    <div class="mb-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-6 text-slate-700">
        <p class="font-bold text-slate-950">Cara mengadu</p>
        <ol class="mt-2 list-decimal space-y-1 pl-5">
            <li>Isi identitas dan nomor HP yang dapat dihubungi.</li>
            <li>Tulis subjek ringkas dan uraian pengaduan; unggah foto atau PDF sebagai lampiran (opsional).</li>
            <li>Selesaikan captcha untuk melindungi formulir dari spam otomatis.</li>
            <li>Simpan nomor tiket untuk memantau status melalui halaman <a href="{{ route('public.complaints.status') }}" class="font-bold text-emerald-700 underline">cek status</a>.</li>
        </ol>
    </div>

    <div class="frontend-card p-6">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('public.complaints.store') }}" enctype="multipart/form-data" class="grid gap-5">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nama</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('name')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700">No. HP</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('phone')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="nik" class="block text-sm font-semibold text-slate-700">NIK</label>
                    <input id="nik" name="nik" value="{{ old('nik') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('nik')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('email')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea id="address" name="address" rows="3" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('address') }}</textarea>
                @error('address')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-semibold text-slate-700">Subjek</label>
                <input id="subject" name="subject" value="{{ old('subject') }}" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('subject')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="complaint_text" class="block text-sm font-semibold text-slate-700">Isi Pengaduan</label>
                <textarea id="complaint_text" name="complaint_text" rows="6" class="mt-2 block w-full rounded-lg border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>{{ old('complaint_text') }}</textarea>
                @error('complaint_text')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="attachments" class="block text-sm font-semibold text-slate-700">Lampiran <span class="font-normal text-slate-500">(opsional)</span></label>
                <p class="mt-1 text-xs text-slate-500">Unggah hingga <strong class="font-semibold text-slate-600">5 berkas</strong> — foto (JPEG, PNG, GIF, WEBP) atau PDF, maks. <strong class="font-semibold text-slate-600">10 MB</strong> per berkas.</p>
                <input id="attachments" name="attachments[]" type="file" multiple accept="image/jpeg,image/png,image/gif,image/webp,.pdf,application/pdf"
                    class="mt-3 block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:bg-emerald-700">
                @error('attachments')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                @error('attachments.*')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            @include('public.partials.captcha', ['captcha' => $captcha])

            <button type="submit" class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">
                Kirim Pengaduan
            </button>
        </form>
    </div>
</section>
@endsection
