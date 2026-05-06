<?php

namespace App\Http\Controllers\Public;

use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\Page;
use App\Models\ProfileMenu;
use App\Services\Public\ThemeService;
use Illuminate\Support\Facades\Schema;

class ProfileController extends BasePublicController
{
    public function index(ThemeService $themeService)
    {
        $village = $themeService->villageSetting();
        if ($village) {
            $village->load('villageHeadEmployee');
        }
        $menus = $this->fromTable('profile_menus', fn () => ProfileMenu::query()
            ->with('page')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(), collect());
        $pages = $this->fromTable('pages', fn () => Page::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->orderBy('title')
            ->get(), collect());

        return view('public.pages.profile', compact('village', 'menus', 'pages'));
    }

    public function menu(ProfileMenu $profileMenu, ThemeService $themeService)
    {
        if (! $profileMenu->is_active) {
            abort(404);
        }

        $village = $themeService->villageSetting();
        $page = $profileMenu->page;

        if (! $page || ($page->status ?? '') !== 'published') {
            abort(404);
        }

        $featured = $this->imageUrl($page->featured_image ?? null);

        return view('public.pages.profile-detail', [
            'village' => $village,
            'profileMenu' => $profileMenu,
            'profileLabel' => $profileMenu->title,
            'page' => $page,
            'featuredImage' => $featured,
        ]);
    }

    public function page(Page $page, ThemeService $themeService)
    {
        abort_unless(($page->status ?? '') === 'published', 404);

        return view('public.pages.profile-detail', [
            'village' => $themeService->villageSetting(),
            'profileMenu' => null,
            'profileLabel' => $page->title,
            'page' => $page,
            'featuredImage' => $this->imageUrl($page->featured_image ?? null),
        ]);
    }

    public function map(ThemeService $themeService)
    {
        return view('public.pages.map', [
            'village' => $themeService->villageSetting(),
        ]);
    }

    public function structure(ThemeService $themeService)
    {
        $village = $themeService->villageSetting();

        $positions = $this->fromTable('employee_positions', function () {
            return EmployeePosition::query()
                ->where('is_active', true)
                ->with(['employees' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        }, collect());

        $unassigned = $this->fromTable('employees', function () {
            $query = Employee::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name');

            if (Schema::hasColumn('employees', 'employee_position_id')) {
                $query->whereNull('employee_position_id');
            } else {
                return collect();
            }

            return $query->get();
        }, collect());

        return view('public.pages.structure', [
            'village' => $village,
            'positions' => $positions,
            'unassigned' => $unassigned,
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }
}
