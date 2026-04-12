<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use App\Models\KendaraanLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\ConsoleOutput;

class SuratKeputusanController extends Controller
{
    static public function getRegistry($type, $id, $data = null)
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
                'footer' => [
                    'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                        'name' => 'admin.pengajuan.buat_sk',
                        'middleware' => 'signed'
                    ]],
                    'reject' => false,
                    'back' => ['label' => 'Kembali', 'class' => 'btn-secondary'],
                ]
            ]
        ];


        $config = $registries[$type] ?? abort(404);
        $config['filename'] = $data ? ($config['prefix'] ?? '') . $data->nomor_sk : 'DOKUMEN_SK';
        return $config;
    }

    static public function render(Request $request, $type, $id)
    {
        if ($type == 'pdf') {
            $sk = SuratKeputusan::with('pengajuan.kendaraans.pemilik')->findOrFail($id);
            $config = SuratKeputusanController::getRegistry($type, $sk->pengajuan_id, $sk);

            return Pdf::loadView($config['view'], ['sk' => $sk])
                ->setPaper('a4', 'portrait')
                ->stream($config['filename'] . '.pdf');
        }

        $pengajuan = Pengajuan::with(['kendaraans', 'suratKeputusans'])->findOrFail($id);
        $sk = $pengajuan->suratKeputusans->last();
        $config = SuratKeputusanController::getRegistry($type, $pengajuan->id, $sk);

        return view($config['view'], ['sk' => $sk, 'pengajuan' => $pengajuan]);
    }

    public function ajukan(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('kendaraans')->findOrFail($id);
        $baseLogTime = now();

        if (!$pengajuan->kendaraans->where('status', 'diproses')->count()) {
            return response()->json(['message' => 'Pengajuan tidak memiliki kendaraan dengan status "Diproses".'], 400);
        }

        $suratKeputusan = $pengajuan->suratKeputusans;
        $count = $suratKeputusan->count();

        // Cek untuk unit_kerja user sekarang, apakah sudah ada SK yang diajukan untuk unit kerja tersebut
        if ($suratKeputusan->where('unit_kerja', Auth::user()->unit_kerja)->isNotEmpty()) {
            // Map suratKeputusan with unit_kerja
            $skUnitKerja = $suratKeputusan->pluck('unit_kerja')->toArray();
            return response()->json(['message' => 'Surat Keputusan sudah diajukan oleh unit kerja: ' . implode(', ', $skUnitKerja)], 400);
        }

        // Update status kendaraan menjadi "Diajukan ke Bapenda/JR"
        foreach ($pengajuan->kendaraans as $kendaraan) {
            if (in_array($kendaraan->status, ['pengajuan', 'diproses'])) {
                // Simpan log perubahan status
                $logSk = new KendaraanLog([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Surat Keputusan',
                    'status_baru' => $kendaraan->status,
                    'tipe' => 'system',
                    'catatan' => 'Mengajukan Surat Keputusan Kendaraan Oleh ' . Auth::user()->name,
                ]);
                $logSk->created_at = $baseLogTime;
                $logSk->updated_at = $baseLogTime;
                $logSk->save();
            }
        }

        $console = new ConsoleOutput();
        $console->writeln("User " . Auth::user()->unit_kerja );
        $sk = SuratKeputusan::create([
            'pengajuan_id' => $id,
            'user_id' => Auth::id(),
            'unit_kerja' => Auth::user()->unit_kerja,
            'nomor_sk' => 'SK-' . strtoupper(uniqid()),
            'perihal' => "Keputusan untuk Pengajuan ID: $id",
            'isi_putusan' => "Surat Keputusan untuk Pengajuan ID: $id, dibuat oleh " . Auth::user()->name,
            'tanggal_ditetapkan' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($count+1 == 3) {
            foreach ($pengajuan->kendaraans as $kendaraan) {
                if ($kendaraan->status == 'diproses') {
                    $kendaraan->update(['status' => 'selesai']);
                    // Simpan log untuk status selesai.
                    $logSelesai = new KendaraanLog([
                        'kendaraan_id' => $kendaraan->id,
                        'user_id' => Auth::id(),
                        'aksi' => 'Pengajuan Selesai',
                        'status_baru' => 'selesai',
                        'tipe' => 'system',
                        'catatan' => 'Pengajuan Selesai Setelah Ketiga Surat Keputusan Ditetapkan.',
                    ]);
                    // Record 1 second after the SK log so chronological order is explicit.
                    $logSelesai->created_at = $baseLogTime->copy()->addSecond();
                    $logSelesai->updated_at = $baseLogTime->copy()->addSecond();
                    $logSelesai->save();
                }
                
            }
            
        }

        return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('success', 'Surat Keputusan berhasil dibuat dan diajukan. Silakan lanjutkan proses persetujuan.');
    }

}
