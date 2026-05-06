<?php

namespace App\Http\Controllers;

use App\Models\VillageSetting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SiteFaviconController extends Controller
{
    /**
     * Serve /favicon.ico from identitas desa (favicon, lalu logo) agar ikon tab browser konsisten.
     */
    public function __invoke(): BinaryFileResponse|RedirectResponse|Response
    {
        if (! Schema::hasTable('village_settings')) {
            return response()->noContent(404);
        }

        $village = VillageSetting::query()->first();
        $raw = $village ? ($village->favicon ?: ($village->logo_path ?: $village->logo)) : null;

        if (! $raw) {
            return response()->noContent(404);
        }

        if (Str::startsWith($raw, ['http://', 'https://'])) {
            return redirect()->away($raw);
        }

        if (Str::startsWith($raw, '/storage/')) {
            $relative = Str::after($raw, '/storage/');
            if ($relative !== '' && Storage::disk('public')->exists($relative)) {
                return Storage::disk('public')->response($relative, null, [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }

            $publicStoragePath = public_path('storage/'.ltrim($relative, '/'));
            if ($relative !== '' && is_file($publicStoragePath)) {
                return response()->file($publicStoragePath, [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }

            return response()->noContent(404);
        }

        if (Storage::disk('public')->exists($raw)) {
            return Storage::disk('public')->response($raw, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        $publicStoragePath = public_path('storage/'.ltrim($raw, '/'));
        if (is_file($publicStoragePath)) {
            return response()->file($publicStoragePath, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response()->noContent(404);
    }
}
