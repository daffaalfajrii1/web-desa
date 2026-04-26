@extends('layouts.admin')
@section('title', 'Permohonan Informasi')
@section('page_title', 'Permohonan Informasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Permohonan Informasi</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / instansi / email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Baru</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.ppid-request.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Instansi</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->institution ?? '-' }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            @if($item->status === 'new')
                                <span class="badge badge-primary">Baru</span>
                            @elseif($item->status === 'processed')
                                <span class="badge badge-warning">Diproses</span>
                            @elseif($item->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at?->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.ppid-request.show', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.ppid-request.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.ppid-request.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus permohonan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Belum ada permohonan informasi.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection