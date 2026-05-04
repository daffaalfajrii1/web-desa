<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use App\Services\HolidayService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AttendanceHolidayController extends Controller
{
    public function index(Request $request, HolidayService $holidayService)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $setting = AttendanceSetting::current();

        if ($request->boolean('refresh')) {
            $holidayService->forgetYear($year);
        }

        $holidays = collect($setting->use_holiday_api ? $holidayService->forMonth($year, $month) : [])
            ->mapWithKeys(fn (array $holiday) => [
                $holiday['date'] => [
                    'date' => $holiday['date'],
                    'name' => $holiday['name'],
                    'source' => 'API Hari Libur Nasional',
                ],
            ]);

        if ($setting->disable_saturday_attendance || $setting->disable_sunday_attendance) {
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = $start->copy()->endOfMonth();

            foreach (CarbonPeriod::create($start, $end) as $date) {
                $isSaturdayHoliday = $setting->disable_saturday_attendance && $date->isSaturday();
                $isSundayHoliday = $setting->disable_sunday_attendance && $date->isSunday();

                if (! $isSaturdayHoliday && ! $isSundayHoliday) {
                    continue;
                }

                $dateString = $date->toDateString();
                $existing = $holidays->get($dateString);

                if ($existing) {
                    $existing['source'] = collect(explode(', ', $existing['source']))
                        ->push('Akhir Pekan')
                        ->unique()
                        ->implode(', ');

                    $holidays->put($dateString, $existing);

                    continue;
                }

                $holidays->put($dateString, [
                    'date' => $dateString,
                    'name' => $date->isSaturday() ? 'Hari Sabtu' : 'Hari Minggu',
                    'source' => 'Akhir Pekan',
                ]);
            }
        }

        return view('admin.absensi.holidays.index', [
            'holidays' => $holidays->sortBy('date')->values(),
            'month' => $month,
            'year' => $year,
            'setting' => $setting,
            'apiRefreshed' => $request->boolean('refresh'),
        ]);
    }
}
