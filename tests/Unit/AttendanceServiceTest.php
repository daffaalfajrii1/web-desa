<?php

namespace Tests\Unit;

use App\Models\AttendanceSetting;
use App\Services\AttendanceService;
use App\Services\HolidayService;
use App\Services\LocationService;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    public function test_saturday_is_holiday_when_saturday_attendance_is_disabled(): void
    {
        $service = new AttendanceService(new HolidayService, new LocationService);
        $setting = new AttendanceSetting([
            'use_holiday_api' => false,
            'disable_saturday_attendance' => true,
            'disable_sunday_attendance' => true,
        ]);

        $dayType = $service->dayType($setting, '2026-05-02');

        $this->assertTrue($dayType['is_holiday']);
        $this->assertSame('Hari Sabtu', $dayType['holiday_name']);
        $this->assertSame('saturday', $dayType['source']);
    }

    public function test_weekday_is_not_holiday_when_api_is_disabled(): void
    {
        $service = new AttendanceService(new HolidayService, new LocationService);
        $setting = new AttendanceSetting([
            'use_holiday_api' => false,
            'disable_saturday_attendance' => true,
            'disable_sunday_attendance' => true,
        ]);

        $dayType = $service->dayType($setting, '2026-05-04');

        $this->assertFalse($dayType['is_holiday']);
        $this->assertNull($dayType['holiday_name']);
        $this->assertNull($dayType['source']);
    }
}
