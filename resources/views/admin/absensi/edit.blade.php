@extends('layouts.admin')

@section('title', 'Koreksi Absensi')
@section('page_title', 'Koreksi Absensi')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit / Koreksi Absensi</h3>
            </div>

            <form method="POST" action="{{ route('admin.absensi.update', $attendance->id) }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="alert alert-light border">
                        <strong>{{ $attendance->employee?->name ?? '-' }}</strong><br>
                        <span class="text-muted">{{ $attendance->employee?->position ?? '-' }}</span>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" class="form-control @error('attendance_date') is-invalid @enderror"
                                       value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <input type="time" name="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror"
                                       value="{{ old('check_in_time', $attendance->check_in_time ? substr((string) $attendance->check_in_time, 0, 5) : '') }}">
                                @error('check_in_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jam Pulang</label>
                                <input type="time" name="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror"
                                       value="{{ old('check_out_time', $attendance->check_out_time ? substr((string) $attendance->check_out_time, 0, 5) : '') }}">
                                @error('check_out_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    @foreach($statusLabels as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $attendance->status) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Catatan</label>
                                <input type="text" name="note" class="form-control @error('note') is-invalid @enderror"
                                       value="{{ old('note', $attendance->note) }}" maxlength="255">
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Data Lokasi</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">Latitude</dt>
                    <dd class="col-7">{{ $attendance->latitude ?? '-' }}</dd>
                    <dt class="col-5">Longitude</dt>
                    <dd class="col-7">{{ $attendance->longitude ?? '-' }}</dd>
                    <dt class="col-5">Jarak</dt>
                    <dd class="col-7">{{ $attendance->distance_meter !== null ? number_format($attendance->distance_meter, 2, ',', '.') . ' m' : '-' }}</dd>
                    <dt class="col-5">Sumber</dt>
                    <dd class="col-7">{{ $attendance->source ?: '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
