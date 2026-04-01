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
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $superadmin->assignRole('superadmin');

        // 2. Buat user dengan role admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $admin->assignRole('admin');

        // 3. Buat user dengan role penulis
        $penulis = User::create([
            'name' => 'Penulis',
            'email' => 'penulis@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $penulis->assignRole('penulis');


        $penulis = User::create([
            'name' => 'Polda',
            'email' => 'polda@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $penulis->assignRole('polda');

        $penulis = User::create([
            'name' => 'Samsat',
            'email' => 'samsat@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $penulis->assignRole('samsat');

        $bapenda = User::create([
            'name' => 'Bapenda',
            'email' => 'bapenda@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $bapenda->assignRole('bapenda');

        $jr = User::create([
            'name' => 'Jasa Raharja',
            'email' => 'jr@example.com',
            'password' => Hash::make('12345678'), // Password: 12345678
        ]);
        $jr->assignRole('jr');
    }
}
