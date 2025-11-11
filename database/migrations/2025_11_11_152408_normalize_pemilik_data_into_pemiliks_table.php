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
        // 1. Buat tabel 'pemiliks' baru untuk data unik
        Schema::create('pemiliks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->string('nik_pemilik')->unique(); // NIK harus unik
            $table->text('alamat_pemilik');
            $table->string('telp_pemilik');
            $table->string('email_pemilik')->nullable();
            $table->timestamps();
        });

        // 2. Modifikasi tabel 'kendaraans'
        Schema::table('kendaraans', function (Blueprint $table) {
            // Tambahkan foreign key 'pemilik_id'
            // Kita buat 'nullable' agar aman, dan letakkan setelah 'pengajuan_id'
            $table->foreignId('pemilik_id')->nullable()->after('pengajuan_id')->constrained('pemiliks')->nullOnDelete();

            // Hapus kolom-kolom redundan
            $table->dropColumn([
                'nama_pemilik',
                'nik_pemilik',
                'alamat_pemilik',
                'telp_pemilik',
                'email_pemilik'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Kembalikan kolom-kolom ke 'kendaraans'
        Schema::table('kendaraans', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['pemilik_id']);
            $table->dropColumn('pemilik_id');

            // Tambahkan lagi kolom-kolom lama
            $table->string('nama_pemilik')->after('pengajuan_id');
            $table->string('nik_pemilik')->after('nama_pemilik');
            $table->text('alamat_pemilik')->after('nik_pemilik');
            $table->string('telp_pemilik')->after('alamat_pemilik');
            $table->string('email_pemilik')->after('telp_pemilik');
        });

        // 2. Hapus tabel 'pemiliks'
        Schema::dropIfExists('pemiliks');
    }
};