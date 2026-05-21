<<<<<<< HEAD
{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.ajukan', $pengajuan->id) }}"
      method="POST">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formDefaultContainer" style="padding: 0.25rem 0.25rem;">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Keterangan / Catatan Pengajuan</label>
                    <textarea class="form-control" name="catatan" rows="3" required placeholder="Masukkan catatan atau keterangan pengajuan..."></textarea>
                </div>
            </div>
        </div>

    </div>
</form>
=======
﻿<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Form Surat Pengajuan - Bapenda</title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f7f9fc;
                margin: 0;
                padding: 24px;
            }
            .content {
                max-width: 960px;
                margin: 0 auto;
                background: #ffffff;
                border: 1px solid #e2e8eb;
                border-radius: 12px;
                padding: 28px;
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            }
            .section-title {
                font-weight: 700;
                margin-top: 20px;
                margin-bottom: 14px;
            }
            .badge-status {
                display: inline-block;
                padding: .4rem .8rem;
                border-radius: .5rem;
                background: #f8fafc;
                color: #0f172a;
                font-weight: 600;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 18px;
            }
            table th,
            table td {
                padding: 10px 12px;
                border: 1px solid #e2e8f0;
                vertical-align: top;
            }
            .helper-text {
                color: #475569;
                font-size: 0.95rem;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h3 class="mb-1">Form Surat Pengajuan</h3>
                    <p class="helper-text">Form ini menampilkan ringkasan sebelum Polda mengajukan Surat Pengajuan ke Bapenda/Jasa Raharja.</p>
                </div>
                <span class="badge-status">{{ strtoupper(Auth::user()->unit_kerja ?? 'Polda') }}</span>
            </div>

            <div class="mb-4">
                <h5 class="section-title">Ringkasan Pengajuan</h5>
                <table>
                    <tr>
                        <th>Nomor Pengajuan</th>
                        <td>{{ $pengajuan->nomor_pengajuan }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kendaraan</th>
                        <td>{{ $pengajuan->kendaraans->count() }}</td>
                    </tr>
                    <tr>
                        <th>Status Pengajuan</th>
                        <td>{{ ucfirst($pengajuan->status) }}</td>
                    </tr>
                    <tr>
                        <th>Surat Pengajuan Saat Ini</th>
                        <td>{{ $sp?->nomor_sp ?? 'Belum ada SP sebelumnya' }}</td>
                    </tr>
                </table>
            </div>

            <div class="mb-4">
                <h5 class="section-title">Daftar Kendaraan</h5>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NRKB</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan->kendaraans as $kendaraan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kendaraan->nrkb }}</td>
                                <td>{{ $kendaraan->merk_kendaraan }}</td>
                                <td>{{ $kendaraan->tipe_kendaraan }}</td>
                                <td>{{ ucfirst($kendaraan->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <h5 class="section-title">Tujuan Pengajuan</h5>
                <p class="helper-text">Surat Pengajuan ini akan dikirimkan ke Bapenda dan Jasa Raharja. Tombol <strong>Terima</strong> di footer modal akan membuat dokumen dan memulai proses persetujuan.</p>
            </div>

            <div>
                <h5 class="section-title">Petunjuk</h5>
                <p class="helper-text">Periksa kembali data ini, lalu gunakan tombol <strong>Terima</strong> pada footer modal untuk menyelesaikan pengajuan.</p>
            </div>
        </div>
    </body>
</html>
>>>>>>> 029fb85d7f861723a3cfa90ae806efc52e3771f8
