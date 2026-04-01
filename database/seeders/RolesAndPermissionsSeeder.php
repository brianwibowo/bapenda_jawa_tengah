<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions jika diperlukan (opsional untuk sekarang)
        // Permission::create(['name' => 'buat pengajuan']);
        // Permission::create(['name' => 'kelola pengguna']);

        // Buat Roles sesuai yang ada di web.php
        Role::firstOrCreate(['name' => 'penulis']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'superadmin']); // Pastikan namanya 'superadmin', bukan 'super-admin'
        Role::firstOrCreate(['name' => 'polda']);
        Role::firstOrCreate(['name' => 'samsat']);
        Role::firstOrCreate(['name' => 'bapenda']);
        Role::firstOrCreate(['name' => 'jr']);
        Role::firstOrCreate(['name' => 'Pengajuan']);
    }
}