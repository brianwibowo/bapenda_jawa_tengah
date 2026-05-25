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
        <div id="formSkPoldaContainer" style="padding: 0.25rem 0.25rem;">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" required>
                    <small class="text-muted d-block mt-1">Contoh: B/9660-QE/IV/YAN.1./2025/DITLANTAS</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Pernyataan</label>
                    <input type="text" class="form-control" name="nama_pembuat" required>
                    <small class="text-muted d-block mt-1">Contoh: Dwiyanto Setyo Budi</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Dikeluarkan</label>
                    <input type="text" class="form-control" name="tempat" required>
                    <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Dikeluarkan</label>
                    <input type="text" class="form-control" name="tanggal_keluar" required>
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
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Pangkat / NRP</label>
                    <input type="text" class="form-control" name="pangkat_direktur" required>
                    <small class="text-muted d-block mt-1">Contoh: KOMISARIS BESAR POLISI NRP 680903</small>
                </div>
            </div>
        </div>

    </div>
</form>
