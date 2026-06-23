<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Kendaraan;
use App\Models\Pemilik;
use App\Models\Pengajuan;
use App\Models\KendaraanLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_flow(): void
    {
        // 1. Create cabang, users
        $cabang = Cabang::create(['nama' => 'Samsat Cilacap', 'wilayah' => 'Cilacap']);
        
        // Wajib Pajak User
        $wpRole = Role::firstOrCreate(['name' => 'wajib_pajak', 'guard_name' => 'web']);
        $wpUser = User::factory()->create([
            'unit_kerja' => null,
            'cabang_id' => $cabang->id,
        ]);
        $wpUser->assignRole($wpRole);

        // Bapenda Staff User
        $bapendaRole = Role::firstOrCreate(['name' => 'bapenda', 'guard_name' => 'web']);
        $bapendaPermission = Permission::firstOrCreate([
            'name' => 'view_menu_manajemen_pengajuan',
            'guard_name' => 'web',
        ]);
        $bapendaRole->givePermissionTo($bapendaPermission);
        
        $bapendaUser = User::factory()->create([
            'unit_kerja' => 'Bapenda',
            'cabang_id' => $cabang->id,
        ]);
        $bapendaUser->assignRole($bapendaRole);

        // Create Pengajuan for WP
        $pengajuan = Pengajuan::create([
            'user_id' => $wpUser->id,
            'cabang_id' => $cabang->id,
            'nomor_pengajuan' => 'PJ-001',
        ]);

        $pemilik = Pemilik::create([
            'nama_pemilik' => 'Budi',
            'nik_pemilik' => '3374010101010001',
            'alamat_pemilik' => 'Semarang',
            'telp_pemilik' => '08123456789',
            'email_pemilik' => 'budi@example.com',
        ]);

        $kendaraan = Kendaraan::create([
            'pengajuan_id' => $pengajuan->id,
            'pemilik_id' => $pemilik->id,
            'nrkb' => 'H 1234 AB',
            'jenis_kendaraan' => 'Mobil',
            'model_kendaraan' => 'SUV',
            'merk_kendaraan' => 'Toyota',
            'tipe_kendaraan' => 'Rush',
            'tahun_pembuatan' => 2022,
            'isi_silinder' => '1500',
            'jenis_bahan_bakar' => 'Bensin',
            'nomor_rangka' => 'MH123456789012345',
            'nomor_mesin' => 'EN123456',
            'warna_kendaraan' => 'Hitam',
            'warna_tnkb' => 'Hitam',
            'nomor_bpkb' => 'BPKB001',
            'status' => 'pengajuan',
        ]);

        // 2. WP creates log comment
        $this->actingAs($wpUser);
        
        // This should trigger notifications for Bapenda Staff in the same cabang
        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => $wpUser->id,
            'tipe' => 'komentar',
            'aksi' => 'WP Menambahkan Komentar',
            'status_baru' => 'pengajuan',
            'catatan' => 'Berkas sudah lengkap.',
        ]);

        // Verify notification is created for Bapenda Staff
        $this->assertEquals(1, $bapendaUser->unreadNotifications()->count());

        // 3. Login as Bapenda Staff, view list page
        $response = $this->actingAs($bapendaUser)->get(route('admin.pengajuan.index'));
        $response->assertOk();
        
        // Ensure unreadPengajuanIds contains our pengajuan ID
        $response->assertViewHas('unreadPengajuanIds', function($ids) use ($pengajuan) {
            return in_array($pengajuan->id, $ids);
        });

        // 4. View Detail Page as Bapenda Staff
        $response = $this->actingAs($bapendaUser)->get(route('admin.pengajuan.show', $pengajuan));
        $response->assertOk();

        // Ensure unreadLogIds contains the log ID
        $response->assertViewHas('unreadLogIds', function($ids) use ($log) {
            return in_array($log->id, $ids);
        });

        // Notifications should now be marked as read
        $this->assertEquals(0, $bapendaUser->unreadNotifications()->count());

        $bapendaUser = $bapendaUser->fresh();

        // 5. Reload/View Detail Page again
        $response = $this->actingAs($bapendaUser)->get(route('admin.pengajuan.show', $pengajuan));
        $response->assertOk();
        // unreadLogIds should be empty now
        $response->assertViewHas('unreadLogIds', function($ids) {
            return empty($ids);
        });

        // 6. Go back to index, unreadPengajuanIds should be empty
        $response = $this->actingAs($bapendaUser)->get(route('admin.pengajuan.index'));
        $response->assertOk();
        $response->assertViewHas('unreadPengajuanIds', function($ids) {
            return empty($ids);
        });
    }

    public function test_notification_flow_wajib_pajak(): void
    {
        // 1. Create cabang, users
        $cabang = Cabang::create(['nama' => 'Samsat Cilacap', 'wilayah' => 'Cilacap']);
        
        // Wajib Pajak User
        $wpRole = Role::firstOrCreate(['name' => 'wajib_pajak', 'guard_name' => 'web']);
        $wpPermission = Permission::firstOrCreate([
            'name' => 'view_menu_daftar_pengajuan',
            'guard_name' => 'web',
        ]);
        $wpRole->givePermissionTo($wpPermission);

        $wpUser = User::factory()->create([
            'unit_kerja' => null,
            'cabang_id' => $cabang->id,
        ]);
        $wpUser->assignRole($wpRole);

        // Bapenda Staff User
        $bapendaRole = Role::firstOrCreate(['name' => 'bapenda', 'guard_name' => 'web']);
        $bapendaPermission = Permission::firstOrCreate([
            'name' => 'view_menu_manajemen_pengajuan',
            'guard_name' => 'web',
        ]);
        $bapendaRole->givePermissionTo($bapendaPermission);
        
        $bapendaUser = User::factory()->create([
            'unit_kerja' => 'Bapenda',
            'cabang_id' => $cabang->id,
        ]);
        $bapendaUser->assignRole($bapendaRole);

        // Create Pengajuan for WP
        $pengajuan = Pengajuan::create([
            'user_id' => $wpUser->id,
            'cabang_id' => $cabang->id,
            'nomor_pengajuan' => 'PJ-001',
        ]);

        $pemilik = Pemilik::create([
            'nama_pemilik' => 'Budi',
            'nik_pemilik' => '3374010101010001',
            'alamat_pemilik' => 'Semarang',
            'telp_pemilik' => '08123456789',
            'email_pemilik' => 'budi@example.com',
        ]);

        $kendaraan = Kendaraan::create([
            'pengajuan_id' => $pengajuan->id,
            'pemilik_id' => $pemilik->id,
            'nrkb' => 'H 1234 AB',
            'jenis_kendaraan' => 'Mobil',
            'model_kendaraan' => 'SUV',
            'merk_kendaraan' => 'Toyota',
            'tipe_kendaraan' => 'Rush',
            'tahun_pembuatan' => 2022,
            'isi_silinder' => '1500',
            'jenis_bahan_bakar' => 'Bensin',
            'nomor_rangka' => 'MH123456789012345',
            'nomor_mesin' => 'EN123456',
            'warna_kendaraan' => 'Hitam',
            'warna_tnkb' => 'Hitam',
            'nomor_bpkb' => 'BPKB001',
            'status' => 'pengajuan',
        ]);

        // 2. Bapenda Staff creates log comment
        $this->actingAs($bapendaUser);
        
        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => $bapendaUser->id,
            'tipe' => 'komentar',
            'aksi' => 'Bapenda Menambahkan Komentar',
            'status_baru' => 'pengajuan',
            'catatan' => 'Berkas sedang diverifikasi.',
        ]);

        // Verify notification is created for WP User
        $this->assertEquals(1, $wpUser->unreadNotifications()->count());

        // 3. Login as WP, view list page
        $response = $this->actingAs($wpUser)->get(route('pengajuan.index'));
        $response->assertOk();
        
        // Ensure unreadPengajuanIds contains our pengajuan ID
        $response->assertViewHas('unreadPengajuanIds', function($ids) use ($pengajuan) {
            return in_array($pengajuan->id, $ids);
        });

        // 4. View Detail Page as WP
        $response = $this->actingAs($wpUser)->get(route('pengajuan.show', $pengajuan));
        $response->assertOk();

        // Ensure unreadLogIds contains the log ID
        $response->assertViewHas('unreadLogIds', function($ids) use ($log) {
            return in_array($log->id, $ids);
        });

        // Notifications should now be marked as read
        $this->assertEquals(0, $wpUser->unreadNotifications()->count());

        $wpUser = $wpUser->fresh();

        // 5. Reload/View Detail Page again
        $response = $this->actingAs($wpUser)->get(route('pengajuan.show', $pengajuan));
        $response->assertOk();
        // unreadLogIds should be empty now
        $response->assertViewHas('unreadLogIds', function($ids) {
            return empty($ids);
        });

        // 6. Go back to index, unreadPengajuanIds should be empty
        $response = $this->actingAs($wpUser)->get(route('pengajuan.index'));
        $response->assertOk();
        $response->assertViewHas('unreadPengajuanIds', function($ids) {
            return empty($ids);
        });
    }
}
