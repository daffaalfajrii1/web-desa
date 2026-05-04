<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function index(Request $request, AttendanceService $attendanceService)
    {
        $employees = $this->employeeOptions();
        $month = min(12, max(1, (int) $request->input('month', now()->month)));
        $year = (int) $request->input('year', now()->year);
        $summary = $this->todaySummary();
        $grid = $this->monthlyGridRows($request, $attendanceService, $year, $month);

        return view('admin.absensi.index', [
            'employees' => $employees,
            'month' => $month,
            'year' => $year,
            'summary' => $summary,
            'days' => $grid['days'],
            'rows' => $grid['rows'],
            'statusLabels' => Attendance::statusLabels(),
            'statusBadgeClasses' => Attendance::statusBadgeClasses(),
            'statusShortLabels' => $this->statusShortLabels(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.absensi.create', [
            'employees' => $this->employeeOptions(),
            'statusLabels' => Attendance::statusLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => [
                'required',
                Rule::exists('employees', 'id')
                    ->where(fn ($query) => $query
                        ->where('is_active', true)
                        ->whereNotNull('pin_absensi')
                        ->where('pin_absensi', '!=', '')),
            ],
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendances', 'attendance_date')
                    ->where(fn ($query) => $query->where('employee_id', $request->input('employee_id'))),
            ],
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => ['required', Rule::in(Attendance::statuses())],
            'note' => 'nullable|string',
        ]);

        $data['check_in_time'] = ($data['check_in_time'] ?? null) ? $data['check_in_time'] . ':00' : null;
        $data['check_out_time'] = ($data['check_out_time'] ?? null) ? $data['check_out_time'] . ':00' : null;
        $data['is_holiday'] = $data['status'] === Attendance::STATUS_LIBUR;
        $data['source'] = 'manual';

        Attendance::create($data);

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', 'Data absensi manual berhasil ditambahkan.');
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load('employee');

        return view('admin.absensi.edit', [
            'attendance' => $attendance,
            'statusLabels' => Attendance::statusLabels(),
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendances', 'attendance_date')
                    ->where(fn ($query) => $query->where('employee_id', $attendance->employee_id))
                    ->ignore($attendance->id),
            ],
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => ['required', Rule::in(Attendance::statuses())],
            'note' => 'nullable|string',
        ]);

        $data['check_in_time'] = ($data['check_in_time'] ?? null) ? $data['check_in_time'] . ':00' : null;
        $data['check_out_time'] = ($data['check_out_time'] ?? null) ? $data['check_out_time'] . ':00' : null;

        if ($data['status'] === Attendance::STATUS_LIBUR) {
            $data['is_holiday'] = true;
        } else {
            $data['is_holiday'] = false;
            $data['holiday_name'] = null;
        }

        $attendance->update($data);

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function monthly(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $rows = $this->recapRows($request, $year, $month);

        return view('admin.absensi.monthly', [
            'rows' => $rows,
            'month' => $month,
            'year' => $year,
            'employees' => $this->employeeOptions(),
            'statusLabels' => Attendance::statusLabels(),
        ]);
    }

    public function yearly(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $rows = $this->recapRows($request, $year);

        return view('admin.absensi.yearly', [
            'rows' => $rows,
            'year' => $year,
            'employees' => $this->employeeOptions(),
            'statusLabels' => Attendance::statusLabels(),
        ]);
    }

    public function exportDetail(Request $request): StreamedResponse
    {
        $items = $this->filteredAttendanceQuery($request)
            ->orderBy('attendance_date')
            ->orderBy('employee_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Detail Absensi');

        $headers = [
            'Nama Pegawai',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Keterangan',
            'Latitude',
            'Longitude',
            'Jarak Meter',
            'Libur',
            'Nama Libur',
        ];

        $this->writeHeader($sheet, $headers);

        $row = 2;
        $statusLabels = Attendance::statusLabels();

        foreach ($items as $item) {
            $sheet->fromArray([
                $item->employee?->name ?? '-',
                $item->attendance_date?->format('d-m-Y'),
                $this->formatTime($item->check_in_time),
                $this->formatTime($item->check_out_time),
                $statusLabels[$item->status] ?? ucfirst($item->status),
                $item->note,
                $item->latitude,
                $item->longitude,
                $item->distance_meter,
                $item->is_holiday ? 'Ya' : 'Tidak',
                $item->holiday_name,
            ], null, 'A' . $row);

            $row++;
        }

        $this->finishSheet($sheet, count($headers), $row - 1);

        return $this->download($spreadsheet, 'detail-absensi.xlsx');
    }

    public function exportMonthly(Request $request, AttendanceService $attendanceService): StreamedResponse
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $grid = $this->monthlyGridRows($request, $attendanceService, $year, $month);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Bulanan');

        $headers = ['Nama Pegawai', 'Bulan', 'Tahun', 'Hadir', 'Telat', 'Izin', 'Sakit', 'Alpa', 'Libur'];
        $this->writeHeader($sheet, $headers);

        $rowNumber = 2;

        foreach ($grid['rows'] as $row) {
            $sheet->fromArray([
                $row['employee']->name,
                $this->monthName($month),
                $year,
                $row['counts'][Attendance::STATUS_HADIR],
                $row['counts'][Attendance::STATUS_TELAT],
                $row['counts'][Attendance::STATUS_IZIN],
                $row['counts'][Attendance::STATUS_SAKIT],
                $row['counts'][Attendance::STATUS_ALPA],
                $row['counts'][Attendance::STATUS_LIBUR],
            ], null, 'A' . $rowNumber);

            $rowNumber++;
        }

        $this->finishSheet($sheet, count($headers), $rowNumber - 1);

        return $this->download($spreadsheet, 'rekap-absensi-bulanan.xlsx');
    }

    public function exportYearly(Request $request): StreamedResponse
    {
        $year = (int) $request->input('year', now()->year);
        $rows = $this->recapRows($request, $year);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Tahunan');

        $headers = ['Nama Pegawai', 'Tahun', 'Hadir', 'Telat', 'Izin', 'Sakit', 'Alpa', 'Libur'];
        $this->writeHeader($sheet, $headers);

        $rowNumber = 2;

        foreach ($rows as $row) {
            $sheet->fromArray([
                $row['employee']->name,
                $year,
                $row[Attendance::STATUS_HADIR],
                $row[Attendance::STATUS_TELAT],
                $row[Attendance::STATUS_IZIN],
                $row[Attendance::STATUS_SAKIT],
                $row[Attendance::STATUS_ALPA],
                $row[Attendance::STATUS_LIBUR],
            ], null, 'A' . $rowNumber);

            $rowNumber++;
        }

        $this->finishSheet($sheet, count($headers), $rowNumber - 1);

        return $this->download($spreadsheet, 'rekap-absensi-tahunan.xlsx');
    }

    private function filteredAttendanceQuery(Request $request): Builder
    {
        return Attendance::with('employee')
            ->whereHas('employee', fn ($query) => $query->activeWithAttendancePin())
            ->when($request->filled('date'), fn ($query) => $query->whereDate('attendance_date', $request->input('date')))
            ->when($request->filled('month'), fn ($query) => $query->whereMonth('attendance_date', $request->input('month')))
            ->when($request->filled('year'), fn ($query) => $query->whereYear('attendance_date', $request->input('year')))
            ->when($request->filled('employee_id'), fn ($query) => $query->where('employee_id', $request->input('employee_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->whereHas('employee', function ($employeeQuery) use ($search) {
                    $employeeQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('position', 'like', '%' . $search . '%');
                });
            });
    }

    private function todaySummary(): array
    {
        $counts = Attendance::query()
            ->whereHas('employee', fn ($query) => $query->activeWithAttendancePin())
            ->whereDate('attendance_date', now()->toDateString())
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $summary = [];

        foreach (Attendance::statuses() as $status) {
            $summary[$status] = (int) ($counts[$status] ?? 0);
        }

        $summary['total_pegawai'] = Employee::query()->activeWithAttendancePin()->count();

        return $summary;
    }

    private function recapRows(Request $request, int $year, ?int $month = null)
    {
        $employees = Employee::query()
            ->activeWithAttendancePin()
            ->when($request->filled('employee_id'), fn ($query) => $query->where('id', $request->input('employee_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($employeeQuery) use ($search) {
                    $employeeQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('position', 'like', '%' . $search . '%');
                });
            })
            ->orderedForAttendance()
            ->get();

        $counts = Attendance::query()
            ->select('employee_id', 'status', DB::raw('COUNT(*) as total'))
            ->whereYear('attendance_date', $year)
            ->when($month, fn ($query) => $query->whereMonth('attendance_date', $month))
            ->whereIn('employee_id', $employees->pluck('id'))
            ->groupBy('employee_id', 'status')
            ->get()
            ->groupBy('employee_id')
            ->map(fn ($records) => $records->pluck('total', 'status'));

        return $employees->map(function (Employee $employee) use ($counts) {
            $employeeCounts = $counts->get($employee->id, collect());
            $row = ['employee' => $employee];

            foreach (Attendance::statuses() as $status) {
                $row[$status] = (int) ($employeeCounts[$status] ?? 0);
            }

            return $row;
        });
    }

    private function monthlyGridRows(Request $request, AttendanceService $attendanceService, int $year, int $month): array
    {
        $setting = AttendanceSetting::current();
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = $start->copy()->endOfMonth();

        $days = collect(CarbonPeriod::create($start, $end))
            ->map(function (Carbon $date) use ($attendanceService, $setting) {
                $dayType = $attendanceService->dayType($setting, $date);

                return [
                    'date' => $date->toDateString(),
                    'day' => $date->day,
                    'day_name' => $date->locale('id')->translatedFormat('D'),
                    'is_holiday' => $dayType['is_holiday'],
                    'holiday_name' => $dayType['holiday_name'],
                ];
            })
            ->values();

        $employees = Employee::query()
            ->activeWithAttendancePin()
            ->when($request->filled('employee_id'), fn ($query) => $query->where('id', $request->input('employee_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($employeeQuery) use ($search) {
                    $employeeQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('position', 'like', '%' . $search . '%');
                });
            })
            ->orderedForAttendance()
            ->get();

        $attendances = Attendance::query()
            ->whereIn('employee_id', $employees->pluck('id'))
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy('employee_id')
            ->map(fn ($records) => $records->keyBy(fn (Attendance $attendance) => $attendance->attendance_date->toDateString()));

        $statusFilter = $request->input('status');
        $badgeClasses = Attendance::statusBadgeClasses();
        $shortLabels = $this->statusShortLabels();
        $statuses = Attendance::statuses();

        $rows = $employees->map(function (Employee $employee) use ($attendances, $days, $badgeClasses, $shortLabels, $statuses) {
            $employeeAttendances = $attendances->get($employee->id, collect());
            $counts = array_fill_keys($statuses, 0);
            $cells = [];

            foreach ($days as $day) {
                $attendance = $employeeAttendances->get($day['date']);
                $status = $attendance?->status;
                $holidayName = $attendance?->holiday_name ?: $day['holiday_name'];

                if (! $status && $day['is_holiday']) {
                    $status = Attendance::STATUS_LIBUR;
                }

                if ($status) {
                    $counts[$status]++;
                }

                $cells[] = [
                    'date' => $day['date'],
                    'status' => $status,
                    'label' => $status ? ($shortLabels[$status] ?? strtoupper(substr($status, 0, 1))) : '-',
                    'badge_class' => $status ? ($badgeClasses[$status] ?? 'badge-secondary') : 'badge-light',
                    'holiday_name' => $holidayName,
                    'attendance' => $attendance,
                ];
            }

            return [
                'employee' => $employee,
                'counts' => $counts,
                'cells' => $cells,
            ];
        });

        if ($statusFilter) {
            $rows = $rows
                ->filter(fn (array $row) => collect($row['cells'])->contains(fn (array $cell) => $cell['status'] === $statusFilter))
                ->values();
        }

        return [
            'days' => $days,
            'rows' => $rows,
        ];
    }

    private function employeeOptions()
    {
        return Employee::query()
            ->activeWithAttendancePin()
            ->orderedForAttendance()
            ->get();
    }

    private function writeHeader($sheet, array $headers): void
    {
        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '2E7D32'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC'],
                ],
            ],
        ]);
        $sheet->freezePane('A2');
    }

    private function finishSheet($sheet, int $columnCount, int $lastRow): void
    {
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

        foreach (range(1, $columnCount) as $columnIndex) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setAutoSize(true);
        }

        if ($lastRow > 1) {
            $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE5E7EB'],
                    ],
                ],
            ]);

            $sheet->setAutoFilter('A1:' . $lastColumn . $lastRow);
        }
    }

    private function download(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function formatTime(mixed $value): string
    {
        if (! $value) {
            return '-';
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format('H:i');
        }

        return substr((string) $value, 0, 5);
    }

    private function monthName(int $month): string
    {
        $names = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $names[$month] ?? (string) $month;
    }

    private function statusShortLabels(): array
    {
        return [
            Attendance::STATUS_HADIR => 'H',
            Attendance::STATUS_TELAT => 'T',
            Attendance::STATUS_IZIN => 'I',
            Attendance::STATUS_SAKIT => 'S',
            Attendance::STATUS_ALPA => 'A',
            Attendance::STATUS_LIBUR => 'L',
        ];
    }
}
