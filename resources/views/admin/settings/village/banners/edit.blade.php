@extends('layouts.admin')

@section('title', 'Edit Banner Desa')
@section('page_title', 'Edit Banner Desa')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Edit Banner Desa</h3>
    </div>

    <form action="{{ route('admin.settings.desa-banners.update', ['village_banner' => $banner->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.settings.village.banners._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.settings.desa-banners.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
