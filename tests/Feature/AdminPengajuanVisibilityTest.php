<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Kendaraan;
use App\Models\Pemilik;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPengajuanVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_samsat_visibility(): void // Samsat melihat pengajuan cabang sendiri
    {
        [$cabangA, $cabangB] = $this->createCabangs();
        $samsat = $this->createOfficer('samsat', 'Samsat', $cabangA->id);

        $pengajuanCabangA = Pengajuan::create([
            'user_id' => User::factory()->create()->id,
            'cabang_id' => $cabangA->id,
        ]);
        $pengajuanCabangB = Pengajuan::create([
            'user_id' => User::factory()->create()->id,
            'cabang_id' => $cabangB->id,
        ]);

        $response = $this->actingAs($samsat)->get(route('admin.pengajuan.index'));

        $response->assertOk();
        $response->assertSee($pengajuanCabangA->nomor_pengajuan);
        $response->assertDontSee($pengajuanCabangB->nomor_pengajuan);
    }

    public function test_samsat_filter(): void   // Samsat tidak melihat dropdown cabang
    {
        [$cabangA] = $this->createCabangs();
        $samsat = $this->createOfficer('samsat', 'Samsat', $cabangA->id);

        $response = $this->actingAs($samsat)->get(route('admin.pengajuan.index'));

        $response->assertOk();
        $response->assertDontSee('-- Filter Cabang / Wilayah --');
    }

    public function test_polda_visibility(): void // Polda melihat semua pengajuan dari semua cabang
    {
        [$cabangA, $cabangB] = $this->createCabangs();
        $polda = $this->createOfficer('polda', 'Polda', $cabangA->id);

        $pengajuanCabangA = Pengajuan::create([
            'user_id' => User::factory()->create()->id,
            'cabang_id' => $cabangA->id,
        ]);
        $pengajuanCabangB = Pengajuan::create([
            'user_id' => User::factory()->create()->id,
            'cabang_id' => $cabangB->id,
        ]);

        $response = $this->actingAs($polda)->get(route('admin.pengajuan.index'));

        $response->assertOk();
        $response->assertSee($pengajuanCabangA->nomor_pengajuan);
        $response->assertSee($pengajuanCabangB->nomor_pengajuan);
    }

    public function test_non_samsat_filter(): void // Polda melihat dropdown cabang
    {
        [$cabangA] = $this->createCabangs();
        $polda = $this->createOfficer('polda', 'Polda', $cabangA->id);

        $response = $this->actingAs($polda)->get(route('admin.pengajuan.index'));

        $response->assertOk();
        $response->assertSee('-- Filter Cabang / Wilayah --');
    }

    public function test_search_name(): void // Polda dapat mencari pengajuan berdasarkan nama pemilik
    {
        [$cabangA] = $this->createCabangs();
        $polda = $this->createOfficer('polda', 'Polda');

        $pemilik = Pemilik::create([
            'nama_pemilik' => 'Budi Santoso',
            'nik_pemilik' => '3374010101010001',
            'alamat_pemilik' => 'Semarang',
            'telp_pemilik' => '08123456789',
            'email_pemilik' => 'budi@example.com',
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => User::factory()->create()->id,
            'cabang_id' => $cabangA->id,
        ]);

        Kendaraan::create([
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

        $response = $this->actingAs($polda)->get(route('admin.pengajuan.index', [
            'search' => 'Budi Santoso',
        ]));

        $response->assertOk();
        $response->assertSee($pengajuan->nomor_pengajuan);
    }

    private function createOfficer(string $roleName, string $unitKerja, ?int $cabangId = null): User
    {
        $permission = Permission::firstOrCreate([
            'name' => 'view_menu_manajemen_pengajuan',
            'guard_name' => 'web',
        ]);
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        $user = User::factory()->create([
            'unit_kerja' => $unitKerja,
            'cabang_id' => $cabangId,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    /**
     * @return array<int, Cabang>
     */
    private function createCabangs(): array
    {
        return [
            Cabang::create(['nama' => 'Samsat Cilacap', 'wilayah' => 'Cilacap']),
            Cabang::create(['nama' => 'Samsat Pekalongan', 'wilayah' => 'Pekalongan']),
        ];
    }
}
