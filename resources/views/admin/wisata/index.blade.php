@extends('layouts.admin')

@section('title', 'Wisata')
@section('page_title', 'Wisata')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Wisata</h3>
        <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Wisata
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari wisata / alamat / kontak..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Wisata</th>
                    <th>Hari Buka</th>
                    <th>Jam Operasional</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Galeri</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>
                            @if($item->main_image)
                                <img src="{{ asset('storage/' . $item->main_image) }}" width="70" alt="{{ $item->title }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->open_days ?? '-' }}</td>
                        <td>
                            {{ $item->open_time ?? '-' }}
                            @if($item->close_time)
                                - {{ $item->close_time }}
                            @endif
                        </td>
                        <td>{{ $item->contact_phone ?? '-' }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif

                            @if($item->is_featured)
                                <span class="badge badge-primary">Unggulan</span>
                            @endif
                        </td>
                        <td>{{ $item->images->count() }}</td>
                        <td>
                            <a href="{{ route('admin.wisata.show', ['tourism' => $item->id]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.wisata.edit', ['tourism' => $item->id]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.wisata.destroy', ['tourism' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus wisata ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">Belum ada data wisata.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection