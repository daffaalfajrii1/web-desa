@extends('layouts.admin')

@section('title', 'Edit Ringkasan SDGS')
@section('page_title', 'Edit Ringkasan SDGS')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Ringkasan SDGS</h3>
    </div>

    <form action="{{ route('admin.sdgs-summaries.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.sdgs.summaries._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.sdgs-summaries.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection