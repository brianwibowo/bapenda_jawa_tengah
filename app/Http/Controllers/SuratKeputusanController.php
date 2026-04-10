<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratKeputusanController extends Controller
{
    static public function getRegistry($type, $data = null)
    {
        $registries = [
            'pdf' => [
                'view' => 'pdf.view_sk',
                'prefix' => 'SK-',
                'permission' => 'view_own_sk', // Warga juga punya permission ini
            ],
            'form' => [
                'view' => 'form.create_sk',
                'permission' => 'create_sk',
            ]
        ];

        $config = $registries[$type] ?? abort(404);
        $config['filename'] = $data ? ($config['prefix'] ?? '') . $data->nomor_sk : 'DOKUMEN_SK';
        return $config;
    }

    static public function render(Request $request, $type, $id)
    {
        $sk = SuratKeputusan::with('pengajuan')->findOrFail($id);
        $config = SuratKeputusanController::getRegistry($type, $sk);

        if ($type == 'pdf') {
            return Pdf::loadView($config['view'], ['sk' => $sk])
                ->setPaper('a4', 'portrait')
                ->stream($config['filename'] . '.pdf');
        }

        return view($config['view'], ['sk' => $sk]);
    }
}
