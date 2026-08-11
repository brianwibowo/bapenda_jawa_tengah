<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// Ini adalah model untuk tabel 'kendaraan_logs'
/**
 * @property int $id
 * @property int $kendaraan_id
 * @property int $user_id
 * @property string $aksi
 * @property string $status_baru
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kendaraan $kendaraan
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereKendaraanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereStatusBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KendaraanLog whereUserId($value)
 * @mixin \Eloquent
 */
class KendaraanLog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Ganti nama tabel jika Laravel tidak bisa menebaknya (opsional, tapi aman)
    protected $table = 'kendaraan_logs';

    protected $fillable = [
        'kendaraan_id', // <-- Terhubung ke kendaraan
        'user_id',
        'aksi',
        'tipe',
        'status_baru',
        'catatan',
        'sp_id',
        'sp_status',
        'sk_id',
        'sk_status',
        'revisi_fields',
        'revisi_resolved_at',
    ];

    protected $casts = [
        'revisi_fields' => 'array',
        'revisi_resolved_at' => 'datetime',
    ];

    /**
     * Cek apakah log ini adalah SK Draft (belum diterbitkan).
     */
    public function isSkDraft(): bool
    {
        return $this->sk_status === 'draft';
    }

    /**
     * Cek apakah log ini adalah SK yang sudah diterbitkan.
     */
    public function isSkPublished(): bool
    {
        return $this->sk_status === 'terbit';
    }

    /**
     * Cek apakah log ini adalah SP Draft (belum diterbitkan).
     */
    public function isSpDraft(): bool
    {
        return $this->sp_status === 'draft';
    }

    /**
     * Cek apakah log ini adalah SP yang sudah diterbitkan.
     */
    public function isSpPublished(): bool
    {
        return $this->sp_status === 'terbit';
    }

    /**
     * Cek apakah log ini visible untuk user tertentu.
     * Draft SK hanya visible ke unit_kerja pembuat.
     * Log lainnya visible to all.
     */
    public function isVisibleToUser($user): bool
    {
        // Jika bukan draft (null atau 'terbit'), visible to all
        if ($this->sk_status !== 'draft' && $this->sp_status !== 'draft') {
            return true;
        }
        // Draft SK/SP: hanya visible jika unit_kerja viewer = unit_kerja pembuat log
        $creator = $this->user;
        if (!$creator || !$user) return false;
        return $creator->unit_kerja === $user->unit_kerja;
    }

    /**
     * Scope: hanya log yang SK/SP-nya bukan draft (sudah terbit atau tanpa dokumen).
     */
    public function scopeWithoutDraft($query)
    {
        return $query
            ->where(fn ($q) => $q->whereNull('sk_status')->orWhere('sk_status', '!=', 'draft'))
            ->where(fn ($q) => $q->whereNull('sp_status')->orWhere('sp_status', '!=', 'draft'));
    }

    /**
     * Scope: hanya log yang SK/SP-nya masih draft.
     */
    public function scopeOnlyDraft($query)
    {
        return $query
            ->where(fn ($q) => $q->where('sk_status', 'draft')->orWhere('sp_status', 'draft'));
    }

    /**
     * Ambil teks status terakhir untuk sekumpulan kendaraan.
     * Log draft tidak dihitung sebagai status terakhir; jika ada draft pending
     * yang lebih baru dari log terbit, tampilkan "Menunggu Penerbitan {Jenis} {Unit}".
     */
    public static function latestStatusTextForKendaraans(Collection $kendaraanIds): string
    {
        $ids = $kendaraanIds->all();
        $latestDraft = static::whereIn('kendaraan_id', $ids)->onlyDraft()->latest()->first();
        $latestLog   = static::whereIn('kendaraan_id', $ids)->withoutDraft()->latest()->first();

        if ($latestDraft && (!$latestLog || $latestDraft->created_at->gt($latestLog->created_at))) {
            $jenis = $latestDraft->isSkDraft() ? 'SK' : 'SP';
            $unit  = $latestDraft->user->unit_kerja ?? 'Instansi';
            return "Menunggu Penerbitan {$jenis} {$unit}";
        }

        return $latestLog?->aksi ?? 'Pengajuan Baru';
    }

    /**
     * Cek apakah log revisi ini masih menunggu response.
     */
    public function isRevisionPending(): bool
    {
        return $this->tipe === 'revisi'
            && !empty($this->revisi_fields)
            && is_null($this->revisi_resolved_at);
    }

    /**
     * Relasi: Log ini milik Kendaraan mana.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /**
     * Relasi: Siapa User yang melakukan aksi ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($log) {
            // Load necessary relationships
            $log->loadMissing('kendaraan.pengajuan.user', 'user.roles');

            $pengajuan = $log->kendaraan->pengajuan ?? null;
            if (!$pengajuan) return;

            // Touch the pengajuan to update its updated_at timestamp
            // This ensures "Update Terakhir" reflects the latest log/diskusi
            $pengajuan->touch();

            $creator = $log->user;
            if (!$creator) return;

            // 1. Beritahu Wajib Pajak (jika pencipta log bukan Wajib Pajak itu sendiri)
            if ($pengajuan->user && $creator->id !== $pengajuan->user_id) {
                $pengajuan->user->notify(new \App\Notifications\LogAktivitasNotification(
                    $log,
                    "Admin/Instansi menambahkan aktivitas: " . $log->aksi,
                    route('pengajuan.show', $pengajuan->id)
                ));
            }

            // 2. Beritahu Admin/Verifikator lain yang relevan (di cabang yang sama atau tingkat wilayah/null)
            $admins = \App\Models\User::where(function($query) use ($pengajuan) {
                    $query->where('cabang_id', $pengajuan->cabang_id)
                          ->orWhereNull('cabang_id');
                })
                ->where('id', '<>', $creator->id) // Jangan notifikasi diri sendiri
                ->whereHas('roles', function($q) {
                    $q->whereIn('name', ['superadmin', 'samsat', 'polda', 'bapenda', 'jasa_raharja', 'admin_instansi', 'staff_instansi', 'kepala_instansi']);
                })->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\LogAktivitasNotification(
                    $log,
                    "Aktivitas baru oleh {$creator->name}: " . $log->aksi,
                    route('admin.pengajuan.show', $pengajuan->id)
                ));
            }
        });
    }
}