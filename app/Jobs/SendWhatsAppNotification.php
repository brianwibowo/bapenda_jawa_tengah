<?php

namespace App\Jobs;

use App\Models\Pengajuan;
use App\Models\Kendaraan;
use App\Models\WhatsAppLog;
use App\Services\FonnteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan ulang jika gagal.
     */
    public int $tries = 3;

    /**
     * Jeda antar retry: 1 menit, 5 menit, 10 menit.
     */
    public array $backoff = [60, 300, 600];

    /**
     * Timeout eksekusi job (detik).
     */
    public int $timeout = 30;

    public function __construct(
        public readonly Pengajuan $pengajuan,
        public readonly ?Kendaraan $kendaraan,
        public readonly string $skType,     // regident | polda | pembebasan
        public readonly string $pdfUrl,
        public readonly string $localPdfPath,
        public readonly string $wpPhone,
        public readonly string $wpName,
        public readonly string $nrkb,
    ) {}

    public function handle(FonnteService $fonnte): void
    {
        // 1. Cek apakah sudah pernah berhasil dikirim (duplicate prevention)
        $alreadySent = WhatsAppLog::where('pengajuan_id', $this->pengajuan->id)
            ->where('kendaraan_id', $this->kendaraan?->id)
            ->where('sk_type', $this->skType)
            ->where('status', 'sent')
            ->exists();

        if ($alreadySent) {
            Log::info("[Fonnte] SK {$this->skType} untuk pengajuan #{$this->pengajuan->id} sudah pernah dikirim. Skip.");
            return;
        }

        // 2. Buat log entry dulu dengan status pending
        $waLog = WhatsAppLog::create([
            'pengajuan_id'    => $this->pengajuan->id,
            'kendaraan_id'    => $this->kendaraan?->id,
            'no_hp_tujuan'    => $this->wpPhone,
            'sk_type'         => $this->skType,
            'file_url'        => $this->pdfUrl,
            'message_preview' => $this->buildMessage(),
            'status'          => 'pending',
        ]);

        // 3. Kirim WA via Fonnte (teks + link download)
        // NOTE: Untuk kirim file attachment langsung, upgrade plan Fonnte ke paket media.
        // Setelah upgrade, ganti send() → sendWithFile() dan aktifkan localPdfPath.
        $result = $fonnte->send(
            phone:   $this->wpPhone,
            message: $this->buildMessage(),
        );

        // 4. Update log berdasarkan hasil
        if ($result['success']) {
            $waLog->update([
                'status'          => 'sent',
                'fonnte_response' => $result['response'],
                'sent_at'         => now(),
            ]);
            Log::info("[Fonnte] SK {$this->skType} berhasil dikirim ke {$this->wpPhone}");
        } else {
            $waLog->update([
                'status'          => 'failed',
                'fonnte_response' => $result['response'],
                'error_message'   => $result['error'],
            ]);

            // TODO: Aktifkan baris ini saat menggunakan queue (QUEUE_CONNECTION=database)
            // agar job bisa di-retry otomatis. Jangan aktifkan saat mode sync.
            // throw new \RuntimeException("[Fonnte] Gagal kirim WA: " . $result['error']);

            Log::warning("[Fonnte] WA gagal (non-fatal) untuk pengajuan #{$this->pengajuan->id}: " . $result['error']);
        }
    }

    /**
     * Dipanggil setelah semua retry habis.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("[Fonnte] Job gagal total untuk pengajuan #{$this->pengajuan->id} ({$this->skType}): " . $exception->getMessage());

        // Update log terakhir (status pending) ke failed
        WhatsAppLog::where('pengajuan_id', $this->pengajuan->id)
            ->where('kendaraan_id', $this->kendaraan?->id)
            ->where('sk_type', $this->skType)
            ->where('status', 'pending')
            ->update([
                'status'        => 'failed',
                'error_message' => 'Job gagal setelah ' . $this->tries . ' percobaan: ' . $exception->getMessage(),
            ]);
    }

    private function buildMessage(): string
    {
        $skLabel = match ($this->skType) {
            'regident'   => 'Surat Keterangan Penghapusan Regident',
            'polda'      => 'Surat Keputusan Polda',
            'pembebasan' => 'Surat Keputusan Pembebasan',
            default      => 'Surat Keputusan',
        };

        return <<<MSG
        Yth. Bapak/Ibu {$this->wpName},

        {$skLabel} atas kendaraan dengan NRKB *{$this->nrkb}* telah diterbitkan oleh pejabat berwenang.

        📄 *Download dokumen SK Anda di sini:*
        {$this->pdfUrl}

        Silakan simpan sebagai bukti resmi proses penghapusan kendaraan Anda.

        Jika ada pertanyaan, silakan hubungi kantor Bapenda Jawa Tengah.

        — Sistem Bapenda Jateng
        MSG;
    }
}
