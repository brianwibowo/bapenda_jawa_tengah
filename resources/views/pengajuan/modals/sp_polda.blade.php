{{-- Modal Full Form: SP Polda → Bapenda/JR (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpPolda" tabindex="-1" aria-labelledby="modalSpPoldaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSpPoldaLabel">
                    <i class="fas fa-file-alt me-2"></i>Surat Pengajuan ke Bapenda & Jasa Raharja
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSpPolda" method="POST" action="{{ route('admin.pengajuan.ajukan', $pengajuan->id) }}">
                @csrf
                <div class="modal-body" style="padding: 1.25rem;">

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnPreviewSpPolda">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanSpPolda">
                        <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSpPolda = document.getElementById('formSpPolda');
    const btnPreview = document.getElementById('btnPreviewSpPolda');
    const btnSimpan = document.getElementById('btnSimpanSpPolda');

    if (btnPreview && formSpPolda) {
        btnPreview.addEventListener('click', async function() {
            // Validate form first
            if (!formSpPolda.checkValidity()) {
                formSpPolda.reportValidity();
                return;
            }

            btnPreview.disabled = true;
            btnPreview.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memuat...';

            try {
                const formData = new FormData(formSpPolda);
                formData.append('preview', '1');

                const response = await fetch(formSpPolda.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                if (result.data && result.data.pdf_url) {
                    // Hide this modal and open PDF viewer
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalSpPolda'));
                    modal.hide();

                    if (typeof openPdfViewer === 'function') {
                        openPdfViewer(result.data.pdf_url, {
                            title: 'Preview Surat Pengajuan Polda',
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

    if (formSpPolda && btnSimpan) {
        formSpPolda.addEventListener('submit', function(e) {
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
        });
    }
});
</script>
