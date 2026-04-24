<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $categories = Category::where('type', 'post')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->withCount('posts')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-berita.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.kategori-berita.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['type'] = 'post';

        Category::create($data);

        return redirect()->route('admin.kategori-berita.index')
            ->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    public function edit(Category $kategori_beritum)
    {
        return view('admin.kategori-berita.edit', [
            'category' => $kategori_beritum,
        ]);
    }

    public function update(Request $request, Category $kategori_beritum)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $kategori_beritum->id,
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $kategori_beritum->update($data);

        return redirect()->route('admin.kategori-berita.index')
            ->with('success', 'Kategori berita berhasil diperbarui.');
    }

    public function destroy(Category $kategori_beritum)
    {
        $kategori_beritum->delete();

        return redirect()->route('admin.kategori-berita.index')
            ->with('success', 'Kategori berita berhasil dihapus.');
    }

    public function legalProductIndex(Request $request)
{
    $search = $request->get('search');

    $categories = \App\Models\Category::where('type', 'legal_product')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        })
        ->withCount('legalProducts')
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.kategori-produk-hukum.index', compact('categories'));
}

public function legalProductCreate()
{
    return view('admin.kategori-produk-hukum.create');
}

public function legalProductStore(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:categories,slug',
    ]);

    $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
    $data['type'] = 'legal_product';

    \App\Models\Category::create($data);

    return redirect()->route('admin.kategori-produk-hukum.index')
        ->with('success', 'Kategori produk hukum berhasil ditambahkan.');
}

public function legalProductEdit(\App\Models\Category $category)
{
    abort_unless($category->type === 'legal_product', 404);

    return view('admin.kategori-produk-hukum.edit', compact('category'));
}

public function legalProductUpdate(Request $request, \App\Models\Category $category)
{
    abort_unless($category->type === 'legal_product', 404);

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
    ]);

    $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);

    $category->update($data);

    return redirect()->route('admin.kategori-produk-hukum.index')
        ->with('success', 'Kategori produk hukum berhasil diperbarui.');
}

public function legalProductDestroy(\App\Models\Category $category)
{
    abort_unless($category->type === 'legal_product', 404);

    $category->delete();

    return redirect()->route('admin.kategori-produk-hukum.index')
        ->with('success', 'Kategori produk hukum berhasil dihapus.');
}
}