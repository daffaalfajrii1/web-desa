@extends('layouts.admin')

@section('title', 'Edit Penerima Bansos')
@section('page_title', 'Edit Penerima Bansos')

@section('content')
<div class="card card-warning recipient-form-card">
    <div class="card-header">
        <h3 class="card-title">Edit Penerima Bansos</h3>
    </div>

    <form action="{{ route('admin.bansos-recipient.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.bansos-recipient._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.bansos-recipient.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection