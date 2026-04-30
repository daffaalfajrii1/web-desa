@extends('layouts.admin')

@section('title', 'SDGS Nilai Tujuan')
@section('page_title', 'SDGS Nilai Tujuan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Nilai Tujuan SDGS</h3>
        <a href="{{ route('admin.sdgs-goal-values.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Nilai Tujuan
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Tahun SDGS</label>
                    <select name="sdgs_summary_id" class="form-control">
                        <option value="">Semua Tahun</option>
                        @foreach($summaries as $summary)
                            <option value="{{ $summary->id }}" {{ (string)request('sdgs_summary_id') === (string)$summary->id ? 'selected' : '' }}>
                                {{ $summary->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.sdgs-goal-values.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Tujuan</th>
                        <th>Skor</th>
                        <th>Capaian %</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->goal?->goal_number ?? '-' }}</td>
                            <td>{{ $item->summary?->year ?? '-' }}</td>
                            <td>
                                <strong>{{ $item->goal?->goal_name ?? '-' }}</strong>
                            </td>
                            <td>{{ number_format($item->score, 2, ',', '.') }}</td>
                            <td>{{ $item->achievement_percent !== null ? number_format($item->achievement_percent, 2, ',', '.') . '%' : '-' }}</td>
                            <td>
                                @if($item->status === 'baik')
                                    <span class="badge badge-success">Baik</span>
                                @elseif($item->status === 'berkembang')
                                    <span class="badge badge-warning">Berkembang</span>
                                @else
                                    <span class="badge badge-danger">Prioritas</span>
                                @endif
                            </td>
                            <td>{{ $item->short_description ?: '-' }}</td>
                            <td>
                                <a href="{{ route('admin.sdgs-goal-values.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sdgs-goal-values.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus nilai tujuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada nilai tujuan SDGS.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    </div>
</div>
@endsection