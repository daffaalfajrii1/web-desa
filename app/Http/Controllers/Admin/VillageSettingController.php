<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\VillageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VillageSettingController extends Controller
{
    public function edit()
    {
        $setting = VillageSetting::with('villageHeadEmployee')->firstOrCreate([]);
        $employees = Employee::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.settings.village.edit', compact('setting', 'employees'));
    }

    public function update(Request $request)
    {
        $setting = VillageSetting::firstOrCreate([]);

        $data = $request->validate([
            'village_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',
            'regency_name' => 'nullable|string|max:255',
            'province_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'village_head_employee_id' => [
                'nullable',
                Rule::exists('employees', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'village_head_name_manual' => 'nullable|string|max:255',
            'welcome_message' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'active_theme' => 'nullable|string|max:100',
            'logo_desa' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['village_head_employee_id'] = $data['village_head_employee_id'] ?: null;
        $data['village_head_name_manual'] = $data['village_head_name_manual'] ?: null;
        $data['head_position'] = null;
        $data['head_name'] = $data['village_head_employee_id']
            ? Employee::query()->whereKey($data['village_head_employee_id'])->value('name')
            : $data['village_head_name_manual'];

        unset($data['logo_desa']);

        if ($request->hasFile('logo_desa')) {
            $oldLogoPath = $setting->logo_path;
            $data['logo_path'] = $request->file('logo_desa')->store('village/logo', 'public');

            if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        }

        $setting->update($data);

        return redirect()
            ->route('admin.settings.desa.edit')
            ->with('success', 'Identitas desa berhasil diperbarui.');
    }
}
