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
        // Kolum untuk sk_id dan sp_id pada tabel kendaraan_logs
        Schema::table('kendaraan_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('sk_id')->nullable()->after('catatan');
            $table->unsignedBigInteger('sp_id')->nullable()->after('sk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kolum untuk sk_id dan sp_id pada tabel kendaraan_logs
        Schema::table('kendaraan_logs', function (Blueprint $table) {
            $table->dropColumn('sk_id');
            $table->dropColumn('sp_id');
        });
    }
};
