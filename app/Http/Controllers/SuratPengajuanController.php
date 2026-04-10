<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratPengajuanController extends Controller
{
    static public function getRegistry($type, $data = null)
    {
        $registries = [
            'pdf' => [
                'view' => 'pdf.view_sp',
                'prefix' => 'SP-',
                'permission' => ['view_dokumen_surat_pengajuan', 'view_dokumen_surat_pengajuan'],
            ],
            'form' => [
                'view' => 'form.create_sp',
                'permission' => ['create_pdf_pengajuan', 'create_pdf_balasan_polda', 'create_pdf_pengajuan_bapenda_jr'],
            ]
        ];

        $config = $registries[$type] ?? abort(404);
        $config['filename'] = $data ? ($config['prefix'] ?? '') . $data->nomor_sp : 'DOKUMEN_SP';
        return $config;
    }

    static public function render(Request $request, $type, $id)
    {
        $sp = SuratPengajuan::with('pengajuan.kendaraans.pemilik')->findOrFail($id);
        $config = SuratPengajuanController::getRegistry($type, $sp);

        if ($type == 'pdf') {
            return Pdf::loadView($config['view'], ['sp' => $sp])
                ->setPaper('a4', 'portrait')
                ->stream($config['filename'] . '.pdf');
        }

        return view($config['view'], ['sp' => $sp]);
    }

    public function storeLog(Request $request, Pengajuan $pengajuan)
    {
        if (Auth::id() !== $pengajuan->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => 'required|in:komentar,revisi,admin,system',
            'aksi' => 'nullable|string|max:255',
            'status_baru' => 'nullable|in:pengajuan,diproses,selesai,ditolak',
            'catatan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:5120',
        ]);

        $kendaraan = $pengajuan->kendaraans->firstWhere('id', $validated['kendaraan_id']);
        if (!$kendaraan) {
            return redirect()->route('pengajuan.show', $pengajuan)->with('error', 'Kendaraan tidak ditemukan dalam bundel ini.');
        }

        $user = Auth::user();

        $log = \App\Models\KendaraanLog::create([
            'kendaraan_id' => $kendaraan->id,
            'user_id' => $user->id,
            'tipe' => $validated['tipe'],
            'aksi' => $validated['aksi'] ?? ($validated['tipe'] === 'revisi' ? 'Permintaan Revisi / Upload Tambahan' : 'Komentar'),
            'status_baru' => $validated['status_baru'] ?? $kendaraan->status,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        if ($request->hasFile('lampiran')) {
            $files = $request->file('lampiran');
            // Menangani Multiple File Upload
            foreach ($files as $f) {
                if ($f && $f->isValid()) {
                    $log->addMedia($f)->toMediaCollection('lampiran_log');
                }
            }
        }

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Komentar / Lampiran berhasil diunggah.');
    }

}
