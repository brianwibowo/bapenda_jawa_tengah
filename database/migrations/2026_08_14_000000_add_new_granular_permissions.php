<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cache permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Daftarkan permission baru secara aman (tidak duplikat & tidak menghapus data lama)
        $newPermissions = [
            [
                'name' => 'delete_draft_pengajuan',
                'group_name' => 'Daftar Pengajuan',
            ],
            [
                'name' => 'publish_surat_keputusan',
                'group_name' => 'Surat Keputusan PDF',
            ],
            [
                'name' => 'publish_surat_pengajuan',
                'group_name' => 'Surat Pengajuan PDF',
            ],
        ];

        foreach ($newPermissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['group_name' => $perm['group_name']]
            );
        }

        // 2. Berikan izin dasar ke roles yang sudah ada (hanya menambahkan, tidak menghapus izin lain)
        $roleAssignments = [
            'superadmin' => ['delete_draft_pengajuan', 'publish_surat_keputusan', 'publish_surat_pengajuan'],
            'wajib_pajak' => ['delete_draft_pengajuan'],
            'samsat' => ['delete_draft_pengajuan'],
            'polda' => ['publish_surat_keputusan', 'publish_surat_pengajuan'],
            'bapenda' => ['publish_surat_keputusan', 'publish_surat_pengajuan'],
            'jasa_raharja' => ['publish_surat_keputusan', 'publish_surat_pengajuan'],
        ];

        foreach ($roleAssignments as $roleName => $perms) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($perms);
            }
        }

        // Reset cache permission lagi setelah update
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::whereIn('name', [
            'delete_draft_pengajuan',
            'publish_surat_keputusan',
            'publish_surat_pengajuan',
        ])->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
