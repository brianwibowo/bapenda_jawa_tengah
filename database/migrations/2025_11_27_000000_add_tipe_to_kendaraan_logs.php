<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('kendaraan_logs', 'tipe')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->string('tipe')->nullable()->after('aksi')->comment('tipe log: komentar|revisi|admin|system');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('kendaraan_logs', 'tipe')) {
            Schema::table('kendaraan_logs', function (Blueprint $table) {
                $table->dropColumn('tipe');
            });
        }
    }
};
