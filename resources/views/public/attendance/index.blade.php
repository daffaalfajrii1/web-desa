@extends('public.layouts.app')

@section('title', 'Absensi Pegawai')

@section('content')
<section class="bg-emerald-950 py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-6">
        <p class="text-sm font-semibold text-amber-200">Pegawai</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Absensi Pegawai</h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50">
            Pilih pegawai, masukkan PIN absensi, lalu kirim absen masuk atau pulang.
        </p>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-6 lg:py-14">
    <div class="frontend-card p-6">
        <h2 class="text-xl font-bold text-slate-950">Form Absensi</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Jam masuk {{ substr((string) $setting->check_in_start, 0, 5) }} - {{ substr((string) $setting->check_in_end, 0, 5) }},
            jam pulang {{ substr((string) $setting->check_out_start, 0, 5) }} - {{ substr((string) $setting->check_out_end, 0, 5) }}.
        </p>

        @if (session('success'))
            <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mt-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">{{ session('error') }}</div>
        @endif

        @php
            $checkInStartLabel = substr((string) $setting->check_in_start, 0, 5);
            $checkOutStartLabel = substr((string) $setting->check_out_start, 0, 5);
            $checkOutEndLabel = substr((string) $setting->check_out_end, 0, 5);
        @endphp

        @if (! $canCheckInNow)
            <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900">
                Belum waktu absen masuk — tombol &ldquo;Absen Masuk&rdquo; aktif mulai pukul {{ $checkInStartLabel }} (waktu aplikasi: {{ now()->format('H:i') }}).
            </div>
        @endif

        @if (! $canCheckOutNow)
            <div class="mt-5 rounded-lg border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">
                Di luar rentang absen pulang ({{ $checkOutStartLabel }}&ndash;{{ $checkOutEndLabel }}). Tombol &ldquo;Absen Pulang&rdquo; dinonaktifkan sampai masuk jendela jam pulang.
            </div>
        @endif

        <form method="POST" action="{{ route('public.attendance.check-in') }}" class="mt-6 space-y-5" id="public-attendance-form">
            @csrf
            <input type="hidden" name="latitude" id="attendance_latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="attendance_longitude" value="{{ old('longitude') }}">

            <div>
                <label for="employee_id" class="block text-sm font-semibold text-slate-700">Pegawai</label>
                <select id="employee_id" name="employee_id" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    <option value="">Pilih pegawai</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }} - {{ $employee->position }}</option>
                    @endforeach
                </select>
                @error('employee_id')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="pin" class="block text-sm font-semibold text-slate-700">PIN Absensi</label>
                <input id="pin" name="pin" type="password" inputmode="numeric" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                @error('pin')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div id="attendance-location-note" class="rounded-lg bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-500">
                @if ($setting->validate_location)
                    Meminta lokasi perangkat untuk pengecekan radius…
                @else
                    Validasi lokasi dinonaktifkan — absen dapat dilakukan tanpa GPS (dari mana saja).
                @endif
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <button type="submit" name="action_check_in" value="1"
                    @disabled(! $canCheckInNow)
                    title="{{ $canCheckInNow ? '' : 'Absen masuk dibuka mulai pukul ' . $checkInStartLabel }}"
                    class="rounded-lg bg-emerald-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-45 disabled:hover:bg-emerald-700">
                    Absen Masuk
                </button>
                <button type="submit" formaction="{{ route('public.attendance.check-out') }}" name="action_check_out" value="1"
                    @disabled(! $canCheckOutNow)
                    title="{{ $canCheckOutNow ? '' : 'Absen pulang hanya pada ' . $checkOutStartLabel . '–' . $checkOutEndLabel }}"
                    class="rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-45 disabled:hover:bg-slate-950">
                    Absen Pulang
                </button>
            </div>
        </form>
    </div>

    <div class="frontend-card p-6">
        <h2 class="text-xl font-bold text-slate-950">Aktivitas Hari Ini</h2>
        <div class="mt-5 grid gap-3">
            @forelse ($todayAttendances as $attendance)
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-bold text-slate-950">{{ $attendance->employee?->name ?: 'Pegawai' }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $statusLabels[$attendance->status] ?? ucfirst($attendance->status) }}</p>
                        </div>
                        <div class="text-right text-sm text-slate-600">
                            <p>Masuk: {{ $attendance->check_in_time ? substr($attendance->check_in_time, 0, 5) : '-' }}</p>
                            <p>Pulang: {{ $attendance->check_out_time ? substr($attendance->check_out_time, 0, 5) : '-' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="frontend-empty">Belum ada absensi hari ini.</div>
            @endforelse
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var requireLocation = @json((bool) $setting->validate_location);
        var latitude = document.getElementById('attendance_latitude');
        var longitude = document.getElementById('attendance_longitude');
        var note = document.getElementById('attendance-location-note');

        if (! requireLocation) {
            if (note) {
                note.classList.remove('text-slate-500', 'bg-slate-50');
                note.classList.add('text-slate-600', 'bg-slate-100');
            }
            return;
        }

        if (! navigator.geolocation || ! latitude || ! longitude) {
            if (note) {
                note.textContent = 'Browser tidak mendukung geolocation. Aktifkan atau gunakan perangkat lain jika validasi radius diperlukan.';
                note.classList.add('text-amber-700', 'bg-amber-50');
            }
            return;
        }

        navigator.geolocation.getCurrentPosition(function (position) {
            latitude.value = position.coords.latitude;
            longitude.value = position.coords.longitude;

            if (note) {
                note.textContent = 'Lokasi perangkat berhasil dibaca.';
                note.classList.remove('text-slate-500', 'bg-slate-50');
                note.classList.add('text-emerald-700', 'bg-emerald-50');
            }
        }, function () {
            if (note) {
                note.textContent = 'Lokasi belum tersedia. Validasi radius aktif — izinkan akses lokasi atau absensi dapat ditolak.';
                note.classList.add('text-amber-700', 'bg-amber-50');
            }
        }, {
            enableHighAccuracy: true,
            timeout: 6000,
            maximumAge: 60000
        });
    });
</script>
@endsection
