<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- 1. WAJIB ADA (Import Trait)
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // <-- 2. WAJIB ADA (Gunakan Trait)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'unit_kerja', // Pastikan ini ada
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: Bundel pengajuan yang dibuat oleh user ini.
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }

    /**
     * Relasi: Log aksi yang dilakukan oleh user ini.
     */
    public function pengajuanLogs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class);
    }
}