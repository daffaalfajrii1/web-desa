<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePosition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeePositionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $items = EmployeePosition::withCount('employees')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('position_type', 'like', '%'.$search.'%');
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jabatan-sotk.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jabatan-sotk.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:employee_positions,name',
            'slug' => 'nullable|string|max:255|unique:employee_positions,slug',
            'position_type' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: $this->makeUniqueSlug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        EmployeePosition::create($data);

        return redirect()->route('admin.jabatan-sotk.index')
            ->with('success', 'Jabatan SOTK berhasil ditambahkan.');
    }

    public function edit(EmployeePosition $employee_position)
    {
        return view('admin.jabatan-sotk.edit', ['item' => $employee_position]);
    }

    public function update(Request $request, EmployeePosition $employee_position)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:employee_positions,name,'.$employee_position->id,
            'slug' => 'nullable|string|max:255|unique:employee_positions,slug,'.$employee_position->id,
            'position_type' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = $data['slug'] ?: $this->makeUniqueSlug($data['name'], $employee_position->id);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $employee_position->update($data);

        $employee_position->employees()->update([
            'position' => $employee_position->name,
            'position_type' => $employee_position->position_type,
        ]);

        return redirect()->route('admin.jabatan-sotk.index')
            ->with('success', 'Jabatan SOTK berhasil diperbarui.');
    }

    public function destroy(EmployeePosition $employee_position)
    {
        if ($employee_position->employees()->exists()) {
            return redirect()->route('admin.jabatan-sotk.index')
                ->with('error', 'Jabatan masih dipakai pegawai. Nonaktifkan jabatan atau pindahkan pegawai lebih dulu.');
        }

        $employee_position->delete();

        return redirect()->route('admin.jabatan-sotk.index')
            ->with('success', 'Jabatan SOTK berhasil dihapus.');
    }

    private function makeUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (
            EmployeePosition::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
