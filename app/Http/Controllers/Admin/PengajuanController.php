<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Kendaraan;
use App\Models\KendaraanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * Level 1: Menampilkan daftar BUNDEL pengajuan
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Pengajuan::with(['user'])
                          ->withCount('kendaraans')
                          ->latest('updated_at');

        if ($status) {
            $query->whereHas('kendaraans', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('kendaraans', function ($sq) use ($search) {
                      $sq->where('nrkb', 'like', "%{$search}%")
                         ->orWhere('nama_pemilik', 'like', "%{$search}%");
                  });
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();
        
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Level 2: Menampilkan dasbor BUNDEL (tabel kendaraan interaktif)
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load([
            'kendaraans', 
            'kendaraans.media',
            'kendaraans.logs.user', // Ambil log per kendaraan
            'kendaraans.logs.media' // Ambil lampiran log per kendaraan
        ]);
        
        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    /**
     * METHOD DIPERBARUI: (Aksi dari tombol "Simpan Semua Perubahan")
     * Sekarang juga menangani UPLOAD LAMPIRAN
     */
    public function batchUpdateKendaraanStatus(Request $request, Pengajuan $pengajuan)
    {
        // 1. Validasi input (termasuk 'lampiran')
        $request->validate([
            'status'      => 'required|array',
            'status.*'    => 'required|in:pengajuan,diproses,selesai,ditolak',
            'catatan'     => 'nullable|array',
            'catatan.*'   => 'nullable|string',
            'lampiran'    => 'nullable|array', // Validasi array lampiran
            'lampiran.*'  => 'nullable|file|mimes:pdf,jpg,png,docx|max:10240', // Validasi tiap file
        ]);

        $adminUser = Auth::user();
        $statuses = $request->input('status', []);
        $catatans = $request->input('catatan', []);
        $lampirans = $request->file('lampiran', []); // Ambil file lampiran
        
        $logCount = 0;

        // 2. Loop setiap status yang dikirim dari form
        foreach ($statuses as $kendaraanId => $newStatus) {
            
            $kendaraan = Kendaraan::find($kendaraanId);
            if (!$kendaraan || $kendaraan->pengajuan_id !== $pengajuan->id) {
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
                    case 'diproses': $aksiDeskripsi = 'Diproses oleh ' . $adminUser->unit_kerja; break;
                    case 'selesai': $aksiDeskripsi = 'Diselesaikan oleh ' . $adminUser->unit_kerja; break;
                    case 'ditolak': $aksiDeskripsi = 'Ditolak oleh ' . $adminUser->unit_kerja; break;
                    case 'pengajuan': $aksiDeskripsi = 'Dikembalikan ke status "Baru" oleh ' . $adminUser->unit_kerja; break;
                }
                // Jika status tidak berubah tapi ada catatan/lampiran, buat aksi default
                if (empty($aksiDeskripsi)) {
                    $aksiDeskripsi = 'Catatan/Lampiran ditambahkan oleh ' . $adminUser->unit_kerja;
                }

                // 5. Buat LOG per KENDARAAN
                $log = KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id'      => $adminUser->id,
                    'aksi'         => $aksiDeskripsi,
                    'status_baru'  => $newStatus,
                    'catatan'      => $catatan,
                ]);

                // 6. Handle upload lampiran (jika ada) dan tempelkan ke LOG
                if ($lampiranFile) {
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
        $pengajuan->delete();
        
        return redirect()->route('admin.pengajuan.index')
                         ->with('success', 'Bundel pengajuan ' . $pengajuan->nomor_pengajuan . ' berhasil dihapus.');
    }
}