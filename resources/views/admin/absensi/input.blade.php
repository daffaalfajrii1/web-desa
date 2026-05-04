@extends('layouts.admin')

@section('title', 'Input Absen')
@section('page_title', 'Input Absen')

@section('content')
@php
    $formatTime = fn ($time) => $time ? substr((string) $time, 0, 5) : '-';
@endphp

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Absensi Pegawai</h3>
            </div>

            <form method="POST" id="attendance-form">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pegawai <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                    <option value="">Pilih pegawai</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ (string) old('employee_id') === (string) $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} - {{ $employee->position }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PIN Absensi <span class="text-danger">*</span></label>
                                <input type="password" name="pin" class="form-control @error('pin') is-invalid @enderror" required maxlength="20">
                                @error('pin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude') }}" placeholder="-6.2000000">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude') }}" placeholder="106.8166660">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="button" class="btn btn-outline-primary btn-block" id="get-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <small class="text-muted d-block" id="location-status"></small>
                </div>

                <div class="card-footer d-flex flex-wrap">
                    <button type="submit" formaction="{{ route('admin.absensi.check-in') }}" class="btn btn-success mr-2 mb-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Check In
                    </button>
                    <button type="submit" formaction="{{ route('admin.absensi.check-out') }}" class="btn btn-warning mb-2">
                        <i class="fas fa-sign-out-alt mr-1"></i> Check Out
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Setting Aktif</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6">Check In</dt>
                    <dd class="col-6">{{ $formatTime($setting->check_in_start) }} - {{ $formatTime($setting->check_in_end) }}</dd>

                    <dt class="col-6">Check Out</dt>
                    <dd class="col-6">{{ $formatTime($setting->check_out_start) }} - {{ $formatTime($setting->check_out_end) }}</dd>

                    <dt class="col-6">Radius</dt>
                    <dd class="col-6">{{ number_format($setting->allowed_radius_meter) }} meter</dd>

                    <dt class="col-6">Validasi Lokasi</dt>
                    <dd class="col-6">
                        <span class="badge {{ $setting->validate_location ? 'badge-success' : 'badge-secondary' }}">
                            {{ $setting->validate_location ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </dd>

                    <dt class="col-6">API Libur</dt>
                    <dd class="col-6">
                        <span class="badge {{ $setting->use_holiday_api ? 'badge-success' : 'badge-secondary' }}">
                            {{ $setting->use_holiday_api ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </dd>

                    <dt class="col-6">Hari Minggu</dt>
                    <dd class="col-6">
                        <span class="badge {{ $setting->disable_sunday_attendance ? 'badge-secondary' : 'badge-success' }}">
                            {{ $setting->disable_sunday_attendance ? 'Libur' : 'Aktif' }}
                        </span>
                    </dd>
                </dl>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.absensi.settings.edit') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-cog mr-1"></i> Ubah Setting
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Absensi Hari Ini</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Pegawai</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Status</th>
                    <th>Jarak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($todayAttendances as $attendance)
                    <tr>
                        <td>
                            <strong>{{ $attendance->employee?->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $attendance->employee?->position ?? '-' }}</small>
                        </td>
                        <td>{{ $formatTime($attendance->check_in_time) }}</td>
                        <td>{{ $formatTime($attendance->check_out_time) }}</td>
                        <td>
                            <span class="badge {{ $statusBadgeClasses[$attendance->status] ?? 'badge-secondary' }}">
                                {{ $statusLabels[$attendance->status] ?? ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>
                            {{ $attendance->distance_meter !== null ? number_format($attendance->distance_meter, 2, ',', '.') . ' m' : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada absensi hari ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('get-location');
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        const status = document.getElementById('location-status');

        if (!button) return;

        button.addEventListener('click', function () {
            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung geolocation.';
                return;
            }

            status.textContent = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(function (position) {
                latitude.value = position.coords.latitude.toFixed(7);
                longitude.value = position.coords.longitude.toFixed(7);
                status.textContent = 'Lokasi berhasil diambil.';
            }, function () {
                status.textContent = 'Lokasi gagal diambil.';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        });
    });
</script>
@endpush
