<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Pemberitahuan Penghapusan Data</title>
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
            width: 80%;
            height: auto;
            max-width: 100%;
            border: 1px solid #ccc;
            display: block;
            margin: 0 auto;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- ========================================== --}}
    {{-- HALAMAN 1: SURAT PEMBERITAHUAN UTAMA       --}}
    {{-- ========================================== --}}

    {{-- KOP SURAT --}}
    <table>
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
            <td style="width: 50%; vertical-align: bottom; padding-left: 85px;">
                Semarang, {{ $tanggal_keluar ?? 'April 2025' }}
            </td>
        </tr>
    </table>

    {{-- METADATA SURAT --}}
    <table style="margin-bottom: 20px;">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <table>
                    <tr>
                        <td style="width: 80px; vertical-align: top;">Nomor</td>
                        <td style="width: 10px; vertical-align: top;">:</td>
                        <td style="vertical-align: top;">{{ $nomor_surat ?? 'B/4189 IV/YAN.1./2025/Ditlantas' }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Klasifikasi</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">Biasa</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Lampiran</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">satu lembar</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Hal</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">pemberitahuan penghapusan data kendaraan bermotor NRKB {{ (isset($kendaraans) && count($kendaraans) === 1) ? $kendaraans->first()->nrkb : 'TERLAMPIR' }}.</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="padding-top: 8">Yth.</td>
                        
                    </tr>
                </table>
                 
            </td>  
            
            <td style="width: 40%; vertical-align: top; padding-left: 20px;">
                <br><br><br><br>
                Kepada<br><br>
                <table>
                <tr>
                    <td style="vertical-align: top;"> BAPENDA PROVINSI JAWA TENGAH</td>
                </tr>
                </table><br>
                di<br><br>
                Semarang
            </td>
        </tr>
    </table>

    {{-- ISI SURAT --}}
    <table style="margin-bottom: 15px;">
        <tr>
            <td class="cl">1.</td>
            <td class="tj">
                Rujukan:
                <table style="margin-top: 5px;">
                    <tr>
                        <td class="cl">a.</td>
                        <td class="tj">Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;</td>
                    </tr>
                    <tr>
                        <td class="cl">b.</td>
                        <td class="tj">Peraturan Kepolisian Negara Republik Indonesia Nomor 7 Tahun 2021 tentang Registrasi dan Identifikasi Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">c.</td>
                        <td class="tj">Peraturan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor 07 Tahun 2024 tentang Petunjuk Teknis Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">d.</td>
                        <td class="tj">Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan yang Tertunggak bagi Kendaraan Bermotor yang dilaksanakan Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor atas Dasar Permintaan Pemilik Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">e.</td>
                        <td class="tj">Surat Permohonan Kapolres Temanggung Polda Jateng Nomor: B/1/VII/YAN.1.3.2/2024/LANTAS tanggal 3 Juli 2024 hal permohonan penghapusan data.</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="cl" style="padding-top: 10px;">2.</td>
            <td class="tj" style="padding-top: 10px;">
                Sehubungan dengan rujukan di atas, bersama ini diberitahukan bahwa kendaraan bermotor dengan NRKB {{ (isset($kendaraans) && count($kendaraans) === 1) ? $kendaraans->first()->nrkb : 'TERLAMPIR' }} telah dilakukan identifikasi dan verifikasi pada sistem ERI (Electronic Registration and Identification) Korlantas dan dinyatakan terdaftar serta tidak terblokir.
            </td>
        </tr>
        <tr>
            <td class="cl" style="padding-top: 10px;">3.</td>
            <td class="tj" style="padding-top: 10px;">
                Berkaitan dengan hal tersebut, guna melanjutkan proses penghapusan data registrasi dan identifikasi kendaraan bermotor pada Polri, diperlukan pernyataan dapat dibebaskan atas pokok dan/atau sanksi administrasi PKB atas dasar permohonan pemilik kendaraan bermotor sesuai dengan Pasal 45 Perkaban Nomor 07 Tahun 2024. Selanjutnya dimohon kepada Ka. untuk melaksanakan pemeriksaan subjek dan/atau objek pajak kendaraan bermotor tersebut, sesuai dengan data kendaraan bermotor sebagaimana terlampir.
            </td>
        </tr>
        <tr>
            <td class="cl" style="padding-top: 10px;">4.</td>
            <td class="tj" style="padding-top: 10px;">
                Demikian untuk menjadi maklum.
            </td>
        </tr>
    </table>

    {{-- TEMBUSAN & TANDA TANGAN --}}
    <table style="margin-top: 20px; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                Tembusan:<br>
                1. Kapolda Jateng.<br>
                2. Irwasda Polda Jateng.<br>
                3. Kabidpropam Polda Jateng.
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top; position: relative;">
                <p>
                    a.n. KEPALA KEPOLISIAN DAERAH JAWA TENGAH<br>
                    DIRLANTAS
                </p>
                <div style="height: 80px;">
                    {{-- Ruang untuk Cap dan Tanda Tangan Basah --}}
                </div>
                <p>
                    <span style="text-decoration: underline; font-weight: bold;">
                        {{ $nama_direktur ?? 'M. PRATAMA, S.H., S.I.K., M.H.' }}
                    </span><br>
                    {{ $pangkat_direktur ?? 'KOMISARIS BESAR POLISI NRP 68090397' }}
                </p>
            </td>
        </tr>
    </table>

    @foreach($kendaraans as $index => $k)
    <div class="page-break"></div>

    {{-- KOP LAMPIRAN --}}
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
                    <span style="text-decoration: underline;">NOMOR : {{ $nomor_surat ?? 'B/4189 IV/YAN.1./2025/DITLANTAS' }}</span><br>
                    <span style="text-decoration: underline;">TANGGAL: {{ $tanggal_keluar ?? 'APRIL 2025' }}</span>
                </div>
            </td>
        </tr>
    </table>

    @php
        $vData = (object)[
            'nrkb' => strtoupper($k->nrkb ?? '-'),
            'nama' => strtoupper(optional($k->pemilik)->nama_pemilik ?? '-'),
            'alamat' => strtoupper(optional($k->pemilik)->alamat_pemilik ?? '-'),
            'jenis_model' => strtoupper(($k->jenis_kendaraan ?? '-') . '/' . ($k->model_kendaraan ?? '-')),
            'merek_tipe' => strtoupper(($k->merk_kendaraan ?? '-') . '/' . ($k->tipe_kendaraan ?? '-')),
            'tahun' => $k->tahun_pembuatan ?? '-',
            'isi_silinder' => strtoupper($k->isi_silinder ?? '-'),
            'bahan_bakar' => strtoupper($k->jenis_bahan_bakar ?? '-'),
            'no_rangka' => strtoupper($k->nomor_rangka ?? '-'),
            'no_mesin' => strtoupper($k->nomor_mesin ?? '-'),
            'warna' => strtoupper($k->warna_kendaraan ?? '-'),
            'no_bpkb' => strtoupper($k->nomor_bpkb ?? '-'),
        ];
    @endphp

    {{-- POIN 1: IDENTITAS KENDARAAN --}}
    <table style="margin-bottom: 15px;">
        <tr>
            <td style="width: 25px; vertical-align: top;">1.</td>
            <td style="vertical-align: top;">
                Identitas kendaraan bermotor:
                
                <table style="margin-top: 10px;">
                    <tr><td class="cl">a.</td><td class="lbl">NRKB</td><td class="sep">:</td><td class="val uc">{{ $vData->nrkb }}</td></tr>
                    <tr><td class="cl">b.</td><td class="lbl">Atas nama/NIK</td><td class="sep">:</td><td class="val uc">{{ $vData->nama }}</td></tr>
                    <tr><td class="cl">c.</td><td class="lbl">Alamat</td><td class="sep">:</td><td class="val uc">{{ $vData->alamat }}</td></tr>
                    <tr><td class="cl">d.</td><td class="lbl">Jenis/Model</td><td class="sep">:</td><td class="val uc">{{ $vData->jenis_model }}</td></tr>
                    <tr><td class="cl">e.</td><td class="lbl">Merk/type</td><td class="sep">:</td><td class="val uc">{{ $vData->merek_tipe }}</td></tr>
                    <tr><td class="cl">f.</td><td class="lbl">Tahun</td><td class="sep">:</td><td class="val">{{ $vData->tahun }}</td></tr>
                    <tr><td class="cl">g.</td><td class="lbl">Isi Silinder</td><td class="sep">:</td><td class="val uc">{{ $vData->isi_silinder }}</td></tr>
                    <tr><td class="cl">h.</td><td class="lbl">Jenis Bahan Bakar</td><td class="sep">:</td><td class="val uc">{{ $vData->bahan_bakar }}</td></tr>
                    <tr><td class="cl">i.</td><td class="lbl">Nomor Rangka</td><td class="sep">:</td><td class="val uc">{{ $vData->no_rangka }}</td></tr>
                    <tr><td class="cl">j.</td><td class="lbl">Nomor Mesin</td><td class="sep">:</td><td class="val uc">{{ $vData->no_mesin }}</td></tr>
                    <tr><td class="cl">k.</td><td class="lbl">Warna</td><td class="sep">:</td><td class="val uc">{{ $vData->warna }}</td></tr>
                    <tr><td class="cl">l.</td><td class="lbl">Nomor BPKB</td><td class="sep">:</td><td class="val uc">{{ $vData->no_bpkb }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- POIN 2: DOKUMENTASI --}}
    <table>
        <tr>
            <td style="width: 25px; vertical-align: top;">2.</td>
            <td style="vertical-align: top;">
                Dokumentasi kendaraan bermotor:
                
                @php
                    // Ambil foto dari media library
                    $fotoRanmor = $k->getMedia('foto_ranmor')->take(9);
                    $fotoRows = $fotoRanmor->chunk(3);
                @endphp

                <table class="img-grid" style="margin-top: 10px;">
                    @foreach($fotoRows as $rowIndex => $row)
                        <tr>
                            @foreach($row as $index => $media)
                                <td style="width: 33.33%;">
                                    <img src="{{ $media->getPath() }}" alt="Foto Kendaraan {{ $rowIndex * 3 + $index + 1 }}">
                                </td>
                            @endforeach
                            @for($fill = count($row); $fill < 3; $fill++)
                                <td style="width: 33.33%;"></td>
                            @endfor
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    {{-- TANDA TANGAN LAMPIRAN --}}
    <table style="margin-top: 40px; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center; position: relative;">
                <p>
                    a.n. KEPALA KEPOLISIAN DAERAH JAWA TENGAH<br>
                    DIRLANTAS
                </p>
                <div style="height: 80px;">
                    {{-- Ruang untuk Cap dan Tanda Tangan Basah --}}
                </div>
                <p>
                    <span style="text-decoration: underline; font-weight: bold;">
                        {{ $nama_direktur ?? 'M. PRATAMA, S.H., S.I.K., M.H.' }}
                    </span><br>
                    {{ $pangkat_direktur ?? 'KOMISARIS BESAR POLISI NRP 68090397' }}
                </p>
            </td>
        </tr>
    </table>
    @endforeach

</body>

</html>