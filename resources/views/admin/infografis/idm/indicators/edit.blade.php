@extends('layouts.admin')

@section('title', 'Edit Indikator IDM')
@section('page_title', 'Edit Indikator IDM')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Indikator IDM</h3>
    </div>
    <form action="{{ route('admin.idm-indicators.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.idm.indicators._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.idm-indicators.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection