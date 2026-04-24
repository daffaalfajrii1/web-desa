@extends('layouts.admin')
@section('title', ' Menu Profil Desa')
@section('page_title', ' Menu Profil Desa')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Menu Profil Desa</h3>
        <a href="{{ route('admin.profil-desa.menu.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Menu
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
                        <th>Judul Menu</th>
                        <th>Slug</th>
                        <th>Halaman Terkait</th>
                        <th width="100">Urutan</th>
                        <th width="100">Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $loop->iteration + ($menus->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $menu->title }}</td>
                            <td>{{ $menu->slug }}</td>
                            <td>{{ $menu->page?->title ?? '-' }}</td>
                            <td>{{ $menu->sort_order }}</td>
                            <td>
                                @if($menu->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.profil-desa.menu.edit', $menu->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.profil-desa.menu.destroy', $menu->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus menu ini?')">
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
                            <td colspan="7" class="text-center">Belum ada menu profil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $menus->links() }}
        </div>
    </div>
</div>
@endsection