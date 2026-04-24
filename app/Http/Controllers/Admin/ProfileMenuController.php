<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\ProfileMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileMenuController extends Controller
{
    public function index()
    {
        $menus = ProfileMenu::with('page')->orderBy('sort_order')->paginate(10);

        return view('admin.profil-desa.menu.index', compact('menus'));
    }

    public function create()
    {
        $pages = Page::orderBy('title')->get();

        return view('admin.profil-desa.menu.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:profile_menus,slug',
            'page_id' => 'nullable|exists:pages,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        ProfileMenu::create($data);

        return redirect()->route('admin.profil-desa.menu.index')
            ->with('success', 'Menu profil berhasil ditambahkan.');
    }

    public function show(ProfileMenu $menu)
    {
        return view('admin.profil-desa.menu.show', compact('menu'));
    }

    public function edit(ProfileMenu $menu)
    {
        $pages = Page::orderBy('title')->get();

        return view('admin.profil-desa.menu.edit', compact('menu', 'pages'));
    }

    public function update(Request $request, ProfileMenu $menu)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:profile_menus,slug,' . $menu->id,
            'page_id' => 'nullable|exists:pages,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        $menu->update($data);

        return redirect()->route('admin.profil-desa.menu.index')
            ->with('success', 'Menu profil berhasil diperbarui.');
    }

    public function destroy(ProfileMenu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.profil-desa.menu.index')
            ->with('success', 'Menu profil berhasil dihapus.');
    }
}