@extends('layouts.admin')

@section('title', 'Ubah Status Pengaduan')
@section('page_title', 'Ubah Status Pengaduan')

@section('content')
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Ringkasan Pengaduan</h3>
            </div>
            <div class="card-body">
                <p class="mb-1 text-muted">Kode</p>
                <h5>{{ $item->complaint_code }}</h5>

                <p class="mb-1 mt-3 text-muted">Pelapor</p>
                <h5>{{ $item->name }}</h5>
                <div class="text-muted">{{ $item->phone }}</div>

                <p class="mb-1 mt-3 text-muted">Judul</p>
                <h5>{{ $item->subject }}</h5>

                <p class="mb-1 mt-3 text-muted">Status Saat Ini</p>
                <span class="badge {{ $item->status_badge_class }} p-2">{{ $item->status_label }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title mb-0">Ubah Status dan Catatan</h3>
            </div>
            <form action="{{ route('admin.pengaduan.update', $item->id) }}" method="POST">
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
                        <label>Catatan Admin</label>
                        <textarea name="admin_note" rows="8" class="form-control @error('admin_note') is-invalid @enderror" placeholder="Tuliskan tindak lanjut, alasan penolakan, atau informasi penyelesaian...">{{ old('admin_note', $item->admin_note) }}</textarea>
                        @error('admin_note')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
