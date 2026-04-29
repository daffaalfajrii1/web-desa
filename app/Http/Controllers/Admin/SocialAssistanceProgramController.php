<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAssistanceProgram;
use Illuminate\Http\Request;

class SocialAssistanceProgramController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year');
        $search = $request->get('search');

        $items = SocialAssistanceProgram::withCount('recipients')
            ->when($year, fn ($q) => $q->where('year', $year))
            ->when($search, fn ($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->orderByDesc('year')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.bansos-program.index', compact('items'));
    }

    public function create()
    {
        return view('admin.infografis.bansos-program.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|digits:4',
            'period' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quota' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        SocialAssistanceProgram::create($data);

        return redirect()->route('admin.bansos-program.index')->with('success', 'Program bansos berhasil ditambahkan.');
    }

    public function edit(SocialAssistanceProgram $bansos_program)
    {
        return view('admin.infografis.bansos-program.edit', ['item' => $bansos_program]);
    }

    public function update(Request $request, SocialAssistanceProgram $bansos_program)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|digits:4',
            'period' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quota' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $bansos_program->update($data);

        return redirect()->route('admin.bansos-program.index')->with('success', 'Program bansos berhasil diperbarui.');
    }

    public function destroy(SocialAssistanceProgram $bansos_program)
    {
        $bansos_program->delete();

        return redirect()->route('admin.bansos-program.index')->with('success', 'Program bansos berhasil dihapus.');
    }
}