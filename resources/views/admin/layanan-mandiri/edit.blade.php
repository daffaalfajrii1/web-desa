@extends('layouts.admin')

@section('title', 'Edit Layanan Mandiri')
@section('page_title', 'Edit Layanan Mandiri')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title mb-0">Edit Layanan Mandiri</h3>
    </div>

    <form action="{{ route('admin.layanan-mandiri.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.layanan-mandiri._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.layanan-mandiri.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
