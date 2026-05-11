<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'pengajuan_id',
        'kendaraan_id',
        'no_hp_tujuan',
        'sk_type',
        'file_url',
        'message_preview',
        'status',
        'fonnte_response',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'fonnte_response' => 'array',
        'sent_at'         => 'datetime',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
