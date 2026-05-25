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
        <div id="formSkPenghapusanRegidentContainer" style="padding: 0.25rem 0.25rem;">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" required>
                    <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }}/{{ date('m/Y') }}/Ditlantas</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Sifat</label>
                    <input type="text" class="form-control" name="sifat" required>
                    <small class="text-muted d-block mt-1">Contoh: Segera</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Lampiran</label>
                    <input type="text" class="form-control" name="lampiran" required>
                    <small class="text-muted d-block mt-1">Contoh: 1</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Hal</label>
                    <input type="text" class="form-control" name="hal" required>
                    <small class="text-muted d-block mt-1">Contoh: Penghapusan Regident AA 9660 QE</small>
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">Data Penandatangan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Provinsi</label>
                    <input type="text" class="form-control" name="provinsi" required>
                    <small class="text-muted d-block mt-1">Contoh: Jawa Tengah</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama (Beserta Pangkat/Gelar)</label>
                    <input type="text" class="form-control" name="nama_penandatangan" required>
                    <small class="text-muted d-block mt-1">Contoh: Nadi Santoso, SP, M.Si</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" required>
                    <small class="text-muted d-block mt-1">Contoh: Pembina Utama Muda</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIP</label>
                    <input type="text" class="form-control" name="nip" required>
                    <small class="text-muted d-block mt-1">Contoh: 197009191996031003</small>
                </div>
            </div>
        </div>

    </div>
</form>