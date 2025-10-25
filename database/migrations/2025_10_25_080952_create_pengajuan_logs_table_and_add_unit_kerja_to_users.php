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
        // 1. Buat tabel baru untuk log histori pengajuan
        Schema::create('pengajuan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade'); // Relasi ke pengajuan
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang melakukan aksi
            $table->string('aksi'); // Deskripsi aksi (misal: 'Diproses BAPENDA', 'Ditolak SAMSAT')
            $table->string('status_baru'); // Status pengajuan setelah aksi ini (pengajuan, diproses, selesai, ditolak)
            $table->text('catatan')->nullable(); // Catatan dari admin saat itu
            // Kolom 'lampiran' akan ditangani oleh Spatie Media Library, jadi tidak perlu di sini.
            $table->timestamps(); // Kapan aksi ini terjadi (created_at)
        });

        // 2. Tambahkan kolom unit_kerja ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('unit_kerja')->nullable()->after('email'); // Kolom untuk menyimpan afiliasi (misal: 'BAPENDA JATENG')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel logs
        Schema::dropIfExists('pengajuan_logs');

        // Hapus kolom unit_kerja dari users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('unit_kerja');
        });
    }
};