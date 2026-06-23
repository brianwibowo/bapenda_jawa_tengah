<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $nomor_keputusan }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 2.5cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        td {
            vertical-align: top;
            padding: 1px 0;
        }

        .b { font-weight: bold; }
        .uc { text-transform: uppercase; }
        .tj { text-align: justify; }
        .tc { text-align: center; }

        .lbl { width: 90px; font-weight: bold; }
        .sep { width: 15px; text-align: center; }
        .cl  { width: 22px; }

        .header-kop { text-align: center; font-weight: bold; text-transform: uppercase; margin-top: 5px; }
        .title-box { text-align: center; font-weight: bold; margin: 15px 0; }

        .stempel-container { position: relative; display: inline-block; }
    </style>
</head>
<body>

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td class="tc">
                <img src="{{ public_path('images/Logo-provinsi-jateng-warna.png') }}" style="height:65px;"><br>
                <div class="header-kop">
                    PT JASA RAHARJA (PERSERO)<br><br>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-box">
        <div class="uc">KEPUTUSAN KEPALA KANTOR WILAYAH UTAMA PT JASA RAHARJA JAWA TENGAH</div>
        <div>NOMOR {{ $nomor_keputusan }}</div>
        <br>
        <div class="uc">TENTANG<br><br>
            KEBIJAKAN PEMBEBASAN KEWAJIBAN PEMBAYARAN SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN, KARTU DANA, DAN DENDA SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN BAGI KENDARAAN BERMOTOR YANG DILAKSANAKAN PENGHAPUSAN REGISTRASI DAN IDENTIFIKASI KENDARAAN BERMOTOR ATAS DASAR PERMINTAAN PEMILIK KENDARAAN BERMOTOR
        </div>
        <br>
        <div>KEPALA KANTOR WILAYAH UTAMA PT JASA RAHARJA JAWA TENGAH,</div>
    </div>

    <table style="width: 100%;">
        {{-- MENIMBANG --}}
        <tr>
            <td class="lbl">Menimbang</td>
            <td class="sep">:</td>
            <td class="cl">a.</td>
            <td class="tj">bahwa ketentuan Undang-Undang Nomor 34 Tahun 1964 tentang Dana Kecelakaan Lalu Lintas Jalan beserta peraturan pelaksanaannya mengatur kewajiban bagi setiap pengusaha/pemilik kendaraan bermotor untuk membayar Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan (SWDKLLJ) kepada Perusahaan melalui Kantor Bersama Samsat;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">b.</td>
            <td class="tj">bahwa berdasarkan Keputusan Bersama Pembina Samsat Tingkat Nasional Nomor 900.1.13.1/12024/KEUDA P/30/SP/2024, KB/I/VIII/2024 tanggal 2 Agustus 2024 tentang Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor, tim pembina Samsat tingkat Nasional akan mengimplementasikan ketentuan Pasal 74 ayat (1) huruf a Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">c.</td>
            <td class="tj">bahwa berdasarkan ketentuan Pasal 2 Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, Dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan Yang Tertunggak Bagi Kendaraan Bermotor Yang Dilaksanakan Penghapusan Registrasi Dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor, Kepala Kantor Wilayah diberikan kewenangan untuk memberikan kebijakan pembebasan kewajiban pembayaran SWDKLLJ, Kartu Dana, dan denda SWDKLLJ yang tertunggak bagi setiap kendaraan bermotor yang dilakukan penghapusan registrasi dan identifikasi kendaraan bermotor atas dasar permintaan sendiri dari pengusaha/pemilik kendaraan bermotor yang bersangkutan.</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">d.</td>
            <td class="tj">bahwa berdasarkan Surat Pemberitahuan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident }} tanggal {{ $tanggal_surat_regident }}, kendaraan bermotor dengan Nopol {{ $data->nrkb }} telah dihapuskan dari Sistem Pangkalan data Regident Ranmor Ditlantas Polda Jawa Tengah dan tidak dapat diregistrasi kembali;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">e.</td>
            <td class="tj">bahwa berdasarkan Surat Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor {{ $nomor_surat_bapenda }} tanggal {{ $tanggal_surat_bapenda }}, tentang Pembebasan Atas Pokok dan Tunggakan PKB serta Sanksi Administrasi PKB untuk Kendaraan Bermotor dengan Nomor Polisi {{ $data->nrkb }};</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">f.</td>
            <td class="tj">bahwa berdasarkan Surat Permohonan tanggal {{ $tanggal_surat_permohonan }} perihal Permohonan Pembebasan Kewajiban Pembayaran SWDKLLJ, pemilik kendaraan bermotor dengan Nopol {{ $data->nrkb }} mengajukan permohonan Pembebasan Kewajiban Pembayaran SWDKLLJ kepada PT Jasa Raharja Wilayah Utama Jawa Tengah;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">g.</td>
            <td class="tj">bahwa berdasarkan pertimbangan sebagaimana dimaksud pada huruf a sampai dengan huruf f di atas, maka perlu menetapkan Keputusan Kepala Kantor Wilayah tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan Bagi Kendaraan Bermotor yang Dilaksanakan Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor.</td>
        </tr>

        <tr><td colspan="4" style="height: 8px;"></td></tr>

        {{-- MENGINGAT --}}
        <tr>
            <td class="lbl">Mengingat</td>
            <td class="sep">:</td>
            <td class="cl">1.</td>
            <td class="tj">Undang-Undang Nomor 34 Tahun 1964 tentang Dana Kecelakaan Lalu Lintas Jalan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">2.</td>
            <td class="tj">Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan sebagaimana telah diubah dengan Undang-Undang Nomor 6 tahun 2023 tentang Penetapan Peraturan Pemerintah Pengganti Undang-Undang Nomor 2 tahun 2022 tentang Cipta Kerja Menjadi Undang-Undang;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">3.</td>
            <td class="tj">Peraturan Pemerintah Nomor 18 Tahun 1965 tentang Ketentuan-Ketentuan Pelaksanaan Dana Kecelakaan Lalu Lintas Jalan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">4.</td>
            <td class="tj">Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor sebagaimana telah diubah dengan Peraturan Presiden Nomor 4 Tahun 2025 tentang Perubahan atas Peraturan Presiden Nomor 5 Tahun 2015;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">5.</td>
            <td class="tj">Peraturan Menteri Keuangan Nomor 16/PMK.010/2017 tanggal 13 Februari 2017 tentang Besar Santunan dan Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">6.</td>
            <td class="tj">Keputusan Bersama Pembina Samsat Tingkat Nasional Nomor 900.1.13.1/12024/KEUDA P/30/SP/2024, KB/I/VIII/2024 tanggal 22 Agustus 2024;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">7.</td>
            <td class="tj">Keputusan Bersama Dewan Komisaris dan Direksi Nomor DK/02/SP/2017 dan Nomor P/34/SP/2017 tanggal 31 Oktober 2017;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">8.</td>
            <td class="tj">Keputusan Direksi Nomor Kep/243/2012 tanggal 10 Oktober 2012 tentang Standar Prosedur Operasi Bidang SWDKLLJ;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">9.</td>
            <td class="tj">Keputusan Direksi Nomor Kep/137/2022 tanggal 23 Agustus 2022 tentang Pendelegasian Wewenang Pengelolaan Administrasi dan Keuangan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">10.</td>
            <td class="tj">Peraturan Direksi Nomor PER/4/2025 tanggal 31 Januari 2025 tentang Struktur Organisasi Perusahaan;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">11.</td>
            <td class="tj">Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 Tentang Kebijakan Pembebasan Kewajiban Pembayaran SWDKLLJ, Kartu Dana, Dan Denda SWDKLLJ Yang Tertunggak.</td>
        </tr>

        <tr><td colspan="4" style="height: 8px;"></td></tr>

        {{-- MEMPERHATIKAN --}}
        <tr>
            <td class="lbl">Memperhatikan</td>
            <td class="sep">:</td>
            <td colspan="2" class="tj">Surat Pemberitahuan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident }} tanggal {{ $tanggal_surat_regident }}, kendaraan bermotor dengan Nopol {{ $data->nrkb }} telah dihapuskan dari Sistem Pangkalan data Regident Ranmor Ditlantas Polda Jawa Tengah dan tidak dapat diregistrasi kembali.</td>
        </tr>
    </table>

    <div class="tc b" style="margin: 15px 0;">MEMUTUSKAN:</div>

    <table style="width: 100%;">
        <tr>
            <td class="lbl uc" style="width: 110px;">Menetapkan</td>
            <td class="sep">:</td>
            <td class="b tj">KEPUTUSAN KEPALA KANTOR WILAYAH UTAMA JAWA TENGAH TENTANG KEBIJAKAN PEMBEBASAN KEWAJIBAN PEMBAYARAN SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN, KARTU DANA, DAN DENDA SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN BAGI KENDARAAN BERMOTOR {{ $data->nrkb }} YANG DILAKSANAKAN PENGHAPUSAN REGISTRASI DAN IDENTIFIKASI KENDARAAN BERMOTOR ATAS DASAR PERMINTAAN PEMILIK KENDARAAN BERMOTOR.</td>
        </tr>
        <tr>
            <td class="lbl uc">Kesatu</td>
            <td class="sep">:</td>
            <td class="tj">Memberikan kebijakan pembebasan kewajiban pembayaran SWDKLLJ, Kartu Dana, dan Denda SWDKLLJ yang tertunggak sesuai ketentuan peraturan perundang-undangan kepada kendaraan bermotor dengan identitas sebagai berikut:
                <table style="width: 100%; margin-top: 5px;">
                    <tr><td style="width: 130px;">Nomor Registrasi Kendaraan Bermotor</td><td style="width: 15px;">:</td><td>{{ $data->nrkb }}</td></tr>
                    <tr><td>Nama Pemilik</td><td>:</td><td>{{ $data->nama }}</td></tr>
                    <tr><td>Alamat</td><td>:</td><td>{{ $data->alamat }}</td></tr>
                    <tr><td>Merk/Type</td><td>:</td><td>{{ $data->merk_type }}</td></tr>
                    <tr><td>No. Rangka/Mesin</td><td>:</td><td>{{ $data->no_rangka_mesin }}</td></tr>
                    <tr><td>Jenis/Model</td><td>:</td><td>{{ $data->jenis_model }}</td></tr>
                    <tr><td>Tahun Pembuatan</td><td>:</td><td>{{ $data->tahun }}</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="lbl uc">Kedua</td>
            <td class="sep">:</td>
            <td class="tj">Keputusan Kepala Kantor Wilayah ini mulai berlaku pada tanggal ditetapkan, dengan ketentuan apabila di kemudian hari terdapat kekeliruan di dalamnya akan dibuatkan pembetulan sebagaimana mestinya.</td>
        </tr>
    </table>

    {{-- TTD --}}
    <table style="width: 100%; margin-top: 25px;">
        <tr>
            <td style="width: 35%;"></td>
            <td style="width: 65%; vertical-align: top; text-align: left; padding-left: 1px;">
                Ditetapkan di {{ $tempat_sk }}<br>
                Pada Tanggal: {{ $tanggal_sk }}<br><br>
                <div class="b uc" style="margin-top: 5px; text-align: center;">
                    {{ $jabatan_penandatangan }}
                </div>
                <div class="stempel-container" style="height: 70px;">
                    @if($metode_penanda_tangan === 'ttd_elektronik')
                        <img src="{{ public_path('images/tte_jasa_raharja.png') }}" alt="TTE" style="max-height: 80px; opacity: 0.9;">
                    @endif
                </div>
                <br>
                <div class="b" style="text-align: center; text-decoration: underline;">
                    {{ $nama_penandatangan }}
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: 1px solid #000; margin-top: 5px;">

</body>
</html>
