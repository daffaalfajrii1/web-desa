<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'latest');

        $items = Agenda::with('author')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%')
                      ->orWhere('location', 'like', '%' . $search . '%')
                      ->orWhere('organizer', 'like', '%' . $search . '%')
                      ->orWhere('contact_person', 'like', '%' . $search . '%');
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
            case 'event_date_asc':
                $items->orderBy('start_date', 'asc');
                break;
            case 'event_date_desc':
                $items->orderBy('start_date', 'desc');
                break;
            case 'most_viewed':
                $items->orderBy('views', 'desc');
                break;
            default:
                $items->latest();
                break;
        }

        $items = $items->paginate(10)->withQueryString();

        return view('admin.agenda.index', compact('items', 'sort'));
    }

    public function create()
    {
        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:agendas,slug',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul agenda wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'featured_image.image' => 'Foto agenda harus berupa gambar.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('agendas', 'public');
        }

        Agenda::create($data);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function show(Agenda $agenda)
    {
        $agenda->load('author');

        return view('admin.agenda.show', [
            'item' => $agenda,
        ]);
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agenda.edit', [
            'item' => $agenda,
        ]);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:agendas,slug,' . $agenda->id,
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ], [
            'title.required' => 'Judul agenda wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'featured_image.image' => 'Foto agenda harus berupa gambar.',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            if ($agenda->featured_image && Storage::disk('public')->exists($agenda->featured_image)) {
                Storage::disk('public')->delete($agenda->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('agendas', 'public');
        }

        $agenda->update($data);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->featured_image && Storage::disk('public')->exists($agenda->featured_image)) {
            Storage::disk('public')->delete($agenda->featured_image);
        }

        $agenda->delete();

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}