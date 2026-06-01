<?php

namespace App\Http\Controllers;

use App\Jobs\SendWhatsAppNotification;
use App\Models\SuratKeputusan;
use App\Models\KendaraanLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SuratKeputusanController extends Controller
{

    public static function normalizeUnitKerja(?string $unitKerja): string
    {
        return match (strtolower(trim((string) $unitKerja))) {
            'jr', 'jasa raharja', 'jasa_raharja' => 'JR',
            'bapenda' => 'Bapenda',
            'polda' => 'Polda',
            'samsat' => 'Samsat',
            default => trim((string) $unitKerja),
        };
    }

    static public function getRegistry($type, $id, $data = null, ?string $unitKerja = null)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratKeputusans'])->findOrFail($id);
        $step = $pengajuan->getStep();

        // Jika unitKerja tidak disupply (e.g. saat render PDF via ID), deteksi dari auth user
        if ($unitKerja === null && Auth::check()) {
            $unitKerja = self::normalizeUnitKerja(Auth::user()->unit_kerja);
        }

        $registries = [
            'pdf' => [
                "default" => [
                    'view' => 'pdf.view_sk',
                    'mode' => 'iframe',
                    'role' => 'admin',
                    'prefix' => 'SK-',
                    'permission' => 'view_own_sk',
                    'controllerroute' => 'admin.pengajuan.buat_sk',
                    'footer' => [
                        'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.buat_sk',
                            'middleware' => 'signed'
                        ]],
                        'reject' => false,
                        'back' => false,
                    ]
                ],
                "polda2bapenda&jr" => [
                    'view' => 'pdf.sk_penghapusan_regident',
                    'mode' => 'iframe',
                    'role' => 'polda',
                    'prefix' => 'SK-REGIDENT-',
                    'permission' => 'view_own_sk',
                    'controllerroute' => 'admin.pengajuan.generate_sk_regident',
                    'footer' => [
                        'accept' => ['label' => 'Selesai', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.index',
                        ]],
                        'reject' => false,
                        'back' => false,
                    ]
                ],
                "bapenda" => [
                    'view' => 'pdf.sk_bapenda_pembebasan',
                    'mode' => 'iframe',
                    'role' => 'bapenda',
                    'prefix' => 'SK-BAPENDA-PEMBEBASAN-',
                    'permission' => 'view_own_sk',
                    'controllerroute' => 'pengajuan.generate_sk_pembebasan',
                    'footer' => [
                        'accept' => ['label' => 'Selesai', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.index',
                        ]],
                        'reject' => false,
                        'back' => false,
                    ]
                ],
                "jr" => [
                    'view' => 'pdf.view_sk',  // Dummy — sama dengan default, ganti dengan PDF JR yang sesungguhnya
                    'mode' => 'iframe',
                    'role' => 'jr',
                    'prefix' => 'SK-JR-',
                    'permission' => 'view_own_sk',
                    'controllerroute' => 'admin.pengajuan.buat_sk',
                    'footer' => [
                        'accept' => ['label' => 'Selesai', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.index',
                        ]],
                        'reject' => false,
                        'back' => false,
                    ]
                ],
            ],
            'form' => [
                "default"  => [
                    'view' => 'form.create_sk',
                    'mode' => 'modal',
                    'role' => ['admin'],
                    'permission' => 'create_sk',
                    'footer' => [
                        'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.buat_sk',
                            'middleware' => 'signed'
                        ]],
                        'reject' => false,
                        'back' => ['label' => 'Kembali', 'class' => 'btn-secondary'],
                    ]
                ],
                "polda2bapenda&jr" => [
                    'view' => 'form.sk_regident',
                    'mode' => 'modal',
                    'role' => ['polda'],
                    'permission' => 'create_sk',
                    'footer' => [
                        'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.buat_sk',
                            'middleware' => 'signed'
                        ]],
                        'reject' => false,
                        'back' => ['label' => 'Kembali', 'class' => 'btn-secondary'],
                    ]
                ],
                "bapenda" => [
                    'view' => 'form.sk_bapenda_pembebasan',
                    'mode' => 'modal',
                    'role' => ['bapenda'],
                    'permission' => 'create_sk',
                    'footer' => [
                        'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.buat_sk',
                            'middleware' => 'signed'
                        ]],
                        'reject' => false,
                        'back' => ['label' => 'Kembali', 'class' => 'btn-secondary'],
                    ]
                ],
                "jr" => [
                    'view' => 'form.create_sk',  // Dummy — sama dengan default, ganti dengan form JR yang sesungguhnya
                    'mode' => 'modal',
                    'role' => ['jr'],
                    'permission' => 'create_sk',
                    'footer' => [
                        'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                            'name' => 'admin.pengajuan.buat_sk',
                            'middleware' => 'signed'
                        ]],
                        'reject' => false,
                        'back' => ['label' => 'Kembali', 'class' => 'btn-secondary'],
                    ]
                ],
            ]
        ];

        $config = $registries[$type] ?? abort(404);

        // Routing berbasis unit_kerja (lebih presisi dari routing berbasis step saja)
        $normalizedUK = $unitKerja ? self::normalizeUnitKerja($unitKerja) : null;
        if ($normalizedUK === 'Polda' && isset($config['polda2bapenda&jr'])) {
            $config = $config['polda2bapenda&jr'];
        } elseif ($normalizedUK === 'Bapenda' && isset($config['bapenda'])) {
            $config = $config['bapenda'];
        } elseif ($normalizedUK === 'JR' && isset($config['jr'])) {
            $config = $config['jr'];
        } elseif ((int) $step < 3 && isset($config['polda2bapenda&jr'])) {
            // Fallback berbasis step jika unitKerja tidak diketahui
            $config = $config['polda2bapenda&jr'];
        } elseif ((int) $step < 4 && isset($config['bapenda'])) {
            $config = $config['bapenda'];
        } else {
            $config = $config['default'];
        }

        $config['filename'] = $data ? ($config['prefix'] ?? '') . $data->nomor_sk : 'DOKUMEN_SK';
        return $config;
    }

    static public function render(Request $request, $type, $id)
    {
        $pengajuan = Pengajuan::with(['kendaraans.suratKeputusans', 'suratKeputusans'])->findOrFail($id);
        if ($type == 'pdf') {
            $sk = SuratKeputusan::with('pengajuan.kendaraans.pemilik')->findOrFail($id);
            $config = SuratKeputusanController::getRegistry($type, $sk->pengajuan_id, $sk);
            return Pdf::loadView($config['view'], ['sk' => $sk])
                ->setPaper('a4', 'portrait')
                ->stream($config['filename'] . '.pdf');
        }
        $sk = $pengajuan->suratKeputusans->last();
        $config = SuratKeputusanController::getRegistry($type, $pengajuan->id, $sk);

        $normalizedUnitKerja = '';
        if (Auth::check()) {
            $normalizedUnitKerja = self::normalizeUnitKerja(Auth::user()->unit_kerja);
        }

        return view($config['view'], [
            'sk' => $sk, 
            'pengajuan' => $pengajuan,
            'normalizedUnitKerja' => $normalizedUnitKerja
        ]);
    }

    public function generateSkDefault(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'kendaraan_id' => ['required',
                function($attribute, $value, $fail) {
                    if ($value === 'all') return;
                    if (!\App\Models\Kendaraan::where('id', $value)->exists()) {
                        $fail('Kendaraan tidak ditemukan.');
                    }
                },
            ],
        ]);

        // Ambil data kendaraan berdasarkan pilihan dari form modal
        $kendaraan = $request->kendaraan_id === 'all'
            ? $pengajuan->kendaraans()->with('pemilik')->get()
            : $pengajuan->kendaraans()->with('pemilik')->where('id', $request->kendaraan_id)->get();

        if ($kendaraan->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $arrayResult = [];

        foreach ($kendaraan as $k) {
            $dataPdf = [
                'sk'  => (object)[
                    'nomor_sk'  => null, // Belum ada nomor SK saat generate
                    'kendaraan' => $k,
                ],
            ];

            $arrayResult[$k->id] = [
                'data'           => $dataPdf,
                'pdf_url'        => null,
                'local_pdf_path' => null,
            ];

            // Jika bukan preview, cek apakah SK untuk kendaraan ini sudah ada (skip jika sudah)
            if (!$request->has('preview')) {
                $existingSk = $k->suratKeputusans()->where('unit_kerja', 'Samsat')->first();
                if ($existingSk) {
                    $arrayResult[$k->id]['pdf_url']        = $existingSk->pdf_url;
                    $arrayResult[$k->id]['local_pdf_path'] = $existingSk->local_pdf_path;
                    continue;
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdf.view_sk', ['sk' => (object)[
                'nomor_sk'  => null,
                'kendaraan' => $k,
            ]])->setPaper('a4', 'portrait');

            if ($request->has('preview')) {
                $previewDir = 'sk/preview';
                $prefix = Auth::id() . '_' . $k->id . '_default_';
                // Hapus preview lama agar tidak memenuhi storage
                $existingFiles = Storage::disk('public')->files($previewDir);
                foreach ($existingFiles as $file) {
                    if (str_starts_with(basename($file), $prefix)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
            } else {
                $filename    = 'SK_DEFAULT_' . str_replace(' ', '_', $k->nrkb) . '_' . time() . '.pdf';
                $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
            }

            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = asset('storage/' . $storagePath);
            $localPdfPath   = Storage::disk('public')->path($storagePath);

            // Catat log untuk setiap kendaraan jika bukan preview
            if (!$request->has('preview')) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    'SK Default berhasil diterbitkan',
                    'Catatan: ' . ($request->catatan ?? '-'),
                    storage_path('app/public/' . $storagePath)
                );
                $arrayResult[$k->id]['log_id'] = $log->id;
            }

            $arrayResult[$k->id]['pdf_url']        = $pdfUrlAbsolute;
            $arrayResult[$k->id]['local_pdf_path'] = $localPdfPath;
        }

        return $arrayResult;
    }
