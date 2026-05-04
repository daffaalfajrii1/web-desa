@extends('layouts.admin')

@section('title', 'Pengajuan Layanan')
@section('page_title', 'Pengajuan Layanan')

@section('content')
<style>
    .service-heading {
        border-radius: 14px;
        background: linear-gradient(135deg, #0d6efd, #17a2b8);
        color: #fff;
        padding: 1.2rem;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.08);
    }

    .submission-stat {
        border-radius: 14px;
        padding: 1rem;
        color: #fff;
        min-height: 102px;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.08);
        position: relative;
        overflow: hidden;
    }

    .submission-stat .label {
        font-size: .94rem;
        font-weight: 700;
        opacity: .94;
    }

    .submission-stat .value {
        font-size: 1.65rem;
        font-weight: 800;
        line-height: 1;
        margin-top: .5rem;
    }

    .submission-table td {
        vertical-align: top;
    }

    .registration-number {
        color: #2563eb;
        font-weight: 800;
        letter-spacing: .2px;
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
            <a href="{{ route('admin.layanan-mandiri.fields.index', $service->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-list"></i> Builder Field
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="submission-stat bg-primary">
            <div class="label">Masuk</div>
            <div class="value">{{ number_format($summary['masuk'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="submission-stat bg-warning" style="color:#212529">
            <div class="label">Diproses</div>
            <div class="value">{{ number_format($summary['diproses'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="submission-stat bg-success">
            <div class="label">Selesai</div>
            <div class="value">{{ number_format($summary['selesai'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="submission-stat bg-danger">
            <div class="label">Ditolak</div>
            <div class="value">{{ number_format($summary['ditolak'] ?? 0) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Daftar Pengajuan {{ $service->service_name }}</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Registrasi / nama / NIK / telepon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.layanan-mandiri.submissions.index', $service->id) }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover submission-table">
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Registrasi</th>
                        <th>Pemohon</th>
                        <th>Ringkasan Isian</th>
                        <th>Status</th>
                        <th>Hasil</th>
                        <th>Dikirim</th>
                        <th style="width:130px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>
                            <td>
                                <div class="registration-number">{{ $item->registration_number }}</div>
                            </td>
                            <td>
                                <strong>{{ $item->display_applicant_name }}</strong><br>
                                <small class="text-muted">{{ $item->display_applicant_contact }}</small>
                                @if($item->applicant_nik)
                                    <br><small class="text-muted">NIK: {{ $item->applicant_nik }}</small>
                                @endif
                            </td>
                            <td>
                                @forelse($service->fields->take(3) as $field)
                                    <div class="mb-1">
                                        <strong>{{ $field->field_label }}:</strong>
                                        @include('admin.layanan-mandiri.submissions._field-value', [
                                            'field' => $field,
                                            'value' => data_get($item->form_data, $field->field_name),
                                        ])
                                    </div>
                                @empty
                                    <span class="text-muted">Belum ada field master.</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge {{ $item->status_badge_class }}">{{ $item->status_label }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->result_title ?: '-' }}</strong>
                                @if($item->result_type)
                                    <br><small class="text-muted">{{ $item->result_type_label }}</small>
                                @endif
                            </td>
                            <td>
                                {{ optional($item->submitted_at)->format('d-m-Y H:i') }}
                                @if($item->completed_at)
                                    <br><small class="text-muted">Selesai: {{ $item->completed_at->format('d-m-Y H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.layanan-mandiri.submissions.show', [$service->id, $item->id]) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.layanan-mandiri.submissions.edit', [$service->id, $item->id]) }}" class="btn btn-warning btn-sm" title="Proses">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pengajuan untuk layanan ini.</td>
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
