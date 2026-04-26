@extends('layouts.admin')

@section('title', 'Edit Pegawai')
@section('page_title', 'Edit Pegawai')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Pegawai / SOTK</h3>
    </div>

    <form action="{{ route('admin.pegawai.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.pegawai._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection