@extends('layouts.admin')

@section('title', 'Indikator IDM')
@section('page_title', 'Indikator IDM')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title mb-0">Data Indikator IDM</h3>
        <a href="{{ route('admin.idm-indicators.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Indikator
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Tahun IDM</label>
                    <select name="idm_summary_id" class="form-control">
                        <option value="">Semua Tahun</option>
                        @foreach($summaries as $summary)
                            <option value="{{ $summary->id }}" {{ request('idm_summary_id') == $summary->id ? 'selected' : '' }}>
                                {{ $summary->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Kategori</label>
                    <select name="category" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="IKS" {{ request('category') == 'IKS' ? 'selected' : '' }}>IKS</option>
                        <option value="IKE" {{ request('category') == 'IKE' ? 'selected' : '' }}>IKE</option>
                        <option value="IKL" {{ request('category') == 'IKL' ? 'selected' : '' }}>IKL</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary mr-2">Filter</button>
                    <a href="{{ route('admin.idm-indicators.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Indikator</th>
                        <th>Skor</th>
                        <th>Keterangan</th>
                        <th>Kegiatan</th>
                        <th>Nilai+</th>
                        <th>Pusat</th>
                        <th>Provinsi</th>
                        <th>Kab.</th>
                        <th>Desa</th>
                        <th>CSR</th>
                        <th>Lainnya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->indicator_no }}</td>
                            <td>{{ $item->summary->year ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $item->category }}</span></td>
                            <td>{{ $item->indicator_name }}</td>
                            <td>{{ $item->score }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->activity }}</td>
                            <td>{{ number_format($item->value, 4) }}</td>
                            <td>{{ $item->executor_central }}</td>
                            <td>{{ $item->executor_province }}</td>
                            <td>{{ $item->executor_regency }}</td>
                            <td>{{ $item->executor_village }}</td>
                            <td>{{ $item->executor_csr }}</td>
                            <td>{{ $item->executor_other }}</td>
                            <td>
                                <a href="{{ route('admin.idm-indicators.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.idm-indicators.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center">Belum ada indikator IDM.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    </div>
</div>
@endsection