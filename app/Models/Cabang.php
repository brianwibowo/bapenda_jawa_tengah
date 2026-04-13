<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'wilayah',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }
}
