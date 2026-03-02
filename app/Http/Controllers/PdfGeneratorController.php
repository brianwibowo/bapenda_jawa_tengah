<?php
namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pengajuan;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGeneratorController extends Controller
{

    public function finalPJN($pengajuan_id)
    {
        // 1. Ambil data dari database berdasarkan ID
        // (Pastikan relasi pemilik sudah diload)
        $pengajuan = Pengajuan::with('kendaraans.pemilik')->findOrFail($pengajuan_id);
        $kendaraans = $pengajuan->kendaraans;
        $pemilik = $kendaraans->first()->pemilik;

        // 2. Lempar ke View cetak_spopd.blade.php
        $pdf = Pdf::loadView('pdf.view', [
            'pengajuan' => $pengajuan,
            'kendaraans' => $kendaraans,
            'pemilik' => $pemilik
        ]);

        // 3. Tampilkan PDF
        return $pdf->stream('Preview-' . $pengajuan->nomor_pengajuan . '.pdf');
    }

}