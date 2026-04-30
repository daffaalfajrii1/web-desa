@extends('layouts.admin')

@section('title', 'Edit Nilai Tujuan SDGS')
@section('page_title', 'Edit Nilai Tujuan SDGS')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Nilai Tujuan SDGS</h3>
    </div>

    <form action="{{ route('admin.sdgs-goal-values.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.infografis.sdgs.goal-values._form')
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.sdgs-goal-values.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </div>
    </form>
</div>
@endsection