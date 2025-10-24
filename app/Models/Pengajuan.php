<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia,  SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'catatan_admin',
        'nomor_pengajuan',
        'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
        'nrkb', 'jenis_kendaraan', 'model_kendaraan', 'merk_kendaraan', 'tipe_kendaraan',
        'tahun_pembuatan', 'isi_silinder', 'jenis_bahan_bakar', 'nomor_rangka', 'nomor_mesin',
        'warna_tnkb', 'nomor_bpkb'
    ];

    /**
     * Relasi ke user (penulis)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($pengajuan) {
            // Cek jika nomor pengajuan belum diisi
            if (empty($pengajuan->nomor_pengajuan)) {
                // Buat format: PJN-TAHUNBULAN-4ANGKAUNIK (Contoh: PJN-2510-1234)
                $prefix = 'PJN-' . now()->format('ym') . '-';
                
                // Cari nomor terakhir untuk bulan ini
                $lastPengajuan = self::where('nomor_pengajuan', 'LIKE', $prefix . '%')
                                     ->orderBy('nomor_pengajuan', 'desc')
                                     ->first();
                
                $nextNumber = 1;
                if ($lastPengajuan) {
                    $lastNumber = (int) substr($lastPengajuan->nomor_pengajuan, -4);
                    $nextNumber = $lastNumber + 1;
                }
                
                $pengajuan->nomor_pengajuan = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}