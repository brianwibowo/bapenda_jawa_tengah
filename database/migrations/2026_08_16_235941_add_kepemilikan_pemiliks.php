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
        Schema::table('pemiliks', function (Blueprint $table) {
            // Menambahkan kolom kepemilikan ke tabel pemiliks
            $table->enum('kepemilikan', ['instansi', 'perorangan'])
                ->nullable()
                ->after('email_pemilik')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemiliks', function (Blueprint $table) {
            // Menghapus kolom kepemilikan dari tabel pemiliks
            $table->dropColumn('kepemilikan');
        });
    }
};
