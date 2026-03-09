<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definisikan 3 Hak Akses Dasar (Sesuai Request)
        $basicPermissions = [
            'view_own_pengajuan',
            'create_pengajuan',
            'store_pengajuan',
        ];

        // 2. Buat Data Permissions di Database
        foreach ($basicPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. Buat Akses Group 'Penulis' & 'Superadmin' (Hanya sebagai contoh role)
        $rolePenulis = Role::firstOrCreate(['name' => 'Penulis', 'guard_name' => 'web']);
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);

        // 4. Berikan Izin ke Role Penulis
        $rolePenulis->syncPermissions($basicPermissions);

        // Superadmin otomatis mendapatkan semuanya
        $roleSuperadmin->syncPermissions(Permission::all());

        // 5. Buat Akun Default Superadmin
        $userSuperadmin = User::firstOrCreate(
            ['email' => 'superadmin@bapenda.go.id'],
            [
                'name' => 'Bapenda Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userSuperadmin->assignRole($roleSuperadmin);

        $this->command->info('RBAC Seeder: Berhasil menambahkan Hak Akses dasar, Role, dan User Superadmin (superadmin@bapenda.go.id / password).');
    }
}
