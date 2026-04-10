<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPengajuan extends Model
{
    protected $fillable = [
        'pengajuan_id', 'nomor_sp', 'tanggal_surat', 
        'persetujuan_unit_kerja', 'catatan_instansi'
    ];

    protected $casts = [
        'persetujuan_instansi' => 'array',
        'tanggal_surat' => 'date'
    ];

    public function pengajuan() {
        return $this->belongsTo(Pengajuan::class);
    }

    public function isFullyApproved()
    {
        foreach ($this->persetujuan_instansi as $item) {
            if ($item['status'] !== 'approved') {
                return false;
            }
        }
        return true;
    }
    
}