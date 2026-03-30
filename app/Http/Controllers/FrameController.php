<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SuratKeputusanController as SKController;
use App\Http\Controllers\SuratPengajuanController as SPController;

class FrameController extends Controller
{
    public function requestAccess(Request $request, $type, $category, $id)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::findOrFail($id);
        $config = $type == 'sk' ? SKController::getRegistry($category) : SPController::getRegistry($category);

        // 1. Cek Permission dari RBAC Spatie secara dinamis sesuai kategori
        if (!$user->canAny($config['permission'])) {
            return response()->json(['error' => 'Anda tidak memiliki izin cetak untuk kategori ini.'], 403);
        }

        // 2. Logika District (Future Development)
        // if ($user->unit_kerja !== $pengajuan->unit_kerja && !$user->hasRole('superadmin')) {
        //     return response()->json(['error' => 'Akses ditolak: Wilayah kerja berbeda.'], 403);
        // }

        // 3. Generate Temporary Signed URL (Valid 10 Menit)
        $temporaryUrl = URL::temporarySignedRoute(
            'frame.secure.render', 
            now()->addMinutes(10), 
            ['type' => $type, 'category' => $category, 'id' => $id]
        );
        return response()->json(['access_url' => $temporaryUrl]);
    }

    public function render(Request $request, $type, $category, $id)
    {
        $data = Pengajuan::with(['kendaraans'])->findOrFail($id);
        $config = $type == 'sk' ? SKController::getRegistry($category, $data) : SPController::getRegistry($category, $data);
        
        // Render View secara modular berdasarkan konfigurasi
        $view = $type == 'sk' ? SKController::render($request, $category, $id) : SPController::render($request, $category, $id);

        return $view;
    }
}