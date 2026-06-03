<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $nomor_keputusan }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.35; margin: 0; padding: 20px; color: #000; }
        @page { margin: 2cm 1.5cm; }
        .text-center { text-align: center; } .text-justify { text-align: justify; } .fw-bold { font-weight: bold; }
        .mt-2 { margin-top: 8px; } .mb-2 { margin-bottom: 8px; }
        h2, h3 { margin: 0; padding: 2px 0; } h2 { font-size: 13pt; } h3 { font-size: 11pt; }
        .section-title { font-weight: bold; margin: 12px 0 4px 0; }
        .list-item { margin-left: 25px; margin-bottom: 4px; }
        .memutuskan { text-align: center; font-weight: bold; margin: 15px 0; font-size: 12pt; }
        .menetapkan { font-weight: bold; margin: 10px 0; text-align: justify; }
        .diktum { font-weight: bold; margin-top: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .data-table td { padding: 1px 4px; vertical-align: top; font-size: 11pt; }
        .data-table td:first-child { width: 35%; } .data-table td:nth-child(2) { width: 5%; }
        .signature-block { margin-top: 40px; text-align: right; }
        .signature-name { font-weight: bold; margin-top: 50px; text-decoration: underline; }
        .ref-block { margin: 8px 0; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); opacity: 0.08; font-size: 48pt; font-weight: bold; pointer-events: none; }
    </style>
