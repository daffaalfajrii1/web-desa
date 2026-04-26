@extends('layouts.admin')
@section('title', 'Tambah Dokumen PPID')
@section('page_title', 'Tambah Dokumen PPID')

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Tambah Dokumen PPID</h3></div>
    <form action="{{ route('admin.ppid-document.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @include('admin.ppid-document._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.ppid-document.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection