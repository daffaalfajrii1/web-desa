@extends('layouts.admin')
@section('title', 'Dokumen PPID')
@section('page_title', 'Dokumen PPID')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Dokumen PPID</h3>
        <a href="{{ route('admin.ppid-document.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Dokumen
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul dokumen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="berkala" {{ request('type') === 'berkala' ? 'selected' : '' }}>Informasi Berkala</option>
                        <option value="serta_merta" {{ request('type') === 'serta_merta' ? 'selected' : '' }}>Informasi Serta Merta</option>
                        <option value="setiap_saat" {{ request('type') === 'setiap_saat' ? 'selected' : '' }}>Informasi Setiap Saat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.ppid-document.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Dokumen</th>
                    <th>Section</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>PDF</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->section?->title ?? '-' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($item->section?->type ?? '-')) }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.ppid-document.show', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.ppid-document.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.ppid-document.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada dokumen PPID.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection