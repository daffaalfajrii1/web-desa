<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);

        return view('admin.profil-desa.halaman.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.profil-desa.halaman.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Page::create($data);

        return redirect()->route('admin.profil-desa.halaman.index')
            ->with('success', 'Halaman profil berhasil ditambahkan.');
    }

    public function show(Page $halaman)
    {
        return view('admin.profil-desa.halaman.show', ['page' => $halaman]);
    }

    public function edit(Page $halaman)
    {
        return view('admin.profil-desa.halaman.edit', ['page' => $halaman]);
    }

    public function update(Request $request, Page $halaman)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $halaman->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            if ($halaman->featured_image && Storage::disk('public')->exists($halaman->featured_image)) {
                Storage::disk('public')->delete($halaman->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if ($data['status'] === 'published' && ! $halaman->published_at) {
            $data['published_at'] = now();
        }

        $halaman->update($data);

        return redirect()->route('admin.profil-desa.halaman.index')
            ->with('success', 'Halaman profil berhasil diperbarui.');
    }

    public function destroy(Page $halaman)
    {
        if ($halaman->featured_image && Storage::disk('public')->exists($halaman->featured_image)) {
            Storage::disk('public')->delete($halaman->featured_image);
        }

        $halaman->delete();

        return redirect()->route('admin.profil-desa.halaman.index')
            ->with('success', 'Halaman profil berhasil dihapus.');
    }
}