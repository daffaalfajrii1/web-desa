<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
                    if (Schema::hasColumn('self_services', 'slug')) {
                        $query->orWhere('slug', 'like', '%' . $search . '%');
                    }
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
        $this->mergeNormalizedSlug($request);

        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('self_services', 'slug'),
            ],
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = $data['slug'] !== null ? (string) $data['slug'] : null;

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
        $this->mergeNormalizedSlug($request);

        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('self_services', 'slug')->ignore($self_service->id),
            ],
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = $data['slug'] !== null ? (string) $data['slug'] : null;

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

    private function mergeNormalizedSlug(Request $request): void
    {
        $raw = trim((string) $request->input('slug', ''));
        if ($raw === '') {
            $request->merge(['slug' => null]);

            return;
        }

        $normalized = Str::slug($raw);
        $request->merge(['slug' => $normalized !== '' ? $normalized : null]);
    }
}
