@extends('layouts.admin')

@section('title', 'Tambah Informasi Publik')
@section('page_title', 'Tambah Informasi Publik')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Informasi Publik</h3>
    </div>

    <form action="{{ route('admin.informasi-publik.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.informasi-publik._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.informasi-publik.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection