<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Struktur Baru: unit_kerja membatasi lingkup data Instansi, role membatasi jabatan fitur.
        // Semua Password Dummy: 12345678

        // === POLDA ===
        $poldaKepala = User::updateOrCreate(
            ['email' => 'polda_kepala@example.com'],
            [
                'name' => 'Jenderal Polda',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Polda',
            ]
        );
        $poldaKepala->assignRole(['kepala_instansi', 'polda']);

        $poldaAdmin = User::updateOrCreate(
            ['email' => 'polda_admin@example.com'],
            [
                'name' => 'Admin Polda',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Polda',
            ]
        );
        $poldaAdmin->assignRole(['admin_instansi', 'polda']);

        // === SAMSAT ===
        $samsatAdmin = User::updateOrCreate(
            ['email' => 'samsat_admin@example.com'],
            [
                'name' => 'Admin Samsat Raya',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Samsat',
            ]
        );
        $samsatAdmin->assignRole(['admin_instansi', 'samsat']);

        // === BAPENDA ===
        $bapendaStaff = User::updateOrCreate(
            ['email' => 'bapenda_staff@example.com'],
            [
                'name' => 'Staff Bapenda',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Bapenda',
            ]
        );
        $bapendaStaff->assignRole(['staff_instansi', 'bapenda']);

        // === JASA RAHARJA ===
        $jrKepala = User::updateOrCreate(
            ['email' => 'jr_kepala@example.com'],
            [
                'name' => 'Kepala Jasa Raharja',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Jasa Raharja',
            ]
        );
        $jrKepala->assignRole(['kepala_instansi', 'jasa_raharja']);

        // === WAJIB PAJAK ===
        $cabangSemarang = Cabang::firstOrCreate(
            ['nama' => 'Samsat kota semarang 1', 'wilayah' => 'Semarang']
        );

        $wp = User::updateOrCreate(
            ['email' => 'wp@example.com'],
            [
                'name' => 'Budi Wajib Pajak',
                'password' => Hash::make('12345678'),
                'unit_kerja' => 'Wajib Pajak Semarang',
                'jabatan' => 'Pemilik Kendaraan',
                'cabang_id' => $cabangSemarang->id,
            ]
        );
        $wp->assignRole('wajib_pajak');
    }
}
