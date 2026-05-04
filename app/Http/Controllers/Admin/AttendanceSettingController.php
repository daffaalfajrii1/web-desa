<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceSettingController extends Controller
{
    public function edit()
    {
        return view('admin.absensi.settings.edit', [
            'setting' => AttendanceSetting::current(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'check_in_start' => 'required|date_format:H:i',
            'check_in_end' => 'required|date_format:H:i',
            'check_out_start' => 'required|date_format:H:i',
            'check_out_end' => 'required|date_format:H:i',
            'office_latitude' => 'nullable|numeric|between:-90,90',
            'office_longitude' => 'nullable|numeric|between:-180,180',
            'allowed_radius_meter' => 'required|integer|min:1|max:100000',
            'validate_location' => 'nullable|boolean',
            'use_holiday_api' => 'nullable|boolean',
            'disable_saturday_attendance' => 'nullable|boolean',
            'disable_sunday_attendance' => 'nullable|boolean',
        ]);

        $data['validate_location'] = $request->boolean('validate_location');
        $data['use_holiday_api'] = $request->boolean('use_holiday_api');
        $data['disable_saturday_attendance'] = $request->boolean('disable_saturday_attendance');
        $data['disable_sunday_attendance'] = $request->boolean('disable_sunday_attendance');
        $data['office_latitude'] = $data['office_latitude'] ?? null;
        $data['office_longitude'] = $data['office_longitude'] ?? null;

        if ($data['validate_location'] && ($data['office_latitude'] === null || $data['office_longitude'] === null)) {
            throw ValidationException::withMessages([
                'office_latitude' => 'Latitude dan longitude kantor wajib diisi saat validasi lokasi aktif.',
            ]);
        }

        AttendanceSetting::current()->update($data);

        return redirect()
            ->route('admin.absensi.settings.edit')
            ->with('success', 'Setting absensi berhasil diperbarui.');
    }
}
