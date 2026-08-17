<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DashboardSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $faker = \Faker\Factory::create('id_ID');

    //     // Ensure we have at least one user to attach pengajuans to
    //     $user = User::first();
    //     if (! $user) {
    //         $user = User::firstOrCreate(
    //             ['email' => 'seed.user@example.com'],
    //             [
    //                 'name' => 'Seed User',
    //                 'password' => Hash::make('password'),
    //                 'email_verified_at' => now(),
    //                 'unit_kerja' => 'Seed'
    //             ]
    //         );
    //     }

    //     $jenisOptions = ['Sepeda Motor', 'Mobil Penumpang', 'Truk', 'Bus', 'Mobil Barang'];

    //     for ($i = 1; $i <= 10; $i++) {
    //         $pengajuanData = [
    //             'user_id' => $user->id,
    //             'created_at' => now()->subDays(rand(0, 180)),
    //             'updated_at' => now(),
    //         ];

    //         if (Schema::hasColumn('pengajuans', 'status')) {
    //             $pengajuanData['status'] = $faker->randomElement(['pengajuan', 'diproses', 'selesai']);
    //         }
    //         if (Schema::hasColumn('pengajuans', 'catatan_admin')) {
    //             $pengajuanData['catatan_admin'] = null;
    //         }

    //         $pengajuanId = DB::table('pengajuans')->insertGetId($pengajuanData);

    //         // Handle normalized pemilik schema: if kendaraans has pemilik_id, create pemiliks entry
    //         if (Schema::hasColumn('kendaraans', 'pemilik_id') && Schema::hasTable('pemiliks')) {
    //             $pemilikId = DB::table('pemiliks')->insertGetId([
    //                 'kepemilikan' => $faker->randomElement(['perorangan', 'instansi']),
    //                 'nama_pemilik' => $faker->name,
    //                 'nik_pemilik' => $faker->numerify('################'),
    //                 'alamat_pemilik' => $faker->address,
    //                 'telp_pemilik' => $faker->phoneNumber,
    //                 'email_pemilik' => $faker->safeEmail,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);

    //             DB::table('kendaraans')->insert([
    //                 'pengajuan_id' => $pengajuanId,
    //                 'pemilik_id' => $pemilikId,
    //                 'nrkb' => strtoupper($faker->bothify('??###??')),
    //                 'jenis_kendaraan' => $faker->randomElement($jenisOptions),
    //                 'model_kendaraan' => $faker->word,
    //                 'merk_kendaraan' => $faker->company,
    //                 'tipe_kendaraan' => $faker->word,
    //                 'tahun_pembuatan' => $faker->numberBetween(2005, 2024),
    //                 'isi_silinder' => (string)$faker->numberBetween(100, 3000),
    //                 'jenis_bahan_bakar' => $faker->randomElement(['Bensin','Solar','Listrik']),
    //                 'nomor_rangka' => strtoupper($faker->bothify('??######??')),
    //                 'nomor_mesin' => strtoupper($faker->bothify('##########')),
    //                 'warna_tnkb' => $faker->safeColorName,
    //                 'nomor_bpkb' => strtoupper($faker->bothify('BPKB-####-####')),
    //                 'created_at' => now()->subDays(rand(0, 180)),
    //                 'updated_at' => now()
    //             ]);
    //         } else {
    //             // Fallback to old schema if pemilik fields still exist on kendaraans
    //             DB::table('kendaraans')->insert([
    //                 'pengajuan_id' => $pengajuanId,
    //                 'nama_pemilik' => $faker->name,
    //                 'nik_pemilik' => $faker->numerify('################'),
    //                 'alamat_pemilik' => $faker->address,
    //                 'telp_pemilik' => $faker->phoneNumber,
    //                 'email_pemilik' => $faker->safeEmail,
    //                 'nrkb' => strtoupper($faker->bothify('??###??')),
    //                 'jenis_kendaraan' => $faker->randomElement($jenisOptions),
    //                 'model_kendaraan' => $faker->word,
    //                 'merk_kendaraan' => $faker->company,
    //                 'tipe_kendaraan' => $faker->word,
    //                 'tahun_pembuatan' => $faker->numberBetween(2005, 2024),
    //                 'isi_silinder' => (string)$faker->numberBetween(100, 3000),
    //                 'jenis_bahan_bakar' => $faker->randomElement(['Bensin','Solar','Listrik']),
    //                 'nomor_rangka' => strtoupper($faker->bothify('??######??')),
    //                 'nomor_mesin' => strtoupper($faker->bothify('##########')),
    //                 'warna_tnkb' => $faker->safeColorName,
    //                 'nomor_bpkb' => strtoupper($faker->bothify('BPKB-####-####')),
    //                 'created_at' => now()->subDays(rand(0, 180)),
    //                 'updated_at' => now()
    //             ]);
    //         }
    //     }

    //     $this->command->info('DashboardSampleSeeder: inserted 10 pengajuans + kendaraans');
    // }
}
