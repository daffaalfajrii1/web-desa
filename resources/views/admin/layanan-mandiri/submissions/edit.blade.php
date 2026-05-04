@extends('layouts.admin')

@section('title', 'Proses Pengajuan Layanan')
@section('page_title', 'Proses Pengajuan Layanan')

@section('content')
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Ringkasan Pengajuan</h3>
            </div>
            <div class="card-body">
                <p class="mb-1 text-muted">Nomor Registrasi</p>
                <h5>{{ $item->registration_number }}</h5>

                <p class="mb-1 mt-3 text-muted">Layanan</p>
                <h5>{{ $service->service_name }}</h5>
                <div class="text-muted">{{ $service->service_code }}</div>

                <p class="mb-1 mt-3 text-muted">Pemohon</p>
                <h5>{{ $item->display_applicant_name }}</h5>
                <div class="text-muted">{{ $item->display_applicant_contact }}</div>

                <p class="mb-1 mt-3 text-muted">Status Saat Ini</p>
                <span class="badge {{ $item->status_badge_class }} p-2">{{ $item->status_label }}</span>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Data Isian</h3>
            </div>
            <div class="card-body">
                @forelse($service->fields->take(6) as $field)
                    <div class="mb-3">
                        <strong>{{ $field->field_label }}</strong><br>
                        @include('admin.layanan-mandiri.submissions._field-value', [
                            'field' => $field,
                            'value' => data_get($item->form_data, $field->field_name),
                        ])
                    </div>
                @empty
                    <div class="text-muted">Belum ada field master untuk layanan ini.</div>
                @endforelse

                <a href="{{ route('admin.layanan-mandiri.submissions.show', [$service->id, $item->id]) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye"></i> Lihat Detail Lengkap
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title mb-0">Status dan Hasil Layanan</h3>
            </div>
            <form action="{{ route('admin.layanan-mandiri.submissions.update', [$service->id, $item->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $item->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Hasil</label>
                        <select name="result_type" class="form-control @error('result_type') is-invalid @enderror">
                            <option value="">Belum Ada Hasil</option>
                            @foreach($resultTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('result_type', $item->result_type) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('result_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Judul Hasil</label>
                        <input type="text" name="result_title" class="form-control @error('result_title') is-invalid @enderror"
                               value="{{ old('result_title', $item->result_title) }}" placeholder="Contoh: Surat Keterangan Domisili siap diambil">
                        @error('result_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Catatan Admin</label>
                        <textarea name="admin_note" rows="4" class="form-control @error('admin_note') is-invalid @enderror" placeholder="Catatan internal untuk admin...">{{ old('admin_note', $item->admin_note) }}</textarea>
                        @error('admin_note')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Catatan Hasil untuk Pemohon</label>
                        <textarea name="result_note" rows="6" class="form-control @error('result_note') is-invalid @enderror" placeholder="Isi hasil yang nanti bisa dilihat pemohon dari nomor registrasi. Contoh: Silakan datang ke kantor desa membawa KTP asli.">{{ old('result_note', $item->result_note) }}</textarea>
                        @error('result_note')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>File Hasil</label>
                        <input type="file" name="result_file" class="form-control-file @error('result_file') is-invalid @enderror">
                        @error('result_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">PDF, DOC, DOCX, JPG, JPEG, atau PNG. Maksimal 5 MB.</small>

                        @if($item->result_file)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $item->result_file) }}" target="_blank" class="btn btn-outline-info btn-sm mr-2">
                                    <i class="fas fa-file-download"></i> File Saat Ini
                                </a>
                                <div class="form-check d-inline-block">
                                    <input type="checkbox" name="remove_result_file" value="1" class="form-check-input" id="remove_result_file">
                                    <label class="form-check-label" for="remove_result_file">Hapus file</label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.layanan-mandiri.submissions.show', [$service->id, $item->id]) }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
