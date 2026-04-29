@extends('layouts.admin')

@section('title', 'Edit Statistik Penduduk')
@section('page_title', 'Edit Statistik Penduduk')

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Statistik Penduduk</h3>
    </div>

    <form action="{{ route('admin.population-stats.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tahun <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="year"
                            required
                            class="form-control @error('year') is-invalid @enderror"
                            value="{{ old('year', $item->year) }}"
                        >
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Dusun <span class="text-danger">*</span></label>
                        <select name="hamlet_id" required class="form-control @error('hamlet_id') is-invalid @enderror">
                            <option value="">-- Pilih Dusun --</option>
                            @foreach($hamlets as $hamlet)
                                <option value="{{ $hamlet->id }}"
                                    {{ (string) old('hamlet_id', $item->hamlet_id) === (string) $hamlet->id ? 'selected' : '' }}>
                                    {{ $hamlet->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hamlet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category" id="category_select" required class="form-control @error('category') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $key => $items)
                                <option value="{{ $key }}"
                                    {{ old('category', $item->category) === $key ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Item <span class="text-danger">*</span></label>
                        <select name="item_name" id="item_select" required class="form-control @error('item_name') is-invalid @enderror">
                            <option value="">-- Pilih Item --</option>
                        </select>
                        @error('item_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nilai <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            min="0"
                            name="value"
                            required
                            class="form-control @error('value') is-invalid @enderror"
                            value="{{ old('value', $item->value) }}"
                        >
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.population-stats.index') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-warning" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const categoryItems = @json($categories);
    const selectedCategory = @json(old('category', $item->category));
    const selectedItem = @json(old('item_name', $item->item_name));

    const categorySelect = document.getElementById('category_select');
    const itemSelect = document.getElementById('item_select');

    function renderItems(category, selected = null) {
        itemSelect.innerHTML = '<option value="">-- Pilih Item --</option>';

        if (categoryItems[category]) {
            categoryItems[category].forEach(function (item) {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;

                if (selected && selected === item) {
                    option.selected = true;
                }

                itemSelect.appendChild(option);
            });
        }
    }

    categorySelect.addEventListener('change', function () {
        renderItems(this.value);
    });

    if (selectedCategory) {
        categorySelect.value = selectedCategory;
        renderItems(selectedCategory, selectedItem);
    }
</script>
@endpush