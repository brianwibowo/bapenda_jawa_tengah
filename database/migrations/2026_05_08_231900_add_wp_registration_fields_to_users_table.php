<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('unit_kerja');
            $table->char('domisili_regency_id', 4)->nullable()->after('cabang_id');
            $table->timestamp('taxpayer_statement_accepted_at')->nullable()->after('domisili_regency_id');
            $table->timestamp('law_compliance_statement_accepted_at')->nullable()->after('taxpayer_statement_accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'unit',
                'domisili_regency_id',
                'taxpayer_statement_accepted_at',
                'law_compliance_statement_accepted_at',
            ]);
        });
    }
};
