<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Granular Permissions berdasarkan fitur riil
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'group_name' => 'Dashboard'],

            // Buat Pengajuan (WP)
            ['name' => 'view_menu_buat_pengajuan', 'group_name' => 'Buat Pengajuan'],
            ['name' => 'create_pengajuan_baru', 'group_name' => 'Buat Pengajuan'],

            // Daftar Pengajuan (WP)
            ['name' => 'view_menu_daftar_pengajuan', 'group_name' => 'Daftar Pengajuan'],
            ['name' => 'edit_kendaraan_pengajuan_sendiri', 'group_name' => 'Daftar Pengajuan'],
            ['name' => 'delete_kendaraan_pengajuan_sendiri', 'group_name' => 'Daftar Pengajuan'],

            // Manajemen Pengajuan (Verifikator)
            ['name' => 'view_menu_manajemen_pengajuan', 'group_name' => 'Manajemen Pengajuan'],
            ['name' => 'approve_status_pengajuan', 'group_name' => 'Manajemen Pengajuan'],
            ['name' => 'delete_pengajuan_publik', 'group_name' => 'Manajemen Pengajuan'],
            ['name' => 'view_log_histori', 'group_name' => 'Manajemen Pengajuan'],

            // Hak Akses (Admin)
            ['name' => 'view_menu_hak_akses', 'group_name' => 'Hak Akses'],
            ['name' => 'create_hak_akses', 'group_name' => 'Hak Akses'],

            // Akses Group (Admin)
            ['name' => 'view_menu_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'create_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'edit_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'delete_akses_group', 'group_name' => 'Akses Group'],

            // Pengguna (Admin)
            ['name' => 'view_menu_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'create_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'edit_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'delete_pengguna', 'group_name' => 'Pengguna'],
        ];

        // Bersihkan izin lama untuk menghindari duplikasi kotor saat seeder dijalankan ulang
        \DB::table('role_has_permissions')->delete();
        \DB::table('model_has_permissions')->delete();
        \DB::table('permissions')->delete();

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['group_name' => $perm['group_name']]
            );
        }

        // 2. Ambil Roles yang sudah didaftarkan di RolesAndPermissionsSeeder
        $roleSuperadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $roleKepala = Role::firstOrCreate(['name' => 'kepala_instansi', 'guard_name' => 'web']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin_instansi', 'guard_name' => 'web']);
        $roleStaff = Role::firstOrCreate(['name' => 'staff_instansi', 'guard_name' => 'web']);
        $roleWp = Role::firstOrCreate(['name' => 'wajib_pajak', 'guard_name' => 'web']);

        // 3. Distribusi Izin Dasar
        $roleSuperadmin->syncPermissions(Permission::all());
        
        // Kepala Instansi
        $roleKepala->syncPermissions(['view_dashboard', 'view_menu_manajemen_pengajuan', 'approve_status_pengajuan']);
        
        // Admin Instansi
        $roleAdmin->syncPermissions(['view_dashboard', 'view_menu_manajemen_pengajuan', 'approve_status_pengajuan', 'delete_pengajuan_publik', 'view_log_histori']);
        
        // Staff
        $roleStaff->syncPermissions(['view_dashboard', 'view_menu_manajemen_pengajuan', 'view_log_histori']);

        // Wajib pajak membuat pengajuan miliknya sendiri
        $roleWp->syncPermissions(['view_dashboard', 'view_menu_buat_pengajuan', 'create_pengajuan_baru', 'view_menu_daftar_pengajuan', 'edit_kendaraan_pengajuan_sendiri', 'delete_kendaraan_pengajuan_sendiri']);

        // 4. Default Akun Superadmin Inti
        $userSuperadmin = User::firstOrCreate(
            ['email' => 'superadmin@bapenda.go.id'],
            [
                'name' => 'Bapenda Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'unit_kerja' => 'Bapenda'
            ]
        );
        $userSuperadmin->assignRole($roleSuperadmin);

        $this->command->info('RBAC Seeder: Permission & Role Jabatan berhasil diatur ulang sesuai taksonomi menu!');
    }
}
