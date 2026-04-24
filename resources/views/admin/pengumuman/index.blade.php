@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page_title', 'Pengumuman')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Pengumuman</h3>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Pengumuman
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.pengumuman.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari judul / slug / ringkasan..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <select name="sort" class="form-control">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                        <option value="most_viewed" {{ request('sort') === 'most_viewed' ? 'selected' : '' }}>Urutkan: Terbanyak Dilihat</option>
                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Urutkan: Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Urutkan: Judul Z-A</option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Views</th>
                        <th>Featured Image</th>
                        <th>Lampiran</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
    <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
    <td>{{ $item->title }}</td>
    <td>
        @if($item->status === 'published')
            <span class="badge badge-success">Published</span>
        @else
            <span class="badge badge-secondary">Draft</span>
        @endif
    </td>
    <td>{{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
    <td>{{ $item->views }}</td>
    <td>
        @if($item->featured_image)
            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" width="80">
        @else
            -
        @endif
    </td>
    <td>
        @if($item->attachment)
            <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        @else
            -
        @endif
    </td>
    <td>
        <a href="{{ route('admin.pengumuman.show', $item->id) }}" class="btn btn-info btn-sm">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pengumuman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection