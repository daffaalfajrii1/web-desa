<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class AttendanceCheckController extends Controller
{
    public function index()
    {
        $employees = Employee::query()
            ->activeWithAttendancePin()
            ->orderedForAttendance()
            ->get();

        $setting = AttendanceSetting::current();
        $todayAttendances = Attendance::with('employee')
            ->whereHas('employee', fn ($query) => $query->activeWithAttendancePin())
            ->whereDate('attendance_date', now()->toDateString())
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('admin.absensi.input', [
            'employees' => $employees,
            'setting' => $setting,
            'todayAttendances' => $todayAttendances,
            'statusLabels' => Attendance::statusLabels(),
            'statusBadgeClasses' => Attendance::statusBadgeClasses(),
        ]);
    }

    public function checkIn(Request $request, AttendanceService $attendanceService)
    {
        $data = $this->validateAttendanceInput($request);
        $employee = Employee::findOrFail($data['employee_id']);

        try {
            $result = $attendanceService->checkIn(
                $employee,
                $data['pin'],
                $data['latitude'] ?? null,
                $data['longitude'] ?? null
            );

            return back()->with('success', $result['message']);
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput($request->except('pin'))
                ->with('error', $exception->getMessage());
        }
    }

    public function checkOut(Request $request, AttendanceService $attendanceService)
    {
        $data = $this->validateAttendanceInput($request);
        $employee = Employee::findOrFail($data['employee_id']);

        try {
            $result = $attendanceService->checkOut(
                $employee,
                $data['pin'],
                $data['latitude'] ?? null,
                $data['longitude'] ?? null
            );

            return back()->with('success', $result['message']);
        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput($request->except('pin'))
                ->with('error', $exception->getMessage());
        }
    }

    private function validateAttendanceInput(Request $request): array
    {
        return $request->validate([
            'employee_id' => [
                'required',
                Rule::exists('employees', 'id')
                    ->where(fn ($query) => $query
                        ->where('is_active', true)
                        ->whereNotNull('pin_absensi')
                        ->where('pin_absensi', '!=', '')),
            ],
            'pin' => 'required|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
    }
}
