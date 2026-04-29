@extends('layouts.admin')

@section('title', 'Data Dusun')
@section('page_title', 'Data Dusun')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Data Dusun</h3>
        <a href="{{ route('admin.hamlets.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Dusun
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dusun</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.hamlets.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.hamlets.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus dusun ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Belum ada data dusun.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </div>
</div>
@endsection