@extends('layouts.admin')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Identitas Desa</h3>
    </div>

    <form action="{{ route('admin.settings.desa.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Desa</label>
                        <input type="text" name="village_name" class="form-control" value="{{ old('village_name', $setting->village_name) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="district_name" class="form-control" value="{{ old('district_name', $setting->district_name) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" name="regency_name" class="form-control" value="{{ old('regency_name', $setting->regency_name) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="province_name" class="form-control" value="{{ old('province_name', $setting->province_name) }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $setting->address) }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $setting->postal_code) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $setting->phone) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $setting->whatsapp) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Kepala Desa</label>
                        <input type="text" name="head_name" class="form-control" value="{{ old('head_name', $setting->head_name) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jabatan Kepala Desa</label>
                        <input type="text" name="head_position" class="form-control" value="{{ old('head_position', $setting->head_position) }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Sambutan</label>
                        <textarea name="welcome_message" class="form-control" rows="4">{{ old('welcome_message', $setting->welcome_message) }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Visi</label>
                        <textarea name="vision" class="form-control" rows="4">{{ old('vision', $setting->vision) }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Misi</label>
                        <textarea name="mission" class="form-control" rows="4">{{ old('mission', $setting->mission) }}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Embed Peta</label>
                        <textarea name="map_embed" class="form-control" rows="4">{{ old('map_embed', $setting->map_embed) }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $setting->latitude) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $setting->longitude) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tema Aktif</label>
                        <select name="active_theme" class="form-control">
                            <option value="default" {{ old('active_theme', $setting->active_theme) == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="modern" {{ old('active_theme', $setting->active_theme) == 'modern' ? 'selected' : '' }}>Modern</option>
                            <option value="classic" {{ old('active_theme', $setting->active_theme) == 'classic' ? 'selected' : '' }}>Classic</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection