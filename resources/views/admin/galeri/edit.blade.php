@extends('layouts.admin')

@section('title', 'Edit Galeri')
@section('page_title', 'Edit Galeri')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Galeri</h3>
    </div>

    <form action="{{ route('admin.galeri.update', ['gallery' => $item->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.galeri._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection
