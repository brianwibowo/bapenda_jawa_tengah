<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SK Pembebasan - {{ $nomor_surat_regident ?? '-' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 2.5cm; /* Margin kiri dilebihkan untuk jilid */
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Jangan pakai table-layout: fixed secara global, DomPDF tidak menanganinya dengan baik */
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

        /* Mengunci lebar kolom dengan satuan PX agar dompdf tidak membuat spasi otomatis */
        .lbl { width: 90px; font-weight: bold; }
        .sep { width: 15px; text-align: center; }
        .cl  { width: 22px; } /* Kolom poin a. b. c. */
        
        .header-kop { text-align: center; font-weight: bold; text-transform: uppercase; margin-top: 5px; }
        .title-box { text-align: center; font-weight: bold; margin: 15px 0; }
        
        .stempel-container { position: relative; display: inline-block; }
        .stempel-container img { 
            position: absolute; width: 140px; left: -70px; top: -35px; opacity: 0.8; z-index: -1; 
        }
    </style>
</head>
<body>

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td class="tc">
                <img src="{{ public_path('images/Logo-provinsi-jateng-warna.png') }}" style="height:65px;"><br>
                <div class="header-kop">
                    PEMERINTAH PROVINSI JAWA TENGAH<br>
                    BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH
                </div>
                <hr style="border: 1px solid #000; margin-top: 5px;">
            </td>
        </tr>
    </table>

    <div class="title-box">
        <div class="uc">KEPUTUSAN KEPALA BADAN PENGELOLA PENDAPATAN DAERAH<br>PROVINSI JAWA TENGAH</div>
        <div>NOMOR {{ $nomor_surat_regident ?? '-' }}</div>
        <br>
        <div class="uc">TENTANG<br>
            PEMBEBASAN ATAS POKOK DAN TUNGGAKAN PKB<br>
            SERTA SANKSI ADMINISTRASI PKB UNTUK KENDARAAN BERMOTOR<br>
            DENGAN NOMOR POLISI {{ $data->nrkb ?? '-' }}
        </div>
        <br>
        <div>KEPALA BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH,</div>
    </div>

    <table style="width: 100%;">
        {{-- MENIMBANG --}}
        <tr>
            <td class="lbl">Menimbang</td>
            <td class="sep">:</td>
            <td class="cl">a.</td>
            <td class="tj">Bahwa berdasarkan surat pernyataan dan surat permohonan yang dibuat dan ditandatangani oleh {{ $nama_pembuat_surat_permohonan ?? '-' }} di {{ $tempat_pembuat_surat_permohonan ?? '-' }} pada tanggal {{ $tanggal_pembuat_surat_permohonan ?? '-' }} telah dimohonkan penghapusan regident atas kendaraan bermotor;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">b.</td>
            <td class="tj">Bahwa sehubungan dengan surat permohonan wajib pajak sebagaimana dimaksud pada huruf a, telah diterbitkan Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-' }} tanggal {{ $tanggal_pembuat_surat_regident ?? '30 Juni 2025' }} terkait Penghapusan Regident Ranmor atas nama {{ $data->nama ?? '-' }} dengan Nomor Polisi {{ $data->nrkb }} yang terdaftar di SAMSAT Kabupaten {{ $tempat_pembuat_surat_regident }};</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">c.</td>
            <td class="tj">Bahwa sehubungan dengan huruf a dan huruf b, perlu menetapkan Keputusan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah tentang Pembebasan Atas Pokok Dan Tunggakan PKB Serta Sanksi Administrasi PKB Untuk Kendaraan Bermotor Dengan Nomor Polisi {{ $data->nrkb }};</td>
        </tr>

        <tr><td colspan="4" style="height: 8px;"></td></tr>

        {{-- MENGINGAT --}}
        <tr>
            <td class="lbl">Mengingat</td>
            <td class="sep">:</td>
            <td class="cl">1.</td>
            <td class="tj">Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas Dan Angkutan Jalan (Lembaran Negara Republik Indonesia Tahun 2009 Nomor 96, Tambahan Lembaran Negara Republik Indonesia Nomor 5025);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">2.</td>
            <td class="tj">Undang-Undang Nomor 1 Tahun 2022 tentang Hubungan Keuangan Antara Pemerintah Pusat dan Pemerintah Daerah (Lembaran Negara Republik Indonesia Tahun 2022 Nomor 4, Tambahan Lembaran Negara Republik Indonesia Nomor 6757);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">3.</td>
            <td class="tj">Undang-Undang Nomor 11 Tahun 2023 tentang Provinsi Jawa Tengah (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 58, Tambahan Lembaran Negara Republik Indonesia Nomor 6867);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">4.</td>
            <td class="tj">Peraturan Pemerintah Nomor 35 Tahun 2023 tentang Ketentuan Umum Pajak Daerah dan Retribusi Daerah (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 85, Tambahan Lembaran Negara Republik Indonesia Nomor 6881);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">5.</td>
            <td class="tj">Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2015 Nomor 6);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">6.</td>
            <td class="tj">Peraturan Presiden Nomor 4 Tahun 2025 tentang Perubahan Atas Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2025 Nomor 7);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">7.</td>
            <td class="tj">Peraturan Daerah Provinsi Jawa Tengah Nomor 12 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah (Lembaran Daerah Provinsi Jawa Tengah Nomor 2023 Nomor 12, Tambahan Lembaran Daerah Provinsi Jawa Tengah Nomor 153);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">8.</td>
            <td class="tj">Peraturan Kepolisian Negara Republik Indonesia Nomor 7 Tahun 2021 tentang Registrasi dan Identifikasi Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2021 Nomor 476);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">9.</td>
            <td class="tj">Keputusan Bersama Pembina SAMSAT Tingkat Nasional Nomor 900.1.13.1/12024/KEUDA, Nomor P/30/SP/2024, Nomor KB/I/VIII/2024 tentang Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor;</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">10.</td>
            <td class="tj">Peraturan Gubernur Jawa Tengah Nomor 64 Tahun 2023 tentang Peraturan Pelaksanaan Peraturan Daerah Provinsi Jawa Tengah Nomor 12 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah (Berita Daerah Provinsi Jawa Tengah Tahun 2023 Nomor 64);</td>
        </tr>
        <tr>
            <td class="lbl"></td><td class="sep"></td>
            <td class="cl">11.</td>
            <td class="tj">Peraturan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor 07 Tahun 2024 tentang Petunjuk Teknis Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor;</td>
        </tr>

        <tr><td colspan="4" style="height: 8px;"></td></tr>

        {{-- MEMPERHATIKAN --}}
        <tr>
            <td class="lbl">Memperhatikan</td>
            <td class="sep">:</td>
            <td colspan="2" class="tj">Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-' }} tanggal {{ $tanggal_pembuat_surat_regident ?? '-' }} terkait Penghapusan Regident Ranmor atas nama {{ $data->nama }} dengan Nomor Polisi {{ $data->nrkb }} yang terdaftar di SAMSAT Kabupaten {{ $tempat_pembuat_surat_regident ?? '-' }}.</td>
        </tr>
    </table>

    <div class="tc b" style="margin: 15px 0;">MEMUTUSKAN:</div>

    <table style="width: 100%;">
        <tr>
            <td class="lbl uc" style="width: 110px;">Menetapkan</td>
            <td class="sep">:</td>
            <td class="b tj">KEPUTUSAN KEPALA BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH TENTANG PEMBEBASAN ATAS POKOK DAN TUNGGAKAN PKB SERTA SANKSI ADMINISTRASI PKB UNTUK KENDARAAN BERMOTOR DENGAN NOMOR POLISI {{ $data->nrkb ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl uc">Kesatu</td>
            <td class="sep">:</td>
            <td class="tj">Membebaskan Pokok Dan Tunggakan PKB Serta Sanksi Administrasi PKB untuk kendaraan bermotor, dengan identitas kendaraan sebagai berikut:
                <table style="width: 100%; margin-top: 5px;">
                    <tr><td style="width: 130px;">Nama Pemilik</td><td style="width: 15px;">:</td><td class="uc">{{ $data->nama ?? '-' }}</td></tr>
                    <tr><td>Alamat</td><td>:</td><td class="uc">{{ $data->alamat ?? '-' }}</td></tr>
                    <tr><td>Nomor Polisi</td><td>:</td><td>{{ $data->nrkb ?? '-' }}</td></tr>
                    <tr><td>Merk/Type</td><td>:</td><td>{{ $data->merek ?? '-' }} / {{ $data->tipe ?? '-' }}</td></tr>
                    <tr><td>Tahun Pembuatan</td><td>:</td><td>{{ $data->tahun ?? '-' }}</td></tr>
                    <tr><td>Warna</td><td>:</td><td>{{ $data->warna_kendaraan ?? '-' }}</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="lbl uc">Kedua</td>
            <td class="sep">:</td>
            <td class="tj">Alasan pembebasan pokok Dan Tunggakan PKB serta Sanksi Administrasi PKB untuk kendaraan bermotor sebagaimana dimaksud dalam diktum KESATU, dikarenakan telah dihapuskan dari sistem pangkalan data Regident Ranmor Ditlantas Polda Jawa Tengah dan tidak dapat diregistrasi kembali sesuai dengan Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-'}} tanggal {{ $tanggal_pembuat_surat_regident ?? '-' }} terkait Penghapusan Regicient Rantnor atas nama {{ $data->nama ?? '-' }} dengan Nomor Polisi {{ $data->nrkb }}.</td>
        </tr>
        <tr>
            <td class="lbl uc">Ketiga</td>
            <td class="sep">:</td>
            <td class="tj">Melaksanakan penghapusan data subjek dan objek kendaraan bermotor sebagaimana dimaksud dalam diktum KESATU yang dilaksanakan oleh Bidang Pengolahan Data dan Pengembangan Pendapatan Bapenda Provinsi Jawa Tengah.</td>
        </tr>
        <tr>
            <td class="lbl uc">Keempat</td>
            <td class="sep">:</td>
            <td class="tj">Keputusan ini mulai berlaku pada tanggal ditetapkan.</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 25px;">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div style="font-size: 8pt; line-height: 1.2;">Salinan : Keputusan ini disampaikan kepada Yth:<br>1. Dirlantas Polda Jawa Tengah;<br>2. Inspektur Provinsi Jawa Tengah;<br>3. Kepala BPKAD Provinsi Jawa Tengah;<br>4. Kepala Biro Hukum SETDA Provinsi Jawa Tengah;<br>5. Kepala PT. Jasa Raharja Kantor Wilayah Jawa Tengah;<br>6. Sekretaris Bapenda Prov. Jateng;<br>7. Kepala Bidang Pajak Kendaraan Bermotor, Bapenda Provinsi Jawa Tengah;<br>8. Kepala Bidang Evaluasi dan Pembinaan, Bapenda Provinsi Jawa Tengah;<br>9. Kepala Bidang Pengolahan Data dan Pengembangan Pendapatan Bapenda Provinsi Jawa Tengah;<br>10. Kepala UPPD Kabupaten {{ $tempat_pembuat_surat_regident ?? '-' }};<br>11. Kepala Desa Ganduwetan, Kecamatan Ngadirejo, Kabupaten Temanggung;<br>12. Pertinggal.</div>
            </td>
            <td class="tc" style="width: 45%; vertical-align: top;">
                Ditetapkan di Semarang<br>
                Pada Tanggal: {{ $tanggal_sk ?? '-' }}<br>
                <div class="b uc" style="margin-top: 5px;">
                    Kepala Badan Pengelola<br>Pendapatan Daerah<br>Provinsi Jawa Tengah
                </div>
                <div class="stempel-container" style="height: 70px;">
                    <!-- <img src="{{ public_path('images/stempel_bapenda.png') }}" alt=""> -->
                </div>
                <br>
                <div class="b" style="text-decoration: underline;">
                    {{ $nama_direktur ?? 'NADI SANTOSO' }}
                </div>
            </td>
        </tr>
    </table>

</body>
</html>