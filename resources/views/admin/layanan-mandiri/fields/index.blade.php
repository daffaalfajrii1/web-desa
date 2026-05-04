@extends('layouts.admin')

@section('title', 'Field Layanan')
@section('page_title', 'Field Layanan')

@section('content')
<style>
    .service-heading {
        border-radius: 14px;
        background: linear-gradient(135deg, #0d6efd, #17a2b8);
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.08);
    }

    .field-table td {
        vertical-align: top;
    }

    .field-name {
        font-family: Consolas, Monaco, monospace;
        color: #2563eb;
        font-weight: 700;
    }
</style>

<div class="service-heading mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <div class="mb-1">{{ $service->service_code }}</div>
            <h3 class="mb-1">{{ $service->service_name }}</h3>
            <div>{{ $service->description ?: 'Belum ada deskripsi layanan.' }}</div>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-light btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Layanan
            </a>
            <a href="{{ route('admin.layanan-mandiri.submissions.index', $service->id) }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-inbox"></i> Pengajuan
            </a>
            <a href="{{ route('admin.layanan-mandiri.fields.create', $service->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-plus"></i> Tambah Field
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Builder Field</h3>
        <span class="badge badge-info p-2">{{ $items->count() }} field</span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover field-table">
                <thead>
                    <tr>
                        <th style="width:70px">Urutan</th>
                        <th>Field</th>
                        <th>Tipe</th>
                        <th>Wajib</th>
                        <th>Opsi</th>
                        <th style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <strong>{{ $item->field_label }}</strong><br>
                                <span class="field-name">{{ $item->field_name }}</span>
                                @if($item->placeholder)
                                    <br><small class="text-muted">Placeholder: {{ $item->placeholder }}</small>
                                @endif
                                @if($item->help_text)
                                    <br><small class="text-muted">{{ $item->help_text }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $item->type_badge_class }}">{{ $item->type_label }}</span>
                            </td>
                            <td>
                                @if($item->is_required)
                                    <span class="badge badge-danger">Required</span>
                                @else
                                    <span class="badge badge-secondary">Optional</span>
                                @endif
                            </td>
                            <td>
                                @if($item->options)
                                    @foreach($item->options as $option)
                                        <span class="badge badge-light border mb-1">{{ $option }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.layanan-mandiri.fields.edit', [$service->id, $item->id]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.layanan-mandiri.fields.destroy', [$service->id, $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus field ini?')">
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
                            <td colspan="6" class="text-center">Belum ada field untuk layanan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
