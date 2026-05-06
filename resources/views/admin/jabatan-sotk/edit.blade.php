@extends('layouts.admin')

@section('title', 'Edit Jabatan SOTK')
@section('page_title', 'Edit Jabatan SOTK')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Jabatan SOTK</h3>
    </div>

    <form action="{{ route('admin.jabatan-sotk.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.jabatan-sotk._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.jabatan-sotk.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection
