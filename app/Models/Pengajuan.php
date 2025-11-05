<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
// Hapus 'use Spatie\MediaLibrary\HasMedia'
// Hapus 'use Spatie\MediaLibrary\InteractsWithMedia'

// Hapus 'implements HasMedia'
class Pengajuan extends Model
{
    // Hapus 'InteractsWithMedia', TAPI TETAPKAN 'SoftDeletes'
    use HasFactory, SoftDeletes; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Rampingkan $fillable (status & catatan_admin HILANG)
    protected $fillable = [
        'user_id',
        'nomor_pengajuan',
    ];

    /**
     * Relasi: Pengajuan ini dibuat oleh siapa (User/Penulis).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi BARU: Satu Pengajuan (bundel) punya BANYAK Kendaraan.
     */
    public function kendaraans(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }

    /**
     * HAPUS Relasi: logs()
     * (Relasi logs() sekarang pindah ke model Kendaraan)
     */

    /**
     * Boot method untuk membuat nomor pengajuan unik.
     * (PENTING: Kita hapus set status default dari sini)
     */
    protected static function booted(): void
    {
        static::creating(function ($pengajuan) {
            // Logika generate nomor_pengajuan (INI BENAR)
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
            
            // HAPUS LOGIKA STATUS DRAFT DARI SINI
            // if (empty($pengajuan->status)) {
            //     $pengajuan->status = 'draft'; 
            // }
        });
    }

    /**
     * Accessor BARU: Hitung status bundel secara dinamis.
     * Ini akan dipanggil di view jika kita panggil $pengajuan->status
     */
    public function getStatusAttribute(): string
    {
        // Load relasi kendaraans jika belum ada (optimasi)
        if (! $this->relationLoaded('kendaraans')) {
            $this->load('kendaraans:id,pengajuan_id,status');
        }

        $statuses = $this->kendaraans->pluck('status');

        if ($statuses->isEmpty()) {
            return 'draft'; // Jika tidak ada kendaraan, statusnya 'draft'
        }
        if ($statuses->contains('ditolak')) {
            return 'ditolak'; // Jika ada 1 saja ditolak, status bundel 'ditolak'
        }
        if ($statuses->contains('diproses')) {
            return 'diproses'; // Jika ada 1 saja diproses, status bundel 'diproses'
        }
        if ($statuses->every(fn($status) => $status == 'selesai')) {
            return 'selesai'; // Jika SEMUA selesai, status bundel 'selesai'
        }

        return 'pengajuan'; // Default jika semua masih 'pengajuan'
    }
}