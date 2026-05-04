@extends('layouts.admin')

@section('title', 'Tambah Absensi Manual')
@section('page_title', 'Tambah Absensi Manual')

@section('content')
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
        <h3 class="card-title">Input Manual Absensi</h3>
    </div>

    <form method="POST" action="{{ route('admin.absensi.store') }}">
        @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pegawai <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                            <option value="">Pilih pegawai</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ (string) old('employee_id', request('employee_id')) === (string) $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->position }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" class="form-control @error('attendance_date') is-invalid @enderror"
                               value="{{ old('attendance_date', request('date', now()->toDateString())) }}" required>
                        @error('attendance_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}" {{ old('status', request('status', 'izin')) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jam Masuk</label>
                        <input type="time" name="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror"
                               value="{{ old('check_in_time') }}">
                        @error('check_in_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jam Pulang</label>
                        <input type="time" name="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror"
                               value="{{ old('check_out_time') }}">
                        @error('check_out_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" name="note" class="form-control @error('note') is-invalid @enderror"
                               value="{{ old('note') }}" maxlength="255">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
