<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Pengajuan::with('user')->latest('updated_at');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Menampilkan detail satu pengajuan.
     */
    public function show(Pengajuan $pengajuan)
    {
        // PERBARUI CARA MENGAMBIL DOKUMEN
        // Kita ambil berdasarkan koleksi yang sudah kita buat
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
        
        return view('admin.pengajuan.show', compact('pengajuan', 'dokumen'));
    }

    /**
     * Update status pengajuan.
     */
    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'status' => 'required|in:pengajuan,diproses,selesai,ditolak', 
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.pengajuan.show', $pengajuan)->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Menghapus pengajuan (Soft Delete).
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
}