<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. BUAT tabel 'kendaraans' baru (BUKAN 'alter')
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');

            // Tambahkan kolom Pemilik di sini
            $table->string('nama_pemilik');
            $table->string('nik_pemilik');
            $table->text('alamat_pemilik');
            $table->string('telp_pemilik');
            $table->string('email_pemilik');

            // Tambahkan kolom Kendaraan di sini
            $table->string('nrkb');
            $table->string('jenis_kendaraan');
            $table->string('model_kendaraan');
            $table->string('merk_kendaraan');
            $table->string('tipe_kendaraan');
            $table->year('tahun_pembuatan');
            $table->string('isi_silinder');
            $table->string('jenis_bahan_bakar');
            $table->string('nomor_rangka');
            $table->string('nomor_mesin');
            $table->string('warna_tnkb');
            $table->string('nomor_bpkb');

            $table->timestamps();
        });

        // 2. HAPUS kolom-kolom yang sudah dipindah dari 'pengajuans'
        // Kolom ini dibuat oleh migrasi 'add_identity_fields_to_pengajuans_table'
        Schema::table('pengajuans', function (Blueprint $table) {
            // Cek dulu apakah kolomnya ada sebelum dihapus, agar aman
            if (Schema::hasColumn('pengajuans', 'nama_pemilik')) {
                $table->dropColumn([
                    'nama_pemilik',
                    'nik_pemilik',
                    'alamat_pemilik',
                    'telp_pemilik',
                    'email_pemilik',
                    'nrkb',
                    'jenis_kendaraan',
                    'model_kendaraan',
                    'merk_kendaraan',
                    'tipe_kendaraan',
                    'tahun_pembuatan',
                    'isi_silinder',
                    'jenis_bahan_bakar',
                    'nomor_rangka',
                    'nomor_mesin',
                    'warna_tnkb',
                    'nomor_bpkb'
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Tambahkan kembali kolom ke 'pengajuans'
        Schema::table('pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuans', 'nama_pemilik')) {
                $table->string('nama_pemilik')->nullable();
                $table->string('nik_pemilik')->nullable();
                $table->text('alamat_pemilik')->nullable();
                $table->string('telp_pemilik')->nullable();
                $table->string('email_pemilik')->nullable();
                $table->string('nrkb')->nullable();
                $table->string('jenis_kendaraan')->nullable();
                $table->string('model_kendaraan')->nullable();
                $table->string('merk_kendaraan')->nullable();
                $table->string('tipe_kendaraan')->nullable();
                $table->year('tahun_pembuatan')->nullable();
                $table->string('isi_silinder')->nullable();
                $table->string('jenis_bahan_bakar')->nullable();
                $table->string('nomor_rangka')->nullable();
                $table->string('nomor_mesin')->nullable();
                $table->string('warna_tnkb')->nullable();
                $table->string('nomor_bpkb')->nullable();
            }
        });

        // 2. Hapus tabel 'kendaraans'
        Schema::dropIfExists('kendaraans');
    }
};