<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\KendaraanLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendWhatsAppNotification;
use Illuminate\Support\Arr;

class SuratPengajuanController extends Controller
{

    protected $persetujuanDefault = [
        ['instansi' => 'Bapenda', 'status' => 'pending', 'user_id' => null],
        ['instansi' => 'Jasa Raharja', 'status' => 'pending', 'user_id' => null],
    ];

    protected $persetujuanPolda = [
        ['instansi' => 'Polda', 'status' => 'pending']
    ];

    private const TARGET_POLDA = 'polda';
    private const TARGET_BAPENDA_JR = 'bapenda_jr';

    private function userHasRole($user, string $role): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole($role);
    }

    private function normalizeUnitKerja(?string $unitKerja): string
    {
        return match (strtolower(trim((string) $unitKerja))) {
            'jr', 'jasa raharja', 'jasa_raharja' => 'Jasa Raharja',
            'bapenda' => 'Bapenda',
            'polda' => 'Polda',
            'samsat' => 'Samsat',
            default => trim((string) $unitKerja),
        };
    }

    static public function getRegistry($type, $id, $data = null)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        $user = Auth::user();
        $progress = $pengajuan->getTotalSurat(); // Perhatikan typo: getProgress sesuai model Anda
        
        // Ambil SP terakhir untuk mengecek status persetujuan saat ini
        $suratpengajuan = $pengajuan->getSliceSuratPengajuanLastRejected();
        $lastSp = $suratpengajuan->last();

        $registries = [
            'pdf' => [
                'default' => [
                    'view' => 'pdf.view_sp',
                    'mode' => 'iframe',
                    'prefix' => 'SP-',
                    'permission' => 'view_dokumen_surat_pengajuan',
                    'footer' => [
                        'accept' => false,
                        'reject' => false,
                        'back' => false,
                    ]
                ],
                'polda' => [
                    'view' => 'pdf.sp_polda2bapendaNjr',
                    'mode' => 'iframe',
                    'prefix' => 'SP-POLDA-',
                    'permission' => 'view_dokumen_surat_pengajuan',
                    'footer' => [
                        'accept' => false,
                        'reject' => false,
                        'back' => false,
                    ]
                ],
                'bapenda' => [
                    'view' => 'pdf.sp_balasan_bapenda',
                    'mode' => 'iframe',
                    'prefix' => 'SP-BALASAN-BAPENDA-',
                    'permission' => 'view_dokumen_surat_pengajuan',
                    'footer' => [
                        'accept' => false,
                        'reject' => false,
                        'back' => false,
                    ]
                ]
            ]
        ];

        $config = $registries[$type] ?? abort(404);

        $nrkbStr = '';
        if ($pengajuan && $pengajuan->kendaraans->isNotEmpty()) {
            $nrkbStr = $pengajuan->kendaraans->count() === 1
                ? $pengajuan->kendaraans->first()->nrkb
                : $pengajuan->kendaraans->pluck('nrkb')->implode(', ');
        }

        if ($type === 'pdf' && $data instanceof SuratPengajuan) {
            $path = $data->local_pdf_path ?? $data->pdf_url ?? '';
            if (str_contains(strtoupper($path), 'SP_POLDA') || str_contains(strtoupper($path), 'SP-POLDA') || str_contains(strtoupper($path), 'SURAT PENGAJUAN - POLDA')) {
                $config = $config['polda'];
                $config['filename'] = 'Surat Pengajuan - Polda kepada Bapenda dan Jasa Raharja No Pol ' . $nrkbStr;
            } elseif (str_contains(strtoupper($path), 'SP_BALASAN_BAPENDA') || str_contains(strtoupper($path), 'SP-BALASAN-BAPENDA') || str_contains(strtoupper($path), 'BALASAN BAPENDA')) {
                $config = $config['bapenda'];
                $config['filename'] = 'Balasan Bapenda - Surat Penghapusan Regident No Pol ' . $nrkbStr;
            } elseif (str_contains(strtoupper($path), 'SP_BALASAN_JR') || str_contains(strtoupper($path), 'SP-BALASAN-JR') || str_contains(strtoupper($path), 'BALASAN JR') || str_contains(strtoupper($path), 'PEMBEBASAN SW')) {
                $config = $config['default'];
                $config['filename'] = 'Balasan JR - Surat Pembebasan SW No Pol ' . $nrkbStr;
            } else {
                $config = $config['default'];
                $config['filename'] = 'Surat Pengajuan - Polda kepada Bapenda dan Jasa Raharja No Pol ' . $nrkbStr;
            }
        } else {
            $unitKerja = $user ? match (strtolower(trim((string) $user->unit_kerja))) {
                'jr', 'jasa raharja', 'jasa_raharja' => 'Jasa Raharja',
                'bapenda' => 'Bapenda',
                'polda' => 'Polda',
                default => trim((string) $user->unit_kerja),
            } : '';

            // Cek apakah user adalah responder untuk SP yang aktif saat ini
            $isResponder = false;
            if ($lastSp && !$lastSp->isFullyApproved() && !$lastSp->isRejected()) {
                $statusInstansi = $lastSp->persetujuan_unit_kerja
                    ? collect($lastSp->persetujuan_unit_kerja)->firstWhere(fn($item) => strcasecmp($item['instansi'] ?? '', $unitKerja) === 0)
                    : null;
                if ($statusInstansi && ($statusInstansi['status'] ?? null) === 'pending') {
                    $isResponder = true;
                }
            }

            if ($unitKerja == 'Polda' && isset($config['polda'])) {
                if ($isResponder) {
                    $config = $config['default'];
                } else {
                    $config = $config['polda'];
                }
            } elseif (($unitKerja == 'Bapenda' || $unitKerja == 'Jasa Raharja') && isset($config['bapenda'])) {
                $config = $config['bapenda'];
            } else {
                $config = $config['default'];
            }

            if ($unitKerja === 'Polda') {
                $config['filename'] = 'Surat Pengajuan - Polda kepada Bapenda dan Jasa Raharja No Pol ' . $nrkbStr;
            } elseif ($unitKerja === 'Bapenda') {
                $config['filename'] = 'Balasan Bapenda - Surat Penghapusan Regident No Pol ' . $nrkbStr;
            } elseif ($unitKerja === 'Jasa Raharja') {
                $config['filename'] = 'Balasan JR - Surat Pembebasan SW No Pol ' . $nrkbStr;
            } else {
                $config['filename'] = 'Surat Pengajuan - Polda kepada Bapenda dan Jasa Raharja No Pol ' . $nrkbStr;
            }
        }

        return $config;
    }

    /**
     * Menentukan Permission secara dinamis
     */
    private static function determinePermission($user, $progress)
    {
        $unitKerja = match (strtolower(trim((string) $user->unit_kerja))) {
            'jr', 'jasa raharja', 'jasa_raharja' => 'Jasa Raharja',
            'bapenda' => 'Bapenda',
            'polda' => 'Polda',
            default => trim((string) $user->unit_kerja),
        };

        if ($unitKerja == 'Polda') return ['create_pdf_pengajuan', 'create_pdf_balasan_samsat', 'create_pdf_pengajuan_bapenda_jr'];
        else if ($unitKerja == 'Bapenda' || $unitKerja == 'Jasa Raharja') return ['create_pdf_balasan_polda'];
        if ($progress == 0) return ['create_pdf_pengajuan'];
        return ['create_pdf_pengajuan'];
    }


    /**
     * Membangun Footer secara Conditional
     */
    private static function buildFooter($user, $pengajuan, $lastSp, $progress)
    {
        $footer = [];

        // 1. Tombol Kembali (Selalu Ada)
        $footer['back'] = [
            'label' => 'Kembali',
            'class' => 'btn-secondary',
        ];

        // 2. Logika Tombol Setujui / Ajukan
        $footer['accept'] = self::getAcceptButtonConfig($user, $pengajuan, $lastSp, $progress);
        $footer['reject'] = false; // Default tidak ada tombol tolak

        // 3. Logika Tombol Tolak (Muncul jika ada SP yang perlu direview)
        if ($lastSp && !$lastSp->isFullyApproved() && !$lastSp->isRejected()) {
             $footer['reject'] = [
                'label' => 'Tolak',
                'class' => 'btn-danger',
                'route' => [
                    'name' => 'admin.pengajuan.sp.tolak',
                    'middleware' => 'signed',
                    'params' => ['surat' => $lastSp->id]
                ]
            ];
        }

        return $footer;
    }

    /**
     * Menentukan Route & Label Tombol Accept
     */
    private static function getAcceptButtonConfig($user, $pengajuan, $lastSp, $progress)
    {
        // Kondisi A: Progres 0 (Awal) - Samsat ajukan ke Polda
        if (in_array($progress, [0, 2])) {
            return [
                'label' => 'Ajukan ke ' . ( $progress ? 'Bapenda & JR' : 'Polda'),
                'class' => 'btn-primary',
                'route' => ['name' => 'admin.pengajuan.ajukan', 'middleware' => 'signed']
            ];
        }

        // Kondisi B: Menunggu Persetujuan (Polda/Bapenda/JR)
        if ($lastSp && !$lastSp->isFullyApproved() && !$lastSp->isRejected()) {
            return [
                'label' => 'Setujui Dokumen',
                'class' => 'btn-success',
                'route' => [
                    'name' => 'admin.pengajuan.sp.terima',
                    'middleware' => 'signed',
                    'params' => ['surat' => $lastSp->id]
                ]
            ];
        }

        // Kondisi C: Fase SK (Jika sudah di-approve semua)
        if ($pengajuan->isFullyApprovedByAll()) { // Buat helper ini di model
             return [
                'label' => 'Terbitkan SK',
                'class' => 'btn-info',
                'route' => ['name' => 'admin.pengajuan.buat_sk', 'middleware' => 'signed']
            ];
        }

        return false; // Tidak ada aksi yang tersedia
    }

    static public function render(Request $request, $type, $id)
    {
        if ($type == 'pdf') {
            $sp = SuratPengajuan::with('pengajuan.kendaraans.pemilik')->findOrFail($id);
            $config = SuratPengajuanController::getRegistry($type, $sp->pengajuan_id, $sp);
            $pengajuan = $sp->pengajuan;
            $kendaraans = $pengajuan->kendaraans;
            $pemilik = optional($kendaraans->first())->pemilik;

            if ($sp->local_pdf_path) {
                $path = $sp->local_pdf_path;
                if (file_exists($path)) {
                    return response()->file($path, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $config['filename'] . '.pdf"'
                    ]);
                }
                $relativePath = str_replace([asset('storage/'), url('storage/'), asset('storage'), url('storage')], '', $path);
                $relativePath = ltrim($relativePath, '/');
                if (Storage::disk('public')->exists($relativePath)) {
                    return response()->file(Storage::disk('public')->path($relativePath), [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $config['filename'] . '.pdf"'
                    ]);
                }
            }

            return Pdf::loadView($config['view'], ['sp' => $sp])
                ->setPaper('a4', 'portrait')
                ->stream($config['filename'] . '.pdf');
        }

        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        $sp = $pengajuan->getCurrentSuratPengajuan();
        $config = SuratPengajuanController::getRegistry($type, $pengajuan->id, $sp);

        return view($config['view'], ['sp' => $sp, 'pengajuan' => $pengajuan]);
    }

    public function index()
    {
        $user = Auth::user();
        $query = SuratPengajuan::with('pengajuan');
        $unitKerja = $this->normalizeUnitKerja($user->unit_kerja);

        // Filter berdasarkan unit kerja dan role
        if ($this->userHasRole($user, 'samsat')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Samsat');
            });
        } elseif ($this->userHasRole($user, 'polda') || $unitKerja === 'Polda') {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Polda');
            });
        } elseif ($this->userHasRole($user, 'bapenda') || $unitKerja === 'Bapenda') {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Bapenda');
            });
        } elseif ($this->userHasRole($user, 'jasa_raharja') || $unitKerja === 'Jasa Raharja') {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Jasa Raharja');
            });
        }

        $suratPengajuans = $query->get();
        return view('admin.surat.view_pengajuan', compact('suratPengajuans'));
    }

    public function generateSPDefault(Request $request, Pengajuan $pengajuan)
    {

        $kendaraans = $pengajuan->kendaraans()->with('pemilik')->get();

        if ($kendaraans->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        return [
            'pdf_url' => null,
            'local_pdf_path' => null,
        ];
    }

    public function generateSPPolda(Request $request, Pengajuan $pengajuan)
    {
        // 1. Validasi input (termasuk 'lampiran')
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:pengajuan,diproses,selesai,ditolak',
        ]);

        $statuses = $request->input('status', []);

        $kendaraans = $pengajuan->kendaraans()->with('pemilik')->get();

        if ($kendaraans->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // 2. Loop setiap status yang dikirim dari form
        foreach ($statuses as $kendaraanId => $newStatus) {

            $kendaraan = $kendaraans->get($kendaraanId);
            if (!$kendaraan) {
                continue;
            }
            $oldStatus = $kendaraan->status;

            // Hanya update jika status berubah ATAU ada catatan baru ATAU ada lampiran baru
            if ($oldStatus !== $newStatus) {
                $kendaraan->update([
                    'status' => $newStatus,
                ]);
            }
        }

        /// Dispatch WA notification
        $wpUser = $pengajuan->user;
        if ($wpUser && $wpUser->no_hp) {
            $nrkbString = $kendaraans->count() === 1 
                ? $kendaraans->first()->nrkb 
                : $kendaraans->pluck('nrkb')->implode(', ');
            try {
                SendWhatsAppNotification::dispatch(
                    pengajuan:    $pengajuan,
                    kendaraan:    $kendaraans->first(),
                    skType:       'samsat',
                    pdfUrl:       null,
                    localPdfPath: null,
                    wpPhone:      $wpUser->no_hp,
                    wpName:       $wpUser->name,
                    nrkb:         $nrkbString,
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SP Polda): ' . $e->getMessage());
            }
        }

        return [
            'pdf_url' => null,
            'local_pdf_path' => null,
        ];
    }

    /**
     * Generate PDF Surat Pengajuan POLDA untuk Bapenda dan JR
     */
    public function generateSPPolda2BapendaNJR(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'nomor_surat' => 'required|string',
            'nama_pembuat' => 'required|string',
            'tempat' => 'required|string',
            'tanggal_keluar' => 'required|string',
            'nama_direktur' => 'required|string',
            'pangkat_direktur' => 'required|string',
        ]);

        $kendaraans = $pengajuan->kendaraans()->with('pemilik')->get();

        if ($kendaraans->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $nrkbString = $kendaraans->count() === 1 
            ? $kendaraans->first()->nrkb 
            : $kendaraans->pluck('nrkb')->implode(', ');

        $dataPdf = [
            'kendaraans' => $kendaraans,
            'nomor_surat' => $request->nomor_surat,
            'nama_pembuat' => $request->nama_pembuat,
            'tempat' => $request->tempat,
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_direktur' => $request->nama_direktur,
            'pangkat_direktur' => $request->pangkat_direktur,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.sp_polda2bapendaNjr', $dataPdf)->setPaper('a4', 'portrait');
        
        if ($request->has('preview')) {
            $previewDir = 'sp/preview';
            $prefix = Auth::id() . '_sp_polda_';
            // Delete old preview files matching this pattern to optimize storage space
            $existingFiles = Storage::disk('public')->files($previewDir);
            foreach ($existingFiles as $file) {
                if (str_starts_with(basename($file), $prefix)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
        } else {
            $filename = 'Surat Pengajuan - Polda kepada Bapenda dan Jasa Raharja No Pol ' . $nrkbString . '.pdf';
            $storagePath = 'sp/' . \Illuminate\Support\Str::uuid() . '_' . $filename;
        }
        Storage::disk('public')->put($storagePath, $pdf->output());
        $pdfUrlAbsolute = asset('storage/' . $storagePath);
        $localPdfPath = Storage::disk('public')->path($storagePath);

        if (!$request->has('preview')) {
            // Dispatch WA notification
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan:    $pengajuan,
                        kendaraan:    $kendaraans->first(),
                        skType:       'polda',
                        pdfUrl:       $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone:      $wpUser->no_hp,
                        wpName:       $wpUser->name,
                        nrkb:         $nrkbString,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SP Polda): ' . $e->getMessage());
                }
            }
        }

        return [
            'pdf_url' => $pdfUrlAbsolute,
            'local_pdf_path' => $localPdfPath,
        ];
    }

    /**
     * Generate PDF SP Penghapusan Regident (Freysia)
     */
    public function generateSPPenghapusanRegident(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'nomor_surat' => 'required',
            'sifat' => 'required|string',
            'lampiran' => 'required|string',
            'hal' => 'required|string',
            'provinsi' => 'required|string',
            'nama_penandatangan' => 'required|string',
            'jabatan' => 'required|string',
            'nip' => 'required|string',
        ]);

        $kendaraans = $pengajuan->kendaraans()->with('pemilik')->get();

        if ($kendaraans->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $nrkbString = $kendaraans->count() === 1 
            ? $kendaraans->first()->nrkb 
            : $kendaraans->pluck('nrkb')->implode(', ');

        $dataPdf = [
            'kendaraans' => $kendaraans,
            // Dari Form Input
            'nomor_surat' => strtoupper($request->nomor_surat),
            'sifat' => strtoupper($request->sifat),
            'lampiran' => strtoupper($request->lampiran),
            'hal' => strtoupper($request->hal),
            'provinsi' => strtoupper($request->provinsi),
            'nama_penandatangan' => strtoupper($request->nama_penandatangan),
            'jabatan' => strtoupper($request->jabatan),
            'nip' => strtoupper($request->nip),
            'tanggal_keluar' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.sp_balasan_bapenda', $dataPdf)->setPaper('a4', 'portrait');

        if ($request->has('preview')) {
            $previewDir = 'sp/preview';
            $prefix = Auth::id() . '_sp_balasan_';
            // Delete old preview files matching this pattern to optimize storage space
            $existingFiles = Storage::disk('public')->files($previewDir);
            foreach ($existingFiles as $file) {
                if (str_starts_with(basename($file), $prefix)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
        } else {
            $filename = 'Balasan Bapenda - Surat Penghapusan Regident No Pol ' . $nrkbString . '.pdf';
            $storagePath = 'sp/' . \Illuminate\Support\Str::uuid() . '_' . $filename;
        }

        Storage::disk('public')->put($storagePath, $pdf->output());
        $pdfUrlAbsolute = asset('storage/' . $storagePath);
        $localPdfPath = Storage::disk('public')->path($storagePath);

        if (!$request->has('preview')) {
            // Dispatch WA notification
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan:    $pengajuan,
                        kendaraan:    $kendaraans->first(),
                        skType:       'sp_penghapusan_regident',
                        pdfUrl:       $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone:      $wpUser->no_hp,
                        wpName:       $wpUser->name,
                        nrkb:         $nrkbString,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SP Balasan Bapenda): ' . $e->getMessage());
                }
            }
        }

        return [
            'pdf_url' => $pdfUrlAbsolute,
            'local_pdf_path' => $localPdfPath,
        ];
    }

    /**
     * Generate PDF SP Balasan Jasa Raharja (SWDKLLJ)
     */
    public function generateSPBalasanJR(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'nomor_surat' => 'required',
            'nomor_surat_regident' => 'required|string',
            'nomor_surat_bapenda' => 'required|string',
            'tempat_surat' => 'required|string',
            'tanggal_surat' => 'required|string',
            'nama_penandatangan' => 'required|string',
            'jabatan_penandatangan' => 'required|string',
        ]);

        $kendaraans = $pengajuan->kendaraans()->with('pemilik')->get();
        $firstKendaraan = $kendaraans->first();

        if ($kendaraans->isEmpty()) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $nrkbString = $kendaraans->count() === 1 
            ? $kendaraans->first()->nrkb 
            : $kendaraans->pluck('nrkb')->implode(', ');

        $dataPdf = [
            'kendaraans' => $kendaraans,
            'nomor_surat' => strtoupper($request->nomor_surat),
            'nomor_surat_regident' => strtoupper($request->nomor_surat_regident),
            'nomor_surat_bapenda' => strtoupper($request->nomor_surat_bapenda),
            'tempat_surat' => strtoupper($request->tempat_surat),
            'tanggal_surat' => $request->tanggal_surat,
            'nama_penandatangan' => strtoupper($request->nama_penandatangan),
            'jabatan_penandatangan' => strtoupper($request->jabatan_penandatangan),
            'data' => (object)[
                'nrkb' => strtoupper($firstKendaraan->nrkb ?? '-'),
                'nama' => optional($firstKendaraan->pemilik)->nama_pemilik ?? '-',
                'alamat' => optional($firstKendaraan->pemilik)->alamat_pemilik ?? '-',
                'merk_type' => ($firstKendaraan->merk_kendaraan ?? '-') . '/' . ($firstKendaraan->tipe_kendaraan ?? '-'),
                'no_rangka_mesin' => strtoupper(($firstKendaraan->nomor_rangka ?? '-') . '/' . ($firstKendaraan->nomor_mesin ?? '-')),
                'jenis_model' => strtoupper(($firstKendaraan->jenis_kendaraan ?? '-') . '/' . ($firstKendaraan->model_kendaraan ?? '-')),
                'tahun' => $firstKendaraan->tahun_pembuatan ?? '-',
            ],
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.sp_balasan_jr', $dataPdf)->setPaper('a4', 'portrait');

        if ($request->has('preview')) {
            $previewDir = 'sp/preview';
            $prefix = Auth::id() . '_sp_balasan_jr_';
            // Delete old preview files matching this pattern to optimize storage space
            $existingFiles = Storage::disk('public')->files($previewDir);
            foreach ($existingFiles as $file) {
                if (str_starts_with(basename($file), $prefix)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $storagePath = $previewDir . '/' . $prefix . time() . '.pdf';
        } else {
            $filename = 'Balasan JR - Surat Pembebasan SW No Pol ' . $nrkbString . '.pdf';
            $storagePath = 'sp/' . \Illuminate\Support\Str::uuid() . '_' . $filename;
        }

        Storage::disk('public')->put($storagePath, $pdf->output());
        $pdfUrlAbsolute = asset('storage/' . $storagePath);
        $localPdfPath = Storage::disk('public')->path($storagePath);

        if (!$request->has('preview')) {
            // Dispatch WA notification
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan:    $pengajuan,
                        kendaraan:    $kendaraans->first(),
                        skType:       'sp_balasan_jr',
                        pdfUrl:       $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone:      $wpUser->no_hp,
                        wpName:       $wpUser->name,
                        nrkb:         $nrkbString,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SP Balasan JR): ' . $e->getMessage());
                }
            }
        }

        return [
            'pdf_url' => $pdfUrlAbsolute,
            'local_pdf_path' => $localPdfPath,
        ];
    }


    public function ajukan(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        $user = Auth::user();
        $this->authorizeBranch($pengajuan);

        $kendaraans = $pengajuan->kendaraans;
        if ($kendaraans->isEmpty()) {
            return response()->json(['error' => 'Data kendaraan tidak ditemukan pada pengajuan ini.'], 404);
        }

        $unitKerja = $this->normalizeUnitKerja($user->unit_kerja);
        $data = [];
        if ($unitKerja == "Samsat") {
            // Dummy default data
            $data = $this->generateSPPolda($request, $pengajuan);
        } elseif ($unitKerja == 'Polda') {
            $data = $this->generateSPPolda2BapendaNJR($request, $pengajuan);
        } else {
            return response()->json(['error' => 'Aksi tidak valid untuk unit kerja saat ini.'], 403);
        }
        if ($request->has('preview')) {
            return response()->json([
                'message' => 'Preview Surat Pengajuan',
                'data' => [
                    'pdf_url' => $data['pdf_url'] ?? null,
                ]
            ]);
        }
        // Final Submission Flow
        $baseLogTime = now();
        // Determine draft mode: Samsat (default) = no draft, Polda (non-default) = draft
        $isDraft = ($unitKerja !== 'Samsat');
        if ($unitKerja == 'Polda') {
            $sp = SuratPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'nomor_sp' => 'SP-' . strtoupper(uniqid()),
                'tanggal_surat' => now(),
                'persetujuan_unit_kerja' => $this->persetujuanDefault,
                'pdf_url' => $data['pdf_url'] ?? null,
                'local_pdf_path' => $data['local_pdf_path'] ?? null,
                'created_at' => $baseLogTime,
                'updated_at' => $baseLogTime,
            ]);

            foreach ($kendaraans as $k) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    'SP Polda berhasil diterbitkan',
                    'Nomor Surat: ' . $request->nomor_surat,
                    $data['local_pdf_path'] ?? null,
                    $sp->id
                );
                if ($isDraft) {
                    $log->update(['sp_status' => 'draft']);
                }
            }
        } else {
            $sp = SuratPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'nomor_sp' => 'SP-' . strtoupper(uniqid()),
                'tanggal_surat' => now(),
                'persetujuan_unit_kerja' => $this->persetujuanPolda,
                'pdf_url' => $data['pdf_url'] ?? null,
                'local_pdf_path' => $data['local_pdf_path'] ?? null,
                'created_at' => $baseLogTime,
                'updated_at' => $baseLogTime,
            ]);

            foreach ($kendaraans as $k) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    'Surat Pengajuan berhasil dibuat',
                    'Diajukan ke Polda',
                    $data['local_pdf_path'] ?? null,
                    $sp->id
                );
                // Samsat default: no draft (langsung terbit)
            }
        }

        $successMsg = $isDraft
            ? 'Surat Pengajuan berhasil disimpan sebagai draft.'
            : 'Surat Pengajuan berhasil diajukan.';

        return isset($data['redirect']) ? $data['redirect'] : redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', $successMsg);
    }

    public function tolak(Request $request, SuratPengajuan $surat)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($surat->pengajuan_id);
        
        if (!$request->user()->hasAnyPermission(['create_pdf_pengajuan', 'create_pdf_pengajuan_bapenda_jr', 'create_pdf_balasan_polda'])) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Unauthorized');
            //  response()->json(['message' => 'Unauthorized'], 403);
        }

        $currentSp = $pengajuan->getCurrentSuratPengajuan();
        if (!$currentSp || $currentSp->id !== $surat->id) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Aksi hanya dapat dilakukan pada Surat Pengajuan aktif.');
            //  response()->json(['message' => 'Aksi hanya dapat dilakukan pada Surat Pengajuan aktif.'], 400);
        }

        if ($surat->isFullyApprovedByAll()) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Surat Pengajuan sudah disetujui semua instansi.');
            //  response()->json(['message' => 'Surat Pengajuan sudah disetujui semua instansi.'], 400);
        }
        $instansiUser = $this->normalizeUnitKerja(Auth::user()->unit_kerja);

        // Ambil data array saat ini
        $persetujuan = $surat->persetujuan_unit_kerja ?? [];

        // Cari instansi yang sesuai dan ubah statusnya
        foreach ($persetujuan as $item) {
            if (strcasecmp($item['instansi'] ?? '', $instansiUser) === 0 && ($item['status'] ?? null) == 'pending') {
                if ($surat->pengajuan->kendaraans()->where('status', 'pengajuan')->count()) {
                    $surat->pengajuan->kendaraans()->update(['status' => 'ditolak']);
                }
                $item['status'] = 'rejected';
                $item['user_id'] = Auth::id();
                $item['updated_at'] = now();
            }
        }

        // Simpan kembali
        $surat->persetujuan_unit_kerja = $persetujuan;
        $surat->save();

        $this->logSuratAction(
            $pengajuan,
            'Surat Pengajuan ditolak oleh ' . Auth::user()->unit_kerja,
            'Nomor SP: ' . $surat->nomor_sp,
            $surat->id
        );

        return redirect()->route('admin.pengajuan.show', $surat->pengajuan_id)->with('success', 'Status berhasil diperbarui');
    }

    public function terima(Request $request, SuratPengajuan $surat)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($surat->pengajuan_id);
        if (!$request->user()->hasAnyPermission(['create_pdf_pengajuan', 'create_pdf_pengajuan_bapenda_jr', 'create_pdf_balasan_polda'])) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('admin.pengajuan.show', $pengajuan)->with('error', 'Unauthorized');
        }

        $currentSp = $pengajuan->getCurrentSuratPengajuan();
        if (!$currentSp || $currentSp->id !== $surat->id) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'Aksi hanya dapat dilakukan pada Surat Pengajuan aktif.'], 400);
            }
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Aksi hanya dapat dilakukan pada Surat Pengajuan aktif.');
        }

        if ($surat->isFullyApprovedByAll()) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'Surat Pengajuan sudah disetujui semua instansi.'], 400);
            }
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Surat Pengajuan sudah disetujui semua instansi.');
        }

        $instansiUser = $this->normalizeUnitKerja(Auth::user()->unit_kerja); // Misal: Bapenda / Jasa Raharja
        $default = false;
        // ── KELOMPOK AJAX / PREVIEW FLOW (Untuk submit dari dynamic modal) ──
        if ($request->ajax() || $request->expectsJson() || $request->has('preview') || $request->hasHeader('X-CSRF-TOKEN') || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            if ($instansiUser == "Bapenda") {
                $data = $this->generateSPPenghapusanRegident($request, $pengajuan);
            } else if ($instansiUser == "Jasa Raharja") {
                $data = $this->generateSPBalasanJR($request, $pengajuan);
            } else {
                $data = $this->generateSPDefault($request, $pengajuan);
                $default = true;
            }

            if ($request->has('preview')) {
                return response()->json([
                    'message' => 'Preview Surat Pengajuan',
                    'data' => [
                        'pdf_url' => $data['pdf_url'] ?? null,
                    ]
                ]);
            }

            // Ambil data array saat ini
            $persetujuan = $surat->persetujuan_unit_kerja ?? [];
            $baseLogTime = now();

            // Cari instansi yang sesuai dan ubah statusnya
            foreach ($persetujuan as &$item) {
                if (strcasecmp($item['instansi'] ?? '', $instansiUser) === 0 && ($item['status'] ?? null) == 'pending') {
                    $item['status'] = 'approved';
                    $item['user_id'] = Auth::id();
                    $item['updated_at'] = $baseLogTime;
                    $item['pdf_url'] = $data['pdf_url'] ?? null;
                    $item['local_pdf_path'] = $data['local_pdf_path'] ?? null;
                }
            }

            // Update persetujuan status pada SP yang aktif (SP Polda/asal)
            // JANGAN timpa pdf_url — itu milik SP pengajuan asli (dari Polda)
            // Simpan PDF balasan di kolom terpisah pdf_balasan_url
            $surat->pdf_balasan_url        = $data['pdf_url'] ?? null;
            $surat->local_pdf_balasan_path = $data['local_pdf_path'] ?? null;
            $surat->persetujuan_unit_kerja = $persetujuan;
            $surat->save();

            foreach ($pengajuan->kendaraans as $k) {
                $log = $this->logSuratActionByKendaraanId(
                    $pengajuan,
                    $k->id,
                    $instansiUser == "Bapenda" ? 'SP Balasan Penghapusan Regident berhasil diterbitkan' : ($instansiUser == "Jasa Raharja" ? 'SP Balasan Jasa Raharja berhasil diterbitkan' : 'SP Balasan berhasil diterbitkan'),
                    'Nomor Surat: ' . $request->nomor_surat,
                    $data['local_pdf_path'] ?? null,
                    $surat->id
                );
                // Non-default SP respond: always draft
                $log->update(['sp_status' => $default ? 'terbit' : 'draft']);
            }

            // Jika semua instansi sudah approved, ubah status kendaraan ke diproses
            if ($surat->fresh()->isFullyApprovedByAll()) {
                foreach ($pengajuan->kendaraans as $k) {
                    $k->update(['status' => 'diproses']);
                    $logDiterima = $this->logSuratActionByKendaraanId(
                        $pengajuan,
                        $k->id,
                        'Surat Pengajuan Diterima oleh Semua Instansi',
                        'Status kendaraan diperbarui ke Diproses.',
                        null,
                        $surat->id
                    );
                    $logDiterima->created_at = $baseLogTime->copy()->addSecond();
                    $logDiterima->updated_at = $baseLogTime->copy()->addSecond();
                    $logDiterima->save();
                }
            }

            return redirect()->route('admin.pengajuan.show', $pengajuan->id)
                ->with('success', 'Surat Pengajuan berhasil disimpan sebagai draft.');
        }

        // ── KELOMPOK REDIRECT FLOW (NON-AJAX / FALLBACK) ──
        $persetujuan = $surat->persetujuan_unit_kerja ?? [];

        // Cari instansi yang sesuai dan ubah statusnya
        foreach ($persetujuan as &$item) {
            if (strcasecmp($item['instansi'] ?? '', $instansiUser) === 0 && ($item['status'] ?? null) == 'pending') {
                $item['status'] = 'approved';
                $item['user_id'] = Auth::id();
                $item['updated_at'] = now();
            }
        }

        // Simpan kembali
        $surat->persetujuan_unit_kerja = $persetujuan;
        $surat->save();

        $this->logSuratAction(
            $pengajuan,
            'Surat Pengajuan disetujui oleh ' . Auth::user()->unit_kerja,
            'Nomor SP: ' . $surat->nomor_sp,
            $surat->id
        );

        if ($surat->fresh()->isFullyApprovedByAll() && $surat->pengajuan->kendaraans()->where('status', 'pengajuan')->count()) {
            $surat->pengajuan->kendaraans()->update(['status' => 'diproses']);
        }

        return redirect()->route('admin.pengajuan.show', $surat->pengajuan_id)->with('success', 'Status berhasil diperbarui');
    }

    public function hasPersetujuan(Request $request, $id)
    {
        $sp = SuratPengajuan::findOrFail($id);
        $unitKerja = $this->normalizeUnitKerja(Auth::user()->unit_kerja);

        foreach (($sp->persetujuan_unit_kerja ?? []) as $item) {
            if (strcasecmp($unitKerja, $item['instansi'] ?? '') === 0) {
                return true;
            }
        }
        return false; // Jika instansi tidak ditemukan
    }

    private function hasPendingSuratByTarget(Pengajuan $pengajuan, string $target): bool
    {
        $currentSp = $pengajuan->getCurrentSuratPengajuan();
        if (!$currentSp || $currentSp->isFullyApproved() || $currentSp->isRejected()) {
            return false;
        }

        $targetInstansi = match ($target) {
            self::TARGET_POLDA => ['Polda'],
            self::TARGET_BAPENDA_JR => ['Bapenda', 'Jasa Raharja'],
            default => [],
        };

        return collect($currentSp->persetujuan_unit_kerja ?? [])->contains(function ($item) use ($targetInstansi) {
            $instansi = $item['instansi'] ?? '';
            $status = $item['status'] ?? null;

            return in_array($instansi, $targetInstansi, true) && $status === 'pending';
        });
    }

    private function logSuratAction(Pengajuan $pengajuan, string $actionLabel, string $notes, $file = null): void
    {
        foreach ($pengajuan->kendaraans as $kendaraan) {
            $logCurrent = KendaraanLog::create([
                'kendaraan_id' => $kendaraan->id,
                'user_id' => Auth::id(),
                'aksi' => $actionLabel,
                'status_baru' => $kendaraan->status,
                'tipe' => 'system',
                'catatan' => $notes,
                'sp_id' => (isset($file) && is_int($file)) ? $file : null, // Mengakali $file = $sp_id di parameter jika bukan file
            ]);
            if ($file && !is_int($file)) {
                $logCurrent->addMedia($file)->toMediaCollection("lampiran_log");
            }
        }
    }

    public function uploadFileToMedia(Request $request)
    {
        $request->validate([
            'sp_id' => 'required|integer',
            'log_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif,docx|max:10240',
        ]);

        $suratpengajuan = SuratPengajuan::findOrFail($request->sp_id);
        $log = KendaraanLog::findOrFail($request->log_id);
        
        if ($suratpengajuan->pdf_url) {
            return redirect()->route('admin.pengajuan.show', $suratpengajuan->pengajuan_id)
                ->with('error', 'File PDF sudah ada di media library.');
        }

        $filename = $request->file->getClientOriginalName();
        $storagePath    = 'sp/' . \Illuminate\Support\Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $request->file('file')->get());
        $localPdfPath = Storage::disk('public')->path($storagePath);
        $pdfUrlAbsolute = asset('storage/' . $storagePath);
        
        $suratpengajuan->update([
            'local_pdf_path' => $pdfUrlAbsolute,
            'pdf_url' => $pdfUrlAbsolute,
        ]);
        
        $log->addMedia($localPdfPath)->preservingOriginal()->toMediaCollection("lampiran_log");

        return redirect()->route('admin.pengajuan.show', $suratpengajuan->pengajuan_id)
            ->with('success', 'File PDF berhasil diupload.');
    }

    private function authorizeBranch(Pengajuan $pengajuan): void
    {
        $user = Auth::user();
        $isBranchScoped = $user->can('scoped_to_own_branch') && !$user->hasRole('superadmin');

        if ($isBranchScoped && $user->cabang_id && $pengajuan->cabang_id !== $user->cabang_id) {
            abort(403, 'Akses ditolak: cabang berbeda.');
        }
    }

    private function logSuratActionByKendaraanId(Pengajuan $pengajuan, string $kendaraan_id, string $actionLabel, string $notes, $file = null, int $sp_id = null): KendaraanLog
    {
        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan_id,
            'user_id' => Auth::id(),
            'aksi' => $actionLabel,
            'status_baru' => $pengajuan->kendaraans->find($kendaraan_id)->status,
            'tipe' => 'system',
            'catatan' => $notes,
            'sp_id' => $sp_id,
        ]);
        if ($file) {
            $log->addMedia($file)->preservingOriginal()->toMediaCollection("lampiran_log");
        }
        return $log;
    }

}
