<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $pengajuan_id
 * @property string $nomor_sp
 * @property \Illuminate\Support\Carbon $tanggal_surat
 * @property array $persetujuan_unit_kerja
 * @property string|null $catatan_instansi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereCatatanInstansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereNomorSp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan wherePersetujuanUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereTanggalSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratPengajuan whereUpdatedAt($value)
 * @mixin \Eloquent
 *//*  */
class SuratPengajuan extends Model
{

    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'kendaraan_id',
        'nomor_sp',
        'tanggal_surat',
        'persetujuan_unit_kerja',
        'local_pdf_path',
        'pdf_url',
        'catatan_instansi',
    ];

    protected $casts = [
        'persetujuan_unit_kerja' => 'array',
        'tanggal_surat' => 'date',
    ];

    public function pengajuan() {
        return $this->belongsTo(Pengajuan::class);
    }

    public function kendaraan() {
        return $this->belongsTo(Kendaraan::class);
    }

    public function isFullyApproved()
    {
        if (empty($this->persetujuan_unit_kerja)) return false;
    
        return !collect($this->persetujuan_unit_kerja)->contains(fn($item) => $item['status'] !== 'approved');
    }

    public function isFullyApprovedByAll()
    {
        $currentSp = $this->pengajuan?->getCurrentSuratPengajuan();
        return $currentSp && $currentSp->id === $this->id && $this->isFullyApproved();
    }
    
    public function isRejected()
    {
        if (!$this->persetujuan_unit_kerja) return false;
        foreach ($this->persetujuan_unit_kerja as $item) {
            if (isset($item['status']) && $item['status'] === 'rejected') {
            return true;
        }
        }
        return false;
    }
}