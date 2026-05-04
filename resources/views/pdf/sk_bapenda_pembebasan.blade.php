<!DOCTYPE html>
<html>
<head>
    <title>SK Pembebasan - {{ $nomor_surat_pembebasan ?? '-' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.3; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .title { text-align: center; font-weight: bold; margin-bottom: 5px; }
        .nomor { text-align: center; margin-bottom: 20px; }
        .table-content { width: 100%; border-collapse: collapse; margin-bottom: 10px; vertical-align: top; }
        .table-content td { vertical-align: top; padding: 2px 5px; }
        .indent { padding-left: 30px; }
        .footer-table { width: 100%; margin-top: 30px; }
        .signed-by { width: 50%; text-align: center; float: right; }
        .stempel { position: relative; }
        .stempel img { position: absolute; width: 150px; left: -20px; top: -10px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="logo" style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('images/Logo-provinsi-jateng-warna.png') }}" alt="Logo Jateng" style="width: 80px;">
    </div>
    <div class="header">
        PEMERINTAH PROVINSI JAWA TENGAH<br><br>
        BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH
    </div>

    <div class="title">KEPUTUSAN KEPALA BADAN PENGELOLA PENDAPATAN DAERAH<br> PROVINSI JAWA TENGAH</div>
    <div class="nomor">NOMOR {{ $nomor_surat_regident ?? '-' }}</div>

    <div style="text-align: center; font-weight: bold; margin-bottom: 20px;">
        TENTANG<br><br>
        PEMBEBASAN ATAS POKOK DAN TUNGGAKAN PKB<br>
        SERTA SANKSI ADMINISTRASI PKB UNTUK KENDARAAN BERMOTOR<br>
        DENGAN NOMOR POLISI {{ $data->nrkb ?? '-' }}
    </div>

    <div style="text-align: center; font-weight: bold;">KEPALA BADAN PENGELOLA PENDAPATAN DAERAH PROVINSI JAWA TENGAH,<br></div>

    <table class="table-content">
        <tr>
            <td width="15%">Menimbang</td>
            <td width="2%">:</td>
            <td>
                a. Bahwa berdasarkan surat pernyataan dan surat permohonan yang dibuat dan ditandatangani oleh {{ $nama_pembuat_surat_permohonan ?? '-' }} di {{ $tempat_pembuat_surat_permohonan ?? '-' }} pada tanggal {{ $tanggal_pembuat_surat_permohonan ?? '-' }} teiah dimohonkan penghapusan regident atas kendaraan bermotor;<br>
                b. Bahwa sehubungan dengan surat permohonan wajib pajak sebagaimana dimaksud pada huruf a, telah diterbitkan Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-' }} tanggal {{ $tanggal_pembuat_surat_regident ?? '30 Juni 2025' }} terkait Penghapusan Regident Ranmor atas nama {{ $data->nama ?? '-' }} dengan Nomor Polisi {{ $data->nrkb }} yang terdaftar di SAMSAT Kabupaten {{ $tempat_pembuat_surat_regident }};<br>
                c. Bahwa sehubungan dengan huruf a dan huruf b, perlu menetapkan Keputusan Kepala Badan Pengeiola Pendapatan Daerah Provinsi Jawa Tengah tentang Pembebasan Atas Pokok Dan Tunggakan PKB Serta Sanksi Administrasi PKB Untuk Kendaraan Bermotor Dengan Nomor Polisi {{ $data->nrkb }};
            </td>
        </tr>
        <tr>
            <td>Mengingat</td>
            <td>:</td>
            <td>
                1. Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas Dan Angkutan Jalan (Lembaran Negara Republik Indonesia Tahun 2OO9 Nomor 96, Tambahan Lembaran Negara Republik Indonesia Nomor 5025);  
                2. Undang-Undang Nomor 1 Tahun 2022 tentang Hubungan Keuangan Antara Pemerintah Pusat dan Pemerintah Daerah (Lembaran Negara Republik lndonesia Tahun 2022 Nomor 4, Tambahan Lembaran Negara Republik Indonesia Nomor 6757);
                3. Undang-Undang Nomor 11 Tahun 2A23 tentang Provinsi Jawa Tengah (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 58, Tambahan Lembaran Negara Republik Indonesia Nomor 6867);
                4. Peraturan Pemerintah Nornor 35 Tahun 2O23 tentang Ketentuan Umum Pajak Daerah dan Retribusi Daerah (Lembaran Negara Republik Indonesia Tahun 2023 Nomor 85, Tambahan Lembaran Negara Republik Indonesia Nomor 6881);
                5. Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2015 Nomor 6);
                6. Peraturan Presiden Nomor 4 Tahun 2025 tentang Perubahan Atas Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2025 Nomor 7);
                7. Peraturan Daerah Provinsi Jawa Tengah Nomor 12 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah (Lembaran Daerah Provinsi Jawa Tengah Nomor 2023 Nomor 12, Tambahan Lembaran Daerah Provinsi Jawa Tengah Nomor 153);
                8. Peraturan Kepolisian Negara Republik Indonesia Nomor 7 Tahun 2O2L tentang Registrasi dan Identifikasi Kendaraan Bermotor (Lembaran Negara Republik Indonesia Tahun 2O2l Nomor 476);
                9. Keputusan Bersama Pembina SAMSAT Tingkat Nasional Menteri Dalam Negeri Republik Indonesia, Menteri Keuangan Republik Indonesia dan Kepala Kepolisian Negara Republik Indonesia Nomor 900.1.13.1/12024/KEUDA, Nomor P/30/SP/2024, Nomor KB/I/VIII/2024 tentang Penghapusan Registrasi dan ldentifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor;
                lO. Peraturan Gubernur Jawa Tengah Nomor 64 Tahun 2023 tentang Peraturan Pelaksanaan Peraturan Daerah Provinsi Jawa Tengah Nornor 12 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah (Berita Daerah Provinsi Jawa Tengah Tahun 2023{Nomor 64);
                11. Peraturan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor 07 Tahun 2024 tentang Petunjuk Teknis Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor; 
            </td>
        </tr>
        <tr>
            <td>Memperhatikan</td>
            <td>:</td>
            <td>
                Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-' }} tanggal {{ $tanggal_pembuat_surat_regident ?? '-' }} terkait Penghapusan Regident Ranmor atas nama {{ $data->nama }} dengan Nomor Polisi {{ $data->nrkb }} yang terdaftar di SAMSAT Kabupaten {{ $tempat_pembuat_surat_regident ?? '-' }}.
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-weight: bold; margin-top: 10px;">MEMUTUSKAN:</div>

    <table class="table-content">
        <tr><td width="15%">MENETAPKAN</td><td width="2%">:</td><td>KEPUTUSAN KEPALA BADAN PENGELOLA PENDAPATAN DAERAH PROVINS1 JAWA TENGAH TENTANG PEMBEBASAN ATAS POKOK DAN TUNGGAKAN PKB SERTA SANKSI ADMINISTRASI PKB UNTUK KENDARAAN BERMOTOR DENGAN NOMOR POLISI {{ $data->nrkb ?? '-' }}</td></tr>
        <tr>
            <td>KESATU</td><td>:</td>
            <td>
                Membebaslkan Pokok Dan Tunggakan PKB Serta Sanksi Administrasi PKB untuk kendaraan bermotor, dengan identitas kendaraan sebagai berikut:<br>
                Nama Pemilik : {{ $data->nama ?? '-' }}<br>
                Alamat : {{ $data->alamat ?? '-' }}<br>
                Nomor Polisi : {{ $data->nrkb ?? '-' }}<br>
                Merk/Type : {{ $data->merek ?? '-' }} / {{ $data->tipe ?? '-' }}<br>
                Tahun Pembuatan : {{ $data->tahun ?? '-' }}<br>
                Warna : {{ $data->warna_kendaraan ?? '-' }}
            </td>
        </tr>
        <tr>
            <td>KEDUA</td><td>:</td>
            <td>Alasan pembebasan pokok Dan Tunggakan PKB serta Sanksi Administrasi PKB untuk kendaraan bermotor sebagaimana dimaksud dalam diktum KESATU, dikarenakan telah dihapuskan dari sistem pangkalan data Regident Ranmor Ditlantas Polda Jawa Tengah dan tidak dapat diregistrasi kembali sesuai dengan Surat Keterangan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident ?? '-'}} tanggal {{ $tanggal_pembuat_surat_regident ?? '-' }} terkait Penghapusan Regicient Rantnor atas nama {{ $data->nama ?? '-' }} dengan Nomor Polisi {{ $data->nrkb  }}.</td>
        </tr>
        <tr>
            <td>KETIGA</td><td>:</td>
            <td>Melaksanakan penghapusan data subjek dan objek kendaraan bermotor sebagaimana dimaksud dalam diktum KESATU yang dilaksanakan oleh Bidang pengolahan Data dan Pengembangan Pendapatan Bapenda Provinsi Jawa Tengah.</td>
        </tr>
        <tr>
            <td>KEEMPAT</td><td>:</td>
            <td>AKeputusan ini mulai berlaku pada tanggal ditetapkan</td>
        </tr>
    </table>

    <div class="signed-by">
        Ditetapkan di Semarang<br>
        Pada Tanggal: {{ $tanggal_sk ?? '-' }}<br>
        <strong>KEPALA BADAN PENGELOLA PENDAPATAN DAERAH</strong><br><br>
        <div class="stempel">
            
        </div>
        <br><br><br>
        <strong>{{ $nama_direktur ?? 'NADI SANTOSO' }}</strong>
    </div>

    <div class="salinan">
        Salinan : Keputusan ini disampaikan kepada Yth:<br>
        1. Dirlantas Polda Jawa Tengah;<br>
        2. Inspektur Provinsi Jawa Tengah;<br>
        3. Kepala BPKAD Provinsi Jawa Tengah;<br>
        4. Kepala Biro Hukum SETDA Provinsi Jawa Tengah;<br>
        5. Kepala PT. Jasa Raharja Kantor Wilayah Jawa Tengah;<br>
        6. Sekretaris Bapenda Prov. Jateng;<br>
        7. Kepala Bidang Pajak Kendaraan Bermotor, Bapenda Provinsi Jawa Tengah;<br>
        g. Kepala Bidang Evaluasi dan Pembinaan, Bapenda Provinsi Janva Tengah;<br>
        g. Kepala Bidang Pengolahan Data dan Pengembangan Pendapatan Bapenda Provinsi Jawa Tengah;<br>
        10. Kepala UPPD Kabupaten {{ $tempat_pembuat_surat_regident ?? '-' }};<br>
        11. Kepglil Desa Ganclulyetau, Kecamatan Ngadirejo, Kabupaten Temanggung;<br>
        12. Pertinggal.
    </div>
</body>
</html>