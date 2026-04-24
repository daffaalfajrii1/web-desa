@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>12</h3>
                <p>Total Berita</p>
            </div>
            <div class="icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <a href="#" class="small-box-footer">
                Lihat detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>8</h3>
                <p>Informasi Publik</p>
            </div>
            <div class="icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <a href="#" class="small-box-footer">
                Lihat detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>5</h3>
                <p>Produk Hukum</p>
            </div>
            <div class="icon">
                <i class="fas fa-balance-scale"></i>
            </div>
            <a href="#" class="small-box-footer">
                Lihat detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>3</h3>
                <p>Permohonan PPID</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <a href="#" class="small-box-footer">
                Lihat detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Selamat Datang</h3>
    </div>
    <div class="card-body">
        <p>Halo, <strong>{{ auth()->user()->name }}</strong></p>
        <p>Email: {{ auth()->user()->email }}</p>
        <p>Role: {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</p>
        <p>Dashboard admin web desa sudah menggunakan layout AdminLTE.</p>
    </div>
</div>
@endsection