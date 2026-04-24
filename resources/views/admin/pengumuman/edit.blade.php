@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page_title', 'Edit Pengumuman')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Pengumuman</h3>
    </div>

    <form action="{{ route('admin.pengumuman.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.pengumuman._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')