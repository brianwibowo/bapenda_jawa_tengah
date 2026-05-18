<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


/**
 * @property int $id
 * @property int $pengajuan_id
 * @property int $user_id
 * @property string $unit_kerja
 * @property string $nomor_sk
 * @property string $perihal
 * @property string $isi_putusan
 * @property \Illuminate\Support\Carbon $tanggal_ditetapkan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereIsiPutusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereNomorSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereTanggalDitetapkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUserId($value)
 * @mixin \Eloquent
 *//*  */
class SuratKeputusan extends Model implements HasMedia
{

    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'pengajuan_id', 'kendaraan_id', 'user_id', 'unit_kerja',
        'nomor_sk', 'perihal', 'isi_putusan', 'tanggal_ditetapkan'
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'date',
    ];

    public function pengajuan() {
        return $this->belongsTo(Pengajuan::class);
    }

    public function kendaraan() {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pembuat() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
