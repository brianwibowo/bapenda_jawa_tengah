<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanLog; // Import model Log
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * [Langkah 2 UX]
     * Menampilkan halaman daftar "bundel" pengajuan milik penulis.
     */
    public function index()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())
                              ->withCount('kendaraans') // Hitung jumlah kendaraan
                              ->latest()
                              ->paginate(10);
                              
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * [Langkah 1 UX]
     * Aksi dari tombol "Buat Nomor Pengajuan Baru".
     * Tidak ada form 'create', langsung 'store' untuk buat bundel kosong.
     */
    public function store(Request $request)
    {
        // 1. Buat record Pengajuan (bundel) baru yang kosong
        // Model Pengajuan akan otomatis mengisi 'nomor_pengajuan' dan 'status' = 'draft'
        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
        ]);

        // 2. Buat Log Pertama (Histori)
        $user = Auth::user();
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $user->id,
            'aksi'         => 'Nomor Pengajuan Dibuat',
            'status_baru'  => 'draft',
            'catatan'      => 'Nomor pengajuan dibuat oleh ' . ($user->unit_kerja ?? $user->name),
        ]);

        // 3. Redirect kembali ke halaman index
        return redirect()->route('pengajuan.index')
                         ->with('success', 'Nomor Pengajuan baru (' . $pengajuan->nomor_pengajuan . ') berhasil dibuat. Silakan tambahkan kendaraan.');
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
            'kendaraans', // Daftar kendaraan di dalam bundel ini
            'logs.user'   // Histori log dari bundel ini
        ]);
        
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

        // Validasi: Hanya boleh hapus jika status masih 'draft'
        if ($pengajuan->status !== 'draft') {
             return redirect()->route('pengajuan.index')
                             ->with('error', 'Pengajuan yang sudah dikirim tidak dapat dihapus.');
        }

        // Hapus (Soft Delete)
        $pengajuan->delete();
        
        return redirect()->route('pengajuan.index')
                         ->with('success', 'Pengajuan ' . $pengajuan->nomor_pengajuan . ' berhasil dihapus.');
    }

    /**
     * Method create() (GET /pengajuan/buat) sudah tidak diperlukan lagi
     * karena kita pakai alur UX baru.
     */
}