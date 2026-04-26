@extends('layouts.admin')

@section('title', 'Detail Agenda')
@section('page_title', 'Detail Agenda')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $item->title }}</h3>
        <div>
            <a href="{{ route('admin.agenda.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>Slug:</strong> {{ $item->slug }}<br>
            <strong>Penulis:</strong> {{ $item->author?->name ?? '-' }}<br>
            <strong>Status:</strong> {{ ucfirst($item->status) }}<br>
            <strong>Views:</strong> {{ $item->views }}<br>
            <strong>Tanggal:</strong> {{ $item->start_date?->format('d M Y') ?? '-' }}
            @if($item->end_date)
                - {{ $item->end_date->format('d M Y') }}
            @endif
            <br>
            <strong>Waktu:</strong> {{ $item->start_time ?? '-' }}
            @if($item->end_time)
                - {{ $item->end_time }}
            @endif
            <br>
            <strong>Tempat:</strong> {{ $item->location ?? '-' }}<br>
            <strong>Penyelenggara:</strong> {{ $item->organizer ?? '-' }}<br>
            <strong>Kontak Person:</strong> {{ $item->contact_person ?? '-' }}
        </div>

        @if($item->featured_image)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" style="max-width: 320px;" class="img-fluid rounded">
            </div>
        @endif

        <hr>

        <div>
            {!! $item->description !!}
        </div>
    </div>
</div>
@endsection