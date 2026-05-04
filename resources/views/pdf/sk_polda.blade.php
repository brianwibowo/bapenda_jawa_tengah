<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Lampiran Surat Kapolda Jawa Tengah</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.27cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .b {
            font-weight: bold;
        }

        .uc {
            text-transform: uppercase;
        }

        .tj {
            text-align: justify;
        }

        .tc {
            text-align: center;
        }

        /* Kolom sub-list */
        .cl {
            width: 25px;
            vertical-align: top;
        }

        .lbl {
            width: 160px;
            vertical-align: top;
        }

        .sep {
            width: 15px;
            vertical-align: top;
            text-align: center;
        }

        .val {
            vertical-align: top;
        }

        .img-grid td {
            padding: 5px;
            text-align: center;
            vertical-align: top;
        }

        .img-grid img {
            width: auto;
            height: auto;
            max-width: 120px;
            max-height: 120px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    {{-- ===== KOP SURAT ===== --}}
    <table style="margin-bottom: 30px;">
        <tr>
            {{-- Bagian Kiri --}}
            <td style="width: 50%; vertical-align: top; text-align: center;">
                <img src="{{ public_path('images/tribrata.png') }}" style="height: 60px; margin-bottom: 5px;">
                <div style="border-bottom: 1.5px solid #000; display: inline-block; padding-bottom: 3px;">
                    KEPOLISIAN NEGARA REPUBLIK INDONESIA<br>
                    DAERAH JAWA TENGAH<br>
                    Jalan Pahlawan 1, Semarang 50243
                </div>
            </td>
            {{-- Bagian Kanan --}}
            <td style="width: 50%; vertical-align: bottom; padding-left: 20px;">
                <div style="line-height: 1.5;">
                    <span style="text-decoration: underline;">LAMPIRAN SURAT KAPOLDA JAWA TENGAH</span><br>
                    <span style="text-decoration: underline;">NOMOR : {{ $nomor_surat ?? 'B/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/IV/YAN.1./2025/DITLANTAS' }}</span><br>
                    <span style="text-decoration: underline;">TANGGAL: {{ $tanggal_keluar ?? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; APRIL 2025' }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ===== POIN 1: IDENTITAS KENDARAAN ===== --}}
    <table style="margin-bottom: 15px;">
        <tr>
            <td style="width: 25px; vertical-align: top;">1.</td>
            <td style="vertical-align: top;">
                Identitas kendaraan bermotor:
                
                <table style="margin-top: 10px;">
                    <tr>
                        <td class="cl">a.</td>
                        <td class="lbl">NRKB</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->nrkb ?? 'AA-9660-QE' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">b.</td>
                        <td class="lbl">Atas nama/NIK</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->nama ?? 'PEMERINTAH DESA GANDUWETAN' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">c.</td>
                        <td class="lbl">Alamat</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->alamat ?? 'JL. JUMO NO 03 KEL NGADIREJO KAB TEMANGGUNG' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">d.</td>
                        <td class="lbl">Jenis/Model</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->jenis_model ?? 'SEPEDA MOTOR/RODA TIGA' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">e.</td>
                        <td class="lbl">Merk/type</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->merek_tipe ?? 'VIAR/V15 RL' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">f.</td>
                        <td class="lbl">Tahun</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->tahun ?? '2015' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">g.</td>
                        <td class="lbl">Isi Silinder</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->isi_silinder ?? '150 CC' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">h.</td>
                        <td class="lbl">Jenis Bahan Bakar</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->bahan_bakar ?? 'BENSIN' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">i.</td>
                        <td class="lbl">Nomor Rangka</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->no_rangka ?? 'MGRVR15TAFL207980' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">j.</td>
                        <td class="lbl">Nomor Mesin</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->no_mesin ?? 'YX161FMG15207805' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">k.</td>
                        <td class="lbl">Warna</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->warna ?? 'BIRU' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">l.</td>
                        <td class="lbl">Nomor BPKB</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->no_bpkb ?? 'M01679715' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== POIN 2: DOKUMENTASI ===== --}}
    <table>
        <tr>
            <td style="width: 25px; vertical-align: top;">2.</td>
            <td style="vertical-align: top;">
                Dokumentasi kendaraan bermotor:
                
                {{-- Grid Foto Dokumentasi dari foto_ranmor collection --}}
                <table class="img-grid" style="margin-top: 10px;">
                    @php
                        $fotoRanmor = $kendaraan->getMedia('foto_ranmor') ?? [];
                    @endphp
                    <tr>
                        @forelse($fotoRanmor as $index => $media)
                            @if($index < 3)
                                <td style="width: 33.33%;">
                                    <img src="{{ $media->getPath() }}" alt="Foto Kendaraan {{ $index + 1 }}">
                                </td>
                            @endif
                        @empty
                            <td style="width: 33.33%;"><img src="{{ public_path('images/doc1.jpg') }}" alt="Foto 1"></td>
                            <td style="width: 33.33%;"><img src="{{ public_path('images/doc2.jpg') }}" alt="Foto 2"></td>
                            <td style="width: 33.33%;"><img src="{{ public_path('images/doc3.jpg') }}" alt="Foto 3"></td>
                        @endforelse
                    </tr>
                    <tr>
                        @forelse($fotoRanmor as $index => $media)
                            @if($index >= 3 && $index < 5)
                                <td>
                                    <img src="{{ $media->getPath() }}" alt="Foto Kendaraan {{ $index + 1 }}">
                                </td>
                            @endif
                        @empty
                        @endforelse
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== TANDA TANGAN ===== --}}
    <table style="margin-top: 40px; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center; position: relative;">
                
                <p>
                    a.n. KEPALA KEPOLISIAN DAERAH JAWA TENGAH<br>
                    DIRLANTAS
                </p>
                
                {{-- Ruang untuk Cap dan Tanda Tangan Basah --}}
                <div style="height: 80px;">
                    {{-- Opsional: Jika Anda ingin menambahkan overlay gambar stempel --}}
                    {{-- <img src="{{ public_path('images/stempel_polda.png') }}" style="position: absolute; left: 10%; top: 20px; width: 120px; opacity: 0.8; z-index: -1;"> --}}
                </div>
                
                <p>
                    <span style="text-decoration: underline; font-weight: bold;">
                        {{ $nama_direktur ?? 'M. PRATAMA, S.I.K., S.H., M.H.' }}
                    </span><br>
                    {{ $pangkat_direktur ?? 'KOMISARIS BESAR POLISI NRP 68090397' }}
                </p>
            </td>
        </tr>
    </table>

</body>

</html>
