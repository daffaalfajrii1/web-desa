@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page_title', 'Tambah User')

@section('content')
@php($user = null)

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah User</h3>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            @include('admin.users._form')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="form-control @error('password') is-invalid @enderror"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Konfirmasi Password <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="form-control"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection