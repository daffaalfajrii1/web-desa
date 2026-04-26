<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidSection;
use Illuminate\Http\Request;

class PpidSectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $items = PpidSection::withCount('documents')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('type')
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ppid-section.index', compact('items'));
    }

    public function create()
    {
        return view('admin.ppid-section.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:berkala,serta_merta,setiap_saat',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = auth()->id();

        PpidSection::create($data);

        return redirect()->route('admin.ppid-section.index')
            ->with('success', 'Section PPID berhasil ditambahkan.');
    }

    public function show(PpidSection $ppid_section)
    {
        $ppid_section->load('documents');

        return view('admin.ppid-section.show', ['item' => $ppid_section]);
    }

    public function edit(PpidSection $ppid_section)
    {
        return view('admin.ppid-section.edit', ['item' => $ppid_section]);
    }

    public function update(Request $request, PpidSection $ppid_section)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:berkala,serta_merta,setiap_saat',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $ppid_section->update($data);

        return redirect()->route('admin.ppid-section.index')
            ->with('success', 'Section PPID berhasil diperbarui.');
    }

    public function destroy(PpidSection $ppid_section)
    {
        $ppid_section->delete();

        return redirect()->route('admin.ppid-section.index')
            ->with('success', 'Section PPID berhasil dihapus.');
    }
}