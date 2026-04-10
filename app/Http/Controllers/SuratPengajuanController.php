<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\KendaraanLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SuratPengajuanController extends Controller
{

    protected $persetujuanDefault = [
        ['instansi' => 'bapenda', 'status' => 'pending', 'user_id' => null],
        ['instansi' => 'jasa-raharja', 'status' => 'pending', 'user_id' => null],
    ];

    protected $persetujuanPolda = [
        ['instansi' => 'polda', 'status' => 'pending']
    ];

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
                'footer' => [
                    'accept' => ['label' => 'Setujui', 'class' => 'btn-success', 'route'=> [
                        'name' => 'admin.pengajuan.ajukan',
                        'middleware' => 'signed'
                    ]],
                    'reject' => ['label' => 'Tolak', 'class' => 'btn-danger', 'action' => 'exit']
                ]
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

    public function index()
    {
        $user = Auth::user();
        $query = SuratPengajuan::with('pengajuan');

        // Filter berdasarkan unit kerja dan role
        if ($user->hasRole('samsat')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Samsat');
            });
        } elseif ($user->hasRole('polda')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Polda');
            });
        } elseif ($user->hasRole('bapenda')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Bapenda');
            });
        } elseif ($user->hasRole('jasa-raharja')) {
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('unit_kerja', 'Jasa Raharja');
            });
        }

        $suratPengajuans = $query->get();
        return view('admin.surat.view_pengajuan', compact('suratPengajuans'));
    }

    public function ajukan(Request $request, $id)
    {
        if (Auth::user()->hasRole('samsat')) {
            return $this->ajukanPolda($request, $id);
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function ajukanPolda(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('kendaraans')->findOrFail($id);

        // Validasi: Pastikan pengajuan memiliki kendaraan dengan status "Diajukan"
        if (!$pengajuan->kendaraans->where('status', 'pengajuan')->count()) {
            return response()->json(['message' => 'Pengajuan tidak memiliki kendaraan dengan status "Diajukan".'], 400);
        }

        // Update status kendaraan menjadi "Diajukan ke Polda"
        foreach ($pengajuan->kendaraans as $kendaraan) {
            if ($kendaraan->status == 'pengajuan') {
                $kendaraan->status = 'diproses';
                $kendaraan->save();

                // Simpan log perubahan status
                KendaraanLog::create([
                    'kendaraan_id' => $kendaraan->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'Surat Pengajuan',
                    'status_baru' => $kendaraan->status,
                    'tipe' => 'system',
                    'catatan' => 'Kendaraan diajukan ke Polda oleh ' . Auth::user()->name,
                ]);
            }
        }

        // Simpan data Surat Pengajuan
        $sp = SuratPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_sp' => 'SP-' . strtoupper(uniqid()),
            'tanggal_surat' => now(),
            'persetujuan_instansi' => $this->persetujuanPolda
        ]);


        return redirect()->route('admin.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil diajukan ke Polda. Surat Pengajuan telah dibuat.');
    }

    public function approve(Request $request, $id)
    {
        if (!$request->user()->hasAnyPermission(['create_pdf_pengajuan', 'create_pdf_pengajuan_bapenda_jr', 'create_pdf_balasan_polda'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $sp = SuratPengajuan::findOrFail($id);
        if ($sp->isFullyApproved()) {
            return response()->json(['message' => 'Surat Pengajuan sudah disetujui semua instansi.'], 400);
        }
        $instansiUser = Auth::user()->roles->toArray(); // Misal: bapenda

        // Ambil data array saat ini
        $persetujuan = $sp->persetujuan_instansi;

        // Cari instansi yang sesuai dan ubah statusnya
        foreach ($persetujuan as &$item) {
            if (in_array ($item['instansi'], $instansiUser) && $item['status'] == 'pending') {
                $item['status'] = 'approved';
                $item['user_id'] = Auth::id();
                $item['updated_at'] = now();
            }
        }

        // Simpan kembali
        $sp->persetujuan_instansi = $persetujuan;
        $sp->save();

        return redirect()->route('admin.pengajuan.show', $sp->pengajuan_id)->with('success', 'Status berhasil diperbarui');
    }

}
