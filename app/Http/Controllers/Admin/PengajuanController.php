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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{

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
    /**
     * Level 1: Menampilkan daftar BUNDEL pengajuan
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $selectedCabang = $request->query('cabang_id');
        $user = Auth::user();
        $isBranchScoped = $user->can('scoped_to_own_branch') && !$user->hasRole('superadmin');
        $isSamsat = $isBranchScoped;

        $query = Pengajuan::whereHas('kendaraans', function ($q) {
                $q->where('status', '<>', 'draft');
            })
            ->with(['user', 'kendaraans:id,pengajuan_id,status', 'cabang'])
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
            'suratKeputusans.log'
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
        elseif ($progress >= 6 && $progress < 9 && ($user->unit_kerja == "Polda" || $progress > 6) && $pengajuan->isFullyApprovedByAll() && $suratkeputusan->where('unit_kerja', $user->unit_kerja)->isEmpty() && in_array($this->normalizeUnitKerja($user->unit_kerja),["Polda","Bapenda","Jasa Raharja"])) {
            $permissionSurat['canAjukanSK'] = true;
        }

        foreach($pengajuan->kendaraans as $kendaraan){
            // Existing current Unit Kerja, untuk permissionSurat
            $exisitingSkIds = $kendaraan->suratKeputusans()
            ->where('unit_kerja', $this->normalizeUnitKerja($user->unit_kerja))
            ->first();

            if (!$exisitingSkIds && $progress >= 6 && $progress < 9 && ($user->unit_kerja == "Polda" || $progress > 6) && $pengajuan->isFullyApprovedByAll() && in_array($this->normalizeUnitKerja($user->unit_kerja),["Polda","Bapenda","Jasa Raharja"])) {
                $permissionSurat['canAjukanSK'] = true;
            }
        }

        // Generate signed URLs for modal form submissions (access control)
        $signedUrls = [];
        if (($permissionSurat['canAjukanSP'] ?? null)){
            $signedUrls['sp_ajukan'] = URL::temporarySignedRoute(
                'admin.pengajuan.ajukan', now()->addMinutes(60), ['id' => $pengajuan->id]
            );
        }
        if ($lastSp && ($permissionSurat['canRespondSP'] ?? null)) {
            $signedUrls['sp_terima'] = URL::temporarySignedRoute(
                'admin.pengajuan.sp.terima', now()->addMinutes(60), ['surat' => $lastSp->id]
            );
            $signedUrls['sp_tolak'] = URL::temporarySignedRoute(
                'admin.pengajuan.sp.tolak', now()->addMinutes(60), ['surat' => $lastSp->id]
            );
        }
        if (($permissionSurat['canAjukanSK'] ?? null)) {
            $signedUrls['sk_buat'] = URL::temporarySignedRoute(
                'admin.pengajuan.buat_sk', now()->addMinutes(60), ['id' => $pengajuan->id]
            );
        }

        // Mapping jenis Surat per role untuk modal "Pilih Jenis Surat"
        $sTypeOptions = [];
        $normalizedUK = $this->normalizeUnitKerja($user->unit_kerja);
        switch ($normalizedUK) {
            case 'Samsat':
                if (($permissionSurat['canAjukanSP'] ?? null)){
                    $sTypeOptions[] = ['key' => 'sp_default', 'label' => 'SP Pengajuan ke Polda', 'icon' => 'fas fa-paper-plane', 'modal' => '#modalSpDefault'];
                }
                break;
            case 'Polda':
                if (($permissionSurat['canAjukanSP'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sp_polda2bapendajr', 'label' => 'SP Polda ke Bapenda/JR', 'icon' => 'fas fa-paper-plane', 'modal' => '#modalSpPolda2bapendajr'];
                } else if (($permissionSurat['canAjukanSK'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sk_polda', 'label' => 'SK Polda', 'icon' => 'fas fa-shield-alt', 'modal' => '#modalSkPolda'];
                }
                break;
            case 'Bapenda':
                if (($permissionSurat['canRespondSP'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sp_balasan_bapenda', 'label' => 'SP Balasan Bapenda', 'icon' => 'fas fa-reply', 'modal' => '#modalSpBalasanBapenda'];
                } else if (($permissionSurat['canAjukanSK'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sk_bapenda', 'label' => 'SK Kepala Bapenda (Pembebasan)', 'icon' => 'fas fa-building', 'modal' => '#modalSkPembebasan'];
                }
                break;
            case 'Jasa Raharja':
                if (($permissionSurat['canRespondSP'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sp_balasan_jr', 'label' => 'SP Balasan Jasa Raharja', 'icon' => 'fas fa-reply', 'modal' => '#modalSpBalasanJR'];
                } else if (($permissionSurat['canAjukanSK'] ?? null)) {
                    $sTypeOptions[] = ['key' => 'sk_jr', 'label' => 'SK Jasa Raharja', 'icon' => 'fas fa-file-contract', 'modal' => '#modalSkJR'];
                }
                break;
        }


        return view('admin.pengajuan.show', compact(
            'pengajuan',
            'suratkeputusan',
            'suratpengajuan',
            'progress',
            'permissionSurat',
            'sTypeOptions',
            'signedUrls',
            'lastSp'
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
        $isBranchScoped = $user->can('scoped_to_own_branch') && !$user->hasRole('superadmin');

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

        $log = KendaraanLog::with(['user', 'kendaraan', 'media'])->findOrFail($logId);

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
     * Simpan SK sebagai Draft (AJAX dari modal inline di Log & Diskusi)
     * Memanggil SuratKeputusanController->ajukan() lalu set sk_status='draft' di log-nya.
     */
    public function storeDraftSk(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        // Panggil method ajukan yang existing di SuratKeputusanController
        $skController = app(\App\Http\Controllers\SuratKeputusanController::class);

        // Tambahin flag agar ajukan tahu ini draft mode
        $request->merge(['draft_mode' => true]);

        $response = $skController->ajukan($request, $pengajuan->id);

        // Jika response berupa redirect (karena validasi/kondisi domain gagal)
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return response()->json([
                'success' => false,
                'message' => session('error') ?? 'Gagal membuat Surat Keputusan.',
            ], 400);
        }

        // ajukan() returns JSON response, decode it
        $responseData = $response->getData(true);

        if (isset($responseData['data'])) {
            // Update semua KendaraanLog yang terkait menjadi draft
            foreach ($responseData['data'] as $kendaraanId => $item) {
                if (isset($item['log_id'])) {
                    KendaraanLog::where('id', $item['log_id'])->update(['sk_status' => 'draft']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Surat Keputusan berhasil disimpan sebagai draft.',
            'redirect' => route('admin.pengajuan.show', $pengajuan->id),
            'data' => $responseData['data'] ?? [],
        ]);
    }

    /**
     * Publish (terbitkan) SK Draft:
     * - Upload dokumen bertandatangan
     * - Centang checkbox pernyataan
     * - Ubah sk_status dari 'draft' ke 'terbit'
     * - Visible to all
     */
    public function publishSk(Request $request, KendaraanLog $log)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif,docx|max:10240',
            'pernyataan' => 'required|accepted',
        ], [
            'file.required' => 'Dokumen bertandatangan wajib diunggah.',
            'pernyataan.required' => 'Anda harus mencentang pernyataan penerbitan.',
            'pernyataan.accepted' => 'Anda harus mencentang pernyataan penerbitan.',
        ]);

        // Pastikan log ini memang SK draft
        if ($log->sk_status !== 'draft' || !$log->sk_id) {
            return redirect()->back()->with('error', 'Log ini bukan draft SK yang valid.');
        }

        $pengajuan = $log->kendaraan->pengajuan;
        $this->authorizeBranch($pengajuan);

        // Upload file ke storage
        $filename = $request->file->getClientOriginalName();
        $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $request->file('file')->get());
        $localPdfPath = Storage::disk('public')->path($storagePath);
        $pdfUrlAbsolute = asset('storage/' . $storagePath);

        // Update SuratKeputusan record & hapus pdf sebelumnya pada storage
        $sk = SuratKeputusan::find($log->sk_id);
        if ($sk) {
            $this->deletePhysicalPdf($sk->local_pdf_path);
            $sk->update([
                'local_pdf_path' => $pdfUrlAbsolute,
                'pdf_url' => $pdfUrlAbsolute,
            ]);
        }

        // Clear old draft media first to avoid double media in the log
        $log->clearMediaCollection('lampiran_log');

        // Attach file ke media library log
        $log->addMedia($localPdfPath)->preservingOriginal()->toMediaCollection('lampiran_log');

        // Update sk_status ke terbit
        $log->update(['sk_status' => 'terbit']);

        // Dispatch WhatsApp notification
        if ($sk) {
            $kendaraan = $log->kendaraan;
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    $skType = match ($this->normalizeUnitKerja(Auth::user()->unit_kerja)) {
                        'Polda' => 'regident',
                        'Bapenda' => 'pembebasan',
                        'Jasa Raharja' => 'jr',
                        default => 'default',
                    };
                    SendWhatsAppNotification::dispatch(
                        pengajuan: $pengajuan,
                        kendaraan: $kendaraan,
                        skType: $skType,
                        pdfUrl: $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone: $wpUser->no_hp,
                        wpName: $wpUser->name,
                        nrkb: $kendaraan->nrkb,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SK Publish): ' . $e->getMessage());
                }
            }

            // Cek apakah semua 3 role sudah publish SK → status kendaraan = selesai
            $kendaraan->refresh();
            $totalSkByUnitKerja = $kendaraan->suratKeputusans()
                ->whereIn('unit_kerja', ['Polda', 'Bapenda', 'Jasa Raharja'])
                ->whereDoesntHave('log', function($q) {
                    $q->where('sk_status', 'draft');
                })
                ->distinct('unit_kerja')
                ->count('unit_kerja');

            if ($kendaraan->status == 'diproses' && $totalSkByUnitKerja >= 3) {
                $kendaraan->update(['status' => 'selesai']);
                KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Pengajuan Selesai',
                    'tipe' => 'system',
                    'status_baru' => 'selesai',
                    'catatan' => 'Pengajuan Selesai Setelah Ketiga Surat Keputusan Diterbitkan.',
                ]);
            }
        }

        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', 'Surat Keputusan berhasil diterbitkan.');
    }

    /**
     * Publish (terbitkan) SP Draft:
     * - Upload dokumen bertandatangan
     * - Centang checkbox pernyataan
     * - Ubah sp_status dari 'draft' ke 'terbit'
     */
    public function publishSp(Request $request, KendaraanLog $log)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,heif,docx|max:10240',
            'pernyataan' => 'required|accepted',
        ], [
            'file.required' => 'Dokumen bertandatangan wajib diunggah.',
            'pernyataan.required' => 'Anda harus mencentang pernyataan penerbitan.',
            'pernyataan.accepted' => 'Anda harus mencentang pernyataan penerbitan.',
        ]);

        // Pastikan log ini memang SP draft
        if ($log->sp_status !== 'draft' || !$log->sp_id) {
            return redirect()->back()->with('error', 'Log ini bukan draft SP yang valid.');
        }

        $pengajuan = $log->kendaraan->pengajuan;
        $this->authorizeBranch($pengajuan);

        // Upload file ke storage
        $filename = $request->file->getClientOriginalName();
        $storagePath = 'sp/' . Str::uuid() . '_' . $filename;
        Storage::disk('public')->put($storagePath, $request->file('file')->get());
        $localPdfPath = Storage::disk('public')->path($storagePath);
        $pdfUrlAbsolute = asset('storage/' . $storagePath);

        // Update SuratPengajuan record
        $sp = SuratPengajuan::find($log->sp_id);
        if ($sp) {
            $unitKerja = $this->normalizeUnitKerja(Auth::user()->unit_kerja);
            if ($unitKerja === 'Bapenda' || $unitKerja === 'Jasa Raharja') {
                $persetujuan = $sp->persetujuan_unit_kerja ?? [];
                foreach ($persetujuan as &$item) {
                    if (strcasecmp($item['instansi'] ?? '', $unitKerja) === 0) {
                        $oldPath = $item['local_pdf_path'] ?? null;
                        $this->deletePhysicalPdf($oldPath);
                        $item['pdf_url'] = $pdfUrlAbsolute;
                        $item['local_pdf_path'] = $pdfUrlAbsolute;
                        $item['updated_at'] = now();
                    }
                }
                $this->deletePhysicalPdf($sp->local_pdf_balasan_path);
                $sp->update([
                    'persetujuan_unit_kerja' => $persetujuan,
                    'local_pdf_balasan_path' => $pdfUrlAbsolute,
                    'pdf_balasan_url' => $pdfUrlAbsolute,
                ]);
            } else {
                $this->deletePhysicalPdf($sp->local_pdf_path);
                $sp->update([
                    'local_pdf_path' => $pdfUrlAbsolute,
                    'pdf_url' => $pdfUrlAbsolute,
                ]);
            }
        }

        // Clear old draft media first to avoid double media in the log
        $log->clearMediaCollection('lampiran_log');

        // Attach file ke media library log
        $log->addMedia($localPdfPath)->preservingOriginal()->toMediaCollection('lampiran_log');

        // Update sp_status ke terbit
        $log->update(['sp_status' => 'terbit']);

        // Dispatch WhatsApp notification
        if ($sp) {
            $kendaraan = $log->kendaraan;
            $wpUser = $pengajuan->user;
            if ($wpUser && $wpUser->no_hp) {
                try {
                    SendWhatsAppNotification::dispatch(
                        pengajuan: $pengajuan,
                        kendaraan: $kendaraan,
                        skType: 'sp_publish',
                        pdfUrl: $pdfUrlAbsolute,
                        localPdfPath: $localPdfPath,
                        wpPhone: $wpUser->no_hp,
                        wpName: $wpUser->name,
                        nrkb: $kendaraan->nrkb,
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('[Fonnte] Dispatch error (SP Publish): ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.pengajuan.show', $pengajuan->id)
            ->with('success', 'Surat Pengajuan berhasil diterbitkan.');
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

    /**
     * Generate PDF Surat Keputusan Jasa Raharja
     */
    public function generateSkJasaRaharja(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
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
            'sk_jr_ttd_basah' => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();

        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $pemilik = optional($kendaraan->pemilik);

        $dataPdf = [
            'nomor_keputusan' => strtoupper($request->nomor_keputusan),
            'tempat_sk' => strtoupper($request->tempat_sk),
            'tanggal_sk' => strtoupper($request->tanggal_sk),
            'nama_penandatangan' => strtoupper($request->nama_penandatangan),
            'jabatan_penandatangan' => 'KEPALA KANTOR WILAYAH PT JASA RAHARJA JAWA TENGAH',
            'metode_penanda_tangan' => $request->metode_penanda_tangan,
            'tanggal_surat_permohonan' => strtoupper($request->tanggal_surat_permohonan),
            'nomor_surat_regident' => strtoupper($request->nomor_surat_regident),
            'tanggal_surat_regident' => strtoupper($request->tanggal_surat_regident),
            'nomor_surat_bapenda' => $request->nomor_surat_bapenda ? strtoupper($request->nomor_surat_bapenda) : '-',
            'tanggal_surat_bapenda' => $request->tanggal_surat_bapenda ? strtoupper($request->tanggal_surat_bapenda) : '-',
            'data' => (object)[
                'nama' => strtoupper($pemilik->nama_pemilik ?? '-'),
                'alamat' => strtoupper($pemilik->alamat_pemilik ?? '-'),
                'nik' => strtoupper($pemilik->nik_pemilik ?? '-'),
                'no_tlp' => strtoupper($pemilik->telp_pemilik ?? '-'),
                'email' => strtoupper($pemilik->email_pemilik ?? '-'),
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
                'merk_type' => strtoupper(trim(($kendaraan->merk_kendaraan ?? '') . ' / ' . ($kendaraan->tipe_kendaraan ?? '-'))),
                'no_rangka_mesin' => strtoupper(trim(($kendaraan->nomor_rangka ?? '') . ' / ' . ($kendaraan->nomor_mesin ?? '-'))),
                'jenis_model' => strtoupper(trim(($kendaraan->jenis_kendaraan ?? '') . ' / ' . ($kendaraan->model_kendaraan ?? '-'))),
            ],
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_jasa_raharja_pembebasan', $dataPdf);
        $pdf->setPaper('a4', 'portrait');
        $filename = 'SK_JR_PEMBEBASAN_' . str_replace([' ', '/'], '_', $kendaraan->nrkb) . '_' . str_replace('/', '_', $dataPdf['nomor_keputusan']) . '.pdf';
        $storagePath = 'sk/' . Str::uuid() . '_' . $filename;
        
        $ttd_basah = $request->metode_penanda_tangan == 'ttd_basah';
        if (!$ttd_basah || $request->has('preview')) {
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfUrlAbsolute = url(Storage::disk('public')->url($storagePath));
        } else {
            $pdfUrlAbsolute = null;
        }

        if (!$request->has('preview')) {
            $this->logSuratActionByKendaraanId(
                $pengajuan,
                $kendaraan->id,
                'SK Jasa Raharja Pembebasan berhasil diterbitkan',
                'Nomor Keputusan: ' . $request->nomor_keputusan,
                ($ttd_basah && $request->hasFile('sk_jr_ttd_basah')) ? $request->file('sk_jr_ttd_basah') : ($pdfUrlAbsolute ? Storage::disk('public')->path($storagePath) : null)
            );
        }

        if ($request->has('preview')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    /**
     * Generate PDF Surat Balasan Jasa Raharja (Pembebasan SWDKLLJ)
     * Dipanggil dari modal #modalSkBalasanJR via AJAX (preview & draft)
     */
    public function generateSpBalasanJR(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeBranch($pengajuan);

        // Validasi field INPUT
        $request->validate([
            'kendaraan_id'           => 'required|exists:kendaraans,id',
            'nomor_surat'            => 'required|string',
            'nomor_surat_regident'   => 'required|string',
            'nomor_surat_bapenda'    => 'required|string',
            'tempat_surat'           => 'required|string',
            'tanggal_surat'          => 'required|string',
            'nama_penandatangan'     => 'required|string',
            'jabatan_penandatangan'  => 'required|string',
        ]);

        // Ambil kendaraan dari pengajuan ini
        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();
        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan pada pengajuan ini.');
        }

        $pemilik = optional($kendaraan->pemilik);

        // Compile data PDF: gabungan INPUT + FETCH
        $dataPdf = [
            'nomor_surat'            => strtoupper($request->nomor_surat),
            'nomor_surat_regident'   => strtoupper($request->nomor_surat_regident),
            'nomor_surat_bapenda'    => strtoupper($request->nomor_surat_bapenda),
            'tempat_surat'           => $request->tempat_surat,
            'tanggal_surat'          => $request->tanggal_surat,
            'nama_penandatangan'     => $request->nama_penandatangan,
            'jabatan_penandatangan'  => $request->jabatan_penandatangan,
            // FETCH: data kendaraan + pemilik
            'data' => (object)[
                'nrkb'           => strtoupper($kendaraan->nrkb ?? '-'),
                'nama'           => strtoupper($pemilik->nama_pemilik ?? '-'),
                'alamat'         => strtoupper($pemilik->alamat_pemilik ?? '-'),
                'merk_type'      => strtoupper(trim(($kendaraan->merk_kendaraan ?? '') . ' / ' . ($kendaraan->tipe_kendaraan ?? '-'))),
                'no_rangka_mesin'=> strtoupper(trim(($kendaraan->nomor_rangka ?? '') . ' / ' . ($kendaraan->nomor_mesin ?? '-'))),
                'jenis_model'    => strtoupper(trim(($kendaraan->jenis_kendaraan ?? '') . ' / ' . ($kendaraan->model_kendaraan ?? '-'))),
                'tahun'          => $kendaraan->tahun_pembuatan ?? '-',
            ],
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sp_balasan_jr', $dataPdf);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'SP_BALASAN_JR_' . str_replace([' ', '/'], '_', $kendaraan->nrkb) . '_' . str_replace('/', '_', $request->nomor_surat) . '.pdf';

        // Simpan PDF & log aksi (skip jika preview)
        if (!$request->has('preview')) {
            $storagePath = 'sp/' . Str::uuid() . '_' . $filename;
            Storage::disk('public')->put($storagePath, $pdf->output());

            $this->logSuratActionByKendaraanId(
                $pengajuan,
                $kendaraan->id,
                'SP Balasan Jasa Raharja berhasil diterbitkan',
                'Nomor Surat: ' . $request->nomor_surat,
                Storage::disk('public')->path($storagePath)
            );
        }

        // Preview → download blob, Draft → stream inline
        if ($request->has('preview')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
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

    /**
     * Delete a physical PDF file from the public storage disk using its local path or absolute URL.
     */
    private function deletePhysicalPdf(?string $pathOrUrl): void
    {
        if (!$pathOrUrl) {
            return;
        }

        try {
            // 1. If it's an absolute URL, resolve the local file path from the URL
            if (filter_var($pathOrUrl, FILTER_VALIDATE_URL) || str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
                $parsedUrl = parse_url($pathOrUrl, PHP_URL_PATH);
                // e.g. "/storage/sk/uuid.pdf" or "/storage/sp/uuid.pdf"
                if ($parsedUrl && str_starts_with($parsedUrl, '/storage/')) {
                    $relativePath = substr($parsedUrl, strlen('/storage/')); // e.g. "sk/uuid.pdf"
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }
            } else {
                // 2. If it's already a local absolute path, find the relative path to the public disk
                $publicPathPrefix = Storage::disk('public')->path(''); // e.g. "/workspaces/bapendajawatengah-ssh/storage/app/public/"
                if (str_starts_with($pathOrUrl, $publicPathPrefix)) {
                    $relativePath = substr($pathOrUrl, strlen($publicPathPrefix));
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                } elseif (file_exists($pathOrUrl)) {
                    // Fallback to direct php unlink
                    @unlink($pathOrUrl);
                }
            }
        } catch (\Throwable $e) {
            // Log error or ignore to prevent breaking the flow
            \Illuminate\Support\Facades\Log::error('Gagal menghapus file PDF fisik: ' . $e->getMessage());
        }
    }

}