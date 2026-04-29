@extends('layouts.admin')

@section('title', 'Edit Wisata')
@section('page_title', 'Edit Wisata')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Wisata</h3>
    </div>

    <form action="{{ route('admin.wisata.update', ['tourism' => $item->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.wisata._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.wisata.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')