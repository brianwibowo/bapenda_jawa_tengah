<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kendaraan_logs', 'sk_status')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->string('sk_status')->nullable()->after('sk_id')
                    ->comment('Status SK pada log: null (bukan SK), draft (hanya visible pembuat), terbit (visible semua)');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kendaraan_logs', 'sk_status')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->dropColumn('sk_status');
            });
        }
    }
};
