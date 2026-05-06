<?php

namespace App\Services\Public;

use App\Models\VillageSetting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ThemeService
{
    public const AVAILABLE_THEMES = ['default', 'blue', 'earth'];

    private const COMPLETE_THEMES = ['default'];

    public function resolve(?VillageSetting $setting = null): array
    {
        $setting ??= $this->villageSetting();

        $requested = $this->normalizeTheme($this->requestedTheme($setting));
        $active = in_array($requested, self::COMPLETE_THEMES, true) ? $requested : 'default';

        return [
            'requested' => $requested,
            'active' => $active,
            'class' => 'theme-'.$active,
            'choice_class' => 'theme-choice-'.$requested.($requested !== $active ? ' theme-'.$requested.' theme-fallback-default' : ''),
            'is_fallback' => $requested !== $active,
            'available' => self::AVAILABLE_THEMES,
        ];
    }

    public function villageSetting(): ?VillageSetting
    {
        try {
            if (! Schema::hasTable('village_settings')) {
                return null;
            }

            return VillageSetting::query()->first();
        } catch (Throwable) {
            return null;
        }
    }

    private function requestedTheme(?VillageSetting $setting): string
    {
        if (! $setting) {
            return 'default';
        }

        $attributes = $setting->getAttributes();

        return ($attributes['theme_active'] ?? null)
            ?: ($attributes['active_theme'] ?? null)
            ?: 'default';
    }

    private function normalizeTheme(?string $theme): string
    {
        $theme = strtolower(trim((string) $theme));

        return in_array($theme, self::AVAILABLE_THEMES, true) ? $theme : 'default';
    }
}
