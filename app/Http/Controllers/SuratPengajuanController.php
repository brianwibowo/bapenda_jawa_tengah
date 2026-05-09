<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\KendaraanLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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
                'view' => 'pdf.view_sp',
                'prefix' => 'SP-',
                'permission' => 'view_dokumen_surat_pengajuan',
            ],
            'form' => [
                'view' => 'form.create_sp',
                'permission' => self::determinePermission($user, $progress),
                'footer' => self::buildFooter($user, $pengajuan, $lastSp, $progress)
            ]
        ];

        $config = $registries[$type] ?? abort(404);
        $config['filename'] = $data ? ($config['prefix'] ?? '') . $data->nomor_sp : 'DOKUMEN_SP';
        
        return $config;
    }

    /**
     * Menentukan Permission secara dinamis
     */
    private static function determinePermission($user, $progress)
    {
        if ($user->unit_kerja == 'Polda') return ['create_pdf_pengajuan', 'create_pdf_balasan_samsat', 'create_pdf_pengajuan_bapenda_jr'];
        else if ($user->unit_kerja == 'Bapenda' || $user->unit_kerja == 'Jasa Raharja') return ['create_pdf_balasan_polda'];
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

        // Filter berdasarkan unit kerja dan role
        if ($user->hasRole('samsat')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Samsat');
            });
        } elseif ($user->hasRole('polda')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Polda');
            });
        } elseif ($user->hasRole('bapenda')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Bapenda');
            });
        } elseif ($user->hasRole('jasa_raharja')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Jasa Raharja');
            });
        }

        $suratPengajuans = $query->get();
        return view('admin.surat.view_pengajuan', compact('suratPengajuans'));
    }

    public function ajukan(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        $user = Auth::user();
        $progress = $pengajuan->getTotalSurat();

        if ($user->unit_kerja == 'Samsat') {
            return $this->ajukanPolda($request, $id);
        } else if ($progress == 2 && $user->unit_kerja == 'Polda') {    
            return $this->ajukanBapendaJr($request, $id);
        } else {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Aksi tidak valid untuk status pengajuan saat ini.');
            // response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function ajukanPolda(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        
        // Validasi: Pastikan pengajuan memiliki kendaraan dengan status "Diajukan"
        if (!$pengajuan->kendaraans->where('status', 'pengajuan')->count()) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Pengajuan tidak memiliki kendaraan dengan status "Diajukan".'); 
            // response()->json(['message' => 'Pengajuan tidak memiliki kendaraan dengan status "Diajukan".'], 400);
        }

        if ($this->hasPendingSuratByTarget($pengajuan, self::TARGET_POLDA)) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
                ->with('error', 'Masih ada Surat Pengajuan ke Polda yang pending. Selesaikan dulu sebelum kirim ulang.');
        }

        // Update status kendaraan menjadi "Diajukan ke Polda"
        foreach ($pengajuan->kendaraans as $kendaraan) {
            if (in_array($kendaraan->status, ['pengajuan', 'diproses'])) {
                // Simpan log perubahan status
                KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Surat Pengajuan',
                    'status_baru' => $kendaraan->status,
                    'tipe' => 'system',
                    'catatan' => 'Kendaraan diajukan ke Polda oleh ' . Auth::user()->name,
                ]);
            }
        }

        // Simpan data Surat Pengajuan
        $sp = SuratPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_sp' => 'SP-' . strtoupper(uniqid()),
            'tanggal_surat' => now(),
            'persetujuan_unit_kerja' => $this->persetujuanPolda
        ]);

        $this->logSuratAction($pengajuan, 'Surat Pengajuan ke Polda dibuat', 'Nomor SP: ' . $sp->nomor_sp);


        return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil diajukan ke Polda. Surat Pengajuan telah dibuat.');
    }

    public function ajukanBapendaJr(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['kendaraans', 'suratPengajuan'])->findOrFail($id);
        
        // Validasi: Pastikan pengajuan memiliki kendaraan dengan status "Diproses" ( sudah diverifikasi polda )
        if (!$pengajuan->kendaraans->where('status', 'diproses')->count()) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('error', 'Pengajuan tidak memiliki kendaraan dengan status "Diproses".');
            // response()->json(['message' => 'Pengajuan tidak memiliki kendaraan dengan status "Diproses".'], 400);
        }

        if ($this->hasPendingSuratByTarget($pengajuan, self::TARGET_BAPENDA_JR)) {
            return redirect()->route('admin.pengajuan.show', $pengajuan)
                ->with('error', 'Masih ada Surat Pengajuan ke Bapenda/Jasa Raharja yang pending. Selesaikan dulu sebelum kirim ulang.');
        }

        // Update status kendaraan menjadi "Diajukan ke Bapenda/JR"
        foreach ($pengajuan->kendaraans as $kendaraan) {
            if (in_array($kendaraan->status, ['pengajuan', 'diproses'])) {
                // Simpan log perubahan status
                KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Surat Pengajuan',
                    'status_baru' => $kendaraan->status,
                    'tipe' => 'system',
                    'catatan' => 'Kendaraan diajukan ke Bapenda/JR oleh ' . Auth::user()->name,
                ]);
            }
        }

        // Simpan data Surat Pengajuan
        $sp = SuratPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_sp' => 'SP-' . strtoupper(uniqid()),
            'tanggal_surat' => now(),
            'persetujuan_unit_kerja' => $this->persetujuanDefault
        ]);

        $this->logSuratAction($pengajuan, 'Surat Pengajuan ke Bapenda/Jasa Raharja dibuat', 'Nomor SP: ' . $sp->nomor_sp);

        return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil diajukan ke Bapenda/Jasa Raharja. Surat Pengajuan telah dibuat.');
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
        $instansiUser = Auth::user()->unit_kerja;

        // Ambil data array saat ini
        $persetujuan = $surat->persetujuan_unit_kerja;

        // Cari instansi yang sesuai dan ubah statusnya
        foreach ($persetujuan as &$item) {
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
            'Nomor SP: ' . $surat->nomor_sp
        );

        return redirect()->route('admin.pengajuan.show', $surat->pengajuan_id)->with('success', 'Status berhasil diperbarui');
    }

    public function terima(Request $request, SuratPengajuan $surat)
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
        $instansiUser = Auth::user()->unit_kerja; // Misal: bapenda

        // Ambil data array saat ini
        $persetujuan = $surat->persetujuan_unit_kerja;

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
            'Nomor SP: ' . $surat->nomor_sp
        );

        if ($surat->fresh()->isFullyApprovedByAll() && $surat->pengajuan->kendaraans()->where('status', 'pengajuan')->count()) {
            $surat->pengajuan->kendaraans()->update(['status' => 'diproses']);
        }

        return redirect()->route('admin.pengajuan.show', $surat->pengajuan_id)->with('success', 'Status berhasil diperbarui');
    }

    public function hasPersetujuan(Request $request, $id)
    {
        $sp = SuratPengajuan::findOrFail($id);

        foreach ($sp->persetujuan_unit_kerja as $item) {
            if (Auth::user()->unit_kerja == $item['instansi']) {
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
            ]);
            if ($file) {
                $logCurrent->addMedia($file)->toMediaCollection("lampiran_log");
            }
        }
    }

}
