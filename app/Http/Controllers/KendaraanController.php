<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller
{
    /**
     * [Langkah 6 UX]
     * Menampilkan form untuk menambah kendaraan baru ke sebuah bundel pengajuan.
     */
    public function create(Pengajuan $pengajuan)
    {
        // Keamanan: Pastikan hanya pemilik bundel yang bisa menambah
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('kendaraan.create', compact('pengajuan'));
    }

    /**
     * [Langkah 8 UX]
     * Menyimpan kendaraan baru ke database dan menempelkannya ke bundel pengajuan.
     */
    public function store(Request $request, Pengajuan $pengajuan)
    {
        // Keamanan: Pastikan hanya pemilik bundel yang bisa menambah
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Validasi semua input (Pemilik + Kendaraan + Dokumen)
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|max:100',
            'alamat_pemilik' => 'required|string',
            'telp_pemilik' => 'required|string|max:20',
            'email_pemilik' => 'required|email|max:255',
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

        // 2. Buat record Kendaraan baru
        $kendaraan = $pengajuan->kendaraans()->create($validated);

        // 3. Upload dan tempelkan semua dokumen ke record KENDARAAN
        $this->uploadDokumen($request, $kendaraan);

        // 4. Update status bundel Pengajuan (jika masih 'draft')
        if ($pengajuan->status == 'draft') {
            $pengajuan->update(['status' => 'pengajuan']);
        }
        
        // 5. Catat ke Log Histori
        $user = Auth::user();
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan ditambahkan: ' . $kendaraan->nrkb,
            'status_baru'  => $pengajuan->status,
            'catatan'      => 'Kendaraan (' . $kendaraan->merk_kendaraan . ' ' . $kendaraan->tipe_kendaraan . ') ditambahkan oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        // 6. Redirect kembali ke halaman detail bundel
        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Kendaraan ' . $kendaraan->nrkb . ' berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit detail kendaraan.
     */
    public function edit(Kendaraan $kendaraan)
    {
        if (Auth::id() !== $kendaraan->pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($kendaraan->pengajuan->status, ['draft', 'pengajuan'])) {
            return redirect()->route('pengajuan.show', $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat diedit karena pengajuan sudah diproses.');
        }
        $kendaraan->load('media');
        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Menyimpan perubahan pada kendaraan yang diedit.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        if (Auth::id() !== $kendaraan->pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($kendaraan->pengajuan->status, ['draft', 'pengajuan'])) {
            return redirect()->route('pengajuan.show', $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat diedit karena pengajuan sudah diproses.');
        }

        // 1. Validasi
         $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|max:100',
            'alamat_pemilik' => 'required|string',
            'telp_pemilik' => 'required|string|max:20',
            'email_pemilik' => 'required|email|max:255',
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

            // File bersifat opsional saat update
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

        // 2. Update data kendaraan
        $kendaraan->update($validated);

        // 3. Update dokumen
        $this->uploadDokumen($request, $kendaraan, true);
        
        // 4. Catat ke Log Histori
        $user = Auth::user();
        PengajuanLog::create([
            'pengajuan_id' => $kendaraan->pengajuan_id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan diupdate: ' . $kendaraan->nrkb,
            'status_baru'  => $kendaraan->pengajuan->status,
            'catatan'      => 'Data kendaraan (' . $kendaraan->merk_kendaraan . ') diperbarui oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        // 5. Redirect kembali ke halaman detail bundel
        return redirect()->route('pengajuan.show', $kendaraan->pengajuan)->with('success', 'Kendaraan ' . $kendaraan->nrkb . ' berhasil diperbarui.');
    }

    /**
     * Menghapus satu kendaraan dari bundel pengajuan.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        if (Auth::id() !== $kendaraan->pengajuan->user_id) {
            abort(403, 'Akses ditolak.');
        }
        if (!in_array($kendaraan->pengajuan->status, ['draft', 'pengajuan'])) {
            return redirect()->route('pengajuan.show', $kendaraan->pengajuan)
                             ->with('error', 'Kendaraan tidak dapat dihapus karena pengajuan sudah diproses.');
        }

        $pengajuan = $kendaraan->pengajuan;
        $nrkb = $kendaraan->nrkb;

        $kendaraan->delete();

        if ($pengajuan->kendaraans()->count() == 0) {
            $pengajuan->update(['status' => 'draft']);
            $statusLog = 'draft';
        } else {
            $statusLog = $pengajuan->status;
        }
        
        $user = Auth::user();
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Kendaraan dihapus: ' . $nrkb,
            'status_baru'  => $statusLog,
            'catatan'      => 'Kendaraan (' . $nrkb . ') dihapus dari pengajuan oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Kendaraan ' . $nrkb . ' berhasil dihapus.');
    }


    /**
     * Helper Function untuk meng-handle upload dokumen.
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
     * [Level 3 UX - METHOD YANG HILANG]
     * Menampilkan detail read-only dari satu kendaraan.
     */
    public function show(Kendaraan $kendaraan)
    {
        // Keamanan: Pastikan hanya pemilik ATAU admin yang bisa melihat
        $isAdmin = Auth::user()->hasRole(['admin', 'superadmin']);
        $isPemilik = Auth::id() === $kendaraan->pengajuan->user_id;

        if (!$isAdmin && !$isPemilik) {
            abort(403, 'Akses ditolak.');
        }
        
        // Load semua koleksi media
        $kendaraan->load('media');
        
        return view('kendaraan.show', compact('kendaraan'));
    }
}