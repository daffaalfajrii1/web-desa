@extends('layouts.admin')

@section('title', 'Pegawai / SOTK')
@section('page_title', 'Pegawai / SOTK')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Pegawai / SOTK</h3>
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Pegawai
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / jabatan / email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse($items as $item)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}"
                                     alt="{{ $item->name }}"
                                     class="img-fluid mb-3"
                                     style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center mb-3" style="height:180px;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif

                            <h5 class="mb-1">{{ $item->name }}</h5>
                            <p class="mb-1 text-muted">{{ $item->position }}</p>

                            @if($item->user)
                                <span class="badge badge-primary">Terkait Akun</span>
                            @else
                                <span class="badge badge-secondary">Tanpa Akun</span>
                            @endif

                            <div class="mt-3">
                                @if($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <a href="{{ route('admin.pegawai.show', $item->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pegawai.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pegawai.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pegawai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center mb-0">Belum ada data pegawai.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection