<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage roles',
            'manage settings',
            'manage profil-desa',
            'manage berita',
            'manage pengumuman',
            'manage agenda',
            'manage produk-hukum',
            'manage informasi-publik',
            'manage ppid',
            'manage sotk',
            'manage absensi',
            'manage lapak',
            'manage wisata',
            'manage galeri',
            'manage infografis',
            'manage layanan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $operatorPpid = Role::firstOrCreate(['name' => 'operator_ppid']);
        $operatorSotk = Role::firstOrCreate(['name' => 'operator_sotk']);

        $superAdmin->syncPermissions(Permission::all());

        $editor->syncPermissions([
            'manage profil-desa',
            'manage berita',
            'manage pengumuman',
            'manage agenda',
            'manage produk-hukum',
            'manage informasi-publik',
            'manage lapak',
            'manage wisata',
            'manage galeri',
            'manage infografis',
            'manage layanan',
        ]);

        $operatorPpid->syncPermissions([
            'manage ppid',
        ]);

        $operatorSotk->syncPermissions([
            'manage sotk',
            'manage absensi',
        ]);
    }
}
