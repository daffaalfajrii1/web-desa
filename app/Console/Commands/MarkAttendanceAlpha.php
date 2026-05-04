<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;

class MarkAttendanceAlpha extends Command
{
    protected $signature = 'attendance:mark-alpha {date? : Tanggal absensi format Y-m-d}';

    protected $description = 'Menandai pegawai aktif yang punya PIN dan tidak absen sebagai alpa, atau libur pada hari non kerja.';

    public function handle(AttendanceService $attendanceService): int
    {
        $result = $attendanceService->markAlphaForDate($this->argument('date'));

        if ($result['is_holiday']) {
            $this->info("Tanggal {$result['date']} libur ({$result['holiday_name']}). {$result['created_holiday']} record libur dibuat.");

            return self::SUCCESS;
        }

        $this->info("Tanggal {$result['date']}: {$result['created_alpha']} record alpa dibuat.");

        return self::SUCCESS;
    }
}
