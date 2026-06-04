{{-- Modal Full Form: SP Balasan Bapenda (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpBalasanBapenda" tabindex="-1" aria-labelledby="modalSpBalasanBapendaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="formSpBalasanBapenda" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSpBalasanBapendaLabel">
                        <i class="fas fa-reply me-2"></i>Balas Surat Pengajuan (Bapenda)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
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
                    <button type="button" class="btn btn-outline-primary" id="btnPreviewSpBalasanBapenda">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="button" class="btn btn-success" id="btnSubmitSpBalasanBapenda">
                        <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formSpBalasanBapenda');
    const signedUrl = @json($signedUrls['sp_terima'] ?? '');

    if (!signedUrl) return; // No active SP to respond to

    // Preview: uses terima route with preview=1
    const btnPreview = document.getElementById('btnPreviewSpBalasanBapenda');
    if (btnPreview && form) {
        btnPreview.addEventListener('click', async function() {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            btnPreview.disabled = true;
            btnPreview.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memuat...';

            try {
                const formData = new FormData(form);
                formData.append('preview', '1');
                const response = await fetch(signedUrl, {
                    method: 'POST', body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                const result = await response.json();
                const pdfUrl = result.data?.pdf_url || null;
                if (!pdfUrl) throw new Error('No PDF URL returned');

                const modal = bootstrap.Modal.getInstance(document.getElementById('modalSpBalasanBapenda'));
                modal.hide();
                if (typeof openPdfViewer === 'function') {
                    openPdfViewer(pdfUrl, { title: 'Preview SP Balasan Bapenda', onBack: () => modal.show() });
                } else { window.open(pdfUrl, '_blank'); }
            } catch (error) {
                console.error('Preview error:', error);
                alert('Gagal memuat preview. Silakan coba lagi.');
            } finally {
                btnPreview.disabled = false;
                btnPreview.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
            }
        });
    }

    // Submit (no preview → actual submission via terima)
    const btnSubmit = document.getElementById('btnSubmitSpBalasanBapenda');
    if (btnSubmit && form) {
        btnSubmit.addEventListener('click', function() {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
            const formData = new FormData(form);
            fetch(signedUrl, {
                method: 'POST', body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'text/html' }
            })
            .then(r => {
                if (r.redirected) { window.location.href = r.url; return; }
                return r.text().then(() => { window.location.reload(); });
            })
            .catch(() => { alert('Gagal menyimpan.'); btnSubmit.disabled = false; btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i>Simpan sebagai Draft'; });
        });
    }
});
</script>