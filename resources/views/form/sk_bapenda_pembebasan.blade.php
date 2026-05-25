{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.buat_sk', $pengajuan->id) }}"
      method="POST">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formSkPembebasanContainer" style="padding: 0.25rem 0.25rem;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Pilih Kendaraan</label>
                    <select class="form-select" name="kendaraan_id" required>
                        <option value="">-- Pilih Kendaraan (NRKB) --</option>
                        <option value="all">Semua Kendaraan</option>
                        @foreach($pengajuan->kendaraans as $k)
                            @if ($k->suratKeputusans->where('unit_kerja', $normalizedUnitKerja)->count() > 0)
                                @continue
                            @endif
                            <option value="{{ $k->id }}">{{ $k->nrkb }} - {{ $k->merk_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Data Surat Permohonan Regident</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
                    <input type="text" class="form-control" name="nama_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: Dwiyanto Setyo Budi</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tempat_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: Temanggung</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tanggal_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: 13 Mei 2024</small>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Data Surat Keputusan Regident</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat Keterangan Penghapusan Regident</label>
                    <input type="text" class="form-control" name="nomor_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: SKET/ 01 /VI/YAN.1/2025/Ditlantas</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
                    <input type="text" class="form-control" name="nama_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: Dwiyanto Setyo Budi</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tempat_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: Temanggung</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tanggal_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: 20 Juni 2025</small>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Data Surat Keputusan Pembebasan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat Keterangan Pembebasan</label>
                    <input type="text" class="form-control" name="nomor_surat_pembebasan" required>
                    <small class="text-muted d-block mt-1">Contoh: 900.1.13.1 /1865/2025</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Dikeluarkan SK</label>
                    <input type="text" class="form-control" name="tempat_sk" value="Semarang" required>
                    <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Dikeluarkan SK</label>
                    <input type="text" class="form-control" name="tanggal_sk"
                           value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                    <small class="text-muted d-block mt-1">Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Data Penandatangan (Direktur)</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Direktur (Beserta Pangkat/Gelar)</label>
                    <input type="text" class="form-control" name="nama_direktur" required>
                    <small class="text-muted d-block mt-1">Contoh: M. PRATAMA ADHYASASTRA, S.I.K., S.H., M.H.</small>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Metode Penanda Tangan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="metode_penanda_tangan" class="form-label fw-bold">Metode Penanda Tangan</label>
                    <select name="metode_penanda_tangan" id="metode_penanda_tangan" class="form-select" required>
                        <option value="" selected>Pilih Metode Penanda Tangan </option>
                        <option value="ttd_elektronik">TTD Elektronik</option>
                        <option value="ttd_basah">TTD Basah</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</form>
