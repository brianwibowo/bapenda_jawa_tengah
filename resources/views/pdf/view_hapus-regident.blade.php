<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Penghapusan Regident</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }
        .header-title {
            text-align: center;
            line-height: 1.1;
        }
        .header-title .big {
            font-size: 13pt;
            font-weight: bold;
        }
        .header-title .medium {
            font-size: 11pt;
            font-weight: bold;
        }
        .header-title .small {
            font-size: 9pt;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 90px;
        }
        .meta-colon {
            width: 10px;
        }
        .content-body {
            margin-top: 10px;
        }
        .content-body p,
        .content-body ol,
        .content-body li {
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .content-body ol {
            padding-left: 18px;
            margin-top: 6px;
        }
        .content-body li {
            margin-bottom: 4px;
        }
        .signature {
            margin-top: 20px;
        }
        .signature .sign-right {
            width: 45%;
            float: right;
            text-align: center;
        }
        .signature .sign-right .title {
            font-weight: bold;
            font-size: 10pt;
        }
        .signature .sign-right .name {
            margin-top: 58px;
            font-weight: bold;
        }
        .signature .sign-right .nip {
            margin-top: 4px;
            font-size: 10pt;
        }
        .footer {
            margin-top: 15px;
            font-size: 7.5pt;
            color: #333;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td style="width: 14%; vertical-align: top; padding-right: 8px;">
                <img src="{{ public_path('images/Logo-provinsi-jateng-warna.png') }}" style="width: 70px;">
            </td>
            <td style="width: 86%;">
                <div class="header-title">
                    <div class="small">PEMERINTAH PROVINSI JAWA TENGAH</div>
                    <div class="big">BADAN PENGELOLA PENDAPATAN DAERAH</div>
                    <div class="medium">Jl. Pemuda No.1 Semarang Kode Pos 50142 Telepon (024) 3515514</div>
                    <div class="small">Faksimile (024) 3541673, 3555704 e-mail: bppd@jatengprov.go.id</div>
                    <div class="small">website : http://www.bapenda.jatengprov.go.id</div>
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #000; margin: 6px 0 12px 0;">

    <table>
        <tr>
            <td></td>
            <td style="text-align: right; font-size: 10pt;">Semarang, {{ $tanggal_keluar ?? date('d F Y') }}</td>
        </tr>
    </table>

    <table class="meta-table" style="margin-top: 14px;">
        <tr>
            <td class="meta-label">Nomor</td>
            <td class="meta-colon">:</td>
            <td>{{ $nomor_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Sifat</td>
            <td class="meta-colon">:</td>
            <td>{{ $sifat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Lampiran</td>
            <td class="meta-colon">:</td>
            <td>{{ $lampiran ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Hal</td>
            <td class="meta-colon">:</td>
            <td>{{ $hal ?? '-' }}</td>
        </tr>
    </table>

    <div style="margin-top: 18px;">
        <div style="font-size: 10pt;">Yth. Dirlantas Polda Jateng</div>
        <div style="font-size: 10pt; margin-top: 2px;">di</div>
        <div style="font-size: 10pt;">Semarang</div>
    </div>

    <div class="content-body" style="margin-top: 14px;">
        <p>Menindaklanjuti surat saudara Nomor {{ $nomor_surat ?? '________' }} tanggal {{ $tanggal_keluar ?? '________' }} pemberitahuan penghapusan data kendaraan bermotor NRKB {{ $nama_resident ?? '________' }}, bersama ini disampaikan hal-hal sebagai berikut :</p>
        <ol>
            <li>Subyek Pajak Kendaraan Bermotor (PKB) tersebut terdaftar di Kabupaten {{ $alamat ?? '-' }}, atas nama {{ $nama_pemohon ?? '-' }} dengan Alamat {{ $alamat ?? '-' }}.</li>
            <li>Obyek PKB tersebut berupa sepeda motor roda tiga Merk {{ $nama_resident ?? '-' }}, Type {{ $hal ?? '-' }}.</li>
            <li>Tanggal jatuh tempo PKB terakhir kendaraan bermotor tersebut adalah tanggal {{ $tanggal_keluar ?? '-' }}.</li>
            <li>Berdasarkan Peraturan Kepala Badan Pengelola Pendapatan Daerah Provinsi Jawa Tengah Nomor 07 Tahun 2024 tentang Petunjuk Teknis Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor, dimungkinkan pemberian pembebasan atas pokok dan/ atau sanksi PKB.</li>
            <li>Pemberian pembebasan atas pokok dan/ atau sanksi PKB dapat diberikan karena kendaraan bermotor rusak berat dan tidak dapat dioperasionalkan kembali serta telah dilakukan penghapusan regident kendaraan bermotor.</li>
            <li>Berdasarkan surat saudara maka kendaraan bermotor dengan nomor polisi {{ $nama_resident ?? '-' }} memenuhi kriteria dan dapat diberikan pembebasan atas pokok dan sanksi administrasi PKB.</li>
            <li>Bapenda Provinsi Jawa Tengah akan menerbitkan Keputusan Kepala Bapenda Provinsi Jawa Tengah tentang Pembebasan Atas Pokok dan Sanksi Administrasi PKB untuk nomor polisi {{ $nama_resident ?? '-' }} setelah terbitnya Surat Keputusan Penghapusan Regident dan telah dihapus dari data Electronic Registration and Identification (ERI) untuk NRKB.</li>
        </ol>
        <p style="margin-top: 8px;">Demikian atas kerjasamanya dan disampaikan terima kasih.</p>
    </div>

    <div class="signature">
        <div class="sign-right">
            <div class="title">Kepala Badan Pengelola Pendapatan Daerah Provinsi {{ $provinsi ?? 'Jawa Tengah' }}</div>
            <div style="height: 80px;"></div>
            <div class="name">{{ $nama_penandatangan ?? '-' }}</div>
            <div>{{ $jabatan ?? '-' }}</div>
            <div class="nip">NIP {{ $nip ?? '-' }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Besar Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara.
    </div>
</body>
</html>