/**
 * Generate PDF Surat Keputusan Jasa Raharja (Pembebasan SWDKLLJ)
 * Mengikuti pola: arrayResult per kendaraan, preview mode, draft mode, WA dispatch
 */
public function generateSkJasaRaharja(Request $request, Pengajuan $pengajuan)
{
    $request->validate([
        'kendaraan_id' => ['required', 
            function($attribute, $value, $fail) {
                if ($value === 'all') return;
                if (!\App\Models\Kendaraan::where('id', $value)->exists()) {
                    $fail('Kendaraan tidak ditemukan.');
                }
            },
        ],
        'tanggal_surat_permohonan' => 'required',
        'nomor_surat_regident' => 'required',
        'tanggal_surat_regident' => 'required',
        'nomor_surat_bapenda' => 'nullable',
        'tanggal_surat_bapenda' => 'nullable',
        'nomor_keputusan' => 'required',
        'tempat_sk' => 'required',
        'tanggal_sk' => 'required',
        'nama_penandatangan' => 'required',
        'metode_penanda_tangan' => 'required|in:ttd_elektronik,ttd_basah',
    ]);

    // Ambil data kendaraan (support 'all' atau single)
    $kendaraan = $request->kendaraan_id === 'all'
        ? $pengajuan->kendaraans()->with('pemilik')->get()
        : $pengajuan->kendaraans()->with('pemilik')->where('id', $request->kendaraan_id)->get();

    if ($kendaraan->isEmpty()) {
        return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
    }

    $arrayResult = [];

    foreach ($kendaraan as $k) {
        $pemilik = optional($k->pemilik);
        
        $dataPdf = [
            // Data Surat Keputusan JR
            'nomor_keputusan' => strtoupper($request->nomor_keputusan),
            'tempat_sk' => strtoupper($request->tempat_sk),
            'tanggal_sk' => strtoupper($request->tanggal_sk),
            'nama_penandatangan' => strtoupper($request->nama_penandatangan),
            'jabatan_penandatangan' => 'KEPALA KANTOR WILAYAH PT JASA RAHARJA JAWA TENGAH',
            'metode_penanda_tangan' => $request->metode_penanda_tangan,
            
            // Data Surat Referensi (Menimbang)
            'tanggal_surat_permohonan' => strtoupper($request->tanggal_surat_permohonan),
            'nomor_surat_regident' => strtoupper($request->nomor_surat_regident),
            'tanggal_surat_regident' => strtoupper($request->tanggal_surat_regident),
            'nomor_surat_bapenda' => $request->nomor_surat_bapenda ? strtoupper($request->nomor_surat_bapenda) : '-',
            'tanggal_surat_bapenda' => $request->tanggal_surat_bapenda ? strtoupper($request->tanggal_surat_bapenda) : '-',
            
            // Data Kendaraan & Pemilik (format objek konsisten dengan method lain)
            'data' => (object)[
                'nama' => strtoupper($pemilik->nama_pemilik ?? '-'),
                'alamat' => strtoupper($pemilik->alamat_pemilik ?? '-'),
                'nik' => strtoupper($pemilik->nik_pemilik ?? '-'),
                'no_tlp' => strtoupper($pemilik->telp_pemilik ?? '-'),
                'email' => strtoupper($pemilik->email_pemilik ?? '-'),
                'nrkb' => strtoupper($k->nrkb ?? '-'),
                'merek' => strtoupper($k->merk_kendaraan ?? '-'),
                'tipe' => strtoupper($k->tipe_kendaraan ?? '-'),
                'jenis' => strtoupper($k->jenis_kendaraan ?? '-'),
                'model' => strtoupper($k->model_kendaraan ?? '-'),
                'tahun' => strtoupper($k->tahun_pembuatan ?? '-'),
                'isi_silinder' => strtoupper($k->isi_silinder ?? '-'),
                'no_rangka' => strtoupper($k->nomor_rangka ?? '-'),
                'no_mesin' => strtoupper($k->nomor_mesin ?? '-'),
                'warna_kendaraan' => strtoupper($k->warna_kendaraan ?? '-'),
                'bahan_bakar' => strtoupper($k->jenis_bahan_bakar ?? '-'),
                'warna_tnkb' => strtoupper($k->warna_tnkb ?? '-'),
                'no_bpkb' => strtoupper($k->nomor_bpkb ?? '-'),
                // Tambahan field khusus JR
                'merk_type' => strtoupper(trim(($k->merk_kendaraan ?? '') . ' / ' . ($k->tipe_kendaraan ?? '-'))),
                'no_rangka_mesin' => strtoupper(trim(($k->nomor_rangka ?? '') . ' / ' . ($k->nomor_mesin ?? '-'))),
                'jenis_model' => strtoupper(trim(($k->jenis_kendaraan ?? '') . ' / ' . ($k->model_kendaraan ?? '-'))),
            ],
        ];

        $arrayResult[$k->id] = [
            'data' => $dataPdf,
            'pdf_url' => null,
            'local_pdf_path' => null,
        ];
        
        // Cek apakah SK JR sudah ada untuk kendaraan ini (skip jika sudah)
        if (!$request->has('preview')) {
            $existingSk = $k->suratKeputusans()->where('unit_kerja', 'JR')->first();
            if ($existingSk) {
                $arrayResult[$k->id]['pdf_url'] = $existingSk->pdf_url;
                $arrayResult[$k->id]['local_pdf_path'] = $existingSk->local_pdf_path;
                continue;
            }
        }

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_jasa_raharja_pembebasan', $dataPdf)
            ->setPaper('a4', 'portrait');
            
        if ($request->has('preview')) {
            $previewDir = 'sk/preview';
            $prefix = Auth::id() . '_' . $k->id . '_jr_pembebasan_';
            $existingFiles = Storage::disk('public')->files($previewDir);
            foreach ($existingFiles as $file) {
                if (str_starts_with(basename($file), $prefix)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
        } else {
            $filename = 'SK_JR_PEMBEBASAN_' . str_replace([' ', '/'], '_', $k->nrkb) . '_' . str_replace('/', '_', $dataPdf['nomor_keputusan']) . '.pdf';
            $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
        }

        // Handle TTD Basah: jika mode basah & bukan preview, PDF tidak langsung di-generate
        $ttd_basah = $request->metode_penanda_tangan == 'ttd_basah';
        if (!$ttd_basah || $request->has('preview')) {
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = asset('storage/' . $storagePath);
            $localPdfPath = Storage::disk('public')->path($storagePath);    
        } else {
            $pdfUrlAbsolute = null;
            $localPdfPath = null;
        }

        // Catat log jika bukan preview
        if (!$request->has('preview')) {
            $log = $this->logSuratActionByKendaraanId(
                $pengajuan,
                $k->id,
                'SK Jasa Raharja Pembebasan berhasil diterbitkan',
                'Nomor Keputusan: ' . $request->nomor_keputusan,
                !$ttd_basah ? storage_path('app/public/' . $storagePath) : null
            );
            $arrayResult[$k->id]['log_id'] = $log->id;
        }

        $arrayResult[$k->id]['pdf_url'] = $pdfUrlAbsolute;
        $arrayResult[$k->id]['local_pdf_path'] = $localPdfPath;
    }

    // Dispatch WhatsApp Notification (non-blocking) jika bukan preview
    if (!isset($request->preview)) {
        $wpUser = $pengajuan->user;
        if ($wpUser && $wpUser->no_hp) {
            try {
                if ($request->kendaraan_id === 'all') {
                    SendWhatsAppNotification::dispatch(
                        pengajuan: $pengajuan,
                        kendaraan: null,
                        skType: 'jr_pembebasan',
                        pdfUrl: null,
                        localPdfPath: null,
                        wpPhone: $wpUser->no_hp,
                        wpName: $wpUser->name,
                        nrkb: null,
                    );
                } else {
                    $k = $kendaraan->first();
                    SendWhatsAppNotification::dispatch(
                        pengajuan: $pengajuan,
                        kendaraan: $k,
                        skType: 'jr_pembebasan',
                        pdfUrl: $arrayResult[$k->id]['pdf_url'] ?? null,
                        localPdfPath: $arrayResult[$k->id]['local_pdf_path'] ?? null,
                        wpPhone: $wpUser->no_hp,
                        wpName: $wpUser->name,
                        nrkb: $k->nrkb,
                    );
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK JR Pembebasan): ' . $e->getMessage());
            }
        }
    }

    return $arrayResult;
}
    public function generateSkRegident(Request $request, Pengajuan $pengajuan)
    {
        
        $request->validate([
            // Cek validasi kendaraan id atau semua kendaraan dengan value validassi sama dengan all
            'kendaraan_id' => ['required', 
                function($attribute, $value, $fail) {
                    if ($value === 'all') return;
                    if (!\App\Models\Kendaraan::where('id', $value)->exists()) {
                        $fail('Kendaraan tidak ditemukan.');
                    }
                },
            ],
            'nomor_surat' => 'required',
            'nama_pembuat' => 'required',
            'tempat' => 'required',
            'tanggal_keluar' => 'required',
            'nama_direktur' => 'required',
            'pangkat_direktur' => 'required',
        ]);

        // Ambil data kendaraan berdasarkan pilihan dari form modal
        // Jadikan array agar dapat diproses dengan cara yang sama baik untuk single kendaraan maupun semua kendaraan
        $kendaraan = $request->kendaraan_id === 'all'
            ? $pengajuan->kendaraans()->with('pemilik')->get()
            : $pengajuan->kendaraans()->with('pemilik')->where('id', $request->kendaraan_id)->get();

        if ($kendaraan->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // Key tiap kendaraan id untuk menyimpan hasil PDF dan log secara terpisah
        $arrayResult = [];

        foreach ($kendaraan as $k) {
            $dataPdf = [
                // Dari Form Input
                'nomor_surat' => strtoupper($request->nomor_surat),
                'nama_pembuat' => strtoupper($request->nama_pembuat),
                'tempat' => strtoupper($request->tempat),
                'tanggal_keluar' => strtoupper($request->tanggal_keluar),
                'nama_direktur' => strtoupper($request->nama_direktur),
                'pangkat_direktur' => strtoupper($request->pangkat_direktur),
                // Dari Database Kendaraan/Pemilik
                'data' => (object)[
                    'nama' => strtoupper(optional($k->pemilik)->nama_pemilik ?? '-'),
                    'alamat' => strtoupper(optional($k->pemilik)->alamat_pemilik ?? '-'),
                    'nik' => strtoupper(optional($k->pemilik)->nik_pemilik ?? '-'),
                    'no_tlp' => strtoupper(optional($k->pemilik)->telp_pemilik ?? '-'),
                    'email' => strtoupper(optional($k->pemilik)->email_pemilik ?? '-'),
                    'nrkb' => strtoupper($k->nrkb ?? '-'),
                    'merek' => strtoupper($k->merk_kendaraan ?? '-'),
                    'tipe' => strtoupper($k->tipe_kendaraan ?? '-'),
                    'jenis' => strtoupper($k->jenis_kendaraan ?? '-'),
                    'model' => strtoupper($k->model_kendaraan ?? '-'),
                    'tahun' => strtoupper($k->tahun_pembuatan ?? '-'),
                    'isi_silinder' => strtoupper($k->isi_silinder ?? '-'),
                    'no_rangka' => strtoupper($k->nomor_rangka ?? '-'),
                    'no_mesin' => strtoupper($k->nomor_mesin ?? '-'),
                    'warna_kendaraan' => strtoupper($k->warna_kendaraan ?? '-'),
                    'bahan_bakar' => strtoupper($k->jenis_bahan_bakar ?? '-'),
                    'warna_tnkb' => strtoupper($k->warna_tnkb ?? '-'),
                    'no_bpkb' => strtoupper($k->nomor_bpkb ?? '-'),
                ]
            ];

            $arrayResult[$k->id] = [
                'data' => $dataPdf,
                'pdf_url' => null,
                'local_pdf_path' => null,
            ];
            
            //Cek Kendaraan id ini apakah sudah punya SK Pembebasan dari Polda, jika sudah maka skip proses pembuatan PDF dan log untuk kendaraan ini, tapi tetap simpan data PDF kosong dan URL null di array result agar konsisten dengan kendaraan lain yang diproses
            if (!$request->has('preview')) {
                $existingSkBapenda = $k->suratKeputusans()->where('unit_kerja', 'Polda')->first();
                if ($existingSkBapenda) {
                    $arrayResult[$k->id]['pdf_url'] = $existingSkBapenda->pdf_url;
                    $arrayResult[$k->id]['local_pdf_path'] = $existingSkBapenda->local_pdf_path;
                    continue; // Skip pembuatan PDF dan log untuk kendaraan ini
                }
            }

            // Generate PDF untuk setiap kendaraan
            $pdf = Pdf::loadView('pdf.sk_penghapusan_regident', $dataPdf)->setPaper('a4', 'portrait');
            if ($request->has('preview')) {
                $previewDir = 'sk/preview';
                $prefix = Auth::id() . '_' . $k->id . '_regident_';
                // Delete old preview files matching this pattern to optimize storage space
                $existingFiles = Storage::disk('public')->files($previewDir);
                foreach ($existingFiles as $file) {
                    if (str_starts_with(basename($file), $prefix)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
            } else {
                $filename   = 'SK_REGIDENT_' . str_replace('/', '_', $dataPdf['nomor_surat']) . '_' . str_replace(' ', '_', $k->nrkb) . '.pdf';
                $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
            }
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = asset('storage/' . $storagePath);
            $localPdfPath = Storage::disk('public')->path($storagePath);           

            // Catat log untuk setiap kendaraan jika bukan preview
            if (!$request->has('preview')) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    'SK Penghapusan Regident berhasil diterbitkan',
                    'Nomor Surat: ' . $request->nomor_surat,
                    storage_path('app/public/' . $storagePath)
                );
                $arrayResult[$k->id]['log_id'] = $log->id;
            }

            $arrayResult[$k->id]['pdf_url'] = $pdfUrlAbsolute;
            $arrayResult[$k->id]['local_pdf_path'] = $localPdfPath;
        }

        if (!isset($request->preview)) {
            if ($request->kendaraan_id === 'all') {
                // Dispatch WA notification untuk semua kendaraan (non-blocking, non-fatal)
                $wpUser = $pengajuan->user;
                if ($wpUser && $wpUser->no_hp) {
                    try {
                        SendWhatsAppNotification::dispatch(
                            pengajuan:    $pengajuan,
                            kendaraan:    null, // Karena ini untuk semua kendaraan, kita bisa kirim null atau array kendaraan
                            skType:       'regident',
                            pdfUrl:       null, // Bisa dikirim null atau array URL PDF jika ingin mengirim per kendaraan
                            localPdfPath: null, // Bisa dikirim null atau array path PDF jika ingin mengirim per kendaraan
                            wpPhone:      $wpUser->no_hp,
                            wpName:       $wpUser->name,
                            nrkb:         null, // Karena ini untuk semua kendaraan, kita bisa kirim null atau array NRKB jika ingin mengirim per kendaraan
                        );
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Regident - All): ' . $e->getMessage());
                    }
                }
            } else {
                // Dispatch WA notification untuk single kendaraan (non-blocking, non-fatal)
                $k = $kendaraan->first(); // Karena ini hanya untuk satu kendaraan
                $wpUser = $pengajuan->user;
                if ($wpUser && $wpUser->no_hp) {
                    try {
                        SendWhatsAppNotification::dispatch(
                            pengajuan:    $pengajuan,
                            kendaraan:    $k,
                            skType:       'regident',
                            pdfUrl:       $arrayResult[$k->id]['pdf_url'] ?? null,
                            localPdfPath: $arrayResult[$k->id]['local_pdf_path'] ?? null,
                            wpPhone:      $wpUser->no_hp,
                            wpName:       $wpUser->name,
                            nrkb:         $k->nrkb,
                        );
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Regident - Single): ' . $e->getMessage());
                    }
                }
            }
        }

        return $arrayResult;
    }

    public function generateSkBapenda(Request $request, Pengajuan $pengajuan)
    {

        $request->validate([
            'kendaraan_id' => ['required', 
                function($attribute, $value, $fail) {
                    if ($value === 'all') return;
                    if (!\App\Models\Kendaraan::where('id', $value)->exists()) {
                        $fail('Kendaraan tidak ditemukan.');
                    }
                },
            ],
            'nama_pembuat_surat_permohonan' => 'required',
            'tempat_pembuat_surat_permohonan' => 'required',
            'tanggal_pembuat_surat_permohonan' => 'required',
            'nomor_surat_regident' => 'required',
            'nama_pembuat_surat_regident' => 'required',
            'tempat_pembuat_surat_regident' => 'required',
            'tanggal_pembuat_surat_regident' => 'required',
            'nomor_surat_pembebasan' => 'required',
            'tempat_sk' => 'required',
            'tanggal_sk' => 'required',
            'nama_direktur' => 'required',
            'metode_penanda_tangan' => 'required',
        ]);

        // Ambil data kendaraan berdasarkan pilihan dari form modal
        // Jadikan array agar dapat diproses dengan cara yang sama baik untuk single kendaraan maupun semua kendaraan
        $kendaraan = $request->kendaraan_id === 'all'
            ? $pengajuan->kendaraans()->with('pemilik')->get()
            : $pengajuan->kendaraans()->with('pemilik')->where('id', $request->kendaraan_id)->get();

        if ($kendaraan->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // Key tiap kendaraan id untuk menyimpan hasil PDF dan log secara terpisah
        $arrayResult = [];

        foreach ($kendaraan as $k) {
            $dataPdf = [
                'nama_pembuat_surat_permohonan' => $request->nama_pembuat_surat_permohonan,
                'tempat_pembuat_surat_permohonan' => $request->tempat_pembuat_surat_permohonan,
                'tanggal_pembuat_surat_permohonan' => $request->tanggal_pembuat_surat_permohonan,
                'nomor_surat_regident' => strtoupper($request->nomor_surat_regident),
                'nama_pembuat_surat_regident' => $request->nama_pembuat_surat_regident,
                'tempat_pembuat_surat_regident' => $request->tempat_pembuat_surat_regident,
                'tanggal_pembuat_surat_regident' => $request->tanggal_pembuat_surat_regident,
                'nomor_surat_pembebasan' => strtoupper($request->nomor_surat_pembebasan),
                'tempat_sk' => $request->tempat_sk,
                'tanggal_sk' => $request->tanggal_sk,
                'nama_direktur' => $request->nama_direktur,
                'metode_penanda_tangan' => $request->metode_penanda_tangan,
                'data' => (object)[
                    'nama' => strtoupper(optional($k->pemilik)->nama_pemilik ?? '-'),
                    'alamat' => strtoupper(optional($k->pemilik)->alamat_pemilik ?? '-'),
                    'nik' => strtoupper(optional($k->pemilik)->nik_pemilik ?? '-'),
                    'no_tlp' => strtoupper(optional($k->pemilik)->telp_pemilik ?? '-'),
                    'email' => strtoupper(optional($k->pemilik)->email_pemilik ?? '-'),
                    'nrkb' => strtoupper($k->nrkb ?? '-'),
                    'merek' => strtoupper($k->merk_kendaraan ?? '-'),
                    'tipe' => strtoupper($k->tipe_kendaraan ?? '-'),
                    'jenis' => strtoupper($k->jenis_kendaraan ?? '-'),
                    'model' => strtoupper($k->model_kendaraan ?? '-'),
                    'tahun' => strtoupper($k->tahun_pembuatan ?? '-'),
                    'isi_silinder' => strtoupper($k->isi_silinder ?? '-'),
                    'no_rangka' => strtoupper($k->nomor_rangka ?? '-'),
                    'no_mesin' => strtoupper($k->nomor_mesin ?? '-'),
                    'warna_kendaraan' => strtoupper($k->warna_kendaraan ?? '-'),
                    'bahan_bakar' => strtoupper($k->jenis_bahan_bakar ?? '-'),
                    'warna_tnkb' => strtoupper($k->warna_tnkb ?? '-'),
                    'no_bpkb' => strtoupper($k->nomor_bpkb ?? '-'),
                ],
            ];

            
            
            // Simpan data PDF dan URL absolute-nya per kendaraan
            $arrayResult[$k->id] = [
                'data_pdf' => $dataPdf,
                'pdf_url' => null,
                'local_pdf_path' => null,
                ];
                
            //Cek Kendaraan id ini apakah sudah punya SK Pembebasan dari Bapenda, jika sudah maka skip proses pembuatan PDF dan log untuk kendaraan ini, tapi tetap simpan data PDF kosong dan URL null di array result agar konsisten dengan kendaraan lain yang diproses
            if (!$request->has('preview')) {
                $existingSkBapenda = $k->suratKeputusans()->where('unit_kerja', 'Bapenda')->first();
                if ($existingSkBapenda) {
                    $arrayResult[$k->id]['pdf_url'] = $existingSkBapenda->pdf_url;
                    $arrayResult[$k->id]['local_pdf_path'] = $existingSkBapenda->local_pdf_path;
                    continue; // Skip pembuatan PDF dan log untuk kendaraan ini
                }
            }


            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_bapenda_pembebasan', $dataPdf);
            $pdf->setPaper('a4', 'portrait');
            if ($request->has('preview')) {
                $previewDir = 'sk/preview';
                $prefix = Auth::id() . '_' . $k->id . '_pembebasan_';
                // Delete old preview files matching this pattern to optimize storage space
                $existingFiles = Storage::disk('public')->files($previewDir);
                foreach ($existingFiles as $file) {
                    if (str_starts_with(basename($file), $prefix)) {
                        Storage::disk('public')->delete($file);
                    }
                }
                $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
            } else {
                $filename = 'SK_PEMBEBASAN_' . str_replace(' ', '_', $k->nrkb) . '_' . str_replace('/', '_', $dataPdf['nomor_surat_pembebasan']) . '.pdf';
                $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
            }
            
            $ttd_basah = $request->metode_penanda_tangan == 'ttd_basah';
            if (!$ttd_basah || $request->has('preview')) {
                Storage::disk('public')->put($storagePath, $pdf->output());
                $pdfUrlAbsolute = asset('storage/' . $storagePath);
                $localPdfPath = Storage::disk('public')->path($storagePath);    
            } else {
                $pdfUrlAbsolute = null;
                $localPdfPath = null;
            }

            if (!$request->has('preview')) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    'SK Pembebasan berhasil dibuat dan ditandatangani',
                    'Nomor Surat: ' . $request->nomor_surat_pembebasan,
                    !$ttd_basah ? Storage::disk('public')->path($storagePath) : null
                );
                $arrayResult[$k->id]['log_id'] = $log->id;
            }

            // Simpan URL dan path ke array result
            $arrayResult[$k->id]['pdf_url'] = $pdfUrlAbsolute;
            $arrayResult[$k->id]['local_pdf_path'] = $localPdfPath;
        }
        
        if (!isset($request->preview)) {
            // Dispatch WA notification (non-blocking, non-fatal)
            $wpUser = $pengajuan->user;
            if ($request->kendaraan_id === 'all') {
                if ($wpUser && $wpUser->no_hp) {
                        // Dispatch WA notification untuk semua kendaraan (non-blocking, non-fatal)
                    try {
                        SendWhatsAppNotification::dispatch(
                            pengajuan:    $pengajuan,
                            kendaraan:    null,
                            skType:       'pembebasan',
                            pdfUrl:       null,
                            localPdfPath: null,
                            wpPhone:      $wpUser->no_hp,
                            wpName:       $wpUser->name,
                            nrkb:         null,
                        );
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Pembebasan): ' . $e->getMessage());
                    }
                }
            }else {
                $k = $kendaraan->first();
                if ($wpUser && $wpUser->no_hp) {
                    try {
                        SendWhatsAppNotification::dispatch(
                            pengajuan:    $pengajuan,
                            kendaraan:    $k,
                            skType:       'pembebasan',
                            pdfUrl:       $arrayResult[$k->id]['pdf_url'],
                            localPdfPath: $arrayResult[$k->id]['local_pdf_path'],
                            wpPhone:      $wpUser->no_hp,
                            wpName:       $wpUser->name,
                            nrkb:         $k->nrkb,
                        );
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Pembebasan): ' . $e->getMessage());
                    }
                }
            }
        }

        return $arrayResult;
    }
    
    public function ajukan(Request $request, $pengajuan_id)
    {
        $request->validate([
            'kendaraan_id' => ['required', 
                function($attribute, $value, $fail) {
                    if ($value === 'all') return;
                    if (!\App\Models\Kendaraan::where('id', $value)->exists()) {
                        $fail('Kendaraan tidak ditemukan.');
                    }
                },
            ],
        ]);
        $pengajuan = Pengajuan::with('kendaraans')->findOrFail($pengajuan_id);
        $kendaraan = $request->kendaraan_id === 'all' 
        ? $pengajuan->kendaraans 
        : $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->get();

        if ($kendaraan->isEmpty()) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
                ->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        if (!$pengajuan->kendaraans->where('status', 'diproses')->count()) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Pengajuan tidak memiliki kendaraan dengan status "Diproses".');
            //  response()->json(['message' => 'Pengajuan tidak memiliki kendaraan dengan status "Diproses".'], 400);
        }

        $suratKeputusan = $pengajuan->suratKeputusans;

        // Cek untuk unit_kerja user sekarang, apakah sudah ada SK yang diajukan untuk unit kerja tersebut
        if ($request->kendaraan_id !== 'all' && $suratKeputusan->where('unit_kerja', $this->normalizeUnitKerja(Auth::user()->unit_kerja))->isNotEmpty()) {
                // Map suratKeputusan with unit_kerja
            $skUnitKerja = $suratKeputusan->pluck('unit_kerja')->toArray();
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Surat Keputusan sudah diajukan oleh unit kerja: ' . implode(', ', $skUnitKerja));
            //  response()->json(['message' => 'Surat Keputusan sudah diajukan oleh unit kerja: ' . implode(', ', $skUnitKerja)], 400);
        }
        
        $data = [];

        switch ($this->normalizeUnitKerja(Auth::user()->unit_kerja)) {
            case 'Polda':
                $unitKerja = 'Polda';
                $data = $this->generateSkRegident($request, $pengajuan);
                break;
            case 'Bapenda':
                $unitKerja = 'Bapenda';
                $data = $this->generateSkBapenda($request, $pengajuan);
                break;
            case 'JR':
                $unitKerja = 'JR';
                $data = $this->generateSkJR($request, $pengajuan);
                break;
            default:
                $unitKerja = 'Unit Kerja Lain';
                $data = [];
                break;
        }

        $baseLogTime = now(); // Waktu dasar untuk log, agar semua log yang terkait memiliki timestamp yang konsisten

        if ($request->has('preview')) {
            return response()->json([
                'message' => 'Preview Surat Keputusan',
                'data' => Arr::map($data, function($item) {
                    return [
                        'pdf_url' => $item['pdf_url'] ?? null,
                    ];
                })
            ]);
        }
        // Loop setiap data hasil switch dengan kendaraan
        foreach ($kendaraan as $k) {
            $sk = SuratKeputusan::create([
                'pengajuan_id' => $pengajuan_id,
                'kendaraan_id' => $k->id,
                'user_id' => Auth::id(),
                'unit_kerja' => $unitKerja,
                'nomor_sk' => 'SK-' . strtoupper(uniqid()),
                'local_pdf_path' => !isset($request->preview) && isset($data[$k->id]['local_pdf_path']) ? $data[$k->id]['local_pdf_path'] : null,
                'pdf_url' => !isset($request->preview) && isset($data[$k->id]['pdf_url']) ? $data[$k->id]['pdf_url'] : null,
                'tanggal_ditetapkan' => now(),
                'created_at' => $baseLogTime,
                'updated_at' => $baseLogTime,
            ]);

            if (isset($data[$k->id]['log_id'])) {
                $updateData = ['sk_id' => $sk->id];
                // Jika draft mode, set sk_status=draft pada log
                if ($request->has('draft_mode')) {
                    $updateData['sk_status'] = 'draft';
                }
                KendaraanLog::where('id', $data[$k->id]['log_id'])->update($updateData);
            }

            // Hanya jalankan logic selesai jika BUKAN draft mode
            if (!$request->has('draft_mode')) {
                $totalSkByUnitKerja = $k->suratKeputusans()
                    ->whereIn('unit_kerja', ['Polda', 'Bapenda', 'JR'])
                    ->distinct('unit_kerja')
                    ->count('unit_kerja');

                if ($k->status == 'diproses' && $totalSkByUnitKerja >= 3) {
                    $k->update(['status' => 'selesai']);
                    // Simpan log untuk status selesai.
                    $logSelesai = $this->logSuratActionByKendaraanId(
                        $pengajuan,
                        $k->id,
                        'Pengajuan Selesai',
                        'Pengajuan Selesai Setelah Ketiga Surat Keputusan Ditetapkan.'
                    );
                    // Record 1 second after the SK log so chronological order is explicit.
                    $logSelesai->created_at = $baseLogTime->copy()->addSecond();
                    $logSelesai->updated_at = $baseLogTime->copy()->addSecond();
                    $logSelesai->save();
                }
            }

            $data[$k->id]['sk_id'] = $sk->id; // Simpan ID SK yang baru dibuat untuk referensi jika diperlukan
        }

        return response()->json(['message' => 'Surat Keputusan berhasil diajukan.', 'data' => $data]);

    }

    public function uploadFileToMedia(Request $request)
    {
        // Bentuk Post form data
        // {kendaraan_id: int, file: file}, data bisa > 1
        
        // validate data
        $request->validate([
            'sk_id' => 'required|integer',
            'log_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif,docx|max:10240',
        ]);

        $user = Auth::user();
        $suratkeputusan = SuratKeputusan::findOrFail($request->sk_id);
        $log = KendaraanLog::findOrFail($request->log_id);
        
        if ($suratkeputusan->pdf_url) {
            return redirect()->route('admin.pengajuan.show', $suratkeputusan->pengajuan_id)
                ->with('error', 'File PDF sudah ada di media library.');
        }

        $filename = $request->file->getClientOriginalName();
        $storagePath    = 'sk/' . Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $request->file('file')->get());
        $localPdfPath = Storage::disk('public')->path($storagePath);
        $pdfUrlAbsolute = asset('storage/' . $storagePath);
        
        // Alter table for localPdfPath and pdf_url
        $suratkeputusan->update([
            'local_pdf_path' => $pdfUrlAbsolute,
            'pdf_url' => $pdfUrlAbsolute,
        ]);
        
        $log->addMedia($localPdfPath)->preservingOriginal()->toMediaCollection("lampiran_log");

        return redirect()->route('admin.pengajuan.show', $suratkeputusan->pengajuan_id)
            ->with('success', 'File PDF berhasil diupload.');
    }

    private function logSuratActionByKendaraanId(Pengajuan $pengajuan, string $kendaraan_id, string $actionLabel, string $notes, $file = null, int $sk_id = null): KendaraanLog
    {
        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan_id,
            'user_id' => Auth::id(),
            'aksi' => $actionLabel,
            'status_baru' => $pengajuan->kendaraans->find($kendaraan_id)->status,
            'tipe' => 'system',
            'catatan' => $notes,
            'sk_id' => $sk_id,
        ]);
        if ($file) {
            $log->addMedia($file)->preservingOriginal()->toMediaCollection("lampiran_log");
        }
        return $log;
    }

    private function logSuratAction(Pengajuan $pengajuan, string $actionLabel, string $notes, $file = null): array
    {
        $logArray = [];
        foreach ($pengajuan->kendaraans as $kendaraan) {
            $logArray[$kendaraan->id] = KendaraanLog::create([
                'kendaraan_id' => $kendaraan->id,
                'user_id' => Auth::id(),
                'aksi' => $actionLabel,
                'status_baru' => $kendaraan->status,
                'tipe' => 'system',
                'catatan' => $notes,
                'sk_id' => null,
                'sp_id' => null,
            ]);
            if ($file) {
                $logArray[$kendaraan->id]->addMedia($file)->preservingOriginal()->toMediaCollection("lampiran_log");
            }
        }

        return $logArray;
    }
}
