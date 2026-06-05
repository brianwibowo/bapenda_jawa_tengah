<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
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
            ['name' => 'view_daftar_kendaraan', 'group_name' => 'Daftar Kendaraan'],

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

            // Surat Keputusan & Dokumen Khusus PDF
            ['name' => 'view_own_sk', 'group_name' => 'Surat Keputusan PDF'],
            ['name' => 'create_sk', 'group_name' => 'Surat Keputusan PDF'],
            ['name' => 'create_pdf_pengajuan', 'group_name' => 'Surat Pengajuan PDF'],
            ['name' => 'create_pdf_pengajuan_bapenda_jr', 'group_name' => 'Surat Pengajuan PDF'],
            ['name' => 'create_pdf_balasan_polda', 'group_name' => 'Surat Balasan PDF'],
            ['name' => 'create_pdf_balasan_samsat', 'group_name' => 'Surat Balasan PDF'],
            ['name' => 'view_dokumen_surat_pengajuan', 'group_name' => 'Akses Lihat File PDF'],
            ['name' => 'view_dokumen_surat_balasan', 'group_name' => 'Akses Lihat File PDF'],

            // Hak Akses (Admin)
            ['name' => 'view_menu_hak_akses', 'group_name' => 'Hak Akses'],
            ['name' => 'create_hak_akses', 'group_name' => 'Hak Akses'],

            // Akses Group (Admin)
            ['name' => 'view_menu_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'create_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'edit_akses_group', 'group_name' => 'Akses Group'],
            ['name' => 'delete_akses_group', 'group_name' => 'Akses Group'],

            // Cabang Samsat
            ['name' => 'view_menu_cabang', 'group_name' => 'Cabang'],
            ['name' => 'create_cabang', 'group_name' => 'Cabang'],
            ['name' => 'edit_cabang', 'group_name' => 'Cabang'],
            ['name' => 'delete_cabang', 'group_name' => 'Cabang'],

            // Pengguna (Admin) — Legacy
            ['name' => 'view_menu_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'create_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'edit_pengguna', 'group_name' => 'Pengguna'],
            ['name' => 'delete_pengguna', 'group_name' => 'Pengguna'],

            // Pengguna WP (Split)
            ['name' => 'view_menu_pengguna_wp', 'group_name' => 'Pengguna WP'],

            // Pemangku Kepentingan (Split)
            ['name' => 'view_menu_pengguna_stakeholder', 'group_name' => 'Pemangku Kepentingan'],

            // Aturan Bisnis (Scoping & Otomasi)
            ['name' => 'scoped_to_own_branch', 'group_name' => 'Aturan Bisnis'],
            ['name' => 'auto_process_on_action', 'group_name' => 'Aturan Bisnis'],

            // Revisi Berkas
            ['name' => 'request_revision', 'group_name' => 'Revisi Berkas'],
            ['name' => 'submit_revision', 'group_name' => 'Revisi Berkas'],
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

        // 2. Ambil Roles Sesuai Syarat Dosen (Nama Grup = Instansi)
        $roleSuperadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $roleWp = Role::firstOrCreate(['name' => 'wajib_pajak', 'guard_name' => 'web']);
        $roleSamsat = Role::firstOrCreate(['name' => 'samsat', 'guard_name' => 'web']);
        $rolePolda = Role::firstOrCreate(['name' => 'polda', 'guard_name' => 'web']);
        $roleBapenda = Role::firstOrCreate(['name' => 'bapenda', 'guard_name' => 'web']);
        $roleJr = Role::firstOrCreate(['name' => 'jasa_raharja', 'guard_name' => 'web']);

        // 3. Distribusi Izin Dasar (Sesuai Matriks Dosen)
        $roleSuperadmin->syncPermissions(Permission::where('name', '!=', 'scoped_to_own_branch')->get());

        // Wajib Pajak (WP)
        $roleWp->syncPermissions([
            'view_dashboard', 'view_menu_buat_pengajuan', 'create_pengajuan_baru',
            'view_menu_daftar_pengajuan', 'edit_kendaraan_pengajuan_sendiri', 
            'delete_kendaraan_pengajuan_sendiri', 'view_log_histori', 'view_own_sk',
            'submit_revision'
        ]);

        // Samsat / Polres
        $roleSamsat->syncPermissions([
            'view_dashboard', 'view_menu_manajemen_pengajuan', 'approve_status_pengajuan',
            'create_pdf_pengajuan', 'view_dokumen_surat_balasan', 'view_own_sk',
            'scoped_to_own_branch', 'auto_process_on_action',
            'request_revision', 'submit_revision',
            'view_daftar_kendaraan',
            'view_menu_buat_pengajuan',
            'create_pengajuan_baru',
            'view_menu_daftar_pengajuan',
            'edit_kendaraan_pengajuan_sendiri',
            'delete_kendaraan_pengajuan_sendiri'
        ]);

        // Polda
        $rolePolda->syncPermissions([
            'view_dashboard', 'view_menu_manajemen_pengajuan', 'view_log_histori',
            'create_pdf_pengajuan_bapenda_jr', 'create_pdf_balasan_samsat', 'create_pdf_pengajuan', 'create_sk', 'view_own_sk', 
            'view_dokumen_surat_balasan'
        ]);

        // Bapenda
        $roleBapenda->syncPermissions([
            'view_dashboard', 'view_menu_manajemen_pengajuan', 'view_log_histori',
            'create_pdf_balasan_polda', 'create_sk', 'view_dokumen_surat_pengajuan',
            'view_dokumen_surat_balasan', 'view_own_sk'
        ]);

        // Jasa Raharja (Sama dengan Bapenda pola kerja logikanya)
        $roleJr->syncPermissions([
            'view_dashboard', 'view_menu_manajemen_pengajuan', 'view_log_histori',
            'create_pdf_balasan_polda', 'create_sk', 'view_dokumen_surat_pengajuan',
            'view_dokumen_surat_balasan', 'view_own_sk'
        ]);

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
