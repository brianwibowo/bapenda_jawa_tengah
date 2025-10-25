<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Menampilkan detail satu pengajuan beserta log historinya.
     */
    public function show(Pengajuan $pengajuan)
    {
        // Load relasi logs beserta user yang melakukan log + lampiran log
        $pengajuan->load(['logs.user', 'logs.media']);

        // Ambil dokumen pengajuan utama
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

        // $opsiAksi dihapus karena tidak relevan lagi

        // Kirim data ke view
        return view('admin.pengajuan.show', compact('pengajuan', 'dokumen')); // Kirim tanpa $opsiAksi
    }

    /**
     * Method BARU: Menyimpan Log Aksi dan Mengupdate Status Pengajuan.
     */
    public function storeLogAndUpdateStatus(Request $request, Pengajuan $pengajuan)
    {
        // 1. Validasi input (tanpa 'aksi')
        $request->validate([
            // 'aksi' dihapus dari validasi
            'status_baru' => 'required|in:pengajuan,diproses,selesai,ditolak', // Pastikan semua status valid ada di sini
            'catatan'     => 'nullable|string',
            'lampiran'    => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240',
        ]);

        // 2. Buat deskripsi Aksi secara dinamis
        $statusTerpilih = $request->status_baru;
        $unitKerja = Auth::user()->unit_kerja ?? Auth::user()->name; // Ambil unit kerja atau nama jika unit kosong
        $aksiDeskripsi = '';

        switch ($statusTerpilih) {
            case 'diproses':
                $aksiDeskripsi = 'Diproses oleh ' . $unitKerja;
                break;
            case 'selesai':
                $aksiDeskripsi = 'Diselesaikan oleh ' . $unitKerja;
                break;
            case 'ditolak':
                $aksiDeskripsi = 'Ditolak oleh ' . $unitKerja;
                break;
            case 'pengajuan': // Ini mungkin tidak umum dilakukan admin, tapi jaga-jaga
                 $aksiDeskripsi = 'Status diubah ke Baru oleh ' . $unitKerja;
                 break;
            default:
                $aksiDeskripsi = 'Status diupdate oleh ' . $unitKerja; // Fallback
        }

        // 3. Buat record Log baru dengan aksi dinamis
        $log = PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => Auth::id(),
            'aksi'         => $aksiDeskripsi, // <-- Gunakan deskripsi dinamis
            'status_baru'  => $request->status_baru,
            'catatan'      => $request->catatan,
        ]);

        // 4. Handle upload lampiran (jika ada)
        if ($request->hasFile('lampiran')) {
            $log->addMediaFromRequest('lampiran')->toMediaCollection('lampiran_log');
        }

        // 5. Update status terakhir di tabel pengajuans
        $pengajuan->update([
            'status'         => $request->status_baru,
            'catatan_admin'  => $request->catatan,
        ]);

        // 6. Redirect kembali
        return redirect()->route('admin.pengajuan.show', $pengajuan)->with('success', 'Aksi berhasil dicatat dan status diperbarui.');
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