<?php

namespace App\Http\Controllers\Public;

use App\Models\SelfService;
use App\Services\Public\ThemeService;

class LayananController extends BasePublicController
{
    public function index(ThemeService $themeService)
    {
        $village = $themeService->villageSetting();
        $search = trim((string) request('q', ''));

        $mandiri = $this->fromTable('self_services', fn () => SelfService::query()
            ->where('is_active', true)
            ->when($search !== '', fn ($query) => $query->where(function ($subQuery) use ($search) {
                $subQuery->where('service_name', 'like', '%'.$search.'%')
                    ->orWhere('service_code', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('requirements', 'like', '%'.$search.'%');
            }))
            ->withCount('fields')
            ->orderBy('sort_order')
            ->orderBy('service_name')
            ->get(), collect());

        $grouped = [
            'mandiri' => $mandiri,
        ];

        return view('public.layanan.index', [
            'village' => $village,
            'grouped' => $grouped,
            'mandiriCount' => $mandiri->count(),
            'search' => $search,
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }
}
