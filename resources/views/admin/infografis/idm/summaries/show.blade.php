@extends('layouts.admin')

@section('title', 'Detail IDM')
@section('page_title', 'Detail IDM')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-success">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Ringkasan IDM Tahun {{ $item->year }}</h3>
                <a href="{{ route('admin.idm-indicators.index', ['idm_summary_id' => $item->id]) }}" class="btn btn-success btn-sm">
                    Kelola Indikator
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>IKS:</strong> {{ number_format($item->iks_score, 4) }}</div>
                    <div class="col-md-3"><strong>IKE:</strong> {{ number_format($item->ike_score, 4) }}</div>
                    <div class="col-md-3"><strong>IKL:</strong> {{ number_format($item->ikl_score, 4) }}</div>
                    <div class="col-md-3"><strong>IDM:</strong> {{ number_format($item->idm_score, 4) }}</div>
                    <div class="col-md-3 mt-3"><strong>Status:</strong> {{ $item->idm_status }}</div>
                    <div class="col-md-3 mt-3"><strong>Target:</strong> {{ $item->target_status }}</div>
                    <div class="col-md-3 mt-3"><strong>Minimal:</strong> {{ number_format($item->minimal_target_score, 4) }}</div>
                    <div class="col-md-3 mt-3"><strong>Penambahan:</strong> {{ number_format($item->additional_score_needed, 4) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection