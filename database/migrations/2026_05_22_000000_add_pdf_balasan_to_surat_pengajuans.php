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
            $table->string('pdf_balasan_url')->nullable()->after('pdf_url');
            $table->string('local_pdf_balasan_path')->nullable()->after('pdf_balasan_url');
            // instansi_pembuat: siapa yang membuat SP ini (Samsat, Polda, Bapenda, JR)
            // Digunakan untuk membedakan SP pengajuan vs SP balasan tanpa entitas terpisah
            $table->string('instansi_pembuat')->nullable()->after('local_pdf_balasan_path');
        });
    }

    public function down(): void
    {
        Schema::table('surat_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['pdf_balasan_url', 'local_pdf_balasan_path', 'instansi_pembuat']);
        });
    }
};
