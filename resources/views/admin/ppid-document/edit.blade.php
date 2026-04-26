@extends('layouts.admin')
@section('title', 'Edit Dokumen PPID')
@section('page_title', 'Edit Dokumen PPID')

@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Edit Dokumen PPID</h3></div>
    <form action="{{ route('admin.ppid-document.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.ppid-document._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.ppid-document.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection