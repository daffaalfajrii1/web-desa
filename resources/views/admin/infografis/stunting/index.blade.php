@extends('layouts.admin')

@section('title', 'Data Stunting')
@section('page_title', 'Data Stunting')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Data Stunting</h3>
        <div>
            <a href="{{ route('admin.stunting-chart.index', request()->query()) }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar"></i> Chart
            </a>
            <a href="{{ route('admin.stunting-records.export-excel', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.stunting-records.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <input type="text" name="year" class="form-control" value="{{ request('year') }}">
                </div>
                <div class="col-md-3">
                    <label>Dusun</label>
                    <select name="hamlet_id" class="form-control">
                        <option value="">Semua Dusun</option>
                        @foreach($hamlets as $hamlet)
                            <option value="{{ $hamlet->id }}" {{ (string)request('hamlet_id') === (string)$hamlet->id ? 'selected' : '' }}>
                                {{ $hamlet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="stunting_status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ request('stunting_status') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="stunting" {{ request('stunting_status') === 'stunting' ? 'selected' : '' }}>Stunting</option>
                        <option value="berisiko" {{ request('stunting_status') === 'berisiko' ? 'selected' : '' }}>Berisiko</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.stunting-records.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Dusun</th>
                        <th>Nama Anak</th>
                        <th>JK</th>
                        <th>Usia</th>
                        <th>Tinggi</th>
                        <th>Berat</th>
                        <th>Status</th>
                        <th>Gizi</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $item->year }}</td>
                            <td>{{ $item->hamlet?->name ?? '-' }}</td>
                            <td>
                                <strong>{{ $item->child_name }}</strong><br>
                                <small class="text-muted">{{ $item->parent_name ?: '-' }}</small>
                            </td>
                            <td>{{ $item->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $item->age_in_months ? $item->age_in_months . ' bulan' : '-' }}</td>
                            <td>{{ $item->height_cm ? number_format($item->height_cm, 2, ',', '.') . ' cm' : '-' }}</td>
                            <td>{{ $item->weight_kg ? number_format($item->weight_kg, 2, ',', '.') . ' kg' : '-' }}</td>
                            <td>
                                @if($item->stunting_status === 'stunting')
                                    <span class="badge badge-danger">Stunting</span>
                                @elseif($item->stunting_status === 'berisiko')
                                    <span class="badge badge-warning">Berisiko</span>
                                @else
                                    <span class="badge badge-success">Normal</span>
                                @endif
                            </td>
                            <td>
                                @if($item->nutrition_status === 'buruk')
                                    <span class="badge badge-danger">Buruk</span>
                                @elseif($item->nutrition_status === 'kurang')
                                    <span class="badge badge-warning">Kurang</span>
                                @else
                                    <span class="badge badge-success">Baik</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.stunting-records.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.stunting-records.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">Belum ada data stunting.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    </div>
</div>
@endsection