<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageSetting;
use Illuminate\Http\Request;

class VillageSettingController extends Controller
{
    public function edit()
    {
        $setting = VillageSetting::firstOrCreate([]);

        return view('admin.settings.village.edit', compact('setting'));
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
            'head_name' => 'nullable|string|max:255',
            'head_position' => 'nullable|string|max:255',
            'welcome_message' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'active_theme' => 'nullable|string|max:100',
        ]);

        $setting->update($data);

        return redirect()
    ->route('admin.settings.desa.edit')
    ->with('success', 'Identitas desa berhasil diperbarui.');
    }
}