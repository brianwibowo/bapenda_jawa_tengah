<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan_logs', function (Blueprint $table) {
            $table->json('revisi_fields')->nullable()->after('catatan');
            $table->timestamp('revisi_resolved_at')->nullable()->after('revisi_fields');
        });
    }

    public function down(): void
    {
        Schema::table('kendaraan_logs', function (Blueprint $table) {
            $table->dropColumn(['revisi_fields', 'revisi_resolved_at']);
        });
    }
};
