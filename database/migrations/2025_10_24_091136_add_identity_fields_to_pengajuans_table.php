<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // Kolom Identitas Pemilik
            $table->string('nama_pemilik')->after('user_id');
            $table->string('nik_pemilik')->after('nama_pemilik');
            $table->text('alamat_pemilik')->after('nik_pemilik');
            $table->string('telp_pemilik')->after('alamat_pemilik');
            $table->string('email_pemilik')->after('telp_pemilik');

            // Kolom Identitas Kendaraan
            $table->string('nrkb')->after('email_pemilik'); // Nomor Registrasi Kendaraan Bermotor
            $table->string('jenis_kendaraan')->after('nrkb');
            $table->string('model_kendaraan')->after('jenis_kendaraan');
            $table->string('merk_kendaraan')->after('model_kendaraan');
            $table->string('tipe_kendaraan')->after('merk_kendaraan');
            $table->year('tahun_pembuatan')->after('tipe_kendaraan');
            $table->string('isi_silinder')->after('tahun_pembuatan');
            $table->string('jenis_bahan_bakar')->after('isi_silinder');
            $table->string('nomor_rangka')->after('jenis_bahan_bakar');
            $table->string('nomor_mesin')->after('nomor_rangka');
            $table->string('warna_tnkb')->after('nomor_mesin');
            $table->string('nomor_bpkb')->after('warna_tnkb');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
                'nrkb', 'jenis_kendaraan', 'model_kendaraan', 'merk_kendaraan', 'tipe_kendaraan',
                'tahun_pembuatan', 'isi_silinder', 'jenis_bahan_bakar', 'nomor_rangka', 'nomor_mesin',
                'warna_tnkb', 'nomor_bpkb'
            ]);
        });
    }
};