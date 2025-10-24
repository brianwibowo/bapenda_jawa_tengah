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
        Schema::table('pengajuans', function (Blueprint $table) {
            // Tambahkan kolom untuk nomor pengajuan, letakkan setelah 'id'
            // Dibuat nullable() dan unique() agar aman saat migrasi
            $table->string('nomor_pengajuan')->nullable()->unique()->after('id');
            
            // Tambahkan kolom 'deleted_at' untuk fitur Soft Delete
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('nomor_pengajuan');
            $table->dropSoftDeletes();
        });
    }
};
