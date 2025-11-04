<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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