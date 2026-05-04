<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillageBannerController extends Controller
{
    public function index()
    {
        $banners = VillageBanner::query()
            ->ordered()
            ->paginate(12);

        return view('admin.settings.village.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.settings.village.banners.create', [
            'banner' => new VillageBanner([
                'is_active' => true,
                'sort_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['image_path'] = $request->file('image')->store('village/banners', 'public');
        unset($data['image']);

        VillageBanner::create($data);

        return redirect()
            ->route('admin.settings.desa-banners.index')
            ->with('success', 'Banner desa berhasil ditambahkan.');
    }

    public function edit(VillageBanner $village_banner)
    {
        return view('admin.settings.village.banners.edit', [
            'banner' => $village_banner,
        ]);
    }

    public function update(Request $request, VillageBanner $village_banner)
    {
        $data = $this->validatedData($request, false);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $oldImagePath = $village_banner->image_path;
            $data['image_path'] = $request->file('image')->store('village/banners', 'public');
        }

        unset($data['image']);

        $village_banner->update($data);

        if (! empty($oldImagePath) && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.settings.desa-banners.index')
            ->with('success', 'Banner desa berhasil diperbarui.');
    }

    public function destroy(VillageBanner $village_banner)
    {
        $imagePath = $village_banner->image_path;
        $village_banner->delete();

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.settings.desa-banners.index')
            ->with('success', 'Banner desa berhasil dihapus.');
    }

    public function toggle(VillageBanner $village_banner)
    {
        $village_banner->update([
            'is_active' => ! $village_banner->is_active,
        ]);

        return back()->with('success', 'Status banner desa berhasil diperbarui.');
    }

    private function validatedData(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ]);
    }
}
