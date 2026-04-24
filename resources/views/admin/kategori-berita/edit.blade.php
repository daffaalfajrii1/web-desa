@extends('layouts.admin')
@section('title', ' Edit Kategori Berita')
@section('page_title', ' Edit Kategori Berita')
@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Kategori Berita</h3>
    </div>

    <form action="{{ route('admin.kategori-berita.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.kategori-berita._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.kategori-berita.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection