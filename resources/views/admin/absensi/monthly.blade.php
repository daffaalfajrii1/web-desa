@extends('layouts.admin')

@section('title', 'Rekap Bulanan Absensi')
@section('page_title', 'Rekap Bulanan Absensi')

@section('content')
@php
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Rekap {{ $months[$month] ?? $month }} {{ $year }}</h3>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.absensi.export.monthly', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Bulanan
            </a>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-list"></i> Detail
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <label>Bulan</label>
                    <select name="month" class="form-control">
                        @foreach($months as $number => $name)
                            <option value="{{ $number }}" {{ (int) $month === (int) $number ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Tahun</label>
                    <input type="number" name="year" class="form-control" value="{{ $year }}">
                </div>
                <div class="col-md-3">
                    <label>Pegawai</label>
                    <select name="employee_id" class="form-control">
                        <option value="">Semua Pegawai</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (string) request('employee_id') === (string) $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Cari Nama</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.absensi.monthly') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Hadir</th>
                        <th>Telat</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Libur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $row['employee']->name }}</strong><br>
                                <small class="text-muted">{{ $row['employee']->position }}</small>
                            </td>
                            <td><span class="badge badge-success">{{ $row['hadir'] }}</span></td>
                            <td><span class="badge badge-warning">{{ $row['telat'] }}</span></td>
                            <td><span class="badge badge-primary">{{ $row['izin'] }}</span></td>
                            <td><span class="badge badge-info">{{ $row['sakit'] }}</span></td>
                            <td><span class="badge badge-danger">{{ $row['alpa'] }}</span></td>
                            <td><span class="badge badge-secondary">{{ $row['libur'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
