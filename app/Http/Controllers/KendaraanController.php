<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pengajuan;
use App\Models\KendaraanLog;
use App\Models\Pemilik; // <-- 1. IMPORT MODEL PEMILIK BARU
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr; // <-- 2. Import Array helper

class KendaraanController extends Controller
{
    /**
     * Menampilkan form untuk menambah kendaraan baru ke sebuah bundel pengajuan.
     */
    public function create(Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('kendaraan.create', compact('pengajuan'));
    }

    /**
     * [ROMBAK TOTAL]
     * Menyimpan data Pemilik & Kendaraan ke tabel terpisah.
     */
    public function store(Request $request, Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Validasi semua input (Pemilik + Kendaraan + Dokumen)
        $validatedData = $request->validate([
            // Validasi Pemilik
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|max:100', // NIK akan jadi kunci
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
            'warna_tnkb' => 'required|string|max:50',
            'nomor_bpkb' => 'required|string|max:255',

            // Validasi file (array, max 10MB)
            'surat_permohonan'   => 'required|array|min:1',
            'surat_permohonan.*' => 'required|mimes:pdf,docx|max:10240',
            'surat_pernyataan'   => 'required|array|min:1',
            'surat_pernyataan.*' => 'required|mimes:pdf,docx|max:10240',
            'ktp'   => 'required|array|min:1',
            'ktp.*' => 'required|mimes:pdf,docx,jpg,png|max:10240',
            'bpkb'   => 'required|array|min:1',
            'bpkb.*' => 'required|mimes:pdf,docx|max:10240',
            'tbpkp'   => 'required|array|min:1',
            'tbpkp.*' => 'required|mimes:pdf,docx|max:10240',
            'cek_fisik'   => 'required|array|min:1',
            'cek_fisik.*' => 'required|mimes:pdf,docx|max:10240',
            'foto_ranmor'   => 'required|array|min:1',
            'foto_ranmor.*' => 'required|image|max:10240',
            'stnk'   => 'required|array|min:1',
            'stnk.*' => 'required|mimes:pdf,docx|max:10240',
        ]);

        // 2. Pisahkan data Pemilik
        $dataPemilik = [
            'nama_pemilik' => $validatedData['nama_pemilik'],
            'nik_pemilik' => $validatedData['nik_pemilik'],
            'alamat_pemilik' => $validatedData['alamat_pemilik'],
            'telp_pemilik' => $validatedData['telp_pemilik'],
            'email_pemilik' => $validatedData['email_pemilik'],
        ];

        // 3. Cari atau Buat Pemilik baru (berdasarkan NIK)
        // updateOrCreate:
        // - Jika NIK sudah ada, update datanya.
        // - Jika NIK belum ada, buat record baru.
        $pemilik = Pemilik::updateOrCreate(
            ['nik_pemilik' => $validatedData['nik_pemilik']], // Kunci unik untuk mencari
            $dataPemilik  // Data untuk di-update atau di-create
        );

        // 4. Pisahkan data Kendaraan (ambil semua KECUALI data pemilik & file)
        $dataKendaraan = Arr::except($validatedData, [
            'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
            'surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 'tbpkp',
            'cek_fisik', 'foto_ranmor', 'stnk'
        ]);

        // 5. Tambahkan ID pemilik dan status default
        $dataKendaraan['pemilik_id'] = $pemilik->id;
        $dataKendaraan['status'] = 'pengajuan';

        // 6. Buat record Kendaraan baru, hubungkan dengan pengajuan
        $kendaraan = $pengajuan->kendaraans()->create($dataKendaraan);

        // 7. Upload dan tempelkan semua dokumen ke record KENDARAAN
        $this->uploadDokumen($request, $kendaraan);
        
        // 8. Catat ke Log Histori (per KENDARAAN)
        $user = Auth::user();
        KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan Diajukan',
            'status_baru'  => 'pengajuan',
            'catatan'      => 'Kendaraan (' . $kendaraan->merk_kendaraan . ') diajukan oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        // 9. Redirect kembali ke halaman detail bundel
        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Kendaraan ' . $kendaraan->nrkb . ' berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit detail kendaraan. (Hanya Penulis)
     */
    public function edit(Kendaraan $kendaraan)
    {
        if (Auth::id() !== $kendaraan->pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($kendaraan->status, ['pengajuan'])) {
            return redirect()->route('pengajuan.show', $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat diedit karena sudah diproses.');
        }
        
        // Load relasi media DAN pemilik (untuk mengisi form)
        $kendaraan->load(['media', 'pemilik']); 
        
        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * [ROMBAK TOTAL]
     * Menyimpan perubahan pada data Pemilik & Kendaraan.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        if (Auth::id() !== $kendaraan->pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($kendaraan->status, ['pengajuan'])) {
            return redirect()->route('pengajuan.show', $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat diedit karena sudah diproses.');
        }

        // 1. Validasi
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
            'warna_tnkb' => 'required|string|max:50',
            'nomor_bpkb' => 'required|string|max:255',

            // Validasi file opsional
            'surat_permohonan'   => 'nullable|array',
            'surat_permohonan.*' => 'required_with:surat_permohonan|mimes:pdf,docx|max:10240',
            'surat_pernyataan'   => 'nullable|array',
            'surat_pernyataan.*' => 'required_with:surat_pernyataan|mimes:pdf,docx|max:10240',
            'ktp'   => 'nullable|array',
            'ktp.*' => 'required_with:ktp|mimes:pdf,docx,jpg,png|max:10240',
            'bpkb'   => 'nullable|array',
            'bpkb.*' => 'required_with:bpkb|mimes:pdf,docx|max:10240',
            'tbpkp'   => 'nullable|array',
            'tbpkp.*' => 'required_with:tbpkp|mimes:pdf,docx|max:10240',
            'cek_fisik'   => 'nullable|array',
            'cek_fisik.*' => 'required_with:cek_fisik|mimes:pdf,docx|max:10240',
            'foto_ranmor'   => 'nullable|array',
            'foto_ranmor.*' => 'required_with:foto_ranmor|image|max:10240',
            'stnk'   => 'nullable|array',
            'stnk.*' => 'required_with:stnk|mimes:pdf,docx|max:10240',
        ]);

        // 2. Pisahkan & Update data Pemilik
        $dataPemilik = [
            'nama_pemilik' => $validatedData['nama_pemilik'],
            'nik_pemilik' => $validatedData['nik_pemilik'],
            'alamat_pemilik' => $validatedData['alamat_pemilik'],
            'telp_pemilik' => $validatedData['telp_pemilik'],
            'email_pemilik' => $validatedData['email_pemilik'],
        ];
        
        // Asumsi NIK tidak berubah saat edit, update data pemilik yang terhubung
        $kendaraan->pemilik->update($dataPemilik);
        
        // (Alternatif jika NIK bisa berubah: 
        // $pemilik = Pemilik::updateOrCreate(['nik_pemilik' => $dataPemilik['nik_pemilik']], $dataPemilik);
        // $kendaraan->pemilik_id = $pemilik->id; 
        // )

        // 3. Pisahkan & Update data Kendaraan
        $dataKendaraan = Arr::except($validatedData, [
            'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
            'surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 'tbpkp',
            'cek_fisik', 'foto_ranmor', 'stnk'
        ]);
        
        $kendaraan->update($dataKendaraan);

        // 4. Update dokumen
        $this->uploadDokumen($request, $kendaraan, true); // true = mode update
        
        // 5. Catat ke Log Histori (per KENDARAAN)
        $user = Auth::user();
        KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan diupdate',
            'status_baru'  => $kendaraan->status, // status tidak berubah saat edit
            'catatan'      => 'Data kendaraan (' . $kendaraan->merk_kendaraan . ') diperbarui oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        // 6. Redirect kembali ke halaman detail bundel
        return redirect()->route('pengajuan.show', $kendaraan->pengajuan)->with('success', 'Kendaraan ' . $kendaraan->nrkb . ' berhasil diperbarui.');
    }

    /**
     * Menghapus satu kendaraan dari bundel pengajuan.
     */
    public function destroy(Request $request, Kendaraan $kendaraan)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']);
        $isPemilik = ($user->id === $kendaraan->pengajuan->user_id);

        if (!$isAdmin && !$isPemilik) {
            abort(403, 'Akses ditolak.');
        }
        
        if (!in_array($kendaraan->status, ['pengajuan'])) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Kendaraan tidak dapat dihapus karena sudah diproses.'
                ], 422);
            }
            $redirectRoute = $isAdmin ? 'admin.pengajuan.show' : 'pengajuan.show';
            return redirect()->route($redirectRoute, $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat dihapus karena sudah diproses.');
        }

        $pengajuan = $kendaraan->pengajuan;
        $nrkb = $kendaraan->nrkb;
        
        // Buat log DULU, baru hapus
        KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan dihapus: ' . $nrkb,
            'status_baru'  => $kendaraan->status,
            'catatan'      => 'Kendaraan (' . $nrkb . ') dihapus oleh ' . ($user->unit_kerja ?? $user->name),
        ]);
        
        $kendaraan->delete(); // Hapus kendaraan
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kendaraan ' . $nrkb . ' berhasil dihapus.'
            ]);
        }

        $redirectRoute = $isAdmin ? 'admin.pengajuan.show' : 'pengajuan.show';
        return redirect()->route($redirectRoute, $pengajuan)->with('success', 'Kendaraan ' . $nrkb . ' berhasil dihapus.');
    }


    /**
     * Helper Function untuk meng-handle upload dokumen. (Tidak berubah)
     */
    private function uploadDokumen(Request $request, Kendaraan $kendaraan, $isUpdate = false)
    {
        $kategoriDokumen = [
            'surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 
            'tbpkp', 'cek_fisik', 'foto_ranmor', 'stnk'
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
     * [Level 3 UX] Menampilkan detail read-only dari satu kendaraan.
     */
    public function show(Kendaraan $kendaraan)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['admin', 'superadmin']);
        $isPemilik = ($user->id === $kendaraan->pengajuan->user_id);

        if (!$isAdmin && !$isPemilik) {
            abort(403, 'Akses ditolak.');
        }
        
        // Load relasi media, logs, DAN pemilik
        $kendaraan->load(['media', 'logs.user', 'pemilik']); 
        
        return view('kendaraan.show', compact('kendaraan'));
    }
}