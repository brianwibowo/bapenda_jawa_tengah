<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FrameController extends Controller
{
    /**
     * Definisi Konfigurasi PDF secara Terpusat
     * Ini membuat penambahan kategori baru sangat mudah.
     */
    protected function getPdfRegistry($category, $data = null)
    {
        $registry = [
            'pengajuan' => [
                'view' => 'pdf.view_pengajuan',
                'prefix' => 'SPOPD-',
                'permission' => 'view_own_pengajuan',
                'filename' => $data ? $data->nomor_pengajuan : ''
            ],
            'sk' => [
                'view' => 'pdf.sk',
                'prefix' => 'SKP-',
                'permission' => 'view_own_sk',
                'filename' => $data ? $data->nomor_pengajuan : ''
            ],
        ];

        return $registry[$category] ?? abort(404, 'Kategori laporan tidak ditemukan');
    }

    public function requestAccess(Request $request, $category, $id)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::findOrFail($id);
        $config = $this->getPdfRegistry($category);

        // 1. Cek Permission dari RBAC Spatie secara dinamis sesuai kategori
        if (!$user->can($config['permission'])) {
            return response()->json(['error' => 'Anda tidak memiliki izin cetak untuk kategori ini.'], 403);
        }

        // 2. Logika District (Future Development)
        // if ($user->unit_kerja !== $pengajuan->unit_kerja && !$user->hasRole('superadmin')) {
        //     return response()->json(['error' => 'Akses ditolak: Wilayah kerja berbeda.'], 403);
        // }

        // 3. Generate Temporary Signed URL (Valid 10 Menit)
        $temporaryUrl = URL::temporarySignedRoute(
            'pdf.secure.render', 
            now()->addMinutes(10), 
            ['category' => $category, 'id' => $id]
        );

        return response()->json(['access_url' => $temporaryUrl]);
    }

    public function render(Request $request, $category, $id)
    {
        $data = Pengajuan::with(['kendaraans'])->findOrFail($id);
        $config = $this->getPdfRegistry($category, $data);
        
        // Render View secara modular berdasarkan konfigurasi
        $pdf = Pdf::loadView($config['view'], [
            'pengajuan' => $data,
            'kendaraans' => $data->kendaraans,
            'pemilik' => $data->kendaraans->first()->pemilik ?? null
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($config['prefix'] . $config['filename'] . '.pdf');
    }
}