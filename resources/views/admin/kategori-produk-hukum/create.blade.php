@extends('layouts.admin')
@section('title', ' Tambah Kategori Produk Hukum')
@section('page_title', ' Tambah Kategori Produk Hukum')
@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori Produk Hukum</h3>
    </div>

    <form action="{{ route('admin.kategori-produk-hukum.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @include('admin.kategori-produk-hukum._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.kategori-produk-hukum.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection