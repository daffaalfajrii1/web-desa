<?php

namespace App\View\Composers;

use App\Models\Page;
use App\Models\ProfileMenu;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicFrontendComposer
{
    public function compose(View $view): void
    {
        $menus = collect();
        $pages = collect();
        $orphanPages = collect();

        try {
            if (Schema::hasTable('profile_menus')) {
                $menus = ProfileMenu::query()
                    ->with('page')
                    ->where('is_active', true)
                    ->whereHas('page', fn ($query) => $query->where('status', 'published'))
                    ->orderBy('sort_order')
                    ->get();
            }

            if (Schema::hasTable('pages')) {
                $pages = Page::query()
                    ->where('status', 'published')
                    ->orderBy('title')
                    ->get();

                $linkedPageIds = $menus->pluck('page_id')->filter()->unique()->all();
                $orphanPages = Page::query()
                    ->where('status', 'published')
                    ->when(count($linkedPageIds) > 0, fn ($query) => $query->whereNotIn('id', $linkedPageIds))
                    ->orderBy('title')
                    ->get();
            }
        } catch (\Throwable) {
            $menus = collect();
            $pages = collect();
            $orphanPages = collect();
        }

        $view->with([
            'profileNavMenus' => $menus,
            'profileNavPages' => $pages,
            'profileNavOrphanPages' => $orphanPages,
        ]);
    }
}
