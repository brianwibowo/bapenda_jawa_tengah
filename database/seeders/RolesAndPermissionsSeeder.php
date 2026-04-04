<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Roles (Menghapus nama instansi, murni jabatan yang bisa lintas institusi)
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'kepala_instansi']);
        Role::firstOrCreate(['name' => 'admin_instansi']);
        Role::firstOrCreate(['name' => 'staff_instansi']);
        Role::firstOrCreate(['name' => 'wajib_pajak']);
    }
}