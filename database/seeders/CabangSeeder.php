<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['nama' => 'Samsat kota semarang 1', 'wilayah' => 'Semarang'],
            ['nama' => 'Samsat kota Tegal', 'wilayah' => 'Tegal'],
            ['nama' => 'Samsat kota Pekalongan', 'wilayah' => 'Pekalongan'],
            ['nama' => 'Samsat kota kudus', 'wilayah' => 'kudus'],
            ['nama' => 'Samsat kota Demak', 'wilayah' => 'Demak'],
        ];

        foreach ($cabangs as $data) {
            Cabang::firstOrCreate($data);
        }
    }
}
