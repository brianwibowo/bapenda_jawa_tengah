<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan HasMany
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $pengajuan_id
 * @property string $nama_pemilik
 * @property string $nik_pemilik
 * @property string $alamat_pemilik
 * @property string $telp_pemilik
 * @property string $email_pemilik
 * @property string $nrkb
 * @property string $jenis_kendaraan
 * @property string $model_kendaraan
 * @property string $merk_kendaraan
 * @property string $tipe_kendaraan
 * @property string $tahun_pembuatan
 * @property string $isi_silinder
 * @property string $jenis_bahan_bakar
 * @property string $nomor_rangka
 * @property string $nomor_mesin
 * @property string $warna_tnkb
 * @property string $nomor_bpkb
 * @property string $status
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KendaraanLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Pengajuan $pengajuan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereAlamatPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereEmailPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereIsiSilinder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereJenisBahanBakar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereJenisKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereMerkKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereModelKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNamaPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNikPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorBpkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNomorRangka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereNrkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTahunPembuatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTelpPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereTipeKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kendaraan whereWarnaTnkb($value)
 * @mixin \Eloquent
 */
class Kendaraan extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pengajuan_id',
        
        // Kolom Pemilik
        'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
        
        // Kolom Kendaraan
        'nrkb', 'jenis_kendaraan', 'model_kendaraan', 'merk_kendaraan', 'tipe_kendaraan',
        'tahun_pembuatan', 'isi_silinder', 'jenis_bahan_bakar', 'nomor_rangka', 'nomor_mesin',
        'warna_tnkb', 'nomor_bpkb',

        // Kolom Status (PINDAH KE SINI)
        'status',
        'catatan_admin',
    ];

    /**
     * Relasi: Satu Kendaraan ini milik satu Pengajuan (bundel).
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    /**
     * Relasi BARU: Satu Kendaraan punya BANYAK Log Histori.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(KendaraanLog::class)->latest(); // Urutkan log terbaru di atas
    }
}