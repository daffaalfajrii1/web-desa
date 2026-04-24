<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicInformationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'latest');

        $items = PublicInformation::with('author')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
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

        return view('admin.informasi-publik.index', compact('items', 'sort'));
    }

    public function create()
    {
        return view('admin.informasi-publik.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:public_informations,slug',
            'description' => 'nullable|string',
            'file_path' => 'required|file|mimes:pdf|max:5120',
            'status' => 'required|in:draft,published',
            'published_date' => 'required|date',
        ], [
            'title.required' => 'Judul informasi publik wajib diisi.',
            'file_path.required' => 'File PDF wajib diupload.',
            'file_path.mimes' => 'File harus berformat PDF.',
            'file_path.max' => 'Ukuran file maksimal 5 MB.',
            'published_date.required' => 'Tanggal publikasi wajib diisi.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('public-informations', 'public');
        }

        PublicInformation::create($data);

        return redirect()->route('admin.informasi-publik.index')
            ->with('success', 'Informasi publik berhasil ditambahkan.');
    }

    public function show(PublicInformation $informasi_publik)
    {
        $informasi_publik->load('author');

        return view('admin.informasi-publik.show', [
            'item' => $informasi_publik,
        ]);
    }

    public function edit(PublicInformation $informasi_publik)
    {
        return view('admin.informasi-publik.edit', [
            'item' => $informasi_publik,
        ]);
    }

    public function update(Request $request, PublicInformation $informasi_publik)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:public_informations,slug,' . $informasi_publik->id,
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:draft,published',
            'published_date' => 'required|date',
        ], [
            'title.required' => 'Judul informasi publik wajib diisi.',
            'file_path.mimes' => 'File harus berformat PDF.',
            'file_path.max' => 'Ukuran file maksimal 5 MB.',
            'published_date.required' => 'Tanggal publikasi wajib diisi.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('file_path')) {
            if ($informasi_publik->file_path && Storage::disk('public')->exists($informasi_publik->file_path)) {
                Storage::disk('public')->delete($informasi_publik->file_path);
            }

            $data['file_path'] = $request->file('file_path')->store('public-informations', 'public');
        }

        $informasi_publik->update($data);

        return redirect()->route('admin.informasi-publik.index')
            ->with('success', 'Informasi publik berhasil diperbarui.');
    }

    public function destroy(PublicInformation $informasi_publik)
    {
        if ($informasi_publik->file_path && Storage::disk('public')->exists($informasi_publik->file_path)) {
            Storage::disk('public')->delete($informasi_publik->file_path);
        }

        $informasi_publik->delete();

        return redirect()->route('admin.informasi-publik.index')
            ->with('success', 'Informasi publik berhasil dihapus.');
    }
}