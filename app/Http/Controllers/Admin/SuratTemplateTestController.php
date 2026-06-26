<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class SuratTemplateTestController extends Controller
{
    public function index()
    {
        return view('admin.surat-template-test.index');
    }

    public function showModal(string $type){
        switch ($type){
	    case "sp_default":
		return view('admin.surat-template-test.modal.modalSpDefaultTest', []);
		break;
            case "sp_polda2bapendajr":
                return view('admin.surat-template-test.modal.modalSpPolda2bapendajrTest',[
                    'rujukan' => [
                        "Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;",
                        "Peraturan Kepolisian Negara Republik Indonesia Nomor 7 Tahun 2021 tentang Registrasi dan Identifikasi Kendaraan Bermotor;",
                        "Peraturan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor 07 Tahun 2024 tentang Petunjuk Teknis Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor;",
                        "Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan yang Tertunggak bagi Kendaraan Bermotor yang dilaksanakan Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor atas Dasar Permintaan Pemilik Kendaraan Bermotor;",
                        "Surat Permohonan Kapolres Temanggung Polda Jateng Nomor: B/1/VII/YAN.1.3.2/2024/LANTAS tanggal 3 Juli 2024 hal permohonan penghapusan data."
                        ],
                    'tembusan' => [
                        "Kapolda Jateng",
                        "Irwasda Polda Jateng",
                        "Kabidpropam Polda Jateng"
                    ]
                    ]);
                break;
	    case "sp_balasan_bapenda":
		return view("admin.surat-template-test.modal.modalSpBalasanBapendaTest");
		break;
	    case "sp_balasan_jr":
		return view("admin.surat-template-test.modal.modalSpBalasanJRTest");
		break;
	    case "sk_default":
		return view("admin.surat-template-test.modal.modalSkDefaultTest");
		break;
	    case "sk_polda":
		return view("admin.surat-template-test.modal.modalSkPoldaTest");
		break;
	    case "sk_bapenda":
		return view("admin.surat-template-test.modal.modalSkBapendaTest");
		break;
	    case "sk_jr":
		return view("admin.surat-template-test.modal.modalSkJRTest");
		break;
            default:
                return response()->json(['error' => 'Tipe modal tidak ditemukan'], 404);
		break;
        }

    }

    public function preview(Request $request, string $type)
    {
        $data = $request->all();

        $kendaraanDummy = new class($data) {
            public string $nrkb;
            public string $merk_kendaraan;
            public string $model_kendaraan;
            public string $tipe_kendaraan;
            public string $jenis_kendaraan;
            public string $tahun_pembuatan;
            public string $isi_silinder;
            public string $jenis_bahan_bakar;
            public string $nomor_rangka;
            public string $nomor_mesin;
            public string $warna_kendaraan;
            public string $warna_tnkb;
            public string $nomor_bpkb;
            public object $pemilik;

            public function __construct(array $data)
            {
                $this->nrkb = $data['nrkb'] ?? 'AA 9660 QE';
                $this->merk_kendaraan = $data['merk_kendaraan'] ?? 'VIAR';
                $this->model_kendaraan = $data['model_kendaraan'] ?? 'V 15 RL';
                $this->tipe_kendaraan = $data['tipe_kendaraan'] ?? 'V 15 RL';
                $this->jenis_kendaraan = $data['jenis_kendaraan'] ?? 'SEPEDA MOTOR';
                $this->tahun_pembuatan = $data['tahun_pembuatan'] ?? '2015';
                $this->isi_silinder = $data['isi_silinder'] ?? '150 CC';
                $this->jenis_bahan_bakar = $data['jenis_bahan_bakar'] ?? 'BENSIN';
                $this->nomor_rangka = $data['nomor_rangka'] ?? 'MGRVR15TAFL207980';
                $this->nomor_mesin = $data['nomor_mesin'] ?? 'YX161FMG15207805';
                $this->warna_kendaraan = $data['warna_kendaraan'] ?? 'BIRU';
                $this->warna_tnkb = $data['warna_tnkb'] ?? 'MERAH';
                $this->nomor_bpkb = $data['nomor_bpkb'] ?? 'M01679715';
                $this->pemilik = (object) [
                    'nama_pemilik' => $data['nama_pemilik'] ?? 'PEMERINTAH DESA GANDUWETAN',
                    'nik_pemilik' => $data['nik_pemilik'] ?? '3379999999999999',
                    'alamat_pemilik' => $data['alamat_pemilik'] ?? 'JL JUMO NO 03 KEC. NGADIREJO KAB. TEMANGGUNG',
                    'telp_pemilik' => $data['telp_pemilik'] ?? '08123456789',
                ];
            }

            public function getMedia(string $collection): Collection
            {
                return collect([]);
            }
        };

        $kendaraans = collect([$kendaraanDummy]);
	$rawRujukan = $data['group-rujukan'] ?? [];
	$parsedRujukan = collect($rawRujukan)->pluck('rujukan')->filter()->map(fn($item) => trim($item))->filter()->toArray();

	$rawTembusan = $data['group-tembusan'] ?? [];
	$parsedTembusan = collect($rawTembusan)->pluck('tembusan')->filter()->map(fn($item) => trim($item))->filter()->toArray();


        $viewData = array_merge($data, [
            'kendaraans' => $kendaraans,
            'data' => (object) [
                'nrkb' => $kendaraanDummy->nrkb,
                'nama' => $kendaraanDummy->pemilik->nama_pemilik,
                'alamat' => $kendaraanDummy->pemilik->alamat_pemilik,
                'nik' => $kendaraanDummy->pemilik->nik_pemilik,
                'no_tlp' => $kendaraanDummy->pemilik->telp_pemilik,
                'email' => $data['email'] ?? 'desa@example.com',
                'merek' => $kendaraanDummy->merk_kendaraan,
                'tipe' => $kendaraanDummy->tipe_kendaraan,
                'jenis' => $kendaraanDummy->jenis_kendaraan,
                'model' => $kendaraanDummy->model_kendaraan,
                'tahun' => $kendaraanDummy->tahun_pembuatan,
                'isi_silinder' => $kendaraanDummy->isi_silinder,
                'no_rangka' => $kendaraanDummy->nomor_rangka,
                'no_mesin' => $kendaraanDummy->nomor_mesin,
                'warna_kendaraan' => $kendaraanDummy->warna_kendaraan,
                'warna_tnkb' => $kendaraanDummy->warna_tnkb,
                'bahan_bakar' => $kendaraanDummy->jenis_bahan_bakar,
                'no_bpkb' => $kendaraanDummy->nomor_bpkb,
                'merk_type' => $kendaraanDummy->merk_kendaraan . ' / ' . $kendaraanDummy->tipe_kendaraan,
                'no_rangka_mesin' => $kendaraanDummy->nomor_rangka . ' / ' . $kendaraanDummy->nomor_mesin,
                'jenis_model' => $kendaraanDummy->jenis_kendaraan . ' / ' . $kendaraanDummy->model_kendaraan,
            ],
            'sk' => (object) [
                'nomor_sk' => $data['nomor_sk'] ?? 'SK-TEST/001/2026',
                'kendaraan' => $kendaraanDummy,
            ],
            'rujukan' => $parsedRujukan,
            'tembusan' => $parsedTembusan
        ]);

        $viewMap = [
            'sp_default' => 'pdf.view_sp',
            'sp_polda2bapendajr' => 'pdf.sp_polda2bapendaNjrtest',
            'sp_balasan_bapenda' => 'pdf.sp_balasan_bapenda',
            'sp_balasan_jr' => 'pdf.sp_balasan_jr',
            'sk_default' => 'pdf.view_sk',
            'sk_polda' => 'pdf.sk_polda',
            'sk_bapenda' => 'pdf.sk_bapenda_pembebasan',
            'sk_jr' => 'pdf.sk_jasa_raharja_pembebasan',
        ];

        $view = $viewMap[$type] ?? abort(404);

        $pdf = Pdf::loadView($view, $viewData)->setPaper('a4', 'portrait');
        return $pdf->stream('preview_' . $type . '_' . time() . '.pdf');
    }

    public function renderCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = $request->input('code');
        $php = Blade::compileString($code);

        $viewData = [
            'nama' => 'Pemerintah Desa Ganduwetan',
            'jabatan' => 'Kepala Desa',
            'nrkb' => 'AA 9660 QE',
            'alamat' => 'Jl. Jumo No. 03, Kec. Ngadirejo, Kab. Temanggung',
            '__env' => app('view'),
        ];

        try {
            ob_start();
            extract($viewData);
            eval('?>' . $php);
            $output = ob_get_clean();
        } catch (\Throwable $e) {
            return response($e->getMessage(), 422);
        }

        return response($output, 200)->header('Content-Type', 'text/html');
    }
}
