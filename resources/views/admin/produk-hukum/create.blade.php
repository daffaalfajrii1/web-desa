@extends('layouts.admin')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Produk Hukum</h3>
    </div>

    <form action="{{ route('admin.produk-hukum.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.produk-hukum._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.produk-hukum.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection