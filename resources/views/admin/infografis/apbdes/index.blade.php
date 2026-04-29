@extends('layouts.admin')

@section('title', 'APBDes')
@section('page_title', 'APBDes')

@section('content')
<style>
    .apbdes-card {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
    }

    .apbdes-card .card-header {
        background: #fff;
        border-bottom: 1px solid #eef1f4;
        padding: 1rem 1.25rem;
    }

    .apbdes-year-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1f2d3d;
        margin-bottom: 0.15rem;
    }

    .apbdes-status-line {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .apbdes-box {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        min-height: 110px;
        padding: 1rem 1rem 0.9rem 1rem;
        color: #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.08);
    }

    .apbdes-box .label {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        display: block;
        position: relative;
        z-index: 2;
    }

    .apbdes-box .value {
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.35;
        word-break: break-word;
        position: relative;
        z-index: 2;
    }

    .apbdes-box .icon {
        position: absolute;
        right: 14px;
        bottom: 10px;
        font-size: 56px;
        opacity: 0.12;
        line-height: 1;
        z-index: 1;
    }

    .apbdes-box.bg-income {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }

    .apbdes-box.bg-spending {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }

    .apbdes-box.bg-finance-in {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }

    .apbdes-box.bg-finance-out {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }

    .apbdes-info-box {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        display: flex;
        align-items: center;
        min-height: 98px;
        padding: 0.9rem 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.04);
    }

    .apbdes-info-box .icon-wrap {
        width: 68px;
        height: 68px;
        min-width: 68px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.9rem;
        font-size: 28px;
        color: #fff;
    }

    .apbdes-info-box .content {
        min-width: 0;
    }

    .apbdes-info-box .title {
        font-size: 1rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 0.3rem;
    }

    .apbdes-info-box .amount {
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.35;
        word-break: break-word;
    }

    .apbdes-info-box .amount.positive {
        color: #28a745;
    }

    .apbdes-info-box .amount.negative {
        color: #dc3545;
    }

    .bg-surplus {
        background: #007bff;
    }

    .bg-netto {
        background: #ffc107;
        color: #212529 !important;
    }

    .bg-silpa {
        background: #28a745;
    }

    .apbdes-actions .btn {
        min-width: 40px;
    }

    .apbdes-toolbar .btn {
        margin-left: 0.35rem;
    }

    @media (max-width: 767.98px) {
        .apbdes-toolbar {
            margin-top: 0.75rem;
            width: 100%;
        }

        .apbdes-toolbar .btn {
            margin-left: 0;
            margin-right: 0.35rem;
            margin-bottom: 0.35rem;
        }

        .apbdes-year-title {
            font-size: 1.15rem;
        }

        .apbdes-box .value,
        .apbdes-info-box .amount {
            font-size: 1.05rem;
        }
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Data APBDes</h3>

        <div class="apbdes-toolbar">
    <a href="{{ route('admin.apbdes.chart-view', request()->query()) }}" class="btn btn-info btn-sm">
        <i class="fas fa-chart-bar"></i> Lihat Chart
    </a>
    <a href="{{ route('admin.apbdes.export-excel', request()->query()) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <a href="{{ route('admin.apbdes.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah APBDes
    </a>
</div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label class="font-weight-normal mb-1">Filter Tahun</label>
                    <input type="text" name="year" class="form-control" placeholder="Contoh: 2026" value="{{ request('year') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.apbdes.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        @forelse($items as $item)
            @php
                $surplusDefisit = (float) $item->surplus_defisit;
                $netto = (float) $item->pembiayaan_netto;
                $silpa = (float) $item->silpa;

                $formatRupiah = function ($value) {
                    return 'Rp' . number_format($value, 2, ',', '.');
                };

                $formatSignedRupiah = function ($value) {
                    return $value < 0
                        ? '-Rp' . number_format(abs($value), 2, ',', '.')
                        : 'Rp' . number_format($value, 2, ',', '.');
                };
            @endphp

            <div class="card apbdes-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <div class="apbdes-year-title">APBDes Tahun {{ $item->year }}</div>
                        <div class="apbdes-status-line">
                            Status:
                            @if($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="apbdes-actions mt-2 mt-md-0">
                        <a href="{{ route('admin.apbdes.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.apbdes.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data APBDes ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="apbdes-box bg-income">
                                <span class="label">Pendapatan</span>
                                <div class="value">{{ $formatRupiah($item->pendapatan) }}</div>
                                <div class="icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="apbdes-box bg-spending">
                                <span class="label">Belanja</span>
                                <div class="value">{{ $formatRupiah($item->belanja) }}</div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="apbdes-box bg-finance-in">
                                <span class="label">Pemb. Penerimaan</span>
                                <div class="value">{{ $formatRupiah($item->pembiayaan_penerimaan) }}</div>
                                <div class="icon">
                                    <i class="fas fa-arrow-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="apbdes-box bg-finance-out">
                                <span class="label">Pemb. Pengeluaran</span>
                                <div class="value">{{ $formatRupiah($item->pembiayaan_pengeluaran) }}</div>
                                <div class="icon">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="apbdes-info-box">
                                <div class="icon-wrap bg-surplus">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <div class="content">
                                    <div class="title">Surplus / Defisit</div>
                                    <div class="amount {{ $surplusDefisit < 0 ? 'negative' : 'positive' }}">
                                        {{ $formatSignedRupiah($surplusDefisit) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="apbdes-info-box">
                                <div class="icon-wrap bg-netto">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="content">
                                    <div class="title">Pembiayaan Netto</div>
                                    <div class="amount {{ $netto < 0 ? 'negative' : 'positive' }}">
                                        {{ $formatSignedRupiah($netto) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="apbdes-info-box">
                                <div class="icon-wrap bg-silpa">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="content">
                                    <div class="title">SILPA</div>
                                    <div class="amount {{ $silpa < 0 ? 'negative' : 'positive' }}">
                                        {{ $formatSignedRupiah($silpa) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info mb-0">
                Belum ada data APBDes.
            </div>
        @endforelse

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection