<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $items = ShopCategory::withCount('shops')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-lapak.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kategori-lapak.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shop_categories,slug',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        ShopCategory::create($data);

        return redirect()->route('admin.kategori-lapak.index')
            ->with('success', 'Kategori lapak berhasil ditambahkan.');
    }

    public function edit(ShopCategory $kategori_lapak)
    {
        return view('admin.kategori-lapak.edit', ['item' => $kategori_lapak]);
    }

    public function update(Request $request, ShopCategory $kategori_lapak)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shop_categories,slug,' . $kategori_lapak->id,
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $kategori_lapak->update($data);

        return redirect()->route('admin.kategori-lapak.index')
            ->with('success', 'Kategori lapak berhasil diperbarui.');
    }

    public function destroy(ShopCategory $kategori_lapak)
    {
        $kategori_lapak->delete();

        return redirect()->route('admin.kategori-lapak.index')
            ->with('success', 'Kategori lapak berhasil dihapus.');
    }
}