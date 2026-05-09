<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class FonnteService
{
    private string $token;
    private string $url;
    private int $timeout;

    public function __construct()
    {
        $this->token = config('fonnte.token');
        $this->url = config('fonnte.url');
        $this->timeout = config('fonnte.timeout', 15);
    }

    /**
     * Normalisasi nomor HP ke format internasional Indonesia (62xxxxxxxxxx).
     */
    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // Hapus semua non-digit

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '62')) {
            return '62' . $phone;
        }

        return $phone;
    }

    /**
     * Kirim pesan teks biasa.
     *
     * @return array{success: bool, response: array|null, error: string|null}
     */
    public function send(string $phone, string $message): array
    {
        return $this->callApi([
            'target' => $this->normalizePhone($phone),
            'message' => $message,
        ]);
    }

    /**
     * Kirim pesan + file attachment (multipart upload).
     *
     * @return array{success: bool, response: array|null, error: string|null}
     */
    public function sendWithFile(string $phone, string $message, string $localFilePath): array
    {
        if (!file_exists($localFilePath)) {
            Log::error("[Fonnte] File tidak ditemukan: {$localFilePath}");
            return ['success' => false, 'response' => null, 'error' => "File tidak ditemukan di: {$localFilePath}"];
        }

        $normalizedPhone = $this->normalizePhone($phone);
        $filename = basename($localFilePath);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Authorization' => $this->token])
                ->attach('file', file_get_contents($localFilePath), $filename)
                ->post($this->url, [
                    'target'   => $normalizedPhone,
                    'message'  => $message,
                    'filename' => $filename,
                ]);

            $body = $response->json() ?? [];

            Log::info('[Fonnte] Response dari API', [
                'target'   => $normalizedPhone,
                'filename' => $filename,
                'status'   => $response->status(),
                'body'     => $body,
            ]);

            if ($response->successful() && ($body['status'] ?? false) !== false) {
                return ['success' => true, 'response' => $body, 'error' => null];
            }

            $errorMsg = $body['reason'] ?? $body['message'] ?? 'Unknown Fonnte error';
            return ['success' => false, 'response' => $body, 'error' => $errorMsg];

        } catch (ConnectionException $e) {
            Log::error('[Fonnte] Koneksi timeout/gagal: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => 'Connection error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            Log::error('[Fonnte] Exception tidak terduga: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Panggil Fonnte API dan kembalikan hasil terstruktur.
     */
    private function callApi(array $payload): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Authorization' => $this->token])
                ->post($this->url, $payload);

            $body = $response->json() ?? [];

            if ($response->successful() && ($body['status'] ?? false) !== false) {
                Log::info('[Fonnte] WA terkirim ke ' . $payload['target'], ['response' => $body]);
                return ['success' => true, 'response' => $body, 'error' => null];
            }

            $errorMsg = $body['reason'] ?? $body['message'] ?? 'Unknown Fonnte error';
            Log::warning('[Fonnte] Gagal kirim WA ke ' . $payload['target'], ['response' => $body]);
            return ['success' => false, 'response' => $body, 'error' => $errorMsg];

        } catch (ConnectionException $e) {
            Log::error('[Fonnte] Koneksi timeout/gagal: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => 'Connection error: ' . $e->getMessage()];
        } catch (\Exception $e) {
            Log::error('[Fonnte] Exception tidak terduga: ' . $e->getMessage());
            return ['success' => false, 'response' => null, 'error' => $e->getMessage()];
        }
    }
}
