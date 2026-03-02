<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .section-title { font-weight: bold; text-decoration: underline; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px; vertical-align: top; }
        .label { width: 30%; }
        .colon { width: 2%; }
        .value { border-bottom: 1px dotted #000; } /* Memberi efek garis isian */
    </style>
</head>
<body>

    <div class="header">
        SURAT PENDAFTARAN OBJEK PAJAK DAERAH (SPOPD)<br>
        PAJAK KENDARAAN BERMOTOR DAN BEA BALIK NAMA<br>
        KENDARAAN BERMOTOR<br>
        NOMOR: {{ $pengajuan->nomor_pengajuan }}
    </div>
    <div class="section-title" style="text-decoration: bold;">I. Data Objek Pajak:</div>
    @foreach ($kendaraans as $kendaraan)
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
    @endforeach

    <div class="section-title">II. Data Wajib Pajak:</div>
    <table>
        <tr><td class="label">a. Nama Wajib Pajak</td><td class="colon">:</td><td class="value">{{ $pemilik->nama_pemilik }}</td></tr>
        <tr><td class="label">b. Alamat</td><td class="colon">:</td><td class="value">{{ $pemilik->alamat_pemilik }}</td></tr>
        <tr><td class="label">c. NIK</td><td class="colon">:</td><td class="value">{{ $pemilik->nik_pemilik }}</td></tr>
        <tr><td class="label">d. No Telepon</td><td class="colon">:</td><td class="value">{{ $pemilik->telp_pemilik }}</td></tr>
        <tr><td class="label">e. Email</td><td class="colon">:</td><td class="value">{{ $pemilik->email_pemilik }}</td></tr>
    </table>

</body>
</html>