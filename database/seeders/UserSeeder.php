<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat user dengan role superadmin
        try {
            $superadmin = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $superadmin->assignRole('superadmin');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user superadmin: ' . $e->getMessage());
        }

        try {
            // 2. Buat user dengan role admin
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
        $admin->assignRole('admin');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user admin: ' . $e->getMessage());
        }

        try {
            // 3. Buat user dengan role penulis
            $penulis = User::create([
                'name' => 'Penulis',
                'email' => 'penulis@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $penulis->assignRole('penulis');
        } catch  (\Exception $e) {
            $this->command->error('Gagal membuat user penulis: ' . $e->getMessage());
        }

        try {
            // 4. Buat user dengan role polda
            $polda = User::create([
                'name' => 'Polda',
                'email' => 'polda@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $polda->assignRole('polda');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user polda: ' . $e->getMessage());
        }

        try {
            // 5. Buat user dengan role samsat
            $samsat = User::create([
                'name' => 'Samsat',
                'email' => 'samsat@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $samsat->assignRole('samsat');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user samsat: ' . $e->getMessage());
        }

        try {
            // 6. Buat user dengan role bapenda
            $bapenda = User::create([
                'name' => 'Bapenda',
                'email' => 'bapenda@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $bapenda->assignRole('bapenda');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user bapenda: ' . $e->getMessage());
        }

        try {
            // 7. Buat user dengan role jr
            $jr = User::create([
                'name' => 'Jasa Raharja',
                'email' => 'jr@example.com',
                'password' => Hash::make('12345678'), // Password: 12345678
            ]);
            $jr->assignRole('jr');
        } catch (\Exception $e) {
            $this->command->error('Gagal membuat user jr: ' . $e->getMessage());
        }
    }
}
