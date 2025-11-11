<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemilik extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari 'pemiliks' (opsional, tapi aman)
    protected $table = 'pemiliks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pemilik',
        'nik_pemilik',
        'alamat_pemilik',
        'telp_pemilik',
        'email_pemilik',
    ];

    /**
     * Relasi: Satu Pemilik bisa memiliki BANYAK Kendaraan.
     */
    public function kendaraans(): HasMany
    {
        return $this->hasMany(Kendaraan::class);
    }
}