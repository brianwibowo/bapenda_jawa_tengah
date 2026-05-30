<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom pdf_balasan untuk menyimpan PDF balasan (dari Bapenda/JR saat terima)
     * tanpa menimpa pdf_url SP pengajuan asli (dari Polda).
     */
    public function up(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_pengajuans', 'pdf_url')) {
                $table->string('pdf_url')->nullable();
            }
            if (!Schema::hasColumn('surat_pengajuans', 'pdf_balasan_url')) {
                $table->string('pdf_balasan_url')->nullable();
            }
            if (!Schema::hasColumn('surat_pengajuans', 'local_pdf_balasan_path')) {
                $table->string('local_pdf_balasan_path')->nullable();
            }
            if (!Schema::hasColumn('surat_pengajuans', 'instansi_pembuat')) {
                $table->string('instansi_pembuat')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['pdf_balasan_url', 'local_pdf_balasan_path', 'instansi_pembuat']);
        });
    }
};
