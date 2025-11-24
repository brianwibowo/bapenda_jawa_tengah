<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\KendaraanLog;
use App\Models\Kendaraan;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

class PengajuanController extends Controller
{
    /**
     * Halaman Buat Pengajuan Baru (dengan multiple kendaraan)
     */
    public function create()
    {
        return view('pengajuan.create');
    }

    /**
     * [Langkah 2 UX]
     * Menampilkan halaman daftar "bundel" pengajuan milik penulis.
     */
    public function index()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())
                              // Ambil relasi 'kendaraans' agar accessor status bisa bekerja
                              ->with('kendaraans:id,pengajuan_id,status')
                              ->withCount('kendaraans')
                              ->latest()
                              ->paginate(10);
                              
        return view('pengajuan.index', compact('pengajuans'));
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
            // Buat pengajuan baru jika belum ada
            $pengajuan = Pengajuan::create([
                'user_id' => Auth::id(),
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
            'nama_pemilik', 'nik_pemilik', 'alamat_pemilik', 'telp_pemilik', 'email_pemilik',
            'surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 'tbpkp',
            'cek_fisik', 'foto_ranmor', 'stnk', 'kendaraan_id', 'pengajuan_id'
        ]);

        $dataKendaraan['pemilik_id'] = $pemilik->id;
        $dataKendaraan['status'] = 'pengajuan';

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

        // Catat ke Log Histori
        KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id'      => Auth::id(),
            'aksi'         => $request->has('kendaraan_id') ? 'Kendaraan diupdate' : 'Kendaraan Diajukan',
            'status_baru'  => 'pengajuan',
            'catatan'      => 'Kendaraan (' . $kendaraan->merk_kendaraan . ') ' . ($request->has('kendaraan_id') ? 'diperbarui' : 'diajukan') . ' oleh ' . (Auth::user()->unit_kerja ?? Auth::user()->name),
        ]);

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
        ]);

        $pengajuan = Pengajuan::where('id', $request->pengajuan_id)
                             ->where('user_id', Auth::id())
                             ->firstOrFail();

        // Validasi semua kendaraan sudah lengkap
        $kendaraans = $pengajuan->kendaraans;
        if ($kendaraans->isEmpty()) {
            return redirect()->route('pengajuan.create')
                           ->with('error', 'Minimal harus ada 1 kendaraan untuk membuat pengajuan.');
        }

        // Cek kelengkapan data setiap kendaraan
        $incompleteKendaraans = [];
        foreach ($kendaraans as $kendaraan) {
            $kendaraan->load('pemilik');
            if (!$kendaraan->pemilik || !$kendaraan->nrkb || !$kendaraan->merk_kendaraan) {
                $incompleteKendaraans[] = $kendaraan->id;
            }
        }

        if (!empty($incompleteKendaraans)) {
            return redirect()->route('pengajuan.create', ['pengajuan_id' => $pengajuan->id])
                           ->with('error', 'Beberapa kendaraan belum lengkap datanya. Silakan lengkapi terlebih dahulu.')
                           ->with('incomplete_kendaraans', $incompleteKendaraans);
        }

        // Pengajuan sudah lengkap, redirect ke show (detail pengajuan)
        return redirect()->route('pengajuan.show', $pengajuan)
                         ->with('success', 'Pengajuan ' . $pengajuan->nomor_pengajuan . ' berhasil dibuat dengan ' . $kendaraans->count() . ' kendaraan.');
    }

    /**
     * Helper untuk upload dokumen
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
        ]);
        
        // Urutkan kendaraan berdasarkan created_at (yang pertama dibuat = nomor 1)
        $pengajuan->kendaraans = $pengajuan->kendaraans->sortBy('created_at')->values();
        
        // 3. Tampilkan view
        return view('pengajuan.show', compact('pengajuan'));
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
}