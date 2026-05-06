<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $items = Employee::with(['user', 'employeePosition'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('position', 'like', '%'.$search.'%')
                        ->orWhere('position_type', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%')
                        ->orWhereHas('employeePosition', fn ($positionQuery) => $positionQuery->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.pegawai.index', compact('items'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $positions = EmployeePosition::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.pegawai.create', compact('users', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'employee_position_id' => ['required', Rule::exists('employee_positions', 'id')->where(fn ($query) => $query->where('is_active', true))],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nip' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'pin_absensi' => ['required', 'string', 'min:4', 'max:20', Rule::unique('employees', 'pin_absensi')],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data = $this->applyPositionFields($data);
        $this->releaseUserFromOtherEmployee($data['user_id'] ?? null);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function show(Employee $pegawai)
    {
        $pegawai->load(['user', 'employeePosition']);

        return view('admin.pegawai.show', ['item' => $pegawai]);
    }

    public function edit(Employee $pegawai)
    {
        $users = User::orderBy('name')->get();
        $positions = EmployeePosition::query()
            ->where(function ($query) use ($pegawai) {
                $query->where('is_active', true)
                    ->when($pegawai->employee_position_id, fn ($subQuery) => $subQuery->orWhere('id', $pegawai->employee_position_id));
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.pegawai.edit', [
            'item' => $pegawai,
            'users' => $users,
            'positions' => $positions,
        ]);
    }

    public function update(Request $request, Employee $pegawai)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'employee_position_id' => 'required|exists:employee_positions,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nip' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'pin_absensi' => ['required', 'string', 'min:4', 'max:20', Rule::unique('employees', 'pin_absensi')->ignore($pegawai->id)],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data = $this->applyPositionFields($data);
        $this->releaseUserFromOtherEmployee($data['user_id'] ?? null, $pegawai->id);

        if ($request->hasFile('photo')) {
            if ($pegawai->photo && Storage::disk('public')->exists($pegawai->photo)) {
                Storage::disk('public')->delete($pegawai->photo);
            }

            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $pegawai)
    {
        if ($pegawai->photo && Storage::disk('public')->exists($pegawai->photo)) {
            Storage::disk('public')->delete($pegawai->photo);
        }

        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    private function applyPositionFields(array $data): array
    {
        $position = EmployeePosition::query()->findOrFail($data['employee_position_id']);

        $data['position'] = $position->name;
        $data['position_type'] = $position->position_type;

        return $data;
    }

    private function releaseUserFromOtherEmployee(?int $userId, ?int $exceptEmployeeId = null): void
    {
        if (! $userId) {
            return;
        }

        Employee::query()
            ->where('user_id', $userId)
            ->when($exceptEmployeeId, fn ($query) => $query->where('id', '!=', $exceptEmployeeId))
            ->update(['user_id' => null]);
    }
}
