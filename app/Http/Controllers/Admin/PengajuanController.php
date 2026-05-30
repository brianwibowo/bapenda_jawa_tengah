<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Cabang;
use App\Models\Pengajuan;
use App\Models\Kendaraan;
use App\Models\KendaraanLog;
use App\Models\SuratKeputusan;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{

    private function normalizeUnitKerja(?string $unitKerja): string
    {
        return match (strtolower(trim((string) $unitKerja))) {
            'jr', 'jasa raharja', 'jasa_raharja' => 'JR',
            'bapenda' => 'Bapenda',
            'polda' => 'Polda',
            'samsat' => 'Samsat',
            default => trim((string) $unitKerja),
        };
    }
    /**
     * Level 1: Menampilkan daftar BUNDEL pengajuan
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $selectedCabang = $request->query('cabang_id');
        $user = Auth::user();
        $isBranchScoped = $user->can('scoped_to_own_branch');
        $isSamsat = $isBranchScoped;

        $query = Pengajuan::with(['user', 'kendaraans:id,pengajuan_id,status', 'cabang'])
            ->withCount('kendaraans')
            ->latest('updated_at');

        if ($isBranchScoped) {
            // User dengan permission ini hanya bisa lihat pengajuan dari cabangnya sendiri.
            if (!$user->cabang_id) {
                abort(403, 'Akun Anda belum ditetapkan ke cabang/wilayah.');
            }

            $query->where('cabang_id', $user->cabang_id);
        } elseif ($selectedCabang) {
            $query->where('cabang_id', $selectedCabang);
        }

        if ($status) {
            $query->whereHas('kendaraans', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('kendaraans', function ($sq) use ($search) {
                        $sq->where('nrkb', 'like', "%{$search}%")
                            ->orWhereHas('pemilik', function ($ownerQuery) use ($search) {
                                $ownerQuery->where('nama_pemilik', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $branches = Cabang::orderBy('wilayah', 'asc')->get();
        $pengajuans = $query->paginate(10)->withQueryString();
        // Hitung total surat tiap pengajuan
        $progress = [];
        foreach ($pengajuans as $pengajuan) {
            $progress[$pengajuan->id] = $pengajuan->getTotalSurat();
        }

        return view('admin.pengajuan.index', compact('pengajuans', 'branches', 'selectedCabang', 'isSamsat', 'progress'));
    }

    /**
     * Level 2: Menampilkan dasbor BUNDEL (tabel kendaraan interaktif)
     */
    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $pengajuan->load([
            'kendaraans',
            'kendaraans.media',
            'kendaraans.logs.user', // Ambil log per kendaraan
            'kendaraans.logs.media', // Ambil lampiran log per kendaraan
            'suratPengajuan',
            'suratKeputusans'
        ]);

        $progress = $pengajuan->getTotalSurat();
        $suratkeputusan = $pengajuan->suratKeputusans;
        $suratpengajuan = $pengajuan->getSliceSuratPengajuanLastRejected();
        $user = Auth::user();

        // Logic Modular Condition
        $permissionSurat = [
            'canAjukanSP' => false,
            'canAjukanSK' => false,
            'canRespondSP' => false
        ];

        $lastSp = $pengajuan->getCurrentSuratPengajuan();

        // Jika progres masih awal (0) dan belum ada SP
        if ($progress == 0 && $suratpengajuan->isEmpty() && $user->unit_kerja == 'Samsat') {
            $permissionSurat['canAjukanSP'] = true;
        }
        // Bapenda/JR merespon SP dari Polda
        elseif ($lastSp && !$lastSp->isFullyApproved() && !$lastSp->isRejected()) {
            // Cek apakah user ini termasuk dalam daftar tujuan yang belum approve
            $statusInstansi = $lastSp->persetujuan_unit_kerja
                ? collect($lastSp->persetujuan_unit_kerja)->firstWhere('instansi', $user->unit_kerja)
                : null;
            if ($statusInstansi && $statusInstansi['status'] == 'pending') {
                $permissionSurat['canRespondSP'] = true;
            }
        }
        // Polda ke Bapenda (Jika SP pertama sudah approved)
        elseif ($progress == 2 && $user->unit_kerja == 'Polda') {
            $permissionSurat['canAjukanSP'] = true;
        }
        // Jika sudah fully approved tapi belum ada SK
        elseif ($progress >= 6 && $progress < 9 && ($user->unit_kerja == "Polda" || ($pengajuan->getStep() == 3)) && $pengajuan->isFullyApprovedByAll() && $suratkeputusan->where('unit_kerja', $user->unit_kerja)->isEmpty()) {
            $permissionSurat['canAjukanSK'] = true;
        }

        $isUpload = [
            "sk" => [],
            "sp" => []
        ];

        
        foreach($pengajuan->kendaraans->flatMap(fn($k) => $k->logs)->values() as $log){
            $user_log = User::find($log->user_id);
            if ((!$log->sk_id && !$log->sp_id) || ($user_log && !($user_log->unit_kerja == $user->unit_kerja))) continue;

            if ($log->sk_id) {
                $sk = SuratKeputusan::findOrFail($log->sk_id);
                if (!$sk->local_pdf_path) {
                    $isUpload['sk'][$log->sk_id] = true;
                }
            }
            if ($log->sp_id) {
                $sp = SuratPengajuan::findOrFail($log->sp_id);
                if (!$sp->local_pdf_path) {
                    $isUpload['sp'][$log->sp_id] = true;
                    }
                }
                
            }
                    
            foreach($pengajuan->kendaraans as $kendaraan){
                // Existing current Unit Kerja, untuk permissionSurat
                $exisitingSkIds = $kendaraan->suratKeputusans()
                ->where('unit_kerja', $this->normalizeUnitKerja(Auth::user()->unit_kerja))
                ->first();

                if (!$exisitingSkIds && $progress >= 6) {
                    $permissionSurat['canAjukanSK'] = true;
                }
            }


            return view('admin.pengajuan.show', compact(
            'pengajuan',
            'suratkeputusan',
            'suratpengajuan',
            'progress',
            'permissionSurat',
            'isUpload'
        ));
    }

    /**
     * METHOD DIPERBARUI: (Aksi dari tombol "Simpan Semua Perubahan")
     * Sekarang juga menangani UPLOAD LAMPIRAN
     */
    public function batchUpdateKendaraanStatus(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        // 1. Validasi input (termasuk 'lampiran')
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:pengajuan,diproses,selesai,ditolak',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string',
            'lampiran' => 'nullable|array', // Validasi array lampiran
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif,docx|max:10240', // Validasi tiap file
        ]);

        $adminUser = Auth::user();
        $statuses = $request->input('status', []);
        $catatans = $request->input('catatan', []);
        $lampirans = $request->file('lampiran', []); // Ambil file lampiran

        $logCount = 0;

        // Fetch all related kendaraans upfront to prevent N+1 Queries
        $kendaraans = Kendaraan::whereIn('id', array_keys($statuses), 'and', false)
            ->where('pengajuan_id', $pengajuan->id)
            ->get()
            ->keyBy('id');

        // 2. Loop setiap status yang dikirim dari form
        foreach ($statuses as $kendaraanId => $newStatus) {

            $kendaraan = $kendaraans->get($kendaraanId);
            if (!$kendaraan) {
                continue;
            }

            $oldStatus = $kendaraan->status;
            $catatan = $catatans[$kendaraanId] ?? null;
            $lampiranFile = $lampirans[$kendaraanId] ?? null;

            // Hanya update jika status berubah ATAU ada catatan baru ATAU ada lampiran baru
            if ($oldStatus !== $newStatus || !empty($catatan) || !empty($lampiranFile)) {

                // 3. Update status & catatan di record KENDARAAN
                $kendaraan->update([
                    'status' => $newStatus,
                    'catatan_admin' => $catatan
                ]);

                // 4. Buat deskripsi Aksi dinamis
                $aksiDeskripsi = '';
                switch ($newStatus) {
                    case 'diproses':
                        $aksiDeskripsi = 'Diproses oleh ' . $adminUser->unit_kerja;
                        break;
                    case 'selesai':
                        $aksiDeskripsi = 'Diselesaikan oleh ' . $adminUser->unit_kerja;
                        break;
                    case 'ditolak':
                        $aksiDeskripsi = 'Ditolak oleh ' . $adminUser->unit_kerja;
                        break;
                    case 'pengajuan':
                        $aksiDeskripsi = 'Dikembalikan ke status "Baru" oleh ' . $adminUser->unit_kerja;
                        break;
                }
                // Jika status tidak berubah tapi ada catatan/lampiran, buat aksi default
                if (empty($aksiDeskripsi)) {
                    $aksiDeskripsi = 'Catatan/Lampiran ditambahkan oleh ' . $adminUser->unit_kerja;
                }

                // 5. Buat LOG per KENDARAAN
                $log = KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => $adminUser->id,
                    'aksi' => $aksiDeskripsi,
                    'status_baru' => $newStatus,
                    'catatan' => $catatan,
                ]);

                // 6. Handle upload lampiran (jika ada) dan tempelkan ke LOG
                if ($lampiranFile) {
                    /** @var \App\Models\KendaraanLog $log */
                    // addMedia berasal dari trait InteractsWithMedia pada model KendaraanLog
                    $log->addMedia($lampiranFile)->toMediaCollection('lampiran_log');
                }

                $logCount++;
            }
        }

        return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('success', $logCount . ' data kendaraan berhasil diperbarui.');
    }


    /**
     * Menghapus BUNDEL pengajuan (Soft Delete).
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $pengajuan->delete();

        return redirect()->route('admin.pengajuan.index')
            ->with('success', 'Bundel pengajuan ' . $pengajuan->nomor_pengajuan . ' berhasil dihapus.');
    }

    /**
     * Menyimpan log/diskusi/revisi baru dari Admin untuk Kendaraan tertentu.
     */

    private function authorizeBranch(Pengajuan $pengajuan): void
    {
        $user = Auth::user();
        $isBranchScoped = $user->can('scoped_to_own_branch');

        if ($isBranchScoped && $user->cabang_id && $pengajuan->cabang_id !== $user->cabang_id) {
            abort(403, 'Akses ditolak: cabang berbeda.');
        }
    }
    public function storeLog(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $rules = [
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => 'required|in:komentar,revisi,catatan_admin,status_pengajuan,status_diproses,status_selesai,status_ditolak',
            'status_baru' => 'nullable|string',
            'catatan' => 'nullable|string|max:1000',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,heif,doc,docx|max:5120',
        ];

        // Validasi tambahan: jika tipe = revisi, wajib pilih bagian yang direvisi
        if ($request->tipe === 'revisi') {
            $rules['revisi_fields'] = 'required|array|min:1';
            $rules['revisi_fields.*'] = 'string|in:nama_pemilik,nik_pemilik,alamat_pemilik,telp_pemilik,email_pemilik,nrkb,merk_kendaraan,tipe_kendaraan,jenis_kendaraan,model_kendaraan,tahun_pembuatan,isi_silinder,nomor_rangka,nomor_mesin,warna_kendaraan,jenis_bahan_bakar,warna_tnkb,nomor_bpkb,surat_permohonan,surat_pernyataan,ktp,bpkb,tbpkp,cek_fisik,foto_ranmor,stnk';
        }

        $request->validate($rules);

        // Cek permission RBAC untuk revisi
        $adminUser = Auth::user();
        if ($request->tipe === 'revisi' && !$adminUser->can('request_revision')) {
            abort(403, 'Anda tidak memiliki izin untuk meminta revisi.');
        }

        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);

        // Pastikan kendaraan milik pengajuan ini
        if ($kendaraan->pengajuan_id !== $pengajuan->id) {
            abort(403, 'Kendaraan tidak valid.');
        }

        // Parse tipe: jika tipe berformat "status_xxx", extract status aslinya
        $rawTipe = $request->tipe;
        $resolvedTipe = $rawTipe;
        $resolvedStatusBaru = $request->status_baru ?? $kendaraan->status;

        if (str_starts_with($rawTipe, 'status_')) {
            $resolvedStatusBaru = str_replace('status_', '', $rawTipe);
            $resolvedTipe = 'catatan_admin'; // Normalkan tipe ke catatan_admin
        }

        if ($resolvedTipe === 'revisi') {
            $fieldLabels = $this->getRevisiFieldLabels($request->revisi_fields);
            $aksiText = "Admin {$adminUser->name} meminta revisi: " . implode(', ', $fieldLabels);
        } elseif ($resolvedTipe === 'komentar') {
            $aksiText = "Admin {$adminUser->name} menambahkan komentar";
        } else {
            $aksiText = "Admin {$adminUser->name} menambahkan catatan internal";
        }

        if ($resolvedStatusBaru && $resolvedStatusBaru !== $kendaraan->status) {
            $aksiText .= " (Terkait Status: " . ucfirst($resolvedStatusBaru) . ")";
        }

        // 1. Buat Log
        $logData = [
            'kendaraan_id' => $kendaraan->id,
            'user_id' => $adminUser->id,
            'aksi' => $aksiText,
            'tipe' => $resolvedTipe,
            'status_baru' => $resolvedStatusBaru,
            'catatan' => $request->catatan,
        ];

        // Simpan bagian revisi jika tipe = revisi
        if ($resolvedTipe === 'revisi' && $request->has('revisi_fields')) {
            $logData['revisi_fields'] = $request->revisi_fields;
        }

        $log = KendaraanLog::create($logData);

        // 2. Handle Upload Lampiran Diskusi/Revisi (Multi-file)
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                if ($file && $file->isValid()) {
                    $log->addMedia($file)->toMediaCollection('lampiran_log');
                }
            }
        }

        // 3. Auto-update status kendaraan ke 'diproses' jika user dengan permission
        //    auto_process_on_action melakukan aksi catatan_admin atau revisi
        //    dan kendaraan masih berstatus 'pengajuan'.
        $canAutoProcess = $adminUser->can('auto_process_on_action');

        if ($canAutoProcess
            && $kendaraan->status === 'pengajuan'
            && in_array($resolvedTipe, ['catatan_admin', 'revisi'])) {
            $kendaraan->update(['status' => 'diproses']);
        }

        return back()->with('success', 'Catatan admin berhasil disimpan ke log.');
    }

    /**
     * Helper: Konversi revisi_fields key ke label yang readable.
     */
    private function getRevisiFieldLabels(array $fields): array
    {
        $map = [
            // Identitas Pemilik
            'nama_pemilik' => 'Nama Pemilik',
            'nik_pemilik' => 'NIK Pemilik',
            'alamat_pemilik' => 'Alamat Pemilik',
            'telp_pemilik' => 'Telp Pemilik',
            'email_pemilik' => 'Email Pemilik',
            // Identitas Kendaraan
            'nrkb' => 'NRKB',
            'merk_kendaraan' => 'Merk',
            'tipe_kendaraan' => 'Tipe',
            'jenis_kendaraan' => 'Jenis',
            'model_kendaraan' => 'Model',
            'tahun_pembuatan' => 'Tahun Pembuatan',
            'isi_silinder' => 'Isi Silinder',
            'nomor_rangka' => 'No. Rangka',
            'nomor_mesin' => 'No. Mesin',
            'warna_kendaraan' => 'Warna Kendaraan',
            'jenis_bahan_bakar' => 'Bahan Bakar',
            'warna_tnkb' => 'Warna TNKB',
            'nomor_bpkb' => 'No. BPKB',
            // Dokumen
            'surat_permohonan' => 'Surat Permohonan',
            'surat_pernyataan' => 'Surat Pernyataan',
            'ktp' => 'KTP',
            'bpkb' => 'BPKB',
            'tbpkp' => 'TBPKP',
            'cek_fisik' => 'Cek Fisik',
            'foto_ranmor' => 'Foto Kendaraan',
            'stnk' => 'STNK',
        ];

        return array_map(fn($f) => $map[$f] ?? $f, $fields);
    }

    /**
     * Menampilkan halaman detail khusus dari suatu Log / Aksi (beserta lampiran full)
     */
    public function showLog(Pengajuan $pengajuan, int $logId)
    {
        $this->authorizeBranch($pengajuan);

        $log = \App\Models\KendaraanLog::with(['user', 'kendaraan', 'media'])->findOrFail($logId);

        // Pastikan log tersebut milik bundel pengajuan ini
        if ($log->kendaraan->pengajuan_id !== $pengajuan->id) {
            abort(404, 'Log tidak ditemukan dalam pengajuan ini.');
        }

        //Order by created_at desc untuk menampilkan log terbaru di atas
        $log->load([
            'media' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Ambil data surat terkait untuk ditampilkan di sidebar (jika ada)
        $progress = $pengajuan->getProgress();
        $suratkeputusan = $pengajuan->suratKeputusans;
        $suratpengajuan = $pengajuan->suratPengajuan;

        $admin = true;
        return view('pengajuan.log_show', compact('pengajuan', 'log', 'admin', 'suratkeputusan', 'suratpengajuan', 'progress'));
    }

    /**
     * Menampilkan halaman pilihan SK (Admin)
     */
    public function pilihSk(Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);
        $admin = true;
        return view('pengajuan.pilih_sk', compact('pengajuan', 'admin'));
    }

    /**
     * Generate PDF Surat Keterangan Penghapusan Regident
     */
    public function generateSkRegident(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_surat' => 'required',
            'nama_pembuat' => 'required',
            'tempat' => 'required',
            'tanggal_keluar' => 'required',
            'nama_direktur' => 'required',
            'pangkat_direktur' => 'required',
        ]);

        // Ambil data kendaraan berdasarkan pilihan dari form modal
        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // Gabungkan data dari database dengan inputan form
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
                'nama' => strtoupper(optional($kendaraan->pemilik)->nama_pemilik ?? '-'),
                'alamat' => strtoupper(optional($kendaraan->pemilik)->alamat_pemilik ?? '-'),
                'nik' => strtoupper(optional($kendaraan->pemilik)->nik_pemilik ?? '-'),
                'no_tlp' => strtoupper(optional($kendaraan->pemilik)->telp_pemilik ?? '-'),
                'email' => strtoupper(optional($kendaraan->pemilik)->email_pemilik ?? '-'),
                'nrkb' => strtoupper($kendaraan->nrkb ?? '-'),
                'merek' => strtoupper($kendaraan->merk_kendaraan ?? '-'),
                'tipe' => strtoupper($kendaraan->tipe_kendaraan ?? '-'),
                'jenis' => strtoupper($kendaraan->jenis_kendaraan ?? '-'),
                'model' => strtoupper($kendaraan->model_kendaraan ?? '-'),
                'tahun' => strtoupper($kendaraan->tahun_pembuatan ?? '-'),
                'isi_silinder' => strtoupper($kendaraan->isi_silinder ?? '-'),
                'no_rangka' => strtoupper($kendaraan->nomor_rangka ?? '-'),
                'no_mesin' => strtoupper($kendaraan->nomor_mesin ?? '-'),
                'warna_kendaraan' => strtoupper($kendaraan->warna_kendaraan ?? '-'),
                'bahan_bakar' => strtoupper($kendaraan->jenis_bahan_bakar ?? '-'),
                'warna_tnkb' => strtoupper($kendaraan->warna_tnkb ?? '-'),
                'no_bpkb' => strtoupper($kendaraan->nomor_bpkb ?? '-'),
            ]
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_penghapusan_regident', $dataPdf);
        $pdf->setPaper('a4', 'portrait');
        
        $filename   = 'SK_REGIDENT_' . str_replace(' ', '_', $kendaraan->nrkb) . '_' . Str::uuid() . '.pdf';
        
        if (!$request->has('preview')) {
            $storagePath = 'sk/' . $filename;
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = url(Storage::disk('public')->url($storagePath));

            // Catat log
            $this->logSuratActionByKendaraanId(
                $pengajuan,
                $kendaraan->id,
                'SK Penghapusan Regident berhasil diterbitkan',
                'Nomor Surat: ' . $request->nomor_surat,
                storage_path('app/public/' . $storagePath)
            );

            // Dispatch WA notification (non-blocking, non-fatal)
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan:    $pengajuan,
                        kendaraan:    $kendaraan,
                        skType:       'regident',
                        pdfUrl:       $pdfUrlAbsolute,
                        localPdfPath: Storage::disk('public')->path($storagePath),
                        wpPhone:      $wpUser->no_hp,
                        wpName:       $wpUser->name,
                        nrkb:         $kendaraan->nrkb,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Regident): ' . $e->getMessage());
                }
            }
        }

        return $pdf->stream('SK_PENGHAPUSAN_REGIDENT_' . str_replace(' ', '_', $kendaraan->nrkb) . '.pdf');
    }

    /**
     * Generate PDF Surat Keputusan POLDA (Lampiran Surat Kapolda)
     */
    public function generateSkPolda(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_surat' => 'required|string',
            'nama_pembuat' => 'required|string',
            'tempat' => 'required|string',
            'tanggal_keluar' => 'required|string',
            'nama_direktur' => 'required|string',
            'pangkat_direktur' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);
        $this->authorizeBranch($pengajuan);

        // Ambil kendaraan yang dipilih
        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // Siapkan data untuk PDF
        $dataPdf = [
            'kendaraan' => $kendaraan,
            'data' => (object)[
                'nrkb' => strtoupper($kendaraan->nrkb ?? '-'),
                'nama' => strtoupper(optional($kendaraan->pemilik)->nama_pemilik ?? '-'),
                'alamat' => strtoupper(optional($kendaraan->pemilik)->alamat_pemilik ?? '-'),
                'jenis_model' => strtoupper(($kendaraan->jenis_kendaraan ?? '-') . '/' . ($kendaraan->model_kendaraan ?? '-')),
                'merek_tipe' => strtoupper(($kendaraan->merk_kendaraan ?? '-') . '/' . ($kendaraan->tipe_kendaraan ?? '-')),
                'tahun' => $kendaraan->tahun_pembuatan ?? '-',
                'isi_silinder' => strtoupper($kendaraan->isi_silinder ?? '-'),
                'bahan_bakar' => strtoupper($kendaraan->jenis_bahan_bakar ?? '-'),
                'no_rangka' => strtoupper($kendaraan->nomor_rangka ?? '-'),
                'no_mesin' => strtoupper($kendaraan->nomor_mesin ?? '-'),
                'warna' => strtoupper($kendaraan->warna_kendaraan ?? '-'),
                'no_bpkb' => strtoupper($kendaraan->nomor_bpkb ?? '-'),
            ],
            'nomor_surat' => $request->nomor_surat,
            'nama_pembuat' => $request->nama_pembuat,
            'tempat' => $request->tempat,
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_direktur' => $request->nama_direktur,
            'pangkat_direktur' => $request->pangkat_direktur,
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_polda', $dataPdf);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'SK_POLDA_' . str_replace(' ', '_', $kendaraan->nrkb) . '.pdf';

        if (!$request->has('preview')) {
            // Save PDF to storage
            $storagePath = 'sk/' . $filename;
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = url(Storage::disk('public')->url($storagePath));
            $localPdfPath = Storage::disk('public')->path($storagePath);
            
            // Add PDF to pengajuan media
            $pengajuan->addMedia($localPdfPath)
                ->usingName($filename)
                ->usingFileName($filename)
                ->toMediaCollection('sk_polda_pdf');

            // Create log entry
            $this->logSuratActionByKendaraanId(
                $pengajuan,
                $kendaraan->id,
                'SK Polda berhasil diterbitkan',
                'Nomor Surat: ' . $request->nomor_surat,
                storage_path('app/public/' . $storagePath)
            );

            // Ambil URL publik dari media yang baru disimpan
            $media = $pengajuan->getMedia('sk_polda_pdf')->last();
            $pdfUrlAbsolute = $media ? $media->getFullUrl() : null;
            $localPdfPath = $media ? $media->getPath() : null;

            // Dispatch WA notification (non-blocking, non-fatal)
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp && $pdfUrlAbsolute && $localPdfPath) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan:    $pengajuan,
                        kendaraan:    $kendaraan,
                        skType:       'polda',
                        pdfUrl:       $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone:      $wpUser->no_hp,
                        wpName:       $wpUser->name,
                        nrkb:         $kendaraan->nrkb,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Polda): ' . $e->getMessage());
                }
            }
        }

        // Stream the PDF to browser
        return $pdf->stream($filename);
    }

    /**
     * Generate PDF Surat Keputusan Pembebasan
     */
    public function generateSkPembebasan(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
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
            'sk_pembebasan_ttd_basah' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

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
                'nama' => strtoupper(optional($kendaraan->pemilik)->nama_pemilik ?? '-'),
                'alamat' => strtoupper(optional($kendaraan->pemilik)->alamat_pemilik ?? '-'),
                'nik' => strtoupper(optional($kendaraan->pemilik)->nik_pemilik ?? '-'),
                'no_tlp' => strtoupper(optional($kendaraan->pemilik)->telp_pemilik ?? '-'),
                'email' => strtoupper(optional($kendaraan->pemilik)->email_pemilik ?? '-'),
                'nrkb' => strtoupper($kendaraan->nrkb ?? '-'),
                'merek' => strtoupper($kendaraan->merk_kendaraan ?? '-'),
                'tipe' => strtoupper($kendaraan->tipe_kendaraan ?? '-'),
                'jenis' => strtoupper($kendaraan->jenis_kendaraan ?? '-'),
                'model' => strtoupper($kendaraan->model_kendaraan ?? '-'),
                'tahun' => strtoupper($kendaraan->tahun_pembuatan ?? '-'),
                'isi_silinder' => strtoupper($kendaraan->isi_silinder ?? '-'),
                'no_rangka' => strtoupper($kendaraan->nomor_rangka ?? '-'),
                'no_mesin' => strtoupper($kendaraan->nomor_mesin ?? '-'),
                'warna_kendaraan' => strtoupper($kendaraan->warna_kendaraan ?? '-'),
                'bahan_bakar' => strtoupper($kendaraan->jenis_bahan_bakar ?? '-'),
                'warna_tnkb' => strtoupper($kendaraan->warna_tnkb ?? '-'),
                'no_bpkb' => strtoupper($kendaraan->nomor_bpkb ?? '-'),
            ],
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_bapenda_pembebasan', $dataPdf);
        $pdf->setPaper('a4', 'portrait');
        $filename = 'SK_PEMBEBASAN_' . str_replace(' ', '_', $kendaraan->nrkb) . '_' . str_replace('/','_',str_replace(' ', '_', $request->nomor_surat_pembebasan)) . '.pdf';
        $storagePath    = 'sk/' . Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $pdf->output());
        $pdfUrlAbsolute = url(Storage::disk('public')->url($storagePath));
        

        if (!$request->has('preview')) {
            $log = $this->logSuratActionByKendaraanId(
                $pengajuan,
                $kendaraan->id,
                'SK Pembebasan berhasil dibuat dan ditandatangani',
                'Nomor Surat: ' . $request->nomor_surat_pembebasan,
                ($request->metode_penanda_tangan === 'ttd_basah' && $request->hasFile('sk_pembebasan_ttd_basah')) ? $request->file('sk_pembebasan_ttd_basah') : Storage::disk('public')->path($storagePath)
            );

        }

        if ($request->has('preview')) {
            return $pdf->download($filename);
        }

        // Dispatch WA notification (non-blocking, non-fatal)
        $wpUser = $pengajuan->user;
        if ($wpUser && $wpUser->no_hp) {
            try {
                SendWhatsAppNotification::dispatch(
                    pengajuan:    $pengajuan,
                    kendaraan:    $kendaraan,
                    skType:       'pembebasan',
                    pdfUrl:       $pdfUrlAbsolute,
                    localPdfPath: Storage::disk('public')->path($storagePath),
                    wpPhone:      $wpUser->no_hp,
                    wpName:       $wpUser->name,
                    nrkb:         $kendaraan->nrkb,
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Pembebasan): ' . $e->getMessage());
            }
        }

        return $pdf->stream($filename);
    }

    
    /**
     * Generate PDF SK Penghapusan Regident (Freysia)
     */
    public function generateSkPenghapusanRegident(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_surat' => 'required',
            'sifat' => 'required|string',
            'lampiran' => 'required|string',
            'hal' => 'required|string',
            'provinsi' => 'required|string',
            'nama_penandatangan' => 'required|string',
            'jabatan' => 'required|string',
            'nip' => 'required|string',
        ]);

        // Ambil data kendaraan berdasarkan pilihan dari form modal
        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        // Gabungkan data dari database dengan inputan form
        $dataPdf = [
            // Dari Form Input
            'nomor_surat' => strtoupper($request->nomor_surat),
            'sifat' => strtoupper($request->sifat),
            'lampiran' => strtoupper($request->lampiran),
            'hal' => strtoupper($request->hal),
            'provinsi' => strtoupper($request->provinsi),
            'nama_penandatangan' => strtoupper($request->nama_penandatangan),
            'jabatan' => strtoupper($request->jabatan),
            'nip' => strtoupper($request->nip),
            
            // Dari Database Kendaraan/Pemilik
            'nama_pemohon' => strtoupper(optional($kendaraan->pemilik)->nama_pemilik ?? '-'),
            'alamat' => strtoupper(optional($kendaraan->pemilik)->alamat_pemilik ?? '-'),
            'nomor_identitas' => strtoupper(optional($kendaraan->pemilik)->nik_pemilik ?? '-'),
            'nama_resident' => strtoupper($kendaraan->nrkb ?? '-'),
            'id_resident' => strtoupper($kendaraan->nrkb ?? '-'),
            'alasan' => 'Permintaan penghapusan data oleh pemilik'
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.view_hapus-regident', $dataPdf);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'SK_PENGHAPUSAN_REGIDENT_' . str_replace(' ', '_', $kendaraan->nrkb) . '.pdf';
        $storagePath    = 'sk/' . Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $pdf->output());
        $pdfUrlAbsolute = url(Storage::disk('public')->url($storagePath));

        // Catat log
        $this->logSuratActionByKendaraanId(
            $pengajuan,
            $kendaraan->id,
            'SK Penghapusan Regident (Freysia) berhasil diterbitkan',
            'Nomor Surat: ' . $request->nomor_surat,
            storage_path('app/public/' . $storagePath)
        );

        // Dispatch WA notification (non-blocking, non-fatal)
        $wpUser = $pengajuan->user;
        if ($wpUser && $wpUser->no_hp) {
            try {
                SendWhatsAppNotification::dispatch(
                    pengajuan:    $pengajuan,
                    kendaraan:    $kendaraan,
                    skType:       'sk_penghapusan_regident_freysia',
                    pdfUrl:       $pdfUrlAbsolute,
                    localPdfPath: Storage::disk('public')->path($storagePath),
                    wpPhone:      $wpUser->no_hp,
                    wpName:       $wpUser->name,
                    nrkb:         $kendaraan->nrkb,
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Penghapusan Regident Freysia): ' . $e->getMessage());
            }
        }

        return $pdf->stream('SK_PENGHAPUSAN_REGIDENT_' . str_replace(' ', '_', $kendaraan->nrkb) . '.pdf');
    }
    
    
    private function logSuratActionByKendaraanId(Pengajuan $pengajuan, string $kendaraan_id, string $actionLabel, string $notes, $file = null): KendaraanLog
    {
        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan_id,
            'user_id' => Auth::id(),
            'aksi' => $actionLabel,
            'status_baru' => $pengajuan->kendaraans->find($kendaraan_id)->status,
            'tipe' => 'system',
            'catatan' => $notes,
        ]);
        if ($file) {
            $log->addMedia($file)->toMediaCollection("lampiran_log");
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
            ]);
            if ($file) {
                $logArray[$kendaraan->id]->addMedia($file)->toMediaCollection("lampiran_log");
            }
        }

        return $logArray;
    }

}