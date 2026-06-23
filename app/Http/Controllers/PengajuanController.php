<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pengajuan;
use App\Models\KendaraanLog;
use App\Models\Kendaraan;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class PengajuanController extends Controller
{
    /**
     * Halaman Buat Pengajuan Baru (dengan multiple kendaraan)
     */
    public function create()
    {
        $branches = Cabang::orderBy('wilayah')->get();
        return view('pengajuan.create', compact('branches'));
    }

    /**
     * [Langkah 2 UX]
     * Menampilkan halaman daftar "bundel" pengajuan milik penulis.
     */
    public function index(Request $request)
    {
        $query = Pengajuan::where('user_id', Auth::id())
            ->with('kendaraans:id,pengajuan_id,status')
            ->with('cabang:id,nama,wilayah')
            ->withCount('kendaraans')
            ->latest();

        // Filter: search by nomor_pengajuan
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nomor_pengajuan', 'like', "%{$q}%");
        }

        // Filter: by status (draft, pengajuan, diproses, selesai, ditolak)
        if ($request->filled('status')) {
            $status = $request->status;

            if (!in_array($status, ['draft', 'pengajuan', 'diproses', 'selesai', 'ditolak'])) {
                // ignore unknown statuses
            } else {
                switch ($status) {
                    case 'draft':
                        // no kendaraans OR all kendaraans are draft
                        $query->whereDoesntHave('kendaraans', function ($q) {
                            $q->where('status', '<>', 'draft');
                        });
                        break;

                    case 'ditolak':
                        // any kendaraan ditolak
                        $query->whereHas('kendaraans', function ($q) {
                            $q->where('status', 'ditolak');
                        });
                        break;

                    case 'diproses':
                        // any kendaraan diproses, but exclude those with any ditolak (ditolak takes precedence)
                        $query->whereHas('kendaraans', function ($q) {
                            $q->where('status', 'diproses');
                        })->whereDoesntHave('kendaraans', function ($q) {
                            $q->where('status', 'ditolak');
                        });
                        break;

                    case 'selesai':
                        // all kendaraans selesai (and at least one exists)
                        $query->whereHas('kendaraans')
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', '<>', 'selesai');
                            });
                        break;

                    case 'pengajuan':
                        // has kendaraans, none ditolak, none diproses, none draft, and not all selesai
                        $query->whereHas('kendaraans')
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', 'ditolak');
                            })
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', 'diproses');
                            })
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', 'draft');
                            })
                            // ensure at least one kendaraan is not 'selesai' (so not all finished)
                            ->whereHas('kendaraans', function ($q) {
                                $q->where('status', '<>', 'selesai');
                            });
                        break;
                }
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        $pengajuans = $query->paginate($perPage)->appends($request->except('page'));

        $progress = [];
        foreach ($pengajuans as $pengajuan) {
            $progress[$pengajuan->id] = $pengajuan->getTotalSurat();
        }

        $unreadPengajuanIds = Auth::user()
            ? Auth::user()->unreadNotifications
                ->pluck('data.pengajuan_id')
                ->filter()
                ->toArray()
            : [];

        return view('pengajuan.index', compact('pengajuans', 'progress', 'unreadPengajuanIds'));
    }

    /**
     * Simpan satu kendaraan (dari form Buat Pengajuan)
     */
    public function storeKendaraan(Request $request)
    {
        // Validasi semua input (Pemilik + Kendaraan + Dokumen)
        $validatedData = $request->validate([
            // Validasi Pemilik
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|max:100',
            'alamat_pemilik' => 'required|string',
            'telp_pemilik' => 'required|string|max:20',
            'email_pemilik' => 'required|email|max:255',

            // Validasi Kendaraan
            'nrkb' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|string|max:100',
            'model_kendaraan' => 'required|string|max:100',
            'merk_kendaraan' => 'required|string|max:100',
            'tipe_kendaraan' => 'required|string|max:100',
            'tahun_pembuatan' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            'isi_silinder' => 'required|string|max:100',
            'jenis_bahan_bakar' => 'required|string|max:100',
            'nomor_rangka' => 'required|string|max:255',
            'nomor_mesin' => 'required|string|max:255',
            'warna_kendaraan' => 'required|string|max:100',
            'warna_tnkb' => 'required|string|max:50',
            'nomor_bpkb' => 'required|string|max:255',

            // Validasi file (array, max 10MB)
            'surat_permohonan'   => 'required|array|min:1',
            'surat_permohonan.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'surat_pernyataan'   => 'required|array|min:1',
            'surat_pernyataan.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'ktp'   => 'required|array|min:1',
            'ktp.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'bpkb'   => 'required|array|min:1',
            'bpkb.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'tbpkp'   => 'required|array|min:1',
            'tbpkp.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'cek_fisik'   => 'required|array|min:1',
            'cek_fisik.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'foto_ranmor'   => 'required|array|min:1',
            'foto_ranmor.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            'stnk'   => 'required|array|min:1',
            'stnk.*' => 'required|mimes:pdf,docx,jpg,jpeg,png,heic,heif|max:10240',
            
            // ID kendaraan untuk update (opsional)
            'kendaraan_id' => 'nullable|exists:kendaraans,id',
            'pengajuan_id' => 'nullable|exists:pengajuans,id',
        ]);

        // Cari atau buat Pengajuan
        $pengajuan = null;
        if ($request->has('pengajuan_id') && $request->pengajuan_id) {
            $pengajuan = Pengajuan::where('id', $request->pengajuan_id)
                ->where('user_id', Auth::id())
                ->first();
            if (!$pengajuan) {
                return response()->json(['error' => 'Pengajuan tidak ditemukan'], 404);
            }
        } else {
            // Buat pengajuan baru jika belum ada (tanpa nomor_pengajuan dulu, akan di-generate saat finalisasi)
            $pengajuan = Pengajuan::create([
                'user_id' => Auth::id(),
                'cabang_id' => Auth::user()->cabang_id,
            ]);
        }

        // Pisahkan data Pemilik
        $dataPemilik = [
            'nama_pemilik' => $validatedData['nama_pemilik'],
            'nik_pemilik' => $validatedData['nik_pemilik'],
            'alamat_pemilik' => $validatedData['alamat_pemilik'],
            'telp_pemilik' => $validatedData['telp_pemilik'],
            'email_pemilik' => $validatedData['email_pemilik'],
        ];

        // Cari atau Buat Pemilik baru
        $pemilik = Pemilik::updateOrCreate(
            ['nik_pemilik' => $validatedData['nik_pemilik']],
            $dataPemilik
        );

        // Pisahkan data Kendaraan
        $dataKendaraan = Arr::except($validatedData, [
            'nama_pemilik',
            'nik_pemilik',
            'alamat_pemilik',
            'telp_pemilik',
            'email_pemilik',
            'surat_permohonan',
            'surat_pernyataan',
            'ktp',
            'bpkb',
            'tbpkp',
            'cek_fisik',
            'foto_ranmor',
            'stnk',
            'kendaraan_id',
            'pengajuan_id'
        ]);

        $dataKendaraan['pemilik_id'] = $pemilik->id;
        $dataKendaraan['status'] = 'draft';

        // Update atau create kendaraan
        if ($request->has('kendaraan_id') && $request->kendaraan_id) {
            $kendaraan = Kendaraan::where('id', $request->kendaraan_id)
                ->where('pengajuan_id', $pengajuan->id)
                ->first();
            if ($kendaraan) {
                $kendaraan->update($dataKendaraan);
            } else {
                return response()->json(['error' => 'Kendaraan tidak ditemukan'], 404);
            }
        } else {
            $kendaraan = $pengajuan->kendaraans()->create($dataKendaraan);
        }

        // Upload dokumen
        $this->uploadDokumen($request, $kendaraan, $request->has('kendaraan_id'));

        // TIDAK mencatat ke Log Histori saat simpan kendaraan (bersifat draft)

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil disimpan',
            'kendaraan_id' => $kendaraan->id,
            'pengajuan_id' => $pengajuan->id,
            'nomor_pengajuan' => $pengajuan->nomor_pengajuan
        ]);
    }

    /**
     * Simpan pengajuan lengkap (generate nomor pengajuan)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'cabang_id' => 'nullable|exists:cabangs,id',
        ]);

        $pengajuan = Pengajuan::where('id', $request->pengajuan_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->filled('cabang_id')) {
            $pengajuan->cabang_id = $request->cabang_id;
            $pengajuan->save();
        }

        // Validasi semua kendaraan sudah lengkap
        $pengajuan->loadMissing('kendaraans.pemilik');
        $kendaraans = $pengajuan->kendaraans;
        if ($kendaraans->isEmpty()) {
            return redirect()->route('pengajuan.create')
                ->with('error', 'Minimal harus ada 1 kendaraan untuk membuat pengajuan.');
        }

        // Cek kelengkapan data setiap kendaraan
        $incompleteKendaraans = [];
        foreach ($kendaraans as $kendaraan) {
            if (!$kendaraan->pemilik || !$kendaraan->nrkb || !$kendaraan->merk_kendaraan) {
                $incompleteKendaraans[] = $kendaraan->id;
            }
        }

        if (!empty($incompleteKendaraans)) {
            return redirect()->route('pengajuan.create', ['pengajuan_id' => $pengajuan->id])
                ->with('error', 'Beberapa kendaraan belum lengkap datanya. Silakan lengkapi terlebih dahulu.')
                ->with('incomplete_kendaraans', $incompleteKendaraans);
        }

        // Generate nomor_pengajuan hanya saat finalisasi (bukan di draft)
        if (empty($pengajuan->nomor_pengajuan)) {
            $pengajuan->nomor_pengajuan = Pengajuan::generateNomorPengajuan();
            $pengajuan->save();
        }

        // Update status kendaraan dari draft ke pengajuan, dan catat ke Log Histori (1x per kendaraan)
        foreach ($kendaraans as $kendaraan) {
            if ($kendaraan->status === 'draft') {
                $kendaraan->update(['status' => 'pengajuan']);
                
                KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Kendaraan Diajukan',
                    'tipe' => 'system',
                    'status_baru' => 'pengajuan',
                    'catatan' => 'Kendaraan (' . $kendaraan->merk_kendaraan . ') diajukan oleh ' . (Auth::user()->unit_kerja ?? Auth::user()->name),
                ]);
            }
        }

        // Pengajuan sudah lengkap, redirect ke halaman detail pengajuan
        $isAdmin = auth()->user()->can('view_menu_manajemen_pengajuan');
        $route = $isAdmin ? 'admin.pengajuan.show' : 'pengajuan.show';

        return redirect()->route($route, $pengajuan)
            ->with('success', 'Pengajuan berhasil dibuat! Nomor Pengajuan: ' . $pengajuan->nomor_pengajuan . ' (' . $kendaraans->count() . ' kendaraan)');
    }

    /**
     * Helper untuk upload dokumen
     */
    private function uploadDokumen(Request $request, Kendaraan $kendaraan, $isUpdate = false)
    {
        $kategoriDokumen = [
            'surat_permohonan',
            'surat_pernyataan',
            'ktp',
            'bpkb',
            'tbpkp',
            'cek_fisik',
            'foto_ranmor',
            'stnk'
        ];

        foreach ($kategoriDokumen as $kategori) {
            if ($request->hasFile($kategori)) {
                if ($isUpdate) {
                    $kendaraan->clearMediaCollection($kategori);
                }
                foreach ($request->file($kategori) as $file) {
                    $kendaraan->addMedia($file)->toMediaCollection($kategori);
                }
            }
        }
    }

    /**
     * [Langkah 3 UX]
     * Menampilkan halaman detail "bundel" (berisi tabel kendaraan).
     */
    public function show(Pengajuan $pengajuan)
    {
        // 1. Validasi: Pastikan hanya pemilik yang bisa melihat
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Anda tidak diizinkan melihat pengajuan ini.');
        }

        // 2. Ambil semua relasi yang diperlukan
        $pengajuan->load([
            'kendaraans.pemilik', // Daftar kendaraan dengan data pemilik
            'kendaraans.media', // Media files untuk setiap kendaraan
            'kendaraans.logs.user', // log per kendaraan
            'kendaraans.logs.media', // lampiran log per kendaraan
            'suratPengajuan',
            'suratKeputusans.log'
        ]);
        $pengajuan->setRelation('kendaraans', $pengajuan->kendaraans->sortBy('created_at')->values());

        $progress = $pengajuan->getTotalSurat();

        $unreadLogIds = [];
        if (Auth::check()) {
            $unreadNotifs = Auth::user()->unreadNotifications
                ->filter(function ($n) use ($pengajuan) {
                    return isset($n->data['pengajuan_id']) && $n->data['pengajuan_id'] == $pengajuan->id;
                });
            
            $unreadLogIds = $unreadNotifs->pluck('data.log_id')->filter()->toArray();
            $unreadNotifs->each(function ($n) {
                $n->markAsRead();
            });
        }

        // 3. Tampilkan view
        return view('pengajuan.show', compact('pengajuan', 'progress', 'unreadLogIds'));
    }

    /**
     * Store a log entry (comment + optional file) for a kendaraan within a pengajuan (penulis side).
     */
    public function storeLog(Request $request, Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => 'required|in:komentar,revisi,admin,system',
            'aksi' => 'nullable|string|max:255',
            'status_baru' => 'nullable|in:pengajuan,diproses,selesai,ditolak',
            'catatan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,png,jpeg,heic,heif,doc,docx|max:5120',
        ]);

        $kendaraan = $pengajuan->kendaraans->firstWhere('id', $validated['kendaraan_id']);
        if (!$kendaraan) {
            return redirect()->route('pengajuan.show', $pengajuan)->with('error', 'Kendaraan tidak ditemukan dalam bundel ini.');
        }

        $user = Auth::user();

        $log = KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => $user->id,
            'tipe' => $validated['tipe'],
            'aksi' => $validated['aksi'] ?? ($validated['tipe'] === 'revisi' ? 'Permintaan Revisi / Upload Tambahan' : 'Komentar'),
            'status_baru' => $validated['status_baru'] ?? $kendaraan->status,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        if ($request->hasFile('lampiran')) {
            $files = $request->file('lampiran');
            // Menangani Multiple File Upload
            foreach ($files as $f) {
                if ($f && $f->isValid()) {
                    $log->addMedia($f)->toMediaCollection('lampiran_log');
                }
            }
        }

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Komentar / Lampiran berhasil diunggah.');
    }

    /**
     * Kirim berkas revisi dari Wajib Pajak
     */
    public function submitRevision(Request $request, Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Anda tidak diizinkan melakukan tindakan ini.');
        }

        $request->validate([
            'revision_log_id' => 'required|exists:kendaraan_logs,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
        ]);

        $kendaraan = $pengajuan->kendaraans()->where('id', $request->kendaraan_id)->first();
        if (!$kendaraan) {
            return back()->with('error', 'Data kendaraan tidak ditemukan.');
        }

        $revisionLog = KendaraanLog::where('id', $request->revision_log_id)
            ->where('kendaraan_id', $kendaraan->id)
            ->first();
        if (!$revisionLog || !$revisionLog->isRevisionPending()) {
            return back()->with('error', 'Log permintaan revisi tidak valid atau sudah diselesaikan.');
        }

        // Update data Pemilik jika dikirim dan ada di revisi_fields
        $pemilik = $kendaraan->pemilik;
        $pemilikUpdated = false;
        $pemilikFields = ['nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik'];
        $pemilikDataToUpdate = [];

        foreach ($pemilikFields as $field) {
            if ($request->has($field) && in_array($field, $revisionLog->revisi_fields)) {
                $pemilikDataToUpdate[$field] = $request->input($field);
                $pemilikUpdated = true;
            }
        }
        if ($pemilik && $pemilikUpdated) {
            $pemilik->update($pemilikDataToUpdate);
        }

        // Update data Kendaraan jika dikirim dan ada di revisi_fields
        $kendaraanUpdated = false;
        $kendaraanFields = [
            'nrkb', 'merk_kendaraan', 'tipe_kendaraan', 'jenis_kendaraan', 'model_kendaraan',
            'tahun_pembuatan', 'isi_silinder', 'nomor_rangka', 'nomor_mesin', 'warna_kendaraan',
            'jenis_bahan_bakar', 'warna_tnkb', 'nomor_bpkb'
        ];
        $kendaraanDataToUpdate = [];

        foreach ($kendaraanFields as $field) {
            if ($request->has($field) && in_array($field, $revisionLog->revisi_fields)) {
                $kendaraanDataToUpdate[$field] = $request->input($field);
                $kendaraanUpdated = true;
            }
        }
        if ($kendaraanUpdated) {
            $kendaraan->update($kendaraanDataToUpdate);
        }

        // Update dokumen jika dikirim dan ada di revisi_fields
        $kategoriDokumen = [
            'surat_permohonan',
            'surat_pernyataan',
            'ktp',
            'bpkb',
            'tbpkp',
            'cek_fisik',
            'foto_ranmor',
            'stnk'
        ];

        foreach ($kategoriDokumen as $kategori) {
            $inputName = 'doc_' . $kategori;
            if ($request->hasFile($inputName) && in_array($kategori, $revisionLog->revisi_fields)) {
                $kendaraan->clearMediaCollection($kategori);
                $kendaraan->addMedia($request->file($inputName))->toMediaCollection($kategori);
            }
        }

        // Mark log revisi sebagai terselesaikan
        $revisionLog->update([
            'revisi_resolved_at' => now(),
        ]);

        // Buat log aktivitas baru untuk mencatat revisi diserahkan
        $map = [
            'nama_pemilik' => 'Nama Pemilik',
            'nik_pemilik' => 'NIK Pemilik',
            'alamat_pemilik' => 'Alamat Pemilik',
            'telp_pemilik' => 'Telp Pemilik',
            'email_pemilik' => 'Email Pemilik',
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
            'surat_permohonan' => 'Surat Permohonan',
            'surat_pernyataan' => 'Surat Pernyataan',
            'ktp' => 'KTP',
            'bpkb' => 'BPKB',
            'tbpkp' => 'TBPKP',
            'cek_fisik' => 'Cek Fisik',
            'foto_ranmor' => 'Foto Kendaraan',
            'stnk' => 'STNK',
        ];

        $revisedList = [];
        foreach ($revisionLog->revisi_fields as $f) {
            $inputName = in_array($f, $kategoriDokumen) ? 'doc_' . $f : $f;
            if ($request->has($inputName) || $request->hasFile($inputName)) {
                $revisedList[] = $map[$f] ?? $f;
            }
        }

        $revisedFieldsStr = implode(', ', $revisedList);
        $aksiText = "Revisi diserahkan oleh Wajib Pajak: {$revisedFieldsStr}";

        // Kembalikan status kendaraan ke 'pengajuan' agar dapat diverifikasi ulang
        $kendaraan->update(['status' => 'pengajuan']);

        KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => Auth::id(),
            'tipe' => 'system',
            'aksi' => $aksiText,
            'status_baru' => 'pengajuan',
            'catatan' => 'Berkas revisi telah berhasil diunggah / diperbarui oleh Wajib Pajak.',
        ]);

        return back()->with('success', 'Revisi berkas berhasil dikirim.');
    }

    /**
     * (Opsional) Hapus bundel pengajuan jika masih draft.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        // Validasi: Pastikan hanya pemilik yang bisa menghapus
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403);
        }

        // Cek status dinamis
        if ($pengajuan->status !== 'draft') {
            return redirect()->route('pengajuan.index')
                ->with('error', 'Pengajuan yang sudah diproses tidak dapat dihapus.');
        }

        // Hapus (Soft Delete)
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan ' . $pengajuan->nomor_pengajuan . ' berhasil dihapus.');
    }

    /**
     * Menampilkan halaman detail log tertentu untuh Penulis.
     */
    public function showLog(Pengajuan $pengajuan, $logId)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403);
        }

        $log = KendaraanLog::with(['user', 'kendaraan', 'media'])->findOrFail($logId);
        
        // Pastikan log milik bundel pengajuan ini
        if ($log->kendaraan->pengajuan_id !== $pengajuan->id) {
            abort(404, 'Log tidak ditemukan dalam pengajuan ini.');
        }

        return view('pengajuan.log_show', compact('pengajuan', 'log'));
    }
}