<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\VillageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

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
            'marquee_text' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'theme_active' => ['nullable', Rule::in(['default', 'blue', 'earth'])],
            'logo_desa' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $theme = ($data['theme_active'] ?? null) ?: 'default';
        $data['active_theme'] = $theme;

        if (! Schema::hasColumn('village_settings', 'theme_active')) {
            unset($data['theme_active']);
        }

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
        $this->refreshBrowserFavicons($setting->fresh());

        return redirect()
            ->route('admin.settings.desa.edit')
            ->with('success', 'Identitas desa berhasil diperbarui.');
    }

    private function refreshBrowserFavicons(VillageSetting $setting): void
    {
        $rawPath = $setting->favicon ?: ($setting->logo_path ?: $setting->logo);
        if (! $rawPath) {
            return;
        }

        $binary = $this->readIconBinary($rawPath);
        if (! $binary) {
            return;
        }

        $source = @imagecreatefromstring($binary);
        if (! $source) {
            return;
        }

        try {
            $this->writeResizedPng($source, 32, public_path('favicon-32x32.png'));
            $this->writeResizedPng($source, 16, public_path('favicon-16x16.png'));
            $this->writeResizedPng($source, 180, public_path('apple-touch-icon.png'));
            $this->syncLegacyFaviconIco();
        } finally {
            imagedestroy($source);
        }
    }

    private function syncLegacyFaviconIco(): void
    {
        $png32 = public_path('favicon-32x32.png');
        $ico = public_path('favicon.ico');

        if (! is_file($png32)) {
            return;
        }

        $bytes = @file_get_contents($png32);
        if ($bytes === false) {
            return;
        }

        @file_put_contents($ico, $bytes);
    }

    private function readIconBinary(string $path): ?string
    {
        try {
            if (str_starts_with($path, '/storage/')) {
                $relative = ltrim(substr($path, strlen('/storage/')), '/');
                return Storage::disk('public')->exists($relative)
                    ? Storage::disk('public')->get($relative)
                    : null;
            }

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                $content = @file_get_contents($path);

                return $content !== false ? $content : null;
            }

            return Storage::disk('public')->exists($path)
                ? Storage::disk('public')->get($path)
                : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function writeResizedPng(\GdImage $source, int $size, string $targetPath): void
    {
        $canvas = imagecreatetruecolor($size, $size);
        if (! $canvas) {
            return;
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);

        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $size,
            $size,
            $srcWidth,
            $srcHeight
        );

        imagepng($canvas, $targetPath);
        imagedestroy($canvas);
    }
}
