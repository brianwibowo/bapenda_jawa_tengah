{{--
    PDF: Surat Pembebasan Kewajiban Pembayaran SWDKLLJ (Balasan Jasa Raharja)
    Variabel: $nomor_surat, $nomor_surat_regident, $nomor_surat_bapenda,
              $tempat_surat, $tanggal_surat, $nama_penandatangan, $jabatan_penandatangan, $data (object kendaraan+pemilik)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP Balasan Jasa Raharja - {{ $nomor_surat }}</title>
    <style>
        @page { margin: 2cm 2cm 2cm 2cm; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .fw-bold { font-weight: bold; }

        /* Kop Surat */
        .kop-surat { margin-bottom: 20px; }
        .kop-logo { width: 220px; }
        .kop-subtitle { font-size: 8pt; color: #555; margin-top: 2px; }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
            line-height: 1.4;
            margin: 25px 40px 5px 40px;
        }
        .nomor-surat {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 20px;
        }

        /* Tabel Data Kendaraan */
        .data-table { width: 100%; border-collapse: collapse; margin: 10px 0 15px 30px; }
        .data-table td { padding: 1px 4px; vertical-align: top; font-size: 11pt; }
        .data-table td:first-child { width: 280px; }
        .data-table td:nth-child(2) { width: 15px; }

        /* Paragraf */
        .paragraf { text-align: left; margin: 10px 0; }

        /* Tanda Tangan: blok di pojok kanan, teks rata kiri */
        .signature-block { margin-top: 30px; text-align: right; }
        .signature-inner { display: inline-block; text-align: left; }
        .signature-name { margin-top: 80px; }
    </style>
</head>
<body>

        {{-- ============ KOP SURAT ============ --}}
        <div class="kop-surat">
            <img src="{{ public_path('images/Logo_JS 2024.png') }}" class="kop-logo" alt="Logo Jasa Raharja">
        </div>

        {{-- ============ JUDUL SURAT ============ --}}
        <div class="judul-surat">
            SURAT PEMBEBASAN KEWAJIBAN PEMBAYARAN<br>
            SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN,<br>
            KARTU DANA, DAN DENDA SUMBANGAN WAJIB<br>
            DANA KECELAKAAN LALU LINTAS JALAN
        </div>
        <div class="nomor-surat">NOMOR: {{ $nomor_surat }}</div>

        {{-- ============ PARAGRAF 1: Referensi Regident + Data Kendaraan ============ --}}
        <div class="paragraf">
            Berdasarkan Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident }}, dengan ini diberitahukan bahwa kendaraan bermotor dengan identitas sebagai berikut:
        </div>

        <table class="data-table">
            <tr>
                <td>Nomor Registrasi Kendaraan Bermotor</td>
                <td>:</td>
                <td class="fw-bold">{{ $data->nrkb }}</td>
            </tr>
            <tr>
                <td>Nama Pemilik</td>
                <td>:</td>
                <td>{{ $data->nama }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data->alamat }}</td>
            </tr>
            <tr>
                <td>Merk/Type</td>
                <td>:</td>
                <td>{{ $data->merk_type }}</td>
            </tr>
            <tr>
                <td>No. Rangka/Mesin</td>
                <td>:</td>
                <td>{{ $data->no_rangka_mesin }}</td>
            </tr>
            <tr>
                <td>Jenis/Model</td>
                <td>:</td>
                <td>{{ $data->jenis_model }}</td>
            </tr>
            <tr>
                <td>Tahun Pembuatan</td>
                <td>:</td>
                <td>{{ $data->tahun }}</td>
            </tr>
        </table>

        {{-- ============ PARAGRAF 2: Dasar Hukum Pembebasan ============ --}}
        <div class="paragraf">
            Mempertimbangkan Peraturan Direksi PT Jasa Raharja Nomor PER/25/2025, pemilik kendaraan bermotor tersebut di atas dapat dibebaskan dari kewajiban pembayaran SWDKLLJ, Kartu Dana, dan denda SWDKLLJ sebagaimana pembebasan atas kewajiban pembayaran Pajak Kendaraan Bermotor yang tertuang dalam Surat Badan Pengelola Pendapatan Daerah Nomor {{ $nomor_surat_bapenda }} sesuai teknis dan ketentuan peraturan perundang-undangan yang berlaku.
        </div>

        {{-- ============ PENUTUP ============ --}}
        <div class="paragraf">
            Demikian surat ini dibuat untuk digunakan sebagaimana mestinya.
        </div>

        {{-- ============ TANDA TANGAN ============ --}}
        <div class="signature-block">
            <div class="signature-inner">
                <div>{{ $tempat_surat }}, {{ $tanggal_surat }}</div>
                <br>
                <div>PT Jasa Raharja</div>
                <div>Kantor Wilayah Utama Jawa Tengah</div>
                <div class="signature-name">{{ $nama_penandatangan }}</div>
                <div>{{ $jabatan_penandatangan }}</div>
            </div>
        </div>

</body>
</html>
