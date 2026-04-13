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
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Admin yang membuat SK
            
            // Klasifikasi Instansi: polda / bapenda / jasa_raharja
            $table->string('unit_kerja');
            $table->string('nomor_sk')->unique();
            $table->string('perihal');
            $table->text('isi_putusan');
            $table->date('tanggal_ditetapkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keputusans');
    }
};
