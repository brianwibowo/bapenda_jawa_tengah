{{-- Modal Full Form: SP Balasan Bapenda/JR (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpBapendaJr" tabindex="-1" aria-labelledby="modalSpBapendaJrLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSpBapendaJrLabel">
                    <i class="fas fa-reply me-2"></i>Balas Surat Pengajuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSpBapendaJr" method="POST"
                  action="{{ isset($lastSp) ? route('admin.pengajuan.sp.terima', $lastSp->id) : '#' }}">
                @csrf
                <div class="modal-body" style="padding: 1.25rem;">

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnPreviewSpBapendaJr">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSimpanSpBapendaJr">
                        <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSpBapendaJr = document.getElementById('formSpBapendaJr');
    const btnPreview = document.getElementById('btnPreviewSpBapendaJr');
    const btnSimpan = document.getElementById('btnSimpanSpBapendaJr');

    if (btnPreview && formSpBapendaJr) {
        btnPreview.addEventListener('click', async function() {
            if (!formSpBapendaJr.checkValidity()) {
                formSpBapendaJr.reportValidity();
                return;
            }

            btnPreview.disabled = true;
            btnPreview.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memuat...';

            try {
                const formData = new FormData(formSpBapendaJr);
                formData.append('preview', '1');

                const response = await fetch(formSpBapendaJr.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                if (result.data && result.data.pdf_url) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalSpBapendaJr'));
                    modal.hide();

                    if (typeof openPdfViewer === 'function') {
                        openPdfViewer(result.data.pdf_url, {
                            title: 'Preview Surat Balasan',
                            onBack: function() {
                                modal.show();
                            }
                        });
                    } else {
                        window.open(result.data.pdf_url, '_blank');
                    }
                }
            } catch (error) {
                console.error('Preview error:', error);
                alert('Gagal memuat preview. Silakan coba lagi.');
            } finally {
                btnPreview.disabled = false;
                btnPreview.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
            }
        });
    }

    if (formSpBapendaJr && btnSimpan) {
        formSpBapendaJr.addEventListener('submit', function(e) {
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
        });
    }
});
</script>
