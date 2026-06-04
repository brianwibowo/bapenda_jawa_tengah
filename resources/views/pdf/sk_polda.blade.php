<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Penghapusan Regident Ranmor</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.27cm 1.27cm 1.27cm 1.27cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.2pt;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
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
            width: 18px;
            vertical-align: top;
        }

        .lbl {
            width: 200px;
            vertical-align: top;
        }

        .sep {
            width: 10px;
            vertical-align: top;
            text-align: center;
        }

        .val {
            vertical-align: top;
        }
    </style>
</head>

<body>

    {{-- ===== KOP SURAT ===== --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
        <tr>
            <td
                style="text-align: center; border-bottom: 1px solid #000; padding-bottom: 3px; width: 1%; white-space: nowrap;">
                <span style="font-size: 10pt; font-weight: normal;">
                    KEPOLISIAN NEGARA REPUBLIK INDONESIA<br>
                    DAERAH JAWA TENGAH<br>
                    DIREKTORAT LALU LINTAS
                </span>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>

    {{-- ===== LOGO ===== --}}
    <table style="width:100%; margin-top:4px; margin-bottom:2px;">
        <tr>
            <td class="tc">
                <img src="{{ public_path('images/tribrata.png') }}" style="height:75px;">
            </td>
        </tr>
    </table>

    {{-- ===== JUDUL ===== --}}
    <table style="width:100%; margin-bottom:7px;">
        <tr>
            <td class="tc">
                <span style="font-size:10pt; text-decoration:underline;">SURAT KETERANGAN PENGHAPUSAN REGIDENT
                    RANMOR</span><br>
                Nomor: {{ $nomor_surat ?? 'SKET/ 01 /VI/YAN.1/2025/Ditlantas' }}
            </td>
        </tr>
    </table>

    {{-- ===== POIN 1: RUJUKAN ===== --}}
    <table style="width:100%; margin-bottom:3px;">
        <tr>
            <td style="width:22px; vertical-align:top;">1.</td>
            <td style="vertical-align:top;">
                Rujukan:
                <table style="width:100%; margin-top:5px;">
                    <tr>
                        <td class="cl">a.</td>
                        <td class="tj">Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;</td>
                    </tr>
                    <tr>
                        <td class="cl">b.</td>
                        <td class="tj">Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi
                            Manunggal Satu Atap Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">c.</td>
                        <td class="tj">Peraturan Presiden Nomor 4 Tahun 2025 tentang Perubahan atas Peraturan Presiden
                            Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan
                            Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">d.</td>
                        <td class="tj">Peraturan Kepolisian Negara Republik Indonesia Nomor 7 Tahun 2021 tentang
                            Registrasi dan Identifikasi Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">e.</td>
                        <td class="tj">Keputusan Bersama Pembina Samsat Tingkat Nasional Nomor: 900.1.13.1/12024/KEUDA,
                            Nomor: P/30/SP/2024, Nomor: KB/I/VIII/2024 tentang Penghapusan Registrasi dan Identifikasi
                            Kendaraan Bermotor atas Dasar Permintaan Pemilik Kendaraan Bermotor;</td>
                    </tr>
                    <tr>
                        <td class="cl">f.</td>
                        <td class="tj">Surat Pernyataan Pemilik Kendaraan Bermotor yang dibuat dan ditandatangani oleh
                            {{ $nama_pembuat ?? 'Dwiyanto Setyo Budi' }} di Temanggung pada tanggal 13 Mei 2024;
                        </td>
                    </tr>
                    <tr>
                        <td class="cl">g.</td>
                        <td class="tj">Surat Permohonan Penghapusan Regident Ranmor yang dibuat dan ditandatangani oleh
                            {{ $nama_pembuat ?? 'Dwiyanto Setyo Budi' }} di Temanggung pada tanggal 13 Mei 2024.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== POIN 2: DATA KENDARAAN ===== --}}
    <table style="width:100%; margin-bottom:3px;">
        <tr>
            <td style="width:22px; vertical-align:top;">2.</td>
            <td style="vertical-align:top;">
                <span class="tj">Sehubungan dengan rujukan tersebut di atas, menerangkan bahwa kendaraan bermotor,
                    dengan identitas sebagai berikut:</span>
                <table style="width:100%; margin-top:5px;">
                    <tr>
                        <td class="cl">a.</td>
                        <td class="lbl">Nama Pemilik</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->nama ?? 'PEMERINTAH DESA GANDUWETAN' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">b.</td>
                        <td class="lbl">Alamat</td>
                        <td class="sep">:</td>
                        <td class="val uc">{{ $data->alamat ?? 'JL JUMO NO 03 KEC. NGADIREJO KAB. TEMANGGUNG' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">c.</td>
                        <td class="lbl">NIK/TDP/NIB/Kitas/Kitab</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">d.</td>
                        <td class="lbl">No. TLP/HP</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->no_tlp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">e.</td>
                        <td class="lbl">Email</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">f.</td>
                        <td class="lbl">NRKB</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->nrkb ?? 'AA 9660 QE' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">g.</td>
                        <td class="lbl">Merek</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->merek ?? 'VIAR' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">h.</td>
                        <td class="lbl">Tipe</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->tipe ?? 'V 15 RL' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">i.</td>
                        <td class="lbl">Jenis</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->jenis ?? 'SEPEDA MOTOR' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">j.</td>
                        <td class="lbl">Model</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->model ?? 'RODA TIGA' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">k.</td>
                        <td class="lbl">Tahun Pembuatan</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->tahun ?? '2015' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">l.</td>
                        <td class="lbl">Isi Silinder / Daya Listrik</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->isi_silinder ?? '150 CC' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">m.</td>
                        <td class="lbl">Nomor Rangka</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->no_rangka ?? 'MGRVR15TAFL207980' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">n.</td>
                        <td class="lbl">Nomor Mesin</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->no_mesin ?? 'YX161FMG15207805' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">o.</td>
                        <td class="lbl">Warna Kendaraan Bermotor</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->warna_kendaraan ?? 'BIRU' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">p.</td>
                        <td class="lbl">Bahan Bakar / Sumber Energi</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->bahan_bakar ?? 'BENSIN' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">q.</td>
                        <td class="lbl">Warna TNKB</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->warna_tnkb ?? 'MERAH' }}</td>
                    </tr>
                    <tr>
                        <td class="cl">r.</td>
                        <td class="lbl">Nomor BPKB</td>
                        <td class="sep">:</td>
                        <td class="val">{{ $data->no_bpkb ?? 'M01679715' }}</td>
                    </tr>
                </table>
                <p class="tj" style="margin-top:7px;">
                    Telah <span class="b">"DIHAPUSKAN"</span> dari sistem pangkalan data Regident Ranmor Ditlantas Polda
                    Jawa Tengah dan <span class="b">tidak dapat diregistrasi kembali</span>.
                </p>
            </td>
        </tr>
    </table>

    {{-- ===== POIN 3 ===== --}}
    <table style="width:100%; margin-bottom:4px;">
        <tr>
            <td style="width:22px; vertical-align:top;">3.</td>
            <td>Demikian surat keterangan ini dibuat untuk digunakan sebagaimana mestinya.</td>
        </tr>
    </table>

    {{-- ===== TANDA TANGAN ===== --}}
    <table style="width:100%; margin-top:10px; border-collapse: collapse;">
        <tr>
            {{-- Spacer Kiri --}}
            <td style="width:45%;"></td>
            <td>
                {{-- Area Lokasi & Tanggal --}}
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width:95px; padding-bottom: 2px;">Dikeluarkan di</td>
                        <td style="width:12px; padding-bottom: 2px;">:</td>
                        <td style="padding-bottom: 2px;">{{ $tempat ?? 'Semarang' }}</td>
                    </tr>
                    <tr>
                        {{-- Garis bawah kontinu menggunakan border-bottom pada tiap cell --}}
                        <td style="border-bottom:1px solid #000; padding-bottom: 2px;">Pada tanggal</td>
                        <td style="border-bottom:1px solid #000; padding-bottom: 2px;">:</td>
                        <td style="border-bottom:1px solid #000; padding-bottom: 2px;">
                            {{ $tanggal_keluar ?? '30 Juni 2025' }}
                        </td>
                    </tr>
                </table>

                {{-- Jabatan nempel dengan garis tanggal --}}
                <p style="margin-top:3px; margin-bottom:8px; white-space:nowrap; font-size: 9pt;">
                    DIREKTUR LALU LINTAS POLDA JAWA TENGAH
                </p>

                {{-- Kotak TTD Elektronik --}}
                <table style="width:100%; border:1px solid #bbb;">
                    <tr>
                        <td style="width:72px; padding:8px 6px; text-align:center; vertical-align:middle;">
                            {{-- Pastikan path image benar --}}
                            <img src="{{ public_path('images/tribrata_gold.png') }}" style="width:62px;">
                        </td>
                        <td
                            style="padding:8px 10px 8px 6px; vertical-align:middle; font-size:8pt; line-height:1.45; text-align:center;">
                            Ditandatangani secara elektronik oleh:<br>
                            <br>
                            <span
                                class="b">{{ $nama_direktur ?? 'M. PRATAMA ADHYASASTRA, S.I.K., S.H., M.H.' }}</span><br>
                            {{ $pangkat_direktur ?? 'KOMISARIS BESAR POLISI NRP 680903' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <table style="width:100%; margin-top:6px; border-top:0.5px solid #ccc; padding-top:4px;">
        <tr>
            <td style="vertical-align:bottom; font-size:7pt; color:#333; line-height:1.3; width:62%;">
                Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh
                Balai
                Sertifikasi Elektronik (BSrE) BSSN dan dapat dibuktikan keasliannya melalui pemindaian QR di samping.
            </td>
            <td style="vertical-align:bottom; text-align:right; padding-right:8px; width:22%;">
                <img src="{{ public_path('images/bsre.png') }}" style="height:32px;">
            </td>
        </tr>
    </table>

</body>

</html>