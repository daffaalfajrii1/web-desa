<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\User;
use App\Models\VillageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $positions = $this->seedPositions();
        $employees = $this->seedEmployees($users, $positions);
        $this->seedAttendanceSetting();
        $this->seedAttendances($employees);
        $this->linkVillageHead($employees);
    }

    private function seedUsers(): array
    {
        if (! Schema::hasTable('users')) {
            return [];
        }

        $userSeeds = [
            ['name' => 'Operator Profil', 'email' => 'profil@desa.test', 'role' => 'editor'],
            ['name' => 'Operator PPID', 'email' => 'ppid@desa.test', 'role' => 'operator_ppid'],
            ['name' => 'Operator SOTK', 'email' => 'sotk@desa.test', 'role' => 'operator_sotk'],
        ];

        $users = [];
        foreach ($userSeeds as $seed) {
            $user = User::query()->firstOrCreate(
                ['email' => $seed['email']],
                [
                    'name' => $seed['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (class_exists(Role::class) && Role::query()->where('name', $seed['role'])->exists()) {
                $user->syncRoles([$seed['role']]);
            }

            $users[] = $user;
        }

        return $users;
    }

    private function seedPositions(): array
    {
        if (! Schema::hasTable('employee_positions')) {
            return [];
        }

        $definitions = [
            ['name' => 'Kepala Desa', 'type' => 'kepala_desa'],
            ['name' => 'Sekretaris Desa', 'type' => 'sekretaris'],
            ['name' => 'Kasi Pemerintahan', 'type' => 'kasi'],
            ['name' => 'Kasi Kesejahteraan', 'type' => 'kasi'],
            ['name' => 'Kasi Pelayanan', 'type' => 'kasi'],
            ['name' => 'Kaur Keuangan', 'type' => 'kaur'],
            ['name' => 'Kaur Umum', 'type' => 'kaur'],
            ['name' => 'Kepala Dusun 1', 'type' => 'kadus'],
            ['name' => 'Kepala Dusun 2', 'type' => 'kadus'],
            ['name' => 'Staff Administrasi', 'type' => 'staf'],
        ];

        $positions = [];
        foreach ($definitions as $i => $def) {
            $positions[] = EmployeePosition::query()->updateOrCreate(
                ['slug' => Str::slug($def['name'])],
                [
                    'name' => $def['name'],
                    'position_type' => $def['type'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]
            );
        }

        return $positions;
    }

    private function seedEmployees(array $users, array $positions): array
    {
        if (! Schema::hasTable('employees')) {
            return [];
        }

        $names = [
            'Andi Saputra',
            'Rina Marlina',
            'Dedi Kurniawan',
            'Siti Aisyah',
            'Rudi Hartono',
            'Yuni Kartika',
            'Agus Pratama',
            'Rahmawati',
            'M. Fikri',
            'Nurhayati',
        ];

        $employees = [];
        foreach ($names as $i => $name) {
            $position = $positions[$i] ?? null;
            $emailSlug = Str::slug($name, '.');
            $user = $users[$i % max(count($users), 1)] ?? null;

            $employees[] = Employee::query()->updateOrCreate(
                ['email' => $emailSlug.'@desa.test'],
                [
                    'user_id' => $user?->id,
                    'employee_position_id' => $position?->id,
                    'name' => $name,
                    'position' => $position?->name ?? 'Staff',
                    'position_type' => $position?->position_type,
                    'photo' => 'demo/employees/employee-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT).'.jpg',
                    'nip' => '19790'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'phone' => '0812735500'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'facebook' => 'https://facebook.com/'.$emailSlug,
                    'instagram' => 'https://instagram.com/'.$emailSlug,
                    'twitter' => 'https://x.com/'.$emailSlug,
                    'youtube' => 'https://youtube.com/@'.$emailSlug,
                    'whatsapp' => '0812735500'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'telegram' => 'https://t.me/'.$emailSlug,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                    'attendance_pin' => str_pad((string) (1000 + $i), 4, '0', STR_PAD_LEFT),
                    'pin_absensi' => str_pad((string) (1000 + $i), 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        return $employees;
    }

    private function seedAttendanceSetting(): void
    {
        if (! Schema::hasTable('attendance_settings')) {
            return;
        }

        AttendanceSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'check_in_start' => '06:00:00',
                'check_in_end' => '08:00:00',
                'check_out_start' => '15:00:00',
                'check_out_end' => '18:00:00',
                'office_latitude' => -3.466667,
                'office_longitude' => 102.533333,
                'allowed_radius_meter' => 150,
                'validate_location' => false,
                'use_holiday_api' => true,
                'disable_saturday_attendance' => true,
                'disable_sunday_attendance' => true,
            ]
        );
    }

    private function seedAttendances(array $employees): void
    {
        if (! Schema::hasTable('attendances')) {
            return;
        }

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        foreach ($employees as $index => $employee) {
            if (! $employee instanceof Employee || ! $employee->is_active) {
                continue;
            }

            for ($date = $monthStart->copy(); $date->lte($monthEnd); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue;
                }

                $status = ($index + $date->day) % 13 === 0 ? Attendance::STATUS_TELAT : Attendance::STATUS_HADIR;
                $in = $status === Attendance::STATUS_TELAT ? '07:42:00' : '07:05:00';
                $out = '16:08:00';

                Attendance::query()->updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'check_in_time' => $in,
                        'check_out_time' => $out,
                        'status' => $status,
                        'note' => $status === Attendance::STATUS_TELAT ? 'Datang terlambat karena kondisi lalu lintas.' : 'Hadir sesuai jadwal.',
                        'latitude' => -3.466667,
                        'longitude' => 102.533333,
                        'distance_meter' => 18.5,
                        'is_holiday' => false,
                        'holiday_name' => null,
                        'source' => 'seeder_demo',
                    ]
                );
            }
        }
    }

    private function linkVillageHead(array $employees): void
    {
        if (! Schema::hasTable('village_settings')) {
            return;
        }

        $head = collect($employees)->firstWhere('position', 'Kepala Desa');
        if (! $head instanceof Employee) {
            return;
        }

        VillageSetting::query()->where('id', 1)->update([
            'village_head_employee_id' => $head->id,
            'village_head_name_manual' => $head->name,
        ]);
    }
}
