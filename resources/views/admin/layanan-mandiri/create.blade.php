@extends('layouts.admin')

@section('title', 'Tambah Layanan Mandiri')
@section('page_title', 'Tambah Layanan Mandiri')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title mb-0">Tambah Layanan Mandiri</h3>
    </div>

    <form action="{{ route('admin.layanan-mandiri.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('admin.layanan-mandiri._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection
