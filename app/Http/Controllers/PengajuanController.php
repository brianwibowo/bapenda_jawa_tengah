<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // Pastikan ini ada

class PengajuanController extends Controller
{
    /**
     * Menampilkan halaman riwayat pengajuan milik penulis.
     */
    public function index()
    {
        // Ambil data pengajuan HANYA milik user yang sedang login
        $pengajuans = Pengajuan::where('user_id', Auth::id())
                              ->latest() // Urutkan dari yang terbaru
                              ->paginate(10); // Batasi 10 per halaman

        // Tampilkan view 'riwayat'
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     */
    public function create()
    {
        return view('pengajuan.create');
    }

    /**
     * Menyimpan pengajuan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validasi data identitas
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
            'tahun_pembuatan' => 'required|digits:4|integer|min:1900',
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

        // Gabungkan data user_id dan status
        $dataToCreate = array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'pengajuan',
        ]);

        // Buat pengajuan baru
        $pengajuan = Pengajuan::create($dataToCreate);

        // Upload semua file ke koleksi terpisah
        if ($request->hasFile('surat_permohonan')) {
            foreach ($request->file('surat_permohonan') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('surat_permohonan');
            }
        }
        if ($request->hasFile('surat_pernyataan')) {
            foreach ($request->file('surat_pernyataan') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('surat_pernyataan');
            }
        }
        if ($request->hasFile('ktp')) {
            foreach ($request->file('ktp') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('ktp');
            }
        }
        if ($request->hasFile('bpkb')) {
            foreach ($request->file('bpkb') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('bpkb');
            }
        }
        if ($request->hasFile('tbpkp')) {
            foreach ($request->file('tbpkp') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('tbpkp');
            }
        }
        if ($request->hasFile('cek_fisik')) {
            foreach ($request->file('cek_fisik') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('cek_fisik');
            }
        }
         if ($request->hasFile('foto_ranmor')) {
            foreach ($request->file('foto_ranmor') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('foto_ranmor');
            }
        }
        if ($request->hasFile('stnk')) {
            foreach ($request->file('stnk') as $file) {
                $pengajuan->addMedia($file)->toMediaCollection('stnk');
            }
        }

        // Arahkan ke halaman riwayat
        return redirect()->route('pengajuan.index')->with('success', 'Berkas berhasil diajukan!');
    }

    /**
     * METHOD BARU: Menampilkan detail pengajuan milik penulis.
     */
    public function show(Pengajuan $pengajuan)
    {
        // Pastikan hanya pemilik pengajuan yang bisa melihat
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403, 'Anda tidak diizinkan melihat pengajuan ini.'); // Atau redirect ke halaman lain
        }

        $pengajuan->load(['logs.user', 'logs.media']);

        // Ambil dokumen berdasarkan koleksi
        $dokumen = [
            'surat_permohonan' => $pengajuan->getMedia('surat_permohonan'),
            'surat_pernyataan' => $pengajuan->getMedia('surat_pernyataan'),
            'ktp'               => $pengajuan->getMedia('ktp'),
            'bpkb'              => $pengajuan->getMedia('bpkb'),
            'tbpkp'             => $pengajuan->getMedia('tbpkp'),
            'cek_fisik'         => $pengajuan->getMedia('cek_fisik'),
            'foto_ranmor'       => $pengajuan->getMedia('foto_ranmor'),
            'stnk'              => $pengajuan->getMedia('stnk'),
        ];

        // Tampilkan view detail
        return view('pengajuan.show', compact('pengajuan', 'dokumen'));
    }
}