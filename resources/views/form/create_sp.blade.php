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
