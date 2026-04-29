<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourism;
use App\Models\TourismImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourismController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $items = Tourism::with('images')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhere('contact_person', 'like', '%' . $search . '%')
                        ->orWhere('contact_phone', 'like', '%' . $search . '%')
                        ->orWhere('open_days', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.wisata.index', compact('items'));
    }

    public function create()
    {
        return view('admin.wisata.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tourisms,slug',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'open_days' => 'nullable|string|max:255',
            'closed_days' => 'nullable|string|max:255',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
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
                $data['main_image'] = $request->file('main_image')->store('tourisms/main', 'public');
            }

            $tourism = Tourism::create($data);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('tourisms/gallery', 'public');

                    TourismImage::create([
                        'tourism_id' => $tourism->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.wisata.index')
                ->with('success', 'Wisata berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(Tourism $tourism)
    {
        $tourism->load(['images', 'author']);

        return view('admin.wisata.show', [
            'item' => $tourism,
        ]);
    }

    public function edit(Tourism $tourism)
    {
        $tourism->load('images');

        return view('admin.wisata.edit', [
            'item' => $tourism,
        ]);
    }

    public function update(Request $request, Tourism $tourism)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tourisms,slug,' . $tourism->id,
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'open_days' => 'nullable|string|max:255',
            'closed_days' => 'nullable|string|max:255',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'delete_gallery_images' => 'nullable|array',
            'delete_gallery_images.*' => 'exists:tourism_images,id',
        ]);

        DB::beginTransaction();

        try {
            $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
            $data['is_featured'] = $request->boolean('is_featured');
            $data['is_active'] = $request->boolean('is_active');

            if ($request->hasFile('main_image')) {
                if ($tourism->main_image && Storage::disk('public')->exists($tourism->main_image)) {
                    Storage::disk('public')->delete($tourism->main_image);
                }

                $data['main_image'] = $request->file('main_image')->store('tourisms/main', 'public');
            }

            $tourism->update($data);

            if ($request->filled('delete_gallery_images')) {
                $imagesToDelete = TourismImage::whereIn('id', $request->delete_gallery_images)
                    ->where('tourism_id', $tourism->id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }

            if ($request->hasFile('gallery_images')) {
                $lastOrder = (int) $tourism->images()->max('sort_order');

                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('tourisms/gallery', 'public');

                    TourismImage::create([
                        'tourism_id' => $tourism->id,
                        'image_path' => $path,
                        'sort_order' => $lastOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.wisata.index')
                ->with('success', 'Wisata berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Tourism $tourism)
    {
        if ($tourism->main_image && Storage::disk('public')->exists($tourism->main_image)) {
            Storage::disk('public')->delete($tourism->main_image);
        }

        foreach ($tourism->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $tourism->delete();

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil dihapus.');
    }
}