@extends('layouts.admin')
@section('title', ' Berita')
@section('page_title', ' Berita')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Berita</h3>
        <div>
            <a href="{{ route('admin.kategori-berita.index') }}" class="btn btn-info btn-sm">
                <i class="fas fa-tags"></i> Kategori Berita
            </a>
            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Berita
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.berita.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3 mb-2">
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
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
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
                        <th>Slug</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Featured Image</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration + ($posts->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category?->name ?? 'Tak Berkategori' }}</td>
                            <td>{{ $post->slug }}</td>
                            <td>{{ $post->author?->name ?? '-' }}</td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $post->views }}</td>
                            <td>
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" width="80">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.berita.show', $post->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.berita.edit', $post->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.berita.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus berita ini?')">
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
                            <td colspan="9" class="text-center">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection