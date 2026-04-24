<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'latest');

        $items = Announcement::with('author')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%')
                      ->orWhere('excerpt', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
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
            default:
                $items->latest();
                break;
        }

        $items = $items->paginate(10)->withQueryString();

        return view('admin.pengumuman.index', compact('items', 'sort'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:announcements,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul pengumuman wajib diisi.',
            'featured_image.image' => 'Featured image harus berupa gambar.',
            'attachment.mimes' => 'Lampiran harus berformat PDF.',
            'attachment.max' => 'Ukuran lampiran maksimal 5 MB.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('announcements', 'public');
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Announcement $pengumuman)
    {
        $pengumuman->load('author');

        return view('admin.pengumuman.show', [
            'item' => $pengumuman,
        ]);
    }

    public function edit(Announcement $pengumuman)
    {
        return view('admin.pengumuman.edit', [
            'item' => $pengumuman,
        ]);
    }

    public function update(Request $request, Announcement $pengumuman)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:announcements,slug,' . $pengumuman->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul pengumuman wajib diisi.',
            'featured_image.image' => 'Featured image harus berupa gambar.',
            'attachment.mimes' => 'Lampiran harus berformat PDF.',
            'attachment.max' => 'Ukuran lampiran maksimal 5 MB.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            if ($pengumuman->featured_image && Storage::disk('public')->exists($pengumuman->featured_image)) {
                Storage::disk('public')->delete($pengumuman->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('announcements', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($pengumuman->attachment && Storage::disk('public')->exists($pengumuman->attachment)) {
                Storage::disk('public')->delete($pengumuman->attachment);
            }

            $data['attachment'] = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $pengumuman)
    {
        if ($pengumuman->featured_image && Storage::disk('public')->exists($pengumuman->featured_image)) {
            Storage::disk('public')->delete($pengumuman->featured_image);
        }

        if ($pengumuman->attachment && Storage::disk('public')->exists($pengumuman->attachment)) {
            Storage::disk('public')->delete($pengumuman->attachment);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}