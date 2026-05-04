@extends('layouts.admin')

@section('title', 'Libur Nasional Absensi')
@section('page_title', 'Libur Nasional Absensi')

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
        <h3 class="card-title mb-0">Daftar Libur Nasional & Akhir Pekan {{ $months[$month] ?? $month }} {{ $year }}</h3>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.absensi.holidays.index', ['month' => $month, 'year' => $year, 'refresh' => 1]) }}" class="btn btn-outline-primary btn-sm mr-1">
                <i class="fas fa-sync-alt"></i> Refresh API
            </a>
            <a href="{{ route('admin.absensi.settings.edit') }}" class="btn btn-info btn-sm">
                <i class="fas fa-cog"></i> Setting Absensi
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($apiRefreshed)
            <div class="alert alert-success">Cache libur nasional tahun {{ $year }} sudah diperbarui.</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Bulan</label>
                    <select name="month" class="form-control">
                        @foreach($months as $number => $name)
                            <option value="{{ $number }}" {{ (int) $month === (int) $number ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="number" name="year" class="form-control" value="{{ $year }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.absensi.holidays.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="alert alert-light border">
            <span class="badge {{ $setting->use_holiday_api ? 'badge-success' : 'badge-secondary' }}">
                API Libur {{ $setting->use_holiday_api ? 'Aktif' : 'Nonaktif' }}
            </span>
            <span class="badge {{ $setting->disable_saturday_attendance ? 'badge-success' : 'badge-secondary' }} ml-1">
                Sabtu {{ $setting->disable_saturday_attendance ? 'Libur' : 'Aktif' }}
            </span>
            <span class="badge {{ $setting->disable_sunday_attendance ? 'badge-success' : 'badge-secondary' }} ml-1">
                Minggu {{ $setting->disable_sunday_attendance ? 'Libur' : 'Aktif' }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Nama Libur</th>
                        <th>Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                        @php($date = \Carbon\Carbon::parse($holiday['date'])->locale('id'))
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $date->format('d-m-Y') }}</td>
                            <td>{{ $date->translatedFormat('l') }}</td>
                            <td>{{ $holiday['name'] }}</td>
                            <td>{{ $holiday['source'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data libur nasional atau akhir pekan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
