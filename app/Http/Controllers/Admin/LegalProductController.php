<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LegalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LegalProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', 'legal_product')->orderBy('name')->get();

        $search = $request->get('search');
        $status = $request->get('status');
        $categoryId = $request->get('category_id');
        $sort = $request->get('sort', 'latest');

        $items = LegalProduct::with(['author', 'category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%')
                      ->orWhere('number', 'like', '%' . $search . '%')
                      ->orWhere('document_type', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($categoryId === 'uncategorized', function ($query) {
                $query->whereNull('category_id');
            })
            ->when($categoryId && $categoryId !== 'uncategorized', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });

        switch ($sort) {
            case 'oldest':
                $items->oldest();
                break;
            case 'title_asc':
                $items->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $items->orderBy('title', 'desc');
                break;
            case 'most_viewed':
                $items->orderBy('views', 'desc');
                break;
            case 'category_asc':
                $items->leftJoin('categories', 'legal_products.category_id', '=', 'categories.id')
                    ->select('legal_products.*')
                    ->orderByRaw('CASE WHEN categories.name IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('categories.name', 'asc');
                break;
            case 'category_desc':
                $items->leftJoin('categories', 'legal_products.category_id', '=', 'categories.id')
                    ->select('legal_products.*')
                    ->orderByRaw('CASE WHEN categories.name IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('categories.name', 'desc');
                break;
            default:
                $items->latest();
                break;
        }

        $items = $items->paginate(10)->withQueryString();

        return view('admin.produk-hukum.index', compact('items', 'categories', 'sort'));
    }

    public function create()
    {
        $categories = Category::where('type', 'legal_product')->orderBy('name')->get();

        return view('admin.produk-hukum.create', compact('categories'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:legal_products,slug',
        'category_id' => 'nullable|exists:categories,id',
        'number' => 'required|string|max:255',
        'document_type' => 'required|string|max:255',
        'published_date' => 'required|date',
        'description' => 'nullable|string',
        'file_path' => 'required|file|mimes:pdf|max:5120',
        'status' => 'required|in:draft,published',
    ], [
        'title.required' => 'Judul produk hukum wajib diisi.',
        'number.required' => 'Nomor dokumen wajib diisi.',
        'document_type.required' => 'Jenis produk hukum wajib diisi.',
        'published_date.required' => 'Tanggal publikasi/penetapan wajib diisi.',
        'file_path.required' => 'File PDF wajib diupload.',
        'file_path.mimes' => 'File harus berformat PDF.',
        'file_path.max' => 'Ukuran file maksimal 5 MB.',
    ]);

    $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['title']);
    $data['created_by'] = auth()->id();

    if ($request->hasFile('file_path')) {
        $data['file_path'] = $request->file('file_path')->store('legal-products', 'public');
    }

    LegalProduct::create($data);

    return redirect()->route('admin.produk-hukum.index')
        ->with('success', 'Produk hukum berhasil ditambahkan.');
}

    public function show(LegalProduct $produk_hukum)
    {
        $produk_hukum->load(['author', 'category']);

        return view('admin.produk-hukum.show', [
            'item' => $produk_hukum,
        ]);
    }

    public function edit(LegalProduct $produk_hukum)
    {
        $categories = Category::where('type', 'legal_product')->orderBy('name')->get();

        return view('admin.produk-hukum.edit', [
            'item' => $produk_hukum,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, LegalProduct $produk_hukum)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:legal_products,slug,' . $produk_hukum->id,
        'category_id' => 'nullable|exists:categories,id',
        'number' => 'required|string|max:255',
        'document_type' => 'required|string|max:255',
        'published_date' => 'required|date',
        'description' => 'nullable|string',
        'file_path' => 'nullable|file|mimes:pdf|max:5120',
        'status' => 'required|in:draft,published',
    ], [
        'title.required' => 'Judul produk hukum wajib diisi.',
        'number.required' => 'Nomor dokumen wajib diisi.',
        'document_type.required' => 'Jenis produk hukum wajib diisi.',
        'published_date.required' => 'Tanggal publikasi/penetapan wajib diisi.',
        'file_path.mimes' => 'File harus berformat PDF.',
        'file_path.max' => 'Ukuran file maksimal 5 MB.',
    ]);

    $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['title']);

    if ($request->hasFile('file_path')) {
        if ($produk_hukum->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($produk_hukum->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($produk_hukum->file_path);
        }

        $data['file_path'] = $request->file('file_path')->store('legal-products', 'public');
    }

    $produk_hukum->update($data);

    return redirect()->route('admin.produk-hukum.index')
        ->with('success', 'Produk hukum berhasil diperbarui.');
}

    public function destroy(LegalProduct $produk_hukum)
    {
        if ($produk_hukum->file_path && Storage::disk('public')->exists($produk_hukum->file_path)) {
            Storage::disk('public')->delete($produk_hukum->file_path);
        }

        $produk_hukum->delete();

        return redirect()->route('admin.produk-hukum.index')
            ->with('success', 'Produk hukum berhasil dihapus.');
    }
}