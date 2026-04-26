@extends('layouts.admin')

@section('title', 'FAQ / Section PPID')
@section('page_title', 'FAQ / Section PPID')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">FAQ / Section PPID</h3>
        <a href="{{ route('admin.ppid-section.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Section
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.ppid-section.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Jumlah Dokumen</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ str_replace('_', ' ', ucfirst($item->type)) }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $item->documents_count }}</td>
                            <td>
    <a href="{{ route('admin.ppid-document.create', ['section_id' => $item->id]) }}"
       class="btn btn-primary btn-sm"
       title="Tambah Dokumen">
        <i class="fas fa-plus-circle"></i>
    </a>

    <a href="{{ route('admin.ppid-section.show', $item->id) }}" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('admin.ppid-section.edit', $item->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('admin.ppid-section.destroy', $item->id) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Yakin hapus section ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada section PPID.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    </div>
</div>
@endsection