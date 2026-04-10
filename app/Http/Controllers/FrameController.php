<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SuratKeputusanController as SKController;
use App\Http\Controllers\SuratPengajuanController as SPController;
use Symfony\Component\Console\Output\ConsoleOutput;

class FrameController extends Controller
{
    public function requestAccess(Request $request, $type, $category, $id)
    {
        $user = Auth::user();
        $pengajuan = Pengajuan::findOrFail($id);
        $config = $type == 'sk' ? SKController::getRegistry($category) : SPController::getRegistry($category);

        
        // 1. Cek Permission dari RBAC Spatie secara dinamis sesuai kategori


        if (!$user->canAny($config['permission'])) {
            return response()->json(['error' => 'Anda tidak memiliki izin akses untuk kategori ini.'], 403);
        } 
            
        if ($config['footer'] ?? false) {
            foreach ($config['footer'] as $key => $action) {
                // Cek apakah key 'route' ada agar tidak error
                if (isset($action['route'])) {
                    if ($action['route']['middleware'] == 'signed') {
                        // Pastikan Anda mengupdate langsung ke array asli menggunakan $key
                        $config['footer'][$key]['route']['url'] = URL::temporarySignedRoute(
                            $action['route']['name'], 
                            now()->addMinutes(10), 
                            ['id' => $id]
                        );
                    }
                }
            }

            $config['footer']['accept']['route'] = $config['footer']['accept']['route']['url'] ?? null;
            $config['footer']['reject']['route'] = $config['footer']['reject']['route']['url'] ?? null;
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
        return response()->json(['access_url' => $temporaryUrl, 'footer' => $config['footer'] ?? null]);
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