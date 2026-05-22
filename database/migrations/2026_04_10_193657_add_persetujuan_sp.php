<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel surat_pengajuans.
     */
    public function up(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['status_bapenda', 'status_jasa_raharja']);
            $table->json('persetujuan_unit_kerja')->after('tanggal_surat')->nullable();
        });
    }

    /**
     * Membatalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuans');
    }
};