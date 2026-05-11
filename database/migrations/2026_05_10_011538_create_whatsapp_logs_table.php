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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();
            $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraans')->nullOnDelete();
            $table->string('no_hp_tujuan', 30);
            $table->string('sk_type', 50); // regident, polda, pembebasan
            $table->string('file_url')->nullable();
            $table->text('message_preview')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->json('fonnte_response')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
