<?php

namespace Tests\Unit;

use App\Services\HolidayService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HolidayServiceTest extends TestCase
{
    public function test_for_year_appends_year_and_reads_description_payload(): void
    {
        Cache::forget('holiday_api_indonesia_year_2026');

        config([
            'services.holiday_api.url' => 'https://example.test/api',
            'services.holiday_api.fallback_urls' => [],
        ]);

        Http::fake([
            'https://example.test/api?year=2026' => Http::response([
                'data' => [
                    [
                        'date' => '2026-05-01',
                        'description' => 'Hari Buruh Internasional',
                    ],
                ],
            ]),
        ]);

        $holidays = (new HolidayService)->forYear(2026);

        $this->assertSame([
            [
                'date' => '2026-05-01',
                'name' => 'Hari Buruh Internasional',
                'source' => 'holiday_api',
            ],
        ], $holidays);

        Http::assertSent(fn ($request) => $request->url() === 'https://example.test/api?year=2026');
    }
}
