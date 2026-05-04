@extends('layouts.admin')

@section('title', 'Tambah Banner Desa')
@section('page_title', 'Tambah Banner Desa')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Banner Desa</h3>
    </div>

    <form action="{{ route('admin.settings.desa-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            @include('admin.settings.village.banners._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.settings.desa-banners.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Banner</button>
        </div>
    </form>
</div>
@endsection
