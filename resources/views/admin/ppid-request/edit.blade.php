@extends('layouts.admin')
@section('title', 'Tindak Lanjut Permohonan')
@section('page_title', 'Tindak Lanjut Permohonan')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Tindak Lanjut Permohonan Informasi</h3>
    </div>

    <form action="{{ route('admin.ppid-request.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="mb-3">
                <strong>Nama:</strong> {{ $item->name }}<br>
                <strong>Instansi:</strong> {{ $item->institution ?? '-' }}<br>
                <strong>Email:</strong> {{ $item->email }}<br>
                <strong>Telepon:</strong> {{ $item->phone }}
            </div>

            <div class="form-group">
                <label>Isi Permohonan</label>
                <textarea class="form-control" rows="5" readonly>{{ $item->request_content }}</textarea>
            </div>

            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" required class="form-control">
                    <option value="new" {{ old('status', $item->status) === 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="processed" {{ old('status', $item->status) === 'processed' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ old('status', $item->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ old('status', $item->status) === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Admin</label>
                <textarea name="admin_note" rows="5" class="form-control">{{ old('admin_note', $item->admin_note) }}</textarea>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.ppid-request.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection