<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class AttendanceController extends Controller
{
    public function index(AttendanceService $attendanceService)
    {
        return view('public.attendance.index', [
            'employees' => Employee::query()->activeWithAttendancePin()->orderedForAttendance()->get(),
            'setting' => AttendanceSetting::current(),
            'canCheckInNow' => $attendanceService->canCheckInNow(),
            'canCheckOutNow' => $attendanceService->canCheckOutNow(),
            'todayAttendances' => Attendance::query()
                ->with('employee')
                ->whereDate('attendance_date', now()->toDateString())
                ->latest('updated_at')
                ->limit(8)
                ->get(),
            'statusLabels' => Attendance::statusLabels(),
        ]);
    }

    public function checkIn(Request $request, AttendanceService $attendanceService)
    {
        return $this->handleAttendance($request, $attendanceService, 'checkIn');
    }

    public function checkOut(Request $request, AttendanceService $attendanceService)
    {
        return $this->handleAttendance($request, $attendanceService, 'checkOut');
    }

    private function handleAttendance(Request $request, AttendanceService $attendanceService, string $method)
    {
        $data = $this->validateAttendanceInput($request);
        $employee = Employee::findOrFail($data['employee_id']);

        try {
            $result = $attendanceService->{$method}(
                $employee,
                $data['pin'],
                $data['latitude'] ?? null,
                $data['longitude'] ?? null,
                'public'
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
