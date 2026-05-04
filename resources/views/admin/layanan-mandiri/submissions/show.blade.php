@extends('layouts.admin')

@section('title', 'Detail Pengajuan Layanan')
@section('page_title', 'Detail Pengajuan Layanan')

@section('content')
<style>
    .detail-panel {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 0.125rem 0.35rem rgba(0,0,0,.04);
    }

    .detail-row {
        border-bottom: 1px solid #eef1f4;
        padding: .85rem 0;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #6b7280;
        font-weight: 700;
        font-size: .86rem;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .submission-body {
        white-space: pre-line;
        line-height: 1.7;
        color: #1f2937;
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="card-title mb-0">{{ $service->service_name }}</h3>
                    <div class="text-muted mt-1">{{ $item->registration_number }}</div>
                </div>
                <span class="badge {{ $item->status_badge_class }} p-2">{{ $item->status_label }}</span>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Data Isian Pemohon</h5>

                @forelse($service->fields as $field)
                    <div class="detail-row">
                        <div class="detail-label">{{ $field->field_label }}</div>
                        <div class="mt-1">
                            @include('admin.layanan-mandiri.submissions._field-value', [
                                'field' => $field,
                                'value' => data_get($item->form_data, $field->field_name),
                            ])
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info mb-0">Belum ada field master untuk layanan ini.</div>
                @endforelse

                @php
                    $knownFields = $service->fields->pluck('field_name')->all();
                    $additionalData = collect($item->form_data ?? [])->except($knownFields);
                @endphp

                @if($additionalData->isNotEmpty())
                    <h5 class="mt-4 mb-3">Data Tambahan</h5>
                    @foreach($additionalData as $fieldName => $value)
                        <div class="detail-row">
                            <div class="detail-label">{{ \Illuminate\Support\Str::headline($fieldName) }}</div>
                            <div class="mt-1">
                                @include('admin.layanan-mandiri.submissions._field-value', [
                                    'field' => (object) ['field_type' => is_array($value) ? 'checkbox' : 'text'],
                                    'value' => $value,
                                ])
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Hasil Layanan</h3>
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <div class="detail-label">Jenis Hasil</div>
                    <div>{{ $item->result_type_label }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Judul Hasil</div>
                    <div>{{ $item->result_title ?: '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Catatan Hasil</div>
                    <div class="submission-body">{{ $item->result_note ?: 'Belum ada hasil layanan.' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">File Hasil</div>
                    @if($item->result_file)
                        <a href="{{ asset('storage/' . $item->result_file) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-download"></i> Lihat File Hasil
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="detail-panel p-3">
            <div class="detail-row">
                <div class="detail-label">Pemohon</div>
                <div><strong>{{ $item->display_applicant_name }}</strong></div>
                <div class="text-muted">{{ $item->display_applicant_contact }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">NIK</div>
                <div>{{ $item->applicant_nik ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div>{{ $item->applicant_email ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div>{{ $item->applicant_address ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Masuk</div>
                <div>{{ optional($item->submitted_at)->format('d-m-Y H:i') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Mulai Diproses</div>
                <div>{{ optional($item->processed_at)->format('d-m-Y H:i') ?: '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Selesai</div>
                <div>{{ optional($item->completed_at)->format('d-m-Y H:i') ?: '-' }}</div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title mb-0">Catatan Admin</h3>
            </div>
            <div class="card-body">
                <div class="submission-body">{{ $item->admin_note ?: 'Belum ada catatan admin.' }}</div>
            </div>
        </div>

        <div class="mt-3 d-flex flex-wrap">
            <a href="{{ route('admin.layanan-mandiri.submissions.index', $service->id) }}" class="btn btn-secondary mr-2 mb-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.layanan-mandiri.submissions.edit', [$service->id, $item->id]) }}" class="btn btn-warning mb-2">
                <i class="fas fa-edit"></i> Proses
            </a>
        </div>
    </div>
</div>
@endsection
