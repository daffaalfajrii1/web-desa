@extends('layouts.admin')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Produk Hukum</h3>
    </div>

    <form action="{{ route('admin.produk-hukum.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.produk-hukum._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.produk-hukum.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection