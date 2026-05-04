<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfService;
use Illuminate\Http\Request;

class SelfServiceController extends Controller
{
    public function index(Request $request)
    {
        $items = SelfService::withCount([
            'fields',
            'submissions',
            'submissions as pending_submissions_count' => fn ($q) => $q->whereIn('status', ['masuk', 'diproses']),
        ])
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('service_name', 'like', '%' . $search . '%')
                        ->orWhere('service_code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('sort_order')
            ->orderBy('service_name')
            ->paginate(9)
            ->withQueryString();

        return view('admin.layanan-mandiri.index', compact('items'));
    }

    public function create()
    {
        return view('admin.layanan-mandiri.create', [
            'item' => null,
            'nextCode' => SelfService::generateCode(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        SelfService::create($data);

        return redirect()
            ->route('admin.layanan-mandiri.index')
            ->with('success', 'Layanan mandiri berhasil ditambahkan.');
    }

    public function edit(SelfService $self_service)
    {
        return view('admin.layanan-mandiri.edit', ['item' => $self_service]);
    }

    public function update(Request $request, SelfService $self_service)
    {
        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $self_service->update($data);

        return redirect()
            ->route('admin.layanan-mandiri.index')
            ->with('success', 'Layanan mandiri berhasil diperbarui.');
    }

    public function destroy(SelfService $self_service)
    {
        $self_service->delete();

        return redirect()
            ->route('admin.layanan-mandiri.index')
            ->with('success', 'Layanan mandiri berhasil dihapus.');
    }
}
