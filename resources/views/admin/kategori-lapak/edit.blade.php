@extends('layouts.admin')

@section('title', 'Edit Kategori Lapak')
@section('page_title', 'Edit Kategori Lapak')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Kategori Lapak</h3>
    </div>

    <form action="{{ route('admin.kategori-lapak.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.kategori-lapak._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.kategori-lapak.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection