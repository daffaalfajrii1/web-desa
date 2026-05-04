@extends('layouts.admin')

@section('title', 'Rekap Absensi')
@section('page_title', 'Rekap Absensi')

@section('content')
@php
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $cards = [
        ['key' => 'hadir', 'label' => 'Hadir Hari Ini', 'class' => 'bg-success', 'icon' => 'fa-user-check'],
        ['key' => 'telat', 'label' => 'Telat Hari Ini', 'class' => 'bg-warning', 'icon' => 'fa-clock'],
        ['key' => 'izin', 'label' => 'Izin Hari Ini', 'class' => 'bg-primary', 'icon' => 'fa-envelope-open'],
        ['key' => 'sakit', 'label' => 'Sakit Hari Ini', 'class' => 'bg-info', 'icon' => 'fa-notes-medical'],
        ['key' => 'alpa', 'label' => 'Alpa Hari Ini', 'class' => 'bg-danger', 'icon' => 'fa-user-times'],
        ['key' => 'libur', 'label' => 'Libur Hari Ini', 'class' => 'bg-secondary', 'icon' => 'fa-calendar-day'],
    ];
@endphp

<style>
    .attendance-grid-wrap {
        max-height: 70vh;
        overflow: auto;
        border: 1px solid #e5e7eb;
    }

    .attendance-grid {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 1280px;
    }

    .attendance-grid th,
    .attendance-grid td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .attendance-grid .sticky-col {
        position: sticky;
        left: 0;
        z-index: 3;
        background: #fff;
        min-width: 240px;
        max-width: 280px;
    }

    .attendance-grid thead .sticky-col {
        z-index: 5;
        background: #f8f9fa;
    }

    .attendance-grid .date-col {
        min-width: 54px;
        text-align: center;
    }

    .attendance-grid .summary-col {
        min-width: 56px;
        text-align: center;
        background: #f8f9fa;
    }

    .attendance-grid .holiday-col {
        background: #f1f5f9;
    }

    .attendance-grid .cell-link,
    .attendance-grid .cell-empty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 28px;
        border-radius: 6px;
        font-weight: 700;
    }

    .attendance-grid .cell-empty {
        color: #9ca3af;
        border: 1px dashed #d1d5db;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @foreach($cards as $card)
        <div class="col-md-4 col-xl-2">
            <div class="small-box {{ $card['class'] }}">
                <div class="inner">
                    <h3>{{ $summary[$card['key']] ?? 0 }}</h3>
                    <p>{{ $card['label'] }}</p>
                </div>
                <div class="icon">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-md-4 col-xl-2">
        <div class="small-box bg-light">
            <div class="inner">
                <h3>{{ $summary['total_pegawai'] ?? 0 }}</h3>
                <p>Total Pegawai Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Rekap Bulanan {{ $months[$month] ?? $month }} {{ $year }}</h3>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.absensi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Manual
            </a>
            <a href="{{ route('admin.absensi.holidays.index', ['month' => $month, 'year' => $year]) }}" class="btn btn-info btn-sm">
                <i class="fas fa-calendar-day"></i> Hari Libur
            </a>
            <a href="{{ route('admin.absensi.yearly', request()->query()) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-calendar"></i> Rekap Tahunan
            </a>
            <a href="{{ route('admin.absensi.export.monthly', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Bulanan
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
                <div class="col-md-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Cari Nama</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama / jabatan">
                </div>
                <div class="col-md-4 mt-3 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="mb-3">
            @foreach($statusLabels as $status => $label)
                <span class="badge {{ $statusBadgeClasses[$status] ?? 'badge-secondary' }} mr-1">
                    {{ $statusShortLabels[$status] ?? strtoupper(substr($status, 0, 1)) }}
                </span>
                <span class="text-muted mr-3">{{ $label }}</span>
            @endforeach
        </div>

        <div class="attendance-grid-wrap">
            <table class="table table-bordered table-sm attendance-grid mb-0">
                <thead>
                    <tr>
                        <th class="sticky-col">Pegawai</th>
                        @foreach($days as $day)
                            <th class="date-col {{ $day['is_holiday'] ? 'holiday-col' : '' }}"
                                title="{{ $day['holiday_name'] ?: '' }}">
                                <div>{{ $day['day'] }}</div>
                                <small class="text-muted">{{ $day['day_name'] }}</small>
                            </th>
                        @endforeach
                        <th class="summary-col">H</th>
                        <th class="summary-col">T</th>
                        <th class="summary-col">I</th>
                        <th class="summary-col">S</th>
                        <th class="summary-col">A</th>
                        <th class="summary-col">L</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="sticky-col">
                                <strong>{{ $row['employee']->name }}</strong><br>
                                <small class="text-muted">{{ $row['employee']->position }}</small>
                            </td>
                            @foreach($row['cells'] as $cell)
                                <td class="date-col {{ $cell['status'] === 'libur' ? 'holiday-col' : '' }}">
                                    @if($cell['attendance'])
                                        <a href="{{ route('admin.absensi.edit', $cell['attendance']->id) }}"
                                           class="badge {{ $cell['badge_class'] }} cell-link"
                                           title="{{ $statusLabels[$cell['status']] ?? '-' }}{{ $cell['holiday_name'] ? ' - ' . $cell['holiday_name'] : '' }}">
                                            {{ $cell['label'] }}
                                        </a>
                                    @elseif($cell['status'] === 'libur')
                                        <span class="badge {{ $cell['badge_class'] }} cell-link"
                                              title="{{ $cell['holiday_name'] ?: 'Libur' }}">
                                            {{ $cell['label'] }}
                                        </span>
                                    @else
                                        <a href="{{ route('admin.absensi.create', ['employee_id' => $row['employee']->id, 'date' => $cell['date']]) }}"
                                           class="cell-empty"
                                           title="Tambah absensi manual">
                                            -
                                        </a>
                                    @endif
                                </td>
                            @endforeach
                            <td class="summary-col">{{ $row['counts']['hadir'] }}</td>
                            <td class="summary-col">{{ $row['counts']['telat'] }}</td>
                            <td class="summary-col">{{ $row['counts']['izin'] }}</td>
                            <td class="summary-col">{{ $row['counts']['sakit'] }}</td>
                            <td class="summary-col">{{ $row['counts']['alpa'] }}</td>
                            <td class="summary-col">{{ $row['counts']['libur'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($days) + 7 }}" class="text-center text-muted">
                                Belum ada pegawai aktif dengan PIN absensi pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
