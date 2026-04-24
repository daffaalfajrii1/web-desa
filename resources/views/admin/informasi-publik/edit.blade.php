@extends('layouts.admin')

@section('title', 'Edit Informasi Publik')
@section('page_title', 'Edit Informasi Publik')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Informasi Publik</h3>
    </div>

    <form action="{{ route('admin.informasi-publik.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.informasi-publik._form')
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.informasi-publik.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection