@extends('layouts.admin')

@section('title', 'Tambah Field Layanan')
@section('page_title', 'Tambah Field Layanan')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title mb-0">Tambah Field - {{ $service->service_name }}</h3>
    </div>

    <form action="{{ route('admin.layanan-mandiri.fields.store', $service->id) }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.layanan-mandiri.fields._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.layanan-mandiri.fields.index', $service->id) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection
