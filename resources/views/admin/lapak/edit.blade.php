@extends('layouts.admin')

@section('title', 'Edit Produk Lapak')
@section('page_title', 'Edit Produk Lapak')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Produk Lapak</h3>
    </div>

    <form action="{{ route('admin.lapak.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.lapak._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection

@include('components.tinymce')