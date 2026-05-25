<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Surat Keputusan - Bapenda</title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <style>
            @page { margin: 2cm; }
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .header { text-align: center; margin-bottom: 20px; }
            .header h3 { font-size: 14px; text-transform: uppercase; margin: 4px 0; }
            .divider { border: none; border-top: 2px solid black; margin: 10px 0; }
            .divider-double { border: none; border-top: 4px double black; margin: 10px 0; }
            .section-title { font-weight: bold; text-decoration: underline; margin-top: 15px; margin-bottom: 8px; }
            table { width: 100%; border-collapse: collapse; }
            td { padding: 4px 2px; vertical-align: top; }
            td.label { width: 35%; }
            td.colon { width: 3%; }
            td.value { border-bottom: 1px dotted #000; }
            .section-container { page-break-inside: avoid; margin-bottom: 16px; }
            .signature-table td { border: none; text-align: center; }
        </style>
    </head>
    <body>

        <div class="header">
            <h3>SURAT KEPUTUSAN</h3>
            <h3>PENGHAPUSAN DATA REGIDENT KENDARAAN BERMOTOR</h3>
            @if(isset($sk) && $sk->nomor_sk)
                <p>Nomor: {{ $sk->nomor_sk }}</p>
            @endif
        </div>

        <hr class="divider-double">

        @php
            $kendaraan = isset($sk) ? $sk->kendaraan : null;
            $pemilik   = $kendaraan?->pemilik ?? null;
        @endphp

        <div class="section-container">
            <div class="section-title">I. Data Kendaraan Bermotor:</div>
            <table style="margin-left: 20px;">
                <tr><td class="label">Nomor Kendaraan (NRKB)</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->nrkb ?? '-') }}</td></tr>
                <tr><td class="label">Jenis Kendaraan</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->jenis_kendaraan ?? '-') }}</td></tr>
                <tr><td class="label">Merek / Model</td><td class="colon">:</td><td class="value">{{ strtoupper(($kendaraan?->merk_kendaraan ?? '-') . ' / ' . ($kendaraan?->model_kendaraan ?? '-')) }}</td></tr>
                <tr><td class="label">Tipe</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->tipe_kendaraan ?? '-') }}</td></tr>
                <tr><td class="label">Tahun Pembuatan</td><td class="colon">:</td><td class="value">{{ $kendaraan?->tahun_pembuatan ?? '-' }}</td></tr>
                <tr><td class="label">Isi Silinder / Daya Listrik</td><td class="colon">:</td><td class="value">{{ $kendaraan?->isi_silinder ?? '-' }}</td></tr>
                <tr><td class="label">Bahan Bakar</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->jenis_bahan_bakar ?? '-') }}</td></tr>
                <tr><td class="label">Nomor Rangka</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->nomor_rangka ?? '-') }}</td></tr>
                <tr><td class="label">Nomor Mesin</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->nomor_mesin ?? '-') }}</td></tr>
                <tr><td class="label">Warna Dasar TNKB</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->warna_tnkb ?? '-') }}</td></tr>
                <tr><td class="label">Nomor BPKB</td><td class="colon">:</td><td class="value">{{ strtoupper($kendaraan?->nomor_bpkb ?? '-') }}</td></tr>
            </table>
        </div>

        <div class="section-container">
            <div class="section-title">II. Data Wajib Pajak / Pemilik:</div>
            <table style="margin-left: 20px;">
                <tr><td class="label">Nama Pemilik</td><td class="colon">:</td><td class="value">{{ strtoupper($pemilik?->nama_pemilik ?? '-') }}</td></tr>
                <tr><td class="label">NIK / TDP / NIB</td><td class="colon">:</td><td class="value">{{ $pemilik?->nik_pemilik ?? '-' }}</td></tr>
                <tr><td class="label">Alamat</td><td class="colon">:</td><td class="value">{{ strtoupper($pemilik?->alamat_pemilik ?? '-') }}</td></tr>
                <tr><td class="label">No. Telepon / HP</td><td class="colon">:</td><td class="value">{{ $pemilik?->telp_pemilik ?? '-' }}</td></tr>
            </table>
        </div>

        <hr class="divider">

        <div class="section-container">
            <p>Berdasarkan data di atas, dengan ini dinyatakan bahwa kendaraan bermotor tersebut telah dilakukan penghapusan data Regident sesuai ketentuan yang berlaku.</p>
        </div>

        <div class="section-container" style="margin-top: 30px;">
            <table class="signature-table">
                <tr>
                    <td style="width: 50%;">
                        Mengetahui,<br>
                        Kepala Samsat<br><br><br><br><br>
                        <strong>( ................................................ )</strong>
                    </td>
                    <td style="width: 50%;">
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                        Pejabat yang berwenang<br><br><br><br><br>
                        <strong>( ................................................ )</strong>
                    </td>
                </tr>
            </table>
        </div>

    </body>
</html>