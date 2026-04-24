@extends('layouts.admin')

@section('title', 'Agenda')
@section('page_title', 'Agenda')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Agenda</h3>
        <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Agenda
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.agenda.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari judul / lokasi / penyelenggara..."
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
                        <option value="event_date_asc" {{ request('sort') === 'event_date_asc' ? 'selected' : '' }}>Urutkan: Tanggal Agenda Terdekat</option>
                        <option value="event_date_desc" {{ request('sort') === 'event_date_desc' ? 'selected' : '' }}>Urutkan: Tanggal Agenda Terjauh</option>
                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Urutkan: Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Urutkan: Judul Z-A</option>
                        <option value="most_viewed" {{ request('sort') === 'most_viewed' ? 'selected' : '' }}>Urutkan: Terbanyak Dilihat</option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.agenda.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Foto</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                                {{ $item->start_date?->format('d-m-Y') ?? '-' }}
                                @if($item->end_date)
                                    <br><small>s/d {{ $item->end_date->format('d-m-Y') }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $item->start_time ?? '-' }}
                                @if($item->end_time)
                                    <br><small>s/d {{ $item->end_time }}</small>
                                @endif
                            </td>
                            <td>{{ $item->location ?? '-' }}</td>
                            <td>
                                @if($item->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $item->views }}</td>
                            <td>
                                @if($item->featured_image)
                                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" width="80">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.agenda.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.agenda.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.agenda.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus agenda ini?')">
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
                            <td colspan="9" class="text-center">Belum ada agenda.</td>
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