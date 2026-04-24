@extends('layouts.admin')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Kategori Produk Hukum</h3>
    </div>

    <form action="{{ route('admin.kategori-produk-hukum.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.kategori-produk-hukum._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.kategori-produk-hukum.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection