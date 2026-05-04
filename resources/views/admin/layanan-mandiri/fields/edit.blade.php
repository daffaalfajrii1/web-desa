@extends('layouts.admin')

@section('title', 'Edit Field Layanan')
@section('page_title', 'Edit Field Layanan')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title mb-0">Edit Field - {{ $service->service_name }}</h3>
    </div>

    <form action="{{ route('admin.layanan-mandiri.fields.update', [$service->id, $item->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.layanan-mandiri.fields._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.layanan-mandiri.fields.index', $service->id) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
