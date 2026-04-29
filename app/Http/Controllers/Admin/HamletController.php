<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use Illuminate\Http\Request;

class HamletController extends Controller
{
    public function index()
    {
        $items = Hamlet::orderBy('sort_order')->orderBy('name')->paginate(10);

        return view('admin.infografis.hamlets.index', compact('items'));
    }

    public function create()
    {
        return view('admin.infografis.hamlets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Hamlet::create($data);

        return redirect()->route('admin.hamlets.index')
            ->with('success', 'Dusun berhasil ditambahkan.');
    }

    public function edit(Hamlet $hamlet)
    {
        return view('admin.infografis.hamlets.edit', compact('hamlet'));
    }

    public function update(Request $request, Hamlet $hamlet)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $hamlet->update($data);

        return redirect()->route('admin.hamlets.index')
            ->with('success', 'Dusun berhasil diperbarui.');
    }

    public function destroy(Hamlet $hamlet)
    {
        $hamlet->delete();

        return redirect()->route('admin.hamlets.index')
            ->with('success', 'Dusun berhasil dihapus.');
    }
}