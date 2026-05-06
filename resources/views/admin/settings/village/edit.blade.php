@extends('layouts.admin')

@section('title', 'Identitas Desa')
@section('page_title', 'Identitas Desa')

@section('content')
@php
    $selectedHeadId = old('village_head_employee_id', $setting->village_head_employee_id);
    $manualHeadName = old('village_head_name_manual', $setting->village_head_name_manual ?: $setting->head_name);
    $logoUrl = $setting->logo_url;
    $mapEmbed = old('map_embed', $setting->map_embed);
    $latitude = old('latitude', $setting->latitude);
    $longitude = old('longitude', $setting->longitude);
    $coordinateMapUrl = null;

    if (is_numeric($latitude) && is_numeric($longitude)) {
        $mapDelta = 0.01;
        $coordinateMapUrl = 'https://www.openstreetmap.org/export/embed.html?bbox='
            . ((float) $longitude - $mapDelta) . '%2C'
            . ((float) $latitude - $mapDelta) . '%2C'
            . ((float) $longitude + $mapDelta) . '%2C'
            . ((float) $latitude + $mapDelta)
            . '&layer=mapnik&marker=' . $latitude . '%2C' . $longitude;
    }
@endphp

@once
    <style>
        .identity-section .card-header {
            background: #fff;
        }

        .identity-section .card-title {
            font-weight: 700;
            color: #1f2937;
        }

        .logo-preview {
            width: 148px;
            height: 148px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .logo-placeholder {
            color: #94a3b8;
            font-size: 42px;
        }

        .map-preview {
            min-height: 320px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
        }

        .map-preview iframe {
            display: block;
            width: 100%;
            min-height: 320px;
            border: 0;
        }

        .map-preview-empty {
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            text-align: center;
            padding: 24px;
        }
    </style>
@endonce

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

<form action="{{ route('admin.settings.desa.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Informasi Dasar</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Desa</label>
                        <input type="text" name="village_name" class="form-control @error('village_name') is-invalid @enderror" value="{{ old('village_name', $setting->village_name) }}">
                        @error('village_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="district_name" class="form-control @error('district_name') is-invalid @enderror" value="{{ old('district_name', $setting->district_name) }}">
                        @error('district_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" name="regency_name" class="form-control @error('regency_name') is-invalid @enderror" value="{{ old('regency_name', $setting->regency_name) }}">
                        @error('regency_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="province_name" class="form-control @error('province_name') is-invalid @enderror" value="{{ old('province_name', $setting->province_name) }}">
                        @error('province_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $setting->address) }}</textarea>
                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $setting->postal_code) }}">
                        @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Kontak Desa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $setting->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $setting->whatsapp) }}">
                        @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Pemerintahan Desa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilih Kepala Desa dari Pegawai</label>
                        <select name="village_head_employee_id" id="village_head_employee_id" class="form-control @error('village_head_employee_id') is-invalid @enderror">
                            <option value="">- Gunakan nama manual -</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" data-name="{{ $employee->name }}" {{ (string) $selectedHeadId === (string) $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}{{ $employee->position ? ' - ' . $employee->position : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('village_head_employee_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <small class="text-muted">Jika pegawai dipilih, nama kepala desa mengikuti data pegawai.</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Kepala Desa Manual</label>
                        <input type="text" name="village_head_name_manual" id="village_head_name_manual" class="form-control @error('village_head_name_manual') is-invalid @enderror" value="{{ $manualHeadName }}">
                        @error('village_head_name_manual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Dipakai saat pilihan pegawai kosong.</small>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="alert alert-light border mb-3">
                        Kepala desa aktif: <strong id="village_head_preview">{{ $setting->village_head_name ?: ($manualHeadName ?: '-') }}</strong>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Sambutan</label>
                        <textarea name="welcome_message" class="form-control @error('welcome_message') is-invalid @enderror" rows="4">{{ old('welcome_message', $setting->welcome_message) }}</textarea>
                        @error('welcome_message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Visi</label>
                        <textarea name="vision" class="form-control @error('vision') is-invalid @enderror" rows="5">{{ old('vision', $setting->vision) }}</textarea>
                        @error('vision')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Misi</label>
                        <textarea name="mission" class="form-control @error('mission') is-invalid @enderror" rows="5">{{ old('mission', $setting->mission) }}</textarea>
                        @error('mission')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Marquee / Teks Berjalan</h3>
        </div>
        <div class="card-body">
            <div class="form-group mb-0">
                <label>Isi Marquee Beranda</label>
                <textarea
                    name="marquee_text"
                    class="form-control @error('marquee_text') is-invalid @enderror"
                    rows="4"
                    placeholder="Contoh: Pelayanan kantor desa buka pukul 08.00 - 15.00"
                >{{ old('marquee_text', $setting->marquee_text) }}</textarea>
                @error('marquee_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <small class="text-muted">Pisahkan tiap informasi dengan baris baru. Jika kosong, sistem memakai marquee otomatis.</small>
            </div>
        </div>
    </div>

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Branding Desa</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-start">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Logo Desa</label>
                        <input type="file" name="logo_desa" id="logo_desa" class="form-control-file @error('logo_desa') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        @error('logo_desa')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-2">Format JPG, PNG, atau WebP. Maksimal 2 MB.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="d-block">Preview Logo</label>
                    <div class="logo-preview" id="logo_preview">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo Desa">
                        @else
                            <span class="logo-placeholder"><i class="fas fa-landmark"></i></span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tema Aktif</label>
                        <select name="theme_active" class="form-control @error('theme_active') is-invalid @enderror">
                            @php($selectedTheme = old('theme_active', $setting->theme_active ?: $setting->active_theme ?: 'default'))
                            <option value="default" {{ $selectedTheme === 'default' ? 'selected' : '' }}>Default</option>
                            <option value="blue" {{ $selectedTheme === 'blue' ? 'selected' : '' }}>Blue</option>
                            <option value="earth" {{ $selectedTheme === 'earth' ? 'selected' : '' }}>Earth</option>
                        </select>
                        @error('theme_active')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <a href="{{ route('admin.settings.desa-banners.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-images mr-1"></i> Kelola Banner Public
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card identity-section">
        <div class="card-header">
            <h3 class="card-title mb-0">Lokasi Desa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Embed Peta</label>
                        <textarea name="map_embed" id="map_embed" class="form-control @error('map_embed') is-invalid @enderror" rows="4" placeholder="<iframe ...></iframe>">{{ $mapEmbed }}</textarea>
                        @error('map_embed')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ $latitude }}">
                        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ $longitude }}">
                        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="refresh_coordinate_map" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-sync-alt mr-1"></i> Preview Koordinat
                    </button>
                </div>

                <div class="col-md-12">
                    <label>Preview Map</label>
                    <div class="map-preview" id="map_preview">
                        @if($mapEmbed)
                            {!! $mapEmbed !!}
                        @elseif($coordinateMapUrl)
                            <iframe src="{{ $coordinateMapUrl }}" title="Preview lokasi desa" loading="lazy"></iframe>
                        @else
                            <div class="map-preview-empty">
                                Isi embed peta atau koordinat latitude dan longitude untuk melihat preview.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Identitas Desa
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const headSelect = document.getElementById('village_head_employee_id');
        const manualHead = document.getElementById('village_head_name_manual');
        const headPreview = document.getElementById('village_head_preview');
        const logoInput = document.getElementById('logo_desa');
        const logoPreview = document.getElementById('logo_preview');
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        const mapEmbed = document.getElementById('map_embed');
        const mapPreview = document.getElementById('map_preview');
        const refreshCoordinateMap = document.getElementById('refresh_coordinate_map');

        function updateHeadPreview() {
            const selected = headSelect.options[headSelect.selectedIndex];
            const selectedName = selected ? selected.dataset.name : '';
            headPreview.textContent = selectedName || manualHead.value || '-';
        }

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

        function updateCoordinateMapPreview() {
            if (mapEmbed.value.trim() !== '') {
                mapPreview.innerHTML = mapEmbed.value;
                return;
            }

            const lat = parseFloat(latitude.value);
            const lng = parseFloat(longitude.value);

            if (Number.isNaN(lat) || Number.isNaN(lng)) {
                mapPreview.innerHTML = '<div class="map-preview-empty">Isi embed peta atau koordinat latitude dan longitude untuk melihat preview.</div>';
                return;
            }

            mapPreview.innerHTML = '<iframe src="' + buildMapUrl(lat, lng) + '" title="Preview lokasi desa" loading="lazy"></iframe>';
        }

        headSelect.addEventListener('change', updateHeadPreview);
        manualHead.addEventListener('input', updateHeadPreview);
        refreshCoordinateMap.addEventListener('click', updateCoordinateMapPreview);
        mapEmbed.addEventListener('change', updateCoordinateMapPreview);

        logoInput.addEventListener('change', function () {
            const file = logoInput.files && logoInput.files[0];

            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            const imageUrl = URL.createObjectURL(file);
            logoPreview.innerHTML = '<img src="' + imageUrl + '" alt="Preview logo desa">';
        });

        updateHeadPreview();
    });
</script>
@endpush
