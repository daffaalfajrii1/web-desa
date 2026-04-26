<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCategory;
use App\Models\ShopImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $categoryId = $request->get('category_id');

        $categories = ShopCategory::where('is_active', true)->orderBy('name')->get();

        $items = Shop::with(['category', 'images'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%')
                      ->orWhere('seller_name', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('shop_category_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.lapak.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = ShopCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.lapak.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_category_id' => 'nullable|exists:shop_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shops,slug',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:available,out_of_stock',
            'whatsapp' => 'nullable|string|max:255',
            'seller_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_active'] = $request->boolean('is_active');
            $data['created_by'] = auth()->id();

            if ($request->hasFile('main_image')) {
                $data['main_image'] = $request->file('main_image')->store('shops/main', 'public');
            }

            $shop = Shop::create($data);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('shops/gallery', 'public');

                    ShopImage::create([
                        'shop_id' => $shop->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.lapak.index')->with('success', 'Produk lapak berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(Shop $lapak)
    {
        $lapak->load(['category', 'images', 'author']);

        return view('admin.lapak.show', ['item' => $lapak]);
    }

    public function edit(Shop $lapak)
    {
        $categories = ShopCategory::where('is_active', true)->orderBy('name')->get();
        $lapak->load('images');

        return view('admin.lapak.edit', [
            'item' => $lapak,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Shop $lapak)
    {
        $data = $request->validate([
            'shop_category_id' => 'nullable|exists:shop_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:shops,slug,' . $lapak->id,
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:available,out_of_stock',
            'whatsapp' => 'nullable|string|max:255',
            'seller_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'delete_gallery_images' => 'nullable|array',
            'delete_gallery_images.*' => 'exists:shop_images,id',
        ]);

        DB::beginTransaction();

        try {
            $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_active'] = $request->boolean('is_active');

            if ($request->hasFile('main_image')) {
                if ($lapak->main_image && Storage::disk('public')->exists($lapak->main_image)) {
                    Storage::disk('public')->delete($lapak->main_image);
                }

                $data['main_image'] = $request->file('main_image')->store('shops/main', 'public');
            }

            $lapak->update($data);

            if ($request->filled('delete_gallery_images')) {
                $imagesToDelete = ShopImage::whereIn('id', $request->delete_gallery_images)
                    ->where('shop_id', $lapak->id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }

            if ($request->hasFile('gallery_images')) {
                $lastOrder = (int) $lapak->images()->max('sort_order');

                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('shops/gallery', 'public');

                    ShopImage::create([
                        'shop_id' => $lapak->id,
                        'image_path' => $path,
                        'sort_order' => $lastOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.lapak.index')->with('success', 'Produk lapak berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Shop $lapak)
    {
        if ($lapak->main_image && Storage::disk('public')->exists($lapak->main_image)) {
            Storage::disk('public')->delete($lapak->main_image);
        }

        foreach ($lapak->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $lapak->delete();

        return redirect()->route('admin.lapak.index')->with('success', 'Produk lapak berhasil dihapus.');
    }
}