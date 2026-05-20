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
        Schema::create('surat_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->string('nomor_sp')->unique(); // Contoh: SP/2026/001
            $table->date('tanggal_surat');
            
            // File path lampiran
            $table->string('local_pdf_path')->nullable();
            $table->string('pdf_url')->nullable(); // URL akses file PDF

            // Status Kelulusan (Multi-Institutional)
            $table->enum('status_bapenda', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_jasa_raharja', ['pending', 'approved', 'rejected'])->default('pending');
            
            
            $table->text('catatan_instansi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuans');
    }
};
