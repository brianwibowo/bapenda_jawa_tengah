<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $nomor_pengajuan
 * @property int $user_id
 * @property string $status
 * @property string|null $catatan_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kendaraan> $kendaraans
 * @property-read int|null $kendaraans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengajuanLog> $logs
 * @property-read int|null $logs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereNomorPengajuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengajuan withoutTrashed()
 * @mixin \Eloquent
 */
class Pengajuan extends Model
{
    use HasFactory, SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nomor_pengajuan',
        'status',
        'catatan_admin',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kendaraans(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class)->latest();
    }

    protected static function booted(): void
    {
        static::creating(function ($pengajuan) {
            if (empty($pengajuan->nomor_pengajuan)) {
                $prefix = 'PJN-' . now()->format('ym') . '-';
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

            if (empty($pengajuan->status)) {
                $pengajuan->status = 'draft'; 
            }
        });
    }
}