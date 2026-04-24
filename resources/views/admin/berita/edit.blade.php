@extends('layouts.admin')
@section('title', ' Edit Berita')
@section('page_title', ' Edit Berita')
@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Berita</h3>
    </div>

    <form action="{{ route('admin.berita.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.berita._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')