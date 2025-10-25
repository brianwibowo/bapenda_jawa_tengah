<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia; // <-- Import HasMedia
use Spatie\MediaLibrary\InteractsWithMedia; // <-- Import InteractsWithMedia

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