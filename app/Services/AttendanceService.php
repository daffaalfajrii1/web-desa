<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use Carbon\Carbon;
use DateTimeInterface;
use InvalidArgumentException;

class AttendanceService
{
    public function __construct(
        private readonly HolidayService $holidayService,
        private readonly LocationService $locationService
    ) {}

    public function checkIn(Employee $employee, string $pin, ?float $latitude = null, ?float $longitude = null, string $source = 'admin'): array
    {
        $this->assertEmployeeCanAttend($employee, $pin);

        $setting = AttendanceSetting::current();
        $now = now();
        $date = $now->toDateString();
        $dayType = $this->dayType($setting, $now);

        if ($dayType['is_holiday']) {
            $attendance = $this->markHoliday($employee, $now, $dayType);

            return [
                'attendance' => $attendance,
                'message' => 'Hari ini libur. Absensi ditandai sebagai libur.',
            ];
        }

        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', $date)
            ->first();

        if ($attendance?->check_in_time) {
            throw new InvalidArgumentException('Pegawai sudah check in hari ini.');
        }

        if ($this->isBeforeTime($now, $setting->check_in_start)) {
            throw new InvalidArgumentException('Belum masuk rentang check in.');
        }

        $location = $this->locationService->validate($latitude, $longitude, $setting);

        if (! $location['allowed']) {
            throw new InvalidArgumentException($location['message']);
        }

        $status = $this->isAfterTime($now, $setting->check_in_end)
            ? Attendance::STATUS_TELAT
            : Attendance::STATUS_HADIR;

        $attendance = Attendance::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'attendance_date' => $date,
            ],
            [
                'check_in_time' => $now->format('H:i:s'),
                'status' => $status,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'distance_meter' => $location['distance_meter'],
                'is_holiday' => false,
                'holiday_name' => null,
                'source' => $source,
            ]
        );

        return [
            'attendance' => $attendance,
            'message' => 'Check in berhasil disimpan.',
        ];
    }

    public function checkOut(Employee $employee, string $pin, ?float $latitude = null, ?float $longitude = null, string $source = 'admin'): array
    {
        $this->assertEmployeeCanAttend($employee, $pin);

        $setting = AttendanceSetting::current();
        $now = now();
        $date = $now->toDateString();
        $dayType = $this->dayType($setting, $now);

        if ($dayType['is_holiday']) {
            throw new InvalidArgumentException('Hari ini libur. Check out tidak dapat dilakukan.');
        }

        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('attendance_date', $date)
            ->first();

        if (! $attendance || ! $attendance->check_in_time) {
            throw new InvalidArgumentException('Pegawai belum check in hari ini.');
        }

        if ($attendance->check_out_time) {
            throw new InvalidArgumentException('Pegawai sudah check out hari ini.');
        }

        if (! $this->isInTimeRange($now, $setting->check_out_start, $setting->check_out_end)) {
            throw new InvalidArgumentException('Belum masuk rentang check out.');
        }

        $location = $this->locationService->validate($latitude, $longitude, $setting);

        if (! $location['allowed']) {
            throw new InvalidArgumentException($location['message']);
        }

        $attendance->update([
            'check_out_time' => $now->format('H:i:s'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance_meter' => $location['distance_meter'],
        ]);

        return [
            'attendance' => $attendance,
            'message' => 'Check out berhasil disimpan.',
        ];
    }

    /**
     * Apakah waktu saat ini sudah memenuhi syarat check-in (tidak sebelum jam mulai masuk).
     */
    public function canCheckInNow(?Carbon $moment = null): bool
    {
        $moment = $moment ?? now();
        $setting = AttendanceSetting::current();

        return ! $this->isBeforeTime($moment, $setting->check_in_start);
    }

    /**
     * Apakah waktu saat ini dalam rentang jam pulang (sama dengan validasi server).
     */
    public function canCheckOutNow(?Carbon $moment = null): bool
    {
        $moment = $moment ?? now();
        $setting = AttendanceSetting::current();

        return $this->isInTimeRange($moment, $setting->check_out_start, $setting->check_out_end);
    }

    public function markAlphaForDate(Carbon|string|null $date = null): array
    {
        $date = Carbon::parse($date ?: now())->startOfDay();
        $setting = AttendanceSetting::current();
        $dayType = $this->dayType($setting, $date);

        $createdAlpha = 0;
        $createdHoliday = 0;

        Employee::query()
            ->activeWithAttendancePin()
            ->orderedForAttendance()
            ->each(function (Employee $employee) use ($date, $dayType, &$createdAlpha, &$createdHoliday) {
                if ($dayType['is_holiday']) {
                    $attendance = $this->markHoliday($employee, $date, $dayType, 'system_cron');

                    if ($attendance->wasRecentlyCreated) {
                        $createdHoliday++;
                    }

                    return;
                }

                $attendance = Attendance::query()->firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => Attendance::STATUS_ALPA,
                        'is_holiday' => false,
                        'source' => 'system_cron',
                    ]
                );

                if ($attendance->wasRecentlyCreated) {
                    $createdAlpha++;
                }
            });

        return [
            'date' => $date->toDateString(),
            'created_alpha' => $createdAlpha,
            'created_holiday' => $createdHoliday,
            'is_holiday' => $dayType['is_holiday'],
            'holiday_name' => $dayType['holiday_name'],
        ];
    }

    public function dayType(AttendanceSetting $setting, Carbon|string $date): array
    {
        $date = Carbon::parse($date);

        if ($setting->use_holiday_api) {
            $holiday = $this->holidayService->forDate($date);

            if ($holiday['is_holiday']) {
                return [
                    'is_holiday' => true,
                    'holiday_name' => $holiday['holiday_name'],
                    'source' => 'holiday_api',
                ];
            }
        }

        if ($setting->disable_saturday_attendance && $date->isSaturday()) {
            return [
                'is_holiday' => true,
                'holiday_name' => 'Hari Sabtu',
                'source' => 'saturday',
            ];
        }

        if ($setting->disable_sunday_attendance && $date->isSunday()) {
            return [
                'is_holiday' => true,
                'holiday_name' => 'Hari Minggu',
                'source' => 'sunday',
            ];
        }

        return [
            'is_holiday' => false,
            'holiday_name' => null,
            'source' => null,
        ];
    }

    private function assertEmployeeCanAttend(Employee $employee, string $pin): void
    {
        if (! $employee->is_active) {
            throw new InvalidArgumentException('Pegawai tidak aktif.');
        }

        if (blank($employee->pin_absensi)) {
            throw new InvalidArgumentException('Pegawai belum memiliki PIN absensi.');
        }

        if (! hash_equals((string) $employee->pin_absensi, (string) $pin)) {
            throw new InvalidArgumentException('PIN absensi tidak sesuai.');
        }
    }

    private function markHoliday(Employee $employee, Carbon $date, array $dayType, string $source = 'system_holiday'): Attendance
    {
        return Attendance::query()->firstOrCreate(
            [
                'employee_id' => $employee->id,
                'attendance_date' => $date->toDateString(),
            ],
            [
                'status' => Attendance::STATUS_LIBUR,
                'is_holiday' => true,
                'holiday_name' => $dayType['holiday_name'],
                'source' => $source,
            ]
        );
    }

    private function isBeforeTime(Carbon $moment, string|DateTimeInterface|null $time): bool
    {
        if (! $time) {
            return false;
        }

        return $moment->lt($this->timeOnDate($moment, $time));
    }

    private function isAfterTime(Carbon $moment, string|DateTimeInterface|null $time): bool
    {
        if (! $time) {
            return false;
        }

        return $moment->gt($this->timeOnDate($moment, $time));
    }

    private function isInTimeRange(Carbon $moment, string|DateTimeInterface|null $start, string|DateTimeInterface|null $end): bool
    {
        if (! $start || ! $end) {
            return true;
        }

        $startAt = $this->timeOnDate($moment, $start);
        $endAt = $this->timeOnDate($moment, $end);

        if ($endAt->lt($startAt)) {
            $endAt->addDay();

            if ($moment->lt($startAt)) {
                $startAt->subDay();
            }
        }

        return $moment->betweenIncluded($startAt, $endAt);
    }

    private function timeOnDate(Carbon $date, string|DateTimeInterface $time): Carbon
    {
        $time = $time instanceof DateTimeInterface
            ? $time->format('H:i:s')
            : (strlen($time) === 5 ? $time.':00' : substr($time, 0, 8));

        return $date->copy()->setTimeFromTimeString($time);
    }
}
