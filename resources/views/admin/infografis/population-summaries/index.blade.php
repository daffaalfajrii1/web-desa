@extends('layouts.admin')

@section('title', 'Ringkasan Penduduk')
@section('page_title', 'Ringkasan Penduduk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Ringkasan Penduduk</h3>
        <a href="{{ route('admin.population-summaries.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Ringkasan
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-outline card-success mb-4">
            <div class="card-header">
                <h3 class="card-title">Import Excel Ringkasan Penduduk</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.population-summaries.template') }}" class="btn btn-success btn-sm mb-3">
                    <i class="fas fa-download"></i> Unduh Template Excel
                </a>

                <form action="{{ route('admin.population-summaries.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-2">
                        <label>Upload File Excel</label>
                        <input type="file" name="file" class="form-control-file" accept=".xlsx,.xls" required>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="fas fa-upload"></i> Import Ringkasan
                    </button>
                </form>
            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}">
                </div>
                <div class="col-md-4">
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
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.population-summaries.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Dusun</th>
                    <th>KK</th>
                    <th>Laki-laki</th>
                    <th>Perempuan</th>
                    <th>Total</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>{{ $item->year }}</td>
                        <td>{{ $item->hamlet?->name }}</td>
                        <td>{{ $item->total_kk }}</td>
                        <td>{{ $item->male_count }}</td>
                        <td>{{ $item->female_count }}</td>
                        <td>{{ $item->total_population }}</td>
                        <td>
                            <a href="{{ route('admin.population-summaries.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.population-summaries.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Belum ada ringkasan penduduk.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection