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
            ['nama' => 'Markas Besar Polda Jateng', 'wilayah' => 'Semarang'],
            ['nama' => 'Polrestabes Semarang', 'wilayah' => 'Semarang'],
            ['nama' => 'Polresta Surakarta', 'wilayah' => 'Surakarta'],
            ['nama' => 'Polresta Magelang', 'wilayah' => 'Magelang'],
            ['nama' => 'Polres Magelang Kota', 'wilayah' => 'Magelang Kota'],
            ['nama' => 'Polres Purworejo', 'wilayah' => 'Purworejo'],
            ['nama' => 'Polres Kebumen', 'wilayah' => 'Kebumen'],
            ['nama' => 'Polres Temanggung', 'wilayah' => 'Temanggung'],
            ['nama' => 'Polres Wonosobo', 'wilayah' => 'Wonosobo'],
            ['nama' => 'Polres Tegal', 'wilayah' => 'Tegal'],
            ['nama' => 'Polres Kudus', 'wilayah' => 'Demak'],
        ];

        foreach ($cabangs as $data) {
            Cabang::firstOrCreate($data);
        }
    }
}