</head>
<body>
    @if($metode_penanda_tangan === 'ttd_elektronik')
        <div class="watermark">TTE - PT JASA RAHARJA</div>
    @endif

    <div class="text-center mb-2">
        <h2>KEPUTUSAN KEPALA KANTOR WILAYAH</h2>
        <h2>NOMOR {{ $nomor_keputusan }}</h2>
        <br>
        <h3>TENTANG</h3>
        <h3>KEBIJAKAN PEMBEBASAN KEWAJIBAN PEMBAYARAN SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN, KARTU DANA, DAN DENDA SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN BAGI KENDARAAN BERMOTOR YANG DILAKSANAKAN PENGHAPUSAN REGISTRASI DAN IDENTIFIKASI KENDARAAN BERMOTOR ATAS DASAR PERMINTAAN PEMILIK KENDARAAN BERMOTOR</h3>
        <br>
        <h3>KEPALA KANTOR WILAYAH UTAMA PT JASA RAHARJA JAWA TENGAH.</h3>
    </div>

    <div class="section-title">Menimbang</div>
    <div class="list-item text-justify">a. bahwa ketentuan Undang-Undang Nomor 34 Tahun 1964 tentang Dana Kecelakaan Lalu Lintas Jalan beserta peraturan pelaksanaannya mengatur kewajiban bagi setiap pengusaha/pemilik kendaraan bermotor untuk membayar Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan (SWDKLLJ) kepada Perusahaan melalui Kantor Bersama Samsat;</div>
    <div class="list-item text-justify">b. bahwa berdasarkan Keputusan Bersama Pembina Samsat Tingkat Nasional Nomor 900.1.13.1/12024/KEUDA P/30/SP/2024, KB/I/VIII/2024 tanggal 2 Agustus 2024 tentang Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor, tim pembina Samsat tingkat Nasional akan mengimplementasikan ketentuan Pasal 74 ayat (1) huruf a Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;</div>
    <div class="list-item text-justify">c. bahwa berdasarkan ketentuan Pasal 2 Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, Dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan Yang Tertunggak Bagi Kendaraan Bermotor Yang Dilaksanakan Penghapusan Registrasi Dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor, Kepala Kantor Wilayah diberikan kewenangan untuk memberikan kebijakan pembebasan kewajiban pembayaran SWDKLLJ, Kartu Dana, dan denda SWDKLLJ yang tertunggak bagi setiap kendaraan bermotor yang dilakukan penghapusan registrasi dan identifikasi kendaraan bermotor atas dasar permintaan sendiri dari pengusaha/pemilik kendaraan bermotor yang bersangkutan.</div>
    <div class="list-item text-justify">d. bahwa berdasarkan Surat Pemberitahuan Penghapusan Regident Ranmor Nomor {{ $nomor_surat_regident }} tanggal {{ $tanggal_surat_regident }}, kendaraan bermotor dengan Nopol {{ $data->nrkb }} telah dihapuskan dari Sistem Pangkalan data Regident Ranmor Ditlantas Polda Jawa Tengah dan tidak dapat diregistrasi kembali;</div>
    <div class="list-item text-justify">e. bahwa berdasarkan Surat Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor {{ $nomor_surat_bapenda }} tanggal {{ $tanggal_surat_bapenda }}, tentang Pembebasan Atas Pokok dan Tunggakan PKB serta Sanksi Administrasi PKB untuk Kendaraan Bermotor dengan Nomor Polisi {{ $data->nrkb }};</div>
    <div class="list-item text-justify">f. bahwa berdasarkan Surat Permohonan tanggal {{ $tanggal_surat_permohonan }} perihal Permohonan Pembebasan Kewajiban Pembayaran SWDKLLJ, pemilik kendaraan bermotor dengan Nopol {{ $data->nrkb }} mengajukan permohonan Pembebasan Kewajiban Pembayaran SWDKLLJ kepada PT Jasa Raharja Wilayah Utama Jawa Tengah;</div>
    <div class="list-item text-justify">g. bahwa berdasarkan pertimbangan sebagaimana dimaksud pada huruf a sampai dengan huruf f di atas, maka perlu menetapkan Keputusan Kepala Kantor Wilayah tentang Kebijakan Pembebasan Kewajiban Pembayaran Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan, Kartu Dana, dan Denda Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan Bagi Kendaraan Bermotor yang Dilaksanakan Penghapusan Registrasi dan Identifikasi Kendaraan Bermotor Atas Dasar Permintaan Pemilik Kendaraan Bermotor.</div>

    <div class="section-title">Mengingat</div>
    <div class="list-item">1. Undang-Undang Nomor 34 Tahun 1964 tentang Dana Kecelakaan Lalu Lintas Jalan;</div>
    <div class="list-item">2. Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan sebagaimana telah diubah dengan Undang-Undang Nomor 6 tahun 2023 tentang Penetapan Peraturan Pemerintah Pengganti Undang-Undang Nomor 2 tahun 2022 tentang Cipta Kerja Menjadi Undang-Undang;</div>
    <div class="list-item">3. Peraturan Pemerintah Nomor 18 Tahun 1965 tentang Ketentuan-Ketentuan Pelaksanaan Dana Kecelakaan Lalu Lintas Jalan;</div>
    <div class="list-item">4. Peraturan Presiden Nomor 5 Tahun 2015 tentang Penyelenggaraan Sistem Administrasi Manunggal Satu Atap Kendaraan Bermotor sebagaimana telah diubah dengan Peraturan Presiden Nomor 4 Tahun 2025 tentang Perubahan atas Peraturan Presiden Nomor 5 Tahun 2015;</div>
    <div class="list-item">5. Peraturan Menteri Keuangan Nomor 16/PMK.010/2017 tanggal 13 Februari 2017 tentang Besar Santunan dan Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan;</div>
    <div class="list-item">6. Keputusan Bersama Pembina Samsat Tingkat Nasional Nomor 900.1.13.1/12024/KEUDA P/30/SP/2024, KB/I/VIII/2024 tanggal 22 Agustus 2024;</div>
    <div class="list-item">7. Keputusan Bersama Dewan Komisaris dan Direksi Nomor DK/02/SP/2017 dan Nomor P/34/SP/2017 tanggal 31 Oktober 2017;</div>
    <div class="list-item">8. Keputusan Direksi Nomor Kep/243/2012 tanggal 10 Oktober 2012 tentang Standar Prosedur Operasi Bidang SWDKLLJ;</div>
    <div class="list-item">9. Keputusan Direksi Nomor Kep/137/2022 tanggal 23 Agustus 2022 tentang Pendelegasian Wewenang Pengelolaan Administrasi dan Keuangan;</div>
    <div class="list-item">10. Peraturan Direksi Nomor PER/4/2025 tanggal 31 Januari 2025 tentang Struktur Organisasi Perusahaan;</div>
    <div class="list-item">11. Peraturan Direksi Nomor PER/25/2025 tanggal 25 Maret 2025 Tentang Kebijakan Pembebasan Kewajiban Pembayaran SWDKLLJ, Kartu Dana, Dan Denda SWDKLLJ Yang Tertunggak.</div>

    <div class="ref-block">
        <table style="width:100%;"><tr>
            <td style="width:50%; text-align:left;">Nomor {{ $nomor_keputusan }}</td>
            <td style="width:50%; text-align:right;">Tanggal {{ $tanggal_sk }}</td>
        </tr></table>
    </div>

    <div class="memutuskan">MEMUTUSKAN:</div>

    <div class="menetapkan">
        Menetapkan : KEPUTUSAN KEPALA KANTOR WILAYAH UTAMA JAWA TENGAH TENTANG KEBIJAKAN PEMBEBASAN KEWAJIBAN PEMBAYARAN SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN, KARTU DANA, DAN DENDA SUMBANGAN WAJIB DANA KECELAKAAN LALU LINTAS JALAN BAGI KENDARAAN BERMOTOR {{ $data->nrkb }} YANG DILAKSANAKAN PENGHAPUSAN REGISTRASI DAN IDENTIFIKASI KENDARAAN BERMOTOR ATAS DASAR PERMINTAAN PEMILIK KENDARAAN BERMOTOR.
    </div>

    <div class="diktum">KESATU : Memberikan kebijakan pembebasan kewajiban pembayaran SWDKLLJ, Kartu Dana, dan Denda SWDKLLJ yang tertunggak sesuai ketentuan peraturan perundang-undangan kepada kendaraan bermotor dengan identitas sebagai berikut:</div>
    <table class="data-table">
        <tr><td>Nomor Registrasi Kendaraan Bermotor</td><td>:</td><td>{{ $data->nrkb }}</td></tr>
        <tr><td>Nama Pemilik</td><td>:</td><td>{{ $data->nama }}</td></tr>
        <tr><td>Alamat</td><td>:</td><td>{{ $data->alamat }}</td></tr>
        <tr><td>Merk/Type</td><td>:</td><td>{{ $data->merk_type }}</td></tr>
        <tr><td>No. Rangka/Mesin</td><td>:</td><td>{{ $data->no_rangka_mesin }}</td></tr>
        <tr><td>Jenis/Model</td><td>:</td><td>{{ $data->jenis_model }}</td></tr>
        <tr><td>Tahun Pembuatan</td><td>:</td><td>{{ $data->tahun }}</td></tr>
    </table>

    <div class="diktum">KEDUA : Keputusan Kepala Kantor Wilayah ini mulai berlaku pada tanggal ditetapkan, dengan ketentuan apabila di kemudian hari terdapat kekeliruan di dalamnya akan dibuatkan pembetulan sebagaimana mestinya.</div>

    <div class="signature-block">
        <p>Ditetapkan di {{ $tempat_sk }}<br>pada tanggal {{ $tanggal_sk }}</p>
        <p>{{ $jabatan_penandatangan }}</p>
        <br><br><br>
        @if($metode_penanda_tangan === 'ttd_elektronik')
            <div style="text-align:center; margin: 20px 0;">
                <img src="{{ public_path('images/tte_jasa_raharja.png') }}" alt="TTE" style="max-height: 80px; opacity: 0.9;">
            </div>
        @endif
        <div class="signature-name">{{ $nama_penandatangan }}</div>
    </div>
</body>
</html>