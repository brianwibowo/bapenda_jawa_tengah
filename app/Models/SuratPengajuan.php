<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPengajuan extends Model
{
    protected $fillable = [
        'pengajuan_id', 'nomor_sp', 'tanggal_surat', 
        'status_bapenda', 'status_jasa_raharja', 'catatan_instansi'
    ];

    public function pengajuan() {
        return $this->belongsTo(Pengajuan::class);
    }
}