<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kendaraan_logs', 'sp_status')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->string('sp_status')->nullable()->after('sp_id')
                      ->comment('null=regular, draft=belum terbit, terbit=sudah terbit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kendaraan_logs', 'sp_status')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->dropColumn('sp_status');
            });
        }
    }
};
