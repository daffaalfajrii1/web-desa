@extends('layouts.admin')

@section('title', 'Tambah Statistik Penduduk')
@section('page_title', 'Tambah Statistik Penduduk')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Statistik Penduduk</h3>
    </div>

    <form action="{{ route('admin.population-stats.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tahun <span class="text-danger">*</span></label>
                        <input type="text" name="year" required class="form-control" value="{{ old('year', date('Y')) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Dusun <span class="text-danger">*</span></label>
                        <select name="hamlet_id" required class="form-control">
                            <option value="">-- Pilih Dusun --</option>
                            @foreach($hamlets as $hamlet)
                                <option value="{{ $hamlet->id }}" {{ old('hamlet_id') == $hamlet->id ? 'selected' : '' }}>
                                    {{ $hamlet->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category" id="category_select" required class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $key => $items)
                                <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label>Item <span class="text-danger">*</span></label>
                        <select name="item_name" id="item_select" required class="form-control">
                            <option value="">-- Pilih Item --</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nilai <span class="text-danger">*</span></label>
                        <input type="number" min="0" name="value" required class="form-control" value="{{ old('value', 0) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.population-stats.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const categoryItems = @json($categories);

    const categorySelect = document.getElementById('category_select');
    const itemSelect = document.getElementById('item_select');

    categorySelect.addEventListener('change', function () {
        const selectedCategory = this.value;
        itemSelect.innerHTML = '<option value="">-- Pilih Item --</option>';

        if (categoryItems[selectedCategory]) {
            categoryItems[selectedCategory].forEach(function (item) {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                itemSelect.appendChild(option);
            });
        }
    });
</script>
@endpush