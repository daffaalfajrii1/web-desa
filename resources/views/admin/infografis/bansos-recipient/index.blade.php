@extends('layouts.admin')

@section('title', 'Penerima Bansos')
@section('page_title', 'Penerima Bansos')

@section('content')
<style>
    .recipient-card {
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.05);
    }

    .recipient-mini-box {
        border-radius: 10px;
        padding: .8rem 1rem;
        color: #fff;
        min-height: 92px;
    }

    .recipient-mini-box .label {
        font-size: .92rem;
        font-weight: 600;
        margin-bottom: .25rem;
    }

    .recipient-mini-box .value {
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .recipient-badge {
        font-size: .8rem;
        padding: .4rem .65rem;
        border-radius: 999px;
    }

    .recipient-table td {
        vertical-align: top;
    }

    .recipient-help {
        font-size: .85rem;
        color: #6c757d;
    }
</style>

<div class="card recipient-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Data Penerima Bansos</h3>
        <a href="{{ route('admin.bansos-recipient.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Penerima
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $totalRecipients = $items->total();
            $currentItems = $items->getCollection();
            $readyCount = $currentItems->where('distribution_status', 'ready')->count();
            $distributedCount = $currentItems->where('distribution_status', 'distributed')->count();
            $pendingCount = $currentItems->where('distribution_status', 'pending')->count();
        @endphp

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="recipient-mini-box bg-info">
                    <div class="label">Total Data</div>
                    <div class="value">{{ number_format($totalRecipients) }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="recipient-mini-box bg-primary">
                    <div class="label">Siap Diambil</div>
                    <div class="value">{{ number_format($readyCount) }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="recipient-mini-box bg-success">
                    <div class="label">Sudah Diambil</div>
                    <div class="value">{{ number_format($distributedCount) }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="recipient-mini-box bg-warning" style="color:#212529">
                    <div class="label">Pending</div>
                    <div class="value">{{ number_format($pendingCount) }}</div>
                </div>
            </div>
        </div>

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Program Bansos</label>
                    <select name="program_id" class="form-control">
                        <option value="">Semua Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ (string)request('program_id') === (string)$program->id ? 'selected' : '' }}>
                                {{ $program->name }} - {{ $program->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Status Penyaluran</label>
                    <select name="distribution_status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('distribution_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="ready" {{ request('distribution_status') === 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="distributed" {{ request('distribution_status') === 'distributed' ? 'selected' : '' }}>Sudah Diambil</option>
                        <option value="rejected" {{ request('distribution_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama / NIK / KK..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.bansos-recipient.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="recipient-help mb-3">
            Bantuan bisa berupa uang, barang, jasa, atau campuran.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover recipient-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Penerima</th>
                        <th>Program</th>
                        <th>Dusun</th>
                        <th>Bentuk Bantuan</th>
                        <th>Verifikasi</th>
                        <th>Penyaluran</th>
                        <th>Tanggal Ambil</th>
                        <th style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($items->firstItem() ?? 0) - 1 }}</td>

                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">NIK: {{ $item->nik }}</small><br>
                                <small class="text-muted">KK: {{ $item->kk_number ?: '-' }}</small>
                                @if($item->phone)
                                    <br><small class="text-muted">HP: {{ $item->phone }}</small>
                                @endif
                            </td>

                            <td>
                                {{ $item->program?->name ?? '-' }}<br>
                                <small class="text-muted">{{ $item->program?->year ?? '-' }}</small>
                            </td>

                            <td>{{ $item->hamlet?->name ?? '-' }}</td>

                            <td>
                                @if($item->benefit_type === 'cash')
                                    <span class="badge badge-success recipient-badge">Tunai</span><br>
                                    <strong>Rp{{ number_format($item->amount, 2, ',', '.') }}</strong>
                                @elseif($item->benefit_type === 'goods')
                                    <span class="badge badge-info recipient-badge">Barang</span><br>
                                    {{ $item->item_description ?: '-' }}
                                    @if($item->quantity || $item->unit)
                                        <br><small class="text-muted">
                                            {{ rtrim(rtrim(number_format((float) $item->quantity, 2, ',', '.'), '0'), ',') }} {{ $item->unit }}
                                        </small>
                                    @endif
                                @elseif($item->benefit_type === 'service')
                                    <span class="badge badge-primary recipient-badge">Jasa</span><br>
                                    {{ $item->item_description ?: '-' }}
                                    @if($item->quantity || $item->unit)
                                        <br><small class="text-muted">
                                            {{ rtrim(rtrim(number_format((float) $item->quantity, 2, ',', '.'), '0'), ',') }} {{ $item->unit }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge badge-warning recipient-badge">Campuran</span><br>
                                    @if((float)$item->amount > 0)
                                        <strong>Rp{{ number_format($item->amount, 2, ',', '.') }}</strong><br>
                                    @endif
                                    {{ $item->item_description ?: '-' }}
                                    @if($item->quantity || $item->unit)
                                        <br><small class="text-muted">
                                            {{ rtrim(rtrim(number_format((float) $item->quantity, 2, ',', '.'), '0'), ',') }} {{ $item->unit }}
                                        </small>
                                    @endif
                                @endif
                            </td>

                            <td>
                                @if($item->verification_status === 'verified')
                                    <span class="badge badge-success recipient-badge">Terverifikasi</span>
                                @elseif($item->verification_status === 'rejected')
                                    <span class="badge badge-danger recipient-badge">Ditolak</span>
                                @else
                                    <span class="badge badge-secondary recipient-badge">Pending</span>
                                @endif
                            </td>

                            <td>
                                @if($item->distribution_status === 'distributed')
                                    <span class="badge badge-success recipient-badge">Sudah Diambil</span>
                                @elseif($item->distribution_status === 'ready')
                                    <span class="badge badge-primary recipient-badge">Siap Diambil</span>
                                @elseif($item->distribution_status === 'rejected')
                                    <span class="badge badge-danger recipient-badge">Ditolak</span>
                                @else
                                    <span class="badge badge-warning recipient-badge">Pending</span>
                                @endif

                                @if($item->receiver_name)
                                    <br><small class="text-muted">Penerima: {{ $item->receiver_name }}</small>
                                @endif
                            </td>

                            <td>{{ optional($item->distributed_at)->format('d-m-Y') ?? '-' }}</td>

                            <td>
                                <a href="{{ route('admin.bansos-recipient.edit', $item->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.bansos-recipient.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus penerima bansos ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm mb-1" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada penerima bansos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection