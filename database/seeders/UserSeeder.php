<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Struktur Baru: unit_kerja membatasi lingkup data Instansi, role membatasi jabatan fitur.
        // Semua Password Dummy: 12345678

        // === POLDA ===
        $poldaKepala = User::create([
            'name' => 'Jenderal Polda',
            'email' => 'polda_kepala@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Polda'
        ]);
        $poldaKepala->assignRole('kepala_instansi');
        $poldaKepala->assignRole('polda');

        $poldaAdmin = User::create([
            'name' => 'Admin Polda',
            'email' => 'polda_admin@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Polda'
        ]);
        $poldaAdmin->assignRole('admin_instansi');
        $poldaAdmin->assignRole('polda');

        // === SAMSAT ===
        $samsatAdmin = User::create([
            'name' => 'Admin Samsat Raya',
            'email' => 'samsat_admin@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Samsat'
        ]);
        $samsatAdmin->assignRole('admin_instansi');
        $samsatAdmin->assignRole('samsat');

        // === BAPENDA ===
        $bapendaStaff = User::create([
            'name' => 'Staff Bapenda',
            'email' => 'bapenda_staff@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Bapenda'
        ]);
        $bapendaStaff->assignRole('staff_instansi');
        $bapendaStaff->assignRole('bapenda');

        // === JASA RAHARJA ===
        $jrKepala = User::create([
            'name' => 'Kepala Jasa Raharja',
            'email' => 'jr_kepala@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Jasa Raharja'
        ]);
        $jrKepala->assignRole('kepala_instansi');
        $jrKepala->assignRole('jasa_raharja');

        // === WAJIB PAJAK ===
        $wp = User::create([
            'name' => 'Budi Wajib Pajak',
            'email' => 'wp@example.com',
            'password' => Hash::make('12345678'),
            'unit_kerja' => 'Wajib Pajak'
        ]);
        $wp->assignRole('wajib_pajak');
    }
}
