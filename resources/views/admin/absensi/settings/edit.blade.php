@extends('layouts.admin')

@section('title', 'Setting Absensi')
@section('page_title', 'Setting Absensi')

@section('content')
@php
    $mapLat = old('office_latitude', $setting->office_latitude ?? -6.2000000);
    $mapLng = old('office_longitude', $setting->office_longitude ?? 106.8166660);
    $mapDelta = 0.01;
    $mapUrl = 'https://www.openstreetmap.org/export/embed.html?bbox='
        . ((float) $mapLng - $mapDelta) . '%2C'
        . ((float) $mapLat - $mapDelta) . '%2C'
        . ((float) $mapLng + $mapDelta) . '%2C'
        . ((float) $mapLat + $mapDelta)
        . '&layer=mapnik&marker=' . $mapLat . '%2C' . $mapLng;
@endphp

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Setting Global Absensi</h3>
    </div>

    <form method="POST" action="{{ route('admin.absensi.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Check In Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="check_in_start" class="form-control @error('check_in_start') is-invalid @enderror"
                               value="{{ old('check_in_start', substr((string) $setting->check_in_start, 0, 5)) }}" required>
                        @error('check_in_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Check In Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="check_in_end" class="form-control @error('check_in_end') is-invalid @enderror"
                               value="{{ old('check_in_end', substr((string) $setting->check_in_end, 0, 5)) }}" required>
                        @error('check_in_end')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Check Out Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="check_out_start" class="form-control @error('check_out_start') is-invalid @enderror"
                               value="{{ old('check_out_start', substr((string) $setting->check_out_start, 0, 5)) }}" required>
                        @error('check_out_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Check Out Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="check_out_end" class="form-control @error('check_out_end') is-invalid @enderror"
                               value="{{ old('check_out_end', substr((string) $setting->check_out_end, 0, 5)) }}" required>
                        @error('check_out_end')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Latitude Kantor/Desa</label>
                        <input type="text" name="office_latitude" id="office_latitude" class="form-control @error('office_latitude') is-invalid @enderror"
                               value="{{ old('office_latitude', $setting->office_latitude) }}">
                        @error('office_latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Longitude Kantor/Desa</label>
                        <input type="text" name="office_longitude" id="office_longitude" class="form-control @error('office_longitude') is-invalid @enderror"
                               value="{{ old('office_longitude', $setting->office_longitude) }}">
                        @error('office_longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Radius Diizinkan (meter) <span class="text-danger">*</span></label>
                        <input type="number" name="allowed_radius_meter" class="form-control @error('allowed_radius_meter') is-invalid @enderror"
                               value="{{ old('allowed_radius_meter', $setting->allowed_radius_meter) }}" min="1" required>
                        @error('allowed_radius_meter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="validate_location" value="1" class="custom-control-input" id="validate_location"
                            {{ old('validate_location', $setting->validate_location) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="validate_location">Validasi radius lokasi (GPS)</label>
                    </div>
                    <small class="form-text text-muted d-block" style="margin-top:-0.5rem;">
                        Nonaktifkan agar pegawai bisa absen dari mana saja tanpa pemeriksaan jarak ke kantor/desa. Halaman publik tidak akan meminta izin lokasi perangkat.
                    </small>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="use_holiday_api" value="1" class="custom-control-input" id="use_holiday_api"
                            {{ old('use_holiday_api', $setting->use_holiday_api) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="use_holiday_api">Gunakan API libur nasional</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="disable_saturday_attendance" value="1" class="custom-control-input" id="disable_saturday_attendance"
                            {{ old('disable_saturday_attendance', $setting->disable_saturday_attendance) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="disable_saturday_attendance">Nonaktifkan absensi Sabtu</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="disable_sunday_attendance" value="1" class="custom-control-input" id="disable_sunday_attendance"
                            {{ old('disable_sunday_attendance', $setting->disable_sunday_attendance) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="disable_sunday_attendance">Nonaktifkan absensi Minggu</label>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-info mb-0">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title mb-0">Peta Lokasi Kantor/Desa</h3>
                    <div class="mt-2 mt-md-0">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="use-current-location">
                            <i class="fas fa-map-marker-alt mr-1"></i> Lokasi Saat Ini
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="refresh-map">
                            <i class="fas fa-sync-alt mr-1"></i> Perbarui Peta
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <iframe
                        id="office-map-preview"
                        title="Peta lokasi kantor desa"
                        src="{{ $mapUrl }}"
                        style="width:100%; height:340px; border:0;"
                        loading="lazy">
                    </iframe>
                </div>
                <div class="card-footer">
                    <small class="text-muted" id="map-status">
                        Titik peta mengikuti latitude dan longitude kantor/desa.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Setting</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latitude = document.getElementById('office_latitude');
        const longitude = document.getElementById('office_longitude');
        const map = document.getElementById('office-map-preview');
        const refreshMap = document.getElementById('refresh-map');
        const useCurrentLocation = document.getElementById('use-current-location');
        const status = document.getElementById('map-status');

        function buildMapUrl(lat, lng) {
            const delta = 0.01;
            const minLng = lng - delta;
            const minLat = lat - delta;
            const maxLng = lng + delta;
            const maxLat = lat + delta;

            return 'https://www.openstreetmap.org/export/embed.html?bbox='
                + minLng + '%2C' + minLat + '%2C' + maxLng + '%2C' + maxLat
                + '&layer=mapnik&marker=' + lat + '%2C' + lng;
        }

        function updateMap() {
            const lat = parseFloat(latitude.value);
            const lng = parseFloat(longitude.value);

            if (Number.isNaN(lat) || Number.isNaN(lng)) {
                status.textContent = 'Latitude dan longitude belum valid.';
                return;
            }

            map.src = buildMapUrl(lat, lng);
            status.textContent = 'Preview peta diperbarui.';
        }

        refreshMap.addEventListener('click', updateMap);
        latitude.addEventListener('change', updateMap);
        longitude.addEventListener('change', updateMap);

        useCurrentLocation.addEventListener('click', function () {
            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung geolocation.';
                return;
            }

            status.textContent = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(function (position) {
                latitude.value = position.coords.latitude.toFixed(7);
                longitude.value = position.coords.longitude.toFixed(7);
                updateMap();
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
