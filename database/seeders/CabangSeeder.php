<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['nama' => 'Samsat Semarang I', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Brigjen Sudiarto No.428, Pedurungan, Kota Semarang'],
            ['nama' => 'Samsat Semarang II', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Setiabudi No.110, Banyumanik, Kota Semarang'],
            ['nama' => 'Samsat Semarang III', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Hanoman Raya No.2, Krapyak, Kota Semarang'],
            ['nama' => 'Samsat Kab. Semarang (Ungaran)', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. MT. Hariyono, Sidomulyo, Ungaran Timur'],
            ['nama' => 'Samsat Salatiga', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Brigjen Sudiarto No.7, Salatiga'],
            ['nama' => 'Samsat Kendal', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Pemuda No.50, Kendal'],
            ['nama' => 'Samsat Demak', 'wilayah' => 'Semarang & Sekitarnya', 'alamat' => 'Jl. Sultan Trenggono No.87, Demak'],
            ['nama' => 'Samsat Surakarta', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. DR. Radjiman No.467, Laweyan, Kota Surakarta'],
            ['nama' => 'Samsat Sukoharjo', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Jend. Sudirman No.183, Sukoharjo'],
            ['nama' => 'Samsat Karanganyar', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Jend. Gatot Subroto No.10, Karanganyar'],
            ['nama' => 'Samsat Boyolali', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Pandanaran No.158, Boyolali'],
            ['nama' => 'Samsat Sragen', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Raya Sukowati No.121, Sragen'],
            ['nama' => 'Samsat Klaten', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Pemuda No.12, Klaten'],
            ['nama' => 'Samsat Wonogiri', 'wilayah' => 'Solo Raya (Surakarta)', 'alamat' => 'Jl. Jend. Sudirman No.210, Wonogiri'],
            ['nama' => 'Samsat Pati', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Jend. Sudirman No.54, Pati'],
            ['nama' => 'Samsat Kudus', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Mejobo No.12, Kudus'],
            ['nama' => 'Samsat Jepara', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Kartini No.34, Jepara'],
            ['nama' => 'Samsat Rembang', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Pemuda No.55, Rembang'],
            ['nama' => 'Samsat Blora', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Pemuda No.42, Blora'],
            ['nama' => 'Samsat Grobogan', 'wilayah' => 'Pati & Muria', 'alamat' => 'Jl. Gajah Mada No.25, Purwodadi'],
            ['nama' => 'Samsat Kota Pekalongan', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. DR. Wahidin No.56, Kota Pekalongan'],
            ['nama' => 'Samsat Kab. Pekalongan (Kajen)', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Raya Kajen, Kab. Pekalongan'],
            ['nama' => 'Samsat Batang', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Jend. Sudirman No.240, Batang'],
            ['nama' => 'Samsat Pemalang', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Jend. Sudirman No.60, Pemalang'],
            ['nama' => 'Samsat Kota Tegal', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Kapten Sudibyo No.152, Tegal Barat, Kota Tegal'],
            ['nama' => 'Samsat Kab. Tegal (Slawi)', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Jend. Sudirman, Slawi'],
            ['nama' => 'Samsat Brebes', 'wilayah' => 'Pekalongan & Pantura Barat', 'alamat' => 'Jl. Jend. Sudirman No.118, Brebes'],
            ['nama' => 'Samsat Kota Magelang', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Jend. Sudirman No.45, Kota Magelang'],
            ['nama' => 'Samsat Kab. Magelang (Mungkid)', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Soekarno-Hatta, Mungkid'],
            ['nama' => 'Samsat Temanggung', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Jend. Sudirman No.10, Temanggung'],
            ['nama' => 'Samsat Wonosobo', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Jend. Gatot Subroto No.5, Wonosobo'],
            ['nama' => 'Samsat Purworejo', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Jend. Sudirman No.12, Purworejo'],
            ['nama' => 'Samsat Kebumen', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Tentara Pelajar No.54, Panjer, Kebumen'],
            ['nama' => 'Samsat Banyumas (Purwokerto)', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Prof. DR. Bunyamin, Purwokerto'],
            ['nama' => 'Samsat Cilacap', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Jend. Sudirman No.12, Cilacap'],
            ['nama' => 'Samsat Purbalingga', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Mayjen Sungkono, Purbalingga'],
            ['nama' => 'Samsat Banjarnegara', 'wilayah' => 'Kedu & Banyumas', 'alamat' => 'Jl. Selamanik No.12, Banjarnegara'],
        ];

        foreach ($cabangs as $data) {
            Cabang::updateOrCreate(
                ['nama' => $data['nama'], 'wilayah' => $data['wilayah']],
                ['alamat' => $data['alamat']]
            );
        }
    }
}
