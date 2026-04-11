<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property int $pengajuan_id
 * @property int $user_id
 * @property string $instansi
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereInstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereIsiPutusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereNomorSk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereTanggalDitetapkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratKeputusan whereUserId($value)
 * @mixin \Eloquent
 *//*  */
class SuratKeputusan extends Model
{

    use HasFactory;

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
