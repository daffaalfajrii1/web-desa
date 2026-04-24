<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@desa.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole($role);
    }
}