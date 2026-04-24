@extends('layouts.admin')
@section('title', ' Profil Desa')
@section('page_title', ' Profil Desa')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Halaman Profil Desa</h3>
        <a href="{{ route('admin.profil-desa.halaman.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Halaman
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Judul</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Featured Image</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $loop->iteration + ($pages->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>
                                @if($page->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($page->featured_image)
                                    <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" width="80">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.profil-desa.halaman.show', $page->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.profil-desa.halaman.edit', $page->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.profil-desa.halaman.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus halaman ini?')">
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
                            <td colspan="6" class="text-center">Belum ada halaman profil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection