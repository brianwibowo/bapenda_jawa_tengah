<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{{ $header ?? 'Dashboard' }} - Bapenda</title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

        <script src="{{ asset('kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
        <script>
            WebFont.load({
                google: { families: ["Public Sans:300,400,500,600,700"] },
                custom: {
                    families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                    urls: ["{{ asset('kaiadmin/css/fonts.min.css') }}"],
                },
                active: function () { sessionStorage.fonts = true; },
            });
        </script>

        <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
        <style>
            @page { margin: 2cm; }
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .middle_body {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <div class="middle_body">
        <i class="fas fa-solid fa-file fa-2x"></i>This is a sample PDF document generated from a Blade template.
    </div>
    <body>

    </body>
</html>

{{-- <!DOCTYPE html>
<html>
    <head>
        <style>
            @page { margin: 2cm; }
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
            .section-title { font-weight: bold; text-decoration: underline; margin-top: 15px; }
            table { width: 100%; border-collapse: collapse; }
            td { padding: 4px; vertical-align: top; }
            .label { width: 30%; }
            .colon { width: 2%; }
            .value { 
                border-bottom: 1px dotted #000; 
                padding-bottom: 2px;
                min-width: 150px; /* Agar jika data kosong, garis tetap muncul */
            } /* Memberi efek garis isian */
            .section-container {
                page-break-inside: avoid; /* Perintah agar div ini tidak dipotong ke halaman berikutnya */
                margin-bottom: 20px;
            }
            hr {
                border: none;
                height: 3px; 
                border-top: 1px solid black; 
                border-bottom: 1px solid black; 
                width: 100%;
                margin: 10px 0;
            }
        </style>
    </head>
    <body>

        <div class="header">
            SURAT PENDAFTARAN OBJEK PAJAK DAERAH (SPOPD)<br>
            PAJAK KENDARAAN BERMOTOR DAN BEA BALIK NAMA<br>
            KENDARAAN BERMOTOR<br>
            NOMOR: {{ $pengajuan->nomor_pengajuan }}
        </div>
        <hr>
        <div class="section-title">I. Data Objek Pajak:</div>
        @foreach ($kendaraans as $kendaraan)
            <div class="section-container">
                <div class="section" style="margin-left: 20px;margin-top: 5px;">Objek Pajak {{ $loop->iteration }}:</div>
                <table style="margin-left: 20px;">
                    <tr><td class="label">a. Nomor Kendaraan</td><td class="colon">:</td><td class="value">{{ $kendaraan->nrkb }}</td></tr>
                    <tr><td class="label">b. Jenis Kendaraan</td><td class="colon">:</td><td class="value">{{ $kendaraan->jenis_kendaraan }}</td></tr>
                    <tr><td class="label">c. Model Kendaraan</td><td class="colon">:</td><td class="value">{{ $kendaraan->model_kendaraan }}</td></tr>
                    <tr><td class="label">d. Merek</td><td class="colon">:</td><td class="value">{{ $kendaraan->merk_kendaraan }}</td></tr>
                    <tr><td class="label">e. Type</td><td class="colon">:</td><td class="value">{{ $kendaraan->tipe_kendaraan }}</td></tr>
                    <tr><td class="label">f. Tahun buat/Perakitan</td><td class="colon">:</td><td class="value">{{ $kendaraan->tahun_pembuatan }}</td></tr>
                    <tr><td class="label">g. Besar Isi Cylinder</td><td class="colon">:</td><td class="value">{{ $kendaraan->isi_silinder }} CC</td></tr>
                    <tr><td class="label">h. Bahan Bakar</td><td class="colon">:</td><td class="value">{{ $kendaraan->jenis_bahan_bakar }}</td></tr>
                    <tr><td class="label">i. Nomor Rangka</td><td class="colon">:</td><td class="value">{{ $kendaraan->nomor_rangka }}</td></tr>
                    <tr><td class="label">j. Nomor Mesin</td><td class="colon">:</td><td class="value">{{ $kendaraan->nomor_mesin }}</td></tr>
                    <tr><td class="label">k. Warna Dasar TNKB</td><td class="colon">:</td><td class="value">{{ $kendaraan->warna_tnkb }}</td></tr>
                    <tr><td class="label">l. Nomor BPKB</td><td class="colon">:</td><td class="value">{{ $kendaraan->nomor_bpkb }}</td></tr>
                </table>
            </div>
        @endforeach
        
        <div class="section-container">
            <div class="section-title">II. Data Wajib Pajak:</div>
            <table>
                <tr><td class="label">a. Nama Wajib Pajak</td><td class="colon">:</td><td class="value">{{ $pemilik->nama_pemilik }}</td></tr>
                <tr><td class="label">b. Alamat</td><td class="colon">:</td><td class="value">{{ $pemilik->alamat_pemilik }}</td></tr>
                <tr><td class="label">c. NIK</td><td class="colon">:</td><td class="value">{{ $pemilik->nik_pemilik }}</td></tr>
                <tr><td class="label">d. No Telepon</td><td class="colon">:</td><td class="value">{{ $pemilik->telp_pemilik }}</td></tr>
                <tr><td class="label">e. Email</td><td class="colon">:</td><td class="value">{{ $pemilik->email_pemilik }}</td></tr>
            </table>
        </div>
        <div class="section-container">
            <table style="width: 100%; border: none; margin-top: 30px;">
                <tr>
                    <td style="width: 45%; text-align: center; border: none;">
                        Mengetahui,<br>
                        Petugas Penerima<br><br><br><br><br>
                        <strong>( .................................... )</strong><br>
                        NIP. ...........................
                    </td>

                    <td style="width: 10%; border: none;"></td>

                    <td style="width: 45%; text-align: center; border: none;">
                        Semarang, {{ date('d F Y') }}<br>
                        Wajib Pajak / Kuasa<br><br><br><br><br>
                        <strong>( {{ strtoupper($pemilik->nama_pemilik) }} )</strong>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html> --}}