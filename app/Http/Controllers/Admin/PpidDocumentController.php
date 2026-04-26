<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidDocument;
use App\Models\PpidSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpidDocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');

        $sections = PpidSection::orderBy('title')->get();

        $items = PpidDocument::with('section')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($type, function ($query) use ($type) {
                $query->whereHas('section', function ($q) use ($type) {
                    $q->where('type', $type);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.ppid-document.index', compact('items', 'sections'));
    }

    public function create(Request $request)
{
    $sections = PpidSection::where('is_active', true)
        ->orderBy('type')
        ->orderBy('sort_order')
        ->get();

    $selectedSectionId = $request->get('section_id');

    return view('admin.ppid-document.create', compact('sections', 'selectedSectionId'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'ppid_section_id' => 'required|exists:ppid_sections,id',
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:pdf|max:5120',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('ppid-documents', 'public');
        }

        PpidDocument::create($data);

        return redirect()->route('admin.ppid-document.index')
            ->with('success', 'Dokumen PPID berhasil ditambahkan.');
    }

    public function show(PpidDocument $ppid_document)
    {
        $ppid_document->load('section');

        return view('admin.ppid-document.show', ['item' => $ppid_document]);
    }

    public function edit(PpidDocument $ppid_document)
    {
        $sections = PpidSection::orderBy('type')->orderBy('sort_order')->get();

        return view('admin.ppid-document.edit', [
            'item' => $ppid_document,
            'sections' => $sections,
        ]);
    }

    public function update(Request $request, PpidDocument $ppid_document)
    {
        $data = $request->validate([
            'ppid_section_id' => 'required|exists:ppid_sections,id',
            'title' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('file_path')) {
            if ($ppid_document->file_path && Storage::disk('public')->exists($ppid_document->file_path)) {
                Storage::disk('public')->delete($ppid_document->file_path);
            }

            $data['file_path'] = $request->file('file_path')->store('ppid-documents', 'public');
        }

        $ppid_document->update($data);

        return redirect()->route('admin.ppid-document.index')
            ->with('success', 'Dokumen PPID berhasil diperbarui.');
    }

    public function destroy(PpidDocument $ppid_document)
    {
        if ($ppid_document->file_path && Storage::disk('public')->exists($ppid_document->file_path)) {
            Storage::disk('public')->delete($ppid_document->file_path);
        }

        $ppid_document->delete();

        return redirect()->route('admin.ppid-document.index')
            ->with('success', 'Dokumen PPID berhasil dihapus.');
    }
}