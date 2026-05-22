<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pengajuan extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Rampingkan $fillable (status & catatan_admin HILANG)
    protected $fillable = [
        'user_id',
        'nomor_pengajuan',
        'cabang_id',
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

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    // app/Models/Pengajuan.php
    public function suratPengajuan() {
        return $this->hasMany(SuratPengajuan::class);
    }

    public function suratKeputusans() {
        return $this->hasMany(SuratKeputusan::class);
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

    public function getSliceSuratPengajuanLastRejected()
    {
        $suratPengajuan = $this->suratPengajuan;
        $lastRejectedIndex = $suratPengajuan
            ->filter(fn($sp) => $sp->isRejected())
            ->keys()
            ->last();
        $suratpengajuan = $lastRejectedIndex !== null
            ? $suratPengajuan->slice($lastRejectedIndex + 1)
            : $suratPengajuan;
        return $suratpengajuan;
    }

    public function getCurrentSuratPengajuan(): ?SuratPengajuan
    {
        return $this->getSliceSuratPengajuanLastRejected()->last();
    }

    public function isFullyApprovedByAll(): bool
    {
        $currentSp = $this->getCurrentSuratPengajuan();
        return (bool) $currentSp?->isFullyApproved();
    }

    public function getTotalSurat(): int
    {
        //Cari array terakhir sampai surat pengajuan ditolak, ambil semua surat pengajuan sampai terbaru dari yang ditolak.
        $suratpengajuan = $this->getSliceSuratPengajuanLastRejected();
        $suratTerpenuhi = 0;

        // Cek Surat Pengajuan ke Samsat
        if ($this->hasSuratPengajuanByInstansi($suratpengajuan, 'Polda')) {
            error_log("Test 1");
            $suratTerpenuhi++;
        }
        // Cek Surat Pengajuan ke Polda
        if ($this->hasApprovedSuratPengajuanByInstansi($suratpengajuan, 'Polda')) {
            error_log("Test 2");
            $suratTerpenuhi++;
        }
        // Cek Surat Pengajuan Pending ke Bapenda/JR
        if ($this->hasSuratPengajuanByInstansi($suratpengajuan, 'Bapenda')) {
            error_log("Test 3");
            $suratTerpenuhi++;
        }
        if ($this->hasSuratPengajuanByInstansi($suratpengajuan, 'Jasa Raharja')) {
            error_log("Test 4");
            $suratTerpenuhi++;
         }
        // Cek Surat Pengajuan ke Bapenda/JR ( dari persetujuan_unit_kerja json key 'instansi' dengan value 'bapenda' atau 'jasa_raharja' )
        if ($this->hasApprovedSuratPengajuanByInstansi($suratpengajuan, 'Bapenda')) {
            error_log("Test 5");
            $suratTerpenuhi++;
        }
        if ($this->hasApprovedSuratPengajuanByInstansi($suratpengajuan, 'Jasa Raharja')) {
            error_log("Test 6");
            $suratTerpenuhi++;
        }
        // Cek Surat Keputusan dari Polda
        if ($this->suratKeputusans()->where('unit_kerja', 'Polda')->exists()) {
            error_log("Test 7");
            $suratTerpenuhi++;
        }
        if ($this->suratKeputusans()->where('unit_kerja', 'Bapenda')->exists()) {
            error_log("Test 8");
            $suratTerpenuhi++;
        }
        if ($this->suratKeputusans()->where('unit_kerja', 'Jasa Raharja')->exists()) {
            error_log("Test 9");
            $suratTerpenuhi++;
        }

        return $suratTerpenuhi;
    }

    public function getProgress(): float
    {

        /*
            1. Cek Status Tiap kendaraan apakah pengajuan.
            2. Jika iya Samsat akan bisa memverifikasi lalu mengajukan SP ke Polda.
            3. Jika Polda menyetujui maka status kendaraan berubah menjadi diproses, jika menolak maka status kendaraan berubah menjadi pengajuan lagi atau ditolak, memiliki KendaraanLog yang memberikan info lebih detail dan samsat akan mengecek lagi dan kembali ke nomer 2.
            4. Jika status kendaraan menjadi diproses, Polda akan membuat SP ke Bapenda dan JR, lalu menunggu Surat Balasan ( Atau tanda terima ) dari Bapenda dan JR, jika disetujui maka status kendaraan berubah menjadi selesai, jika ditolak maka status kendaraan berubah menjadi pengajuan lagi atau ditolak, memiliki KendaraanLog yang memberikan info lebih detail dan samsat akan mengecek lagi dan kembali ke nomer 2.
            5. Jika semua Surat Pengajuan sudah dipenuhi ( Polda dan Bapenda/JR ), maka Polda akan membuat SK, dan Bapenda/JR akan membuat SK juga.
            6. Jika Polda, Bapenda, dan JR sudah membuat SK, maka status kendaraan berubah menjadi selesai.

            Progres 1 : Samsat Verfikasi ( Pengajuan ke Polda ) (Total SP = 1)
            Progres 2 : Polda Verifikasi ( Polda ke Bapenda/JR melalui SP ) ( Total SP = 2)
            Progres 3 : Bapenda atau JR memberikan balasan ( Total SP = 0 )
            Progres 4 : Polda, Bapenda, dan JR membuat SK ( Total SK = 3 )
            Total Surat = 6

            100% = 6 Surat Terpenuhi
        
        */

        $totalSurat = 9; // Total surat yang harus dipenuhi untuk 100%
        $suratTerpenuhi = $this->getTotalSurat(); // Hitung surat yang sudah terpenuhi

        return ($suratTerpenuhi / $totalSurat) * 100;
    }

    public function getStep(): int
    {
        $suratpengajuan = $this->getSliceSuratPengajuanLastRejected();
        $totalsurat = $this->getTotalSurat();
        $step = 0;

        if ($totalsurat == 0 && ($suratpengajuan->isEmpty() || ($suratpengajuan->count() == 1 && $suratpengajuan->first()->isRejected()))) {
            $step = 0; // Progres 0: Belum ada SP atau SP pertama ditolak
        } elseif ($totalsurat < 2) {
            $step = 1; // Progres 1: Pengajuan ke Polda
        } elseif ($totalsurat < 7) {
            $step = 2; // Progres 2: Polda ke Bapenda/JR & Balasan dari Bapenda/JR
        } elseif ($totalsurat < 9) {
            $step = 3; // Progres 3: Polda, Bapenda, dan JR membuat SK
        } else {
            $step = 4; // Progres 4: Semua SP terpenuhi dan SK sudah dibuat
        }
        return $step;
    }

    private function hasSuratPengajuanByInstansi($suratPengajuanCollection, string $instansi): bool
    {
        return $suratPengajuanCollection->contains(function ($sp) use ($instansi) {
            return collect($sp->persetujuan_unit_kerja ?? [])->contains(function ($item) use ($instansi) {
                return strcasecmp($item['instansi'] ?? '', $instansi) === 0;
            });
        });
    }

    private function hasApprovedSuratPengajuanByInstansi($suratPengajuanCollection, string $instansi): bool
    {
        return $suratPengajuanCollection->contains(function ($sp) use ($instansi) {
            return collect($sp->persetujuan_unit_kerja ?? [])->contains(function ($item) use ($instansi) {
                return strcasecmp($item['instansi'] ?? '', $instansi) === 0
                    && (($item['status'] ?? null) === 'approved');
            });
        });
    }

    private function hasPendingSuratPengajuanByInstansi($suratPengajuanCollection, string $instansi): bool
    {
        return $suratPengajuanCollection->contains(function ($sp) use ($instansi) {
            return collect($sp->persetujuan_unit_kerja ?? [])->contains(function ($item) use ($instansi) {
                return strcasecmp($item['instansi'] ?? '', $instansi) === 0
                    && (($item['status'] ?? null) === 'pending');
            });
        });
    }
}