<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

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

            $creator = $log->user;
            if (!$creator) return;

            // Jika yang membuat log adalah Wajib Pajak
            if ($creator->hasRole('wajib_pajak')) {
                // Beritahu Admin (Samsat / Bapenda) di cabang yang sama
                $admins = \App\Models\User::where('cabang_id', $pengajuan->cabang_id)
                    ->whereHas('roles', function($q) {
                        $q->whereIn('name', ['samsat', 'bapenda', 'admin_instansi']);
                    })->get();
                
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\LogAktivitasNotification(
                        $log,
                        "Wajib Pajak {$creator->name} menambah aktivitas: " . $log->aksi,
                        route('admin.pengajuan.show', $pengajuan->id)
                    ));
                }
            } else {
                // Jika yang membuat log adalah Admin/Samsat/Bapenda dll
                // Beritahu Wajib Pajak (Pemilik Pengajuan)
                if ($pengajuan->user) {
                    $pengajuan->user->notify(new \App\Notifications\LogAktivitasNotification(
                        $log,
                        "Admin menambahkan aktivitas: " . $log->aksi,
                        route('pengajuan.show', $pengajuan->id)
                    ));
                }
            }
        });
    }
}