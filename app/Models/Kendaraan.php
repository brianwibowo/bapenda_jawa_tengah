<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Kendaraan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengajuan_id',
        
        'nama_pemilik',
        'nik_pemilik',
        'alamat_pemilik',
        'telp_pemilik',
        'email_pemilik',
        
        'nrkb',
        'jenis_kendaraan',
        'model_kendaraan',
        'merk_kendaraan',
        'tipe_kendaraan',
        'tahun_pembuatan',
        'isi_silinder',
        'jenis_bahan_bakar',
        'nomor_rangka',
        'nomor_mesin',
        'warna_tnkb',
        'nomor_bpkb',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}