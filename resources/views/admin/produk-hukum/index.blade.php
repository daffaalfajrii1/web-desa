@extends('layouts.admin')
@section('title', 'Tambah Produk Hukum')
@section('page_title', 'Tambah Produk Hukum')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Produk Hukum</h3>
        <div>
            <a href="{{ route('admin.kategori-produk-hukum.index') }}" class="btn btn-info btn-sm">
                <i class="fas fa-tags"></i> Kategori Produk Hukum
            </a>
            <a href="{{ route('admin.produk-hukum.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Produk Hukum
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.produk-hukum.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari judul / nomor / jenis..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <select name="category_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="uncategorized" {{ request('category_id') === 'uncategorized' ? 'selected' : '' }}>Tak Berkategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <select name="sort" class="form-control">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                        <option value="most_viewed" {{ request('sort') === 'most_viewed' ? 'selected' : '' }}>Urutkan: Terbanyak Dilihat</option>
                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Urutkan: Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Urutkan: Judul Z-A</option>
                        <option value="category_asc" {{ request('sort') === 'category_asc' ? 'selected' : '' }}>Urutkan: Kategori A-Z</option>
                        <option value="category_desc" {{ request('sort') === 'category_desc' ? 'selected' : '' }}>Urutkan: Kategori Z-A</option>
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.produk-hukum.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Nomor</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>File</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->category?->name ?? 'Tak Berkategori' }}</td>
                            <td>{{ $item->number ?? '-' }}</td>
                            <td>{{ $item->document_type ?? '-' }}</td>
                            <td>{{ $item->published_date?->format('d-m-Y') ?? '-' }}</td>
                            <td>
                                @if($item->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.produk-hukum.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.produk-hukum.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.produk-hukum.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk hukum ini?')">
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
                            <td colspan="9" class="text-center">Belum ada produk hukum.</td>
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