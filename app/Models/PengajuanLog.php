<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia; // <-- Import HasMedia
use Spatie\MediaLibrary\InteractsWithMedia; // <-- Import InteractsWithMedia

/**
 * @property int $id
 * @property int $pengajuan_id
 * @property int $user_id
 * @property string $aksi
 * @property string $status_baru
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Pengajuan $pengajuan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog wherePengajuanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereStatusBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengajuanLog whereUserId($value)
 * @mixin \Eloquent
 */
class PengajuanLog extends Model implements HasMedia // <-- Implement HasMedia
{
    use HasFactory, InteractsWithMedia; // <-- Gunakan InteractsWithMedia

    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'aksi',
        'status_baru',
        'catatan',
        // 'lampiran_path' tidak perlu karena pakai Media Library
    ];

    /**
     * Relasi: Log ini milik Pengajuan mana.
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    /**
     * Relasi: Siapa User yang melakukan aksi ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}