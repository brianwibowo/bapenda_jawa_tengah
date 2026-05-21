<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\SuratKeputusan;
use App\Models\SuratPengajuan;
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
        $config = $type == 'sk' ? SKController::getRegistry($category, $id) : SPController::getRegistry($category, $id);
        
        // 1. Cek Permission dari RBAC Spatie secara dinamis sesuai kategori
        if (isset($config['permission']) && !$user->canAny($config['permission'])) {
            return response()->json(['error' => 'Anda tidak memiliki izin akses untuk kategori ini.'], 403);
        } 

        // 2. Cek Role dari RBAC Spatie secara dinamis sesuai kategori
        if (isset($config['role']) && !$user->hasAnyRole($config['role'])) {
            return response()->json(['error' => 'Anda tidak memiliki peran yang diperlukan untuk kategori ini.'], 403);
        }

        if ($config['footer'] ?? false) {
            foreach ($config['footer'] as $key => $action) {
                // Cek apakah key 'route' ada agar tidak error
                if (isset($action['route'])) {
                    if (isset($action['route']['middleware']) && $action['route']['middleware'] == 'signed') {
                        $routeParams = $action['route']['params'] ?? ['id' => $id];
                        // Pastikan Anda mengupdate langsung ke array asli menggunakan $key
                        $config['footer'][$key]['route']['url'] = URL::temporarySignedRoute(
                            $action['route']['name'], 
                            now()->addMinutes(10), 
                            $routeParams
                        );
                    }
                }
            }

            if (isset($config['footer']['accept']['route'])) {
                $config['footer']['accept']['route'] = $config['footer']['accept']['route']['url'] ?? null;
            }
            if (isset($config['footer']['reject']['route'])) {
                $config['footer']['reject']['route'] = $config['footer']['reject']['route']['url'] ?? null;
            }
            if (isset($config['footer']['back']['route'])) {
                $config['footer']['back']['route'] = $config['footer']['back']['route']['url'] ?? null;
            }
        }

        $mode = $config['mode'] ?? 'iframe';
        
        // 3. Generate Temporary Signed URL (Valid 10 Menit)
        if ($mode === 'modal') {
            // Untuk mode modal, kirim URL signed yang akan di-fetch sebagai HTML
            $temporaryUrl = URL::temporarySignedRoute(
                'frame.secure.render',
                now()->addMinutes(10),
                ['type' => $type, 'category' => $category, 'id' => $id]
            );

            $submitRoute = 'admin.pengajuan.ajukan';
            $routeParams = ['id' => $id];

            if ($type === 'sp') {
                $user = Auth::user();
                $unitKerja = $user ? match (strtolower(trim((string) $user->unit_kerja))) {
                    'jr', 'jasa raharja', 'jasa_raharja' => 'Jasa Raharja',
                    'bapenda' => 'Bapenda',
                    'polda' => 'Polda',
                    default => trim((string) $user->unit_kerja),
                } : '';

                if (in_array($unitKerja, ['Bapenda', 'Jasa Raharja'])) {
                    $pengajuan = Pengajuan::find($id);
                    $currentSp = $pengajuan ? $pengajuan->getCurrentSuratPengajuan() : null;
                    if ($currentSp) {
                        $submitRoute = 'admin.pengajuan.sp.terima';
                        $routeParams = ['surat' => $currentSp->id];
                    }
                }
            } elseif ($type === 'sk') {
                $submitRoute = 'admin.pengajuan.buat_sk';
                $routeParams = ['id' => $id];
            }

            $temporaryUrlSubmit = URL::temporarySignedRoute(
                $submitRoute,
                now()->addMinutes(10),
                $routeParams
            );

            return response()->json([
                'mode'       => 'modal',
                'access_url' => $temporaryUrl,
                'footer'     => $config['footer'] ?? null,
                'submit_url' => $temporaryUrlSubmit,
            ]);
        } elseif (isset($request->data) && isset($request->data['pdf_url'])) {
            return response()->json([
                'mode'       => 'iframe',
                'access_url' => $request->data['pdf_url'],
                'footer'     => $config['footer'] ?? null,
            ]);
        } else {
            $temporaryUrl = URL::temporarySignedRoute(
                'frame.secure.render', 
                now()->addMinutes(10), 
                ['type' => $type, 'category' => $category, 'id' => $id]
            );
            return response()->json([
                'mode'       => 'iframe',
                'access_url' => $temporaryUrl,
                'footer'     => $config['footer'] ?? null,
            ]);
        }
    }

    public function render(Request $request, $type, $category, $id)
    {
        // Render View secara modular berdasarkan konfigurasi
        $view = $type == 'sk' ? SKController::render($request, $category, $id) : SPController::render($request, $category, $id);

        return $view;
    }
}