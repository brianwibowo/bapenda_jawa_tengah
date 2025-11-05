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
        // 1. Hapus kolom 'status' dan 'catatan_admin' dari tabel 'pengajuans'
        Schema::table('pengajuans', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pengajuans', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
        });

        // 2. Tambahkan kolom 'status' dan 'catatan_admin' ke tabel 'kendaraans'
        Schema::table('kendaraans', function (Blueprint $table) {
            if (!Schema::hasColumn('kendaraans', 'status')) {
                $table->string('status')->default('pengajuan')->after('nomor_bpkb');
            }
            if (!Schema::hasColumn('kendaraans', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('status');
            }
        });

        // 3. Hapus tabel 'pengajuan_logs' yang salah
        Schema::dropIfExists('pengajuan_logs');

        // 4. Buat tabel 'kendaraan_logs' yang benar
        Schema::create('kendaraan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang beraksi
            $table->string('aksi'); // Deskripsi (cth: "Diproses BAPENDA")
            $table->string('status_baru'); // Status kendaraan setelah aksi
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus tabel 'kendaraan_logs'
        Schema::dropIfExists('kendaraan_logs');

        // 2. Buat ulang tabel 'pengajuan_logs' (kosong)
        Schema::create('pengajuan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aksi');
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 3. Kembalikan kolom ke 'pengajuans'
        Schema::table('pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuans', 'status')) {
                $table->string('status')->default('pengajuan');
            }
            if (!Schema::hasColumn('pengajuans', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable();
            }
        });

        // 4. Hapus kolom dari 'kendaraans'
        Schema::table('kendaraans', function (Blueprint $table) {
            if (Schema::hasColumn('kendaraans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('kendaraans', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
        });
    }
};