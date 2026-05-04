<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class HolidayService
{
    public function forDate(Carbon|string $date): array
    {
        $date = Carbon::parse($date);
        $dateString = $date->toDateString();
        $holidays = collect($this->forYear((int) $date->year))->keyBy('date');

        if (! $holidays->has($dateString)) {
            return [
                'is_holiday' => false,
                'holiday_name' => null,
            ];
        }

        $holiday = $holidays->get($dateString);

        return [
            'is_holiday' => true,
            'holiday_name' => $holiday['name'],
        ];
    }

    public function forMonth(int $year, int $month): array
    {
        return collect($this->forYear($year))
            ->filter(fn (array $holiday) => (int) Carbon::parse($holiday['date'])->month === $month)
            ->sortBy('date')
            ->values()
            ->all();
    }

    public function forYear(int $year): array
    {
        $cacheKey = 'holiday_api_indonesia_year_' . $year;
        $cached = Cache::get($cacheKey);

        if (is_array($cached) && $cached !== []) {
            return $cached;
        }

        $holidays = $this->fetchHolidays($year);

        if ($holidays !== []) {
            Cache::put($cacheKey, $holidays, (int) config('services.holiday_api.cache_ttl', 86400));
        }

        return $holidays;
    }

    public function forgetYear(int $year): void
    {
        Cache::forget('holiday_api_indonesia_year_' . $year);
    }

    private function fetchHolidays(int $year): array
    {
        $holidays = [];

        foreach ($this->apiUrls($year) as $url) {
            try {
                $response = Http::timeout(6)
                    ->acceptJson()
                    ->get($url);

                if (! $response->successful()) {
                    continue;
                }

                $items = $this->normalizeItems($response->json());
                $dateKey = config('services.holiday_api.date_key', 'holiday_date');
                $nameKey = config('services.holiday_api.name_key', 'holiday_name');
                $nationalKey = config('services.holiday_api.national_key', 'is_national_holiday');

                foreach ($items as $item) {
                    if (! is_array($item)) {
                        continue;
                    }

                    $itemDate = data_get($item, $dateKey) ?? data_get($item, 'date') ?? data_get($item, 'tanggal');

                    if (! $itemDate) {
                        continue;
                    }

                    try {
                        $holidayDate = Carbon::parse($itemDate);
                    } catch (Throwable) {
                        continue;
                    }

                    if ((int) $holidayDate->year !== $year) {
                        continue;
                    }

                    if (array_key_exists($nationalKey, $item) && ! (bool) data_get($item, $nationalKey)) {
                        continue;
                    }

                    $holidays[$holidayDate->toDateString()] = [
                        'date' => $holidayDate->toDateString(),
                        'name' => data_get($item, $nameKey)
                            ?? data_get($item, 'name')
                            ?? data_get($item, 'description')
                            ?? data_get($item, 'summary')
                            ?? data_get($item, 'title')
                            ?? 'Hari Libur Nasional',
                        'source' => 'holiday_api',
                    ];
                }

                if ($holidays !== []) {
                    break;
                }
            } catch (Throwable) {
                continue;
            }
        }

        ksort($holidays);

        return array_values($holidays);
    }

    private function apiUrls(int $year): array
    {
        $urls = [
            (string) config('services.holiday_api.url'),
            ...((array) config('services.holiday_api.fallback_urls', [])),
        ];

        return collect($urls)
            ->filter()
            ->map(fn (string $url) => $this->apiUrl($url, $year))
            ->unique()
            ->values()
            ->all();
    }

    private function apiUrl(string $url, int $year): string
    {
        $url = trim($url);

        if (str_contains($url, '{year}')) {
            return str_replace('{year}', (string) $year, $url);
        }

        if (preg_match('/[?&]year=/', $url)) {
            return $url;
        }

        return $url . (str_contains($url, '?') ? '&' : '?') . 'year=' . $year;
    }

    private function normalizeItems(mixed $payload): array
    {
        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        if (isset($payload['holidays']) && is_array($payload['holidays'])) {
            return $payload['holidays'];
        }

        if (isset($payload['items']) && is_array($payload['items'])) {
            return $payload['items'];
        }

        if (isset($payload['results']) && is_array($payload['results'])) {
            return $payload['results'];
        }

        return $payload;
    }
}
