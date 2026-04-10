<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{
    protected $fillable = [
        'pengajuan_id', 'user_id', 'instansi', 
        'nomor_sk', 'perihal', 'isi_putusan', 'tanggal_ditetapkan'
    ];

    public function pengajuan() {
        return $this->belongsTo(Pengajuan::class);
    }

    public function pembuat() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
