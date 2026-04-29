@extends('layouts.admin')

@section('title', 'Statistik Penduduk')
@section('page_title', 'Statistik Penduduk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Statistik Penduduk</h3>
    <div>
        <a href="{{ route('admin.population-stats.chart-view') }}" class="btn btn-success btn-sm">
            <i class="fas fa-chart-bar"></i> Lihat Chart
        </a>
        <a href="{{ route('admin.population-stats.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Statistik
        </a>
    </div>
</div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-outline card-success mb-4">
            <div class="card-header">
                <h3 class="card-title">Import Excel Statistik Penduduk</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.population-stats.template') }}" class="btn btn-success btn-sm mb-3">
                    <i class="fas fa-download"></i> Unduh Template Excel
                </a>

                <form action="{{ route('admin.population-stats.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-2">
                        <label>Upload File Excel</label>
                        <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls" required>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="fas fa-upload"></i> Import Statistik
                    </button>
                </form>
            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <input type="text" name="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}">
                </div>
                <div class="col-md-3">
                    <select name="hamlet_id" class="form-control">
                        <option value="">Semua Dusun</option>
                        @foreach($hamlets as $hamlet)
                            <option value="{{ $hamlet->id }}" {{ (string) request('hamlet_id') === (string) $hamlet->id ? 'selected' : '' }}>
                                {{ $hamlet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.population-stats.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Dusun</th>
                    <th>Kategori</th>
                    <th>Item</th>
                    <th>Nilai</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>{{ $item->year }}</td>
                        <td>{{ $item->hamlet?->name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $item->category)) }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->value }}</td>
                        <td>
                            <a href="{{ route('admin.population-stats.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.population-stats.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus statistik ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada statistik penduduk.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection