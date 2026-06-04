{{-- Modal Full Form: SP Balasan Bapenda (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpBalasanBapenda" tabindex="-1" aria-labelledby="modalSpBalasanBapendaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="formSpBalasanBapenda" class="modal-content border-0 shadow" method="POST">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSpBalasanBapendaLabel">
                    <i class="fas fa-reply me-2"></i>Balas Surat Pengajuan (Bapenda)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.25rem;">
                <div class="container-fluid" id="formSpBalasanBapendaContainer">
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
                            <small class="text-muted d-block mt-1">Contoh: 1 Berkas</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Hal</label>
                            <input type="text" class="form-control" name="hal" required>
                            <small class="text-muted d-block mt-1">Contoh: Pembebasan Pajak Kendaraan Bermotor</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Data Penandatangan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Provinsi</label>
                            <input type="text" class="form-control" name="provinsi" value="Jawa Tengah" required>
                            <small class="text-muted d-block mt-1">Contoh: Jawa Tengah</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Penandatangan (Beserta Gelar)</label>
                            <input type="text" class="form-control" name="nama_penandatangan" required>
                            <small class="text-muted d-block mt-1">Contoh: NADIATUL ANWARAH, S.H., M.H.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jabatan</label>
                            <input type="text" class="form-control" name="jabatan" value="Kepala Bidang Pajak Kendaraan Bermotor" required>
                            <small class="text-muted d-block mt-1">Contoh: Kepala Bidang Pajak Kendaraan Bermotor</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" class="form-control" name="nip" required>
                            <small class="text-muted d-block mt-1">Contoh: 19780211 200501 2 007</small>
                        </div>
                    </div>
                </div>

                {{-- Container Preview PDF --}}
                <div id="previewSpBalasanBapendaContainer" style="display:none;">
                    <iframe id="iframePreviewSpBalasanBapenda" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                </div>
            </div>

            {{-- Footer: Mode Form --}}
            <div class="modal-footer" id="footerFormSpBalasanBapenda">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnTolakSpBalasanBapenda">
                    <i class="fas fa-ban me-1"></i>Tolak
                </button>
                <button type="button" class="btn btn-outline-primary" id="btnPreviewSpBalasanBapenda">
                    <i class="fas fa-eye me-1"></i>Lihat Preview
                </button>
                <button type="button" class="btn btn-success" id="btnSubmitSpBalasanBapenda">
                    <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                </button>
            </div>

            {{-- Footer: Mode Preview --}}
            <div class="modal-footer" id="footerPreviewSpBalasanBapenda" style="display:none;">
                <button type="button" class="btn btn-warning" id="btnEditSpBalasanBapenda">Kembali Edit</button>
                <button type="button" class="btn btn-success" id="btnSubmitSpBalasanBapendaPreview">
                    <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formSpBalasanBapenda');
    const formContainer = document.getElementById('formSpBalasanBapendaContainer');
    const previewContainer = document.getElementById('previewSpBalasanBapendaContainer');
    const footerForm = document.getElementById('footerFormSpBalasanBapenda');
    const footerPreview = document.getElementById('footerPreviewSpBalasanBapenda');
    const iframePreview = document.getElementById('iframePreviewSpBalasanBapenda');
    
    const signedUrl = @json($signedUrls['sp_terima'] ?? '');
    const tolakUrl = @json($signedUrls['sp_tolak'] ?? '');
    let currentBlobUrl = null;

    if (!signedUrl) return; // No active SP to respond to

    // Preview
    document.getElementById('btnPreviewSpBalasanBapenda').addEventListener('click', async function() {
        if (!form.checkValidity()) { form.reportValidity(); return; }
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memuat...';

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

            if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
            
            const pdfResponse = await fetch(pdfUrl);
            const blob = await pdfResponse.blob();
            currentBlobUrl = URL.createObjectURL(blob);
            iframePreview.src = currentBlobUrl;

            formContainer.style.display = 'none';
            footerForm.style.display = 'none';
            previewContainer.style.display = 'block';
            footerPreview.style.display = 'flex';
        } catch (error) {
            console.error('Preview error:', error);
            alert('Gagal memuat preview. Silakan coba lagi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
        }
    });

    // Kembali Edit
    document.getElementById('btnEditSpBalasanBapenda').addEventListener('click', function () {
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });

    // Tolak SP
    document.getElementById('btnTolakSpBalasanBapenda').addEventListener('click', function() {
        const btn = this;
        if (!confirm('Apakah Anda yakin ingin menolak Surat Pengajuan ini?')) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menolak...';
        
        const formData = new FormData(form);
        fetch(tolakUrl, {
            method: 'POST', body: formData,
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => {
            if (r.redirected) { window.location.href = r.url; return; }
            return r.text().then(() => { window.location.reload(); });
        })
        .catch(() => {
            alert('Gagal menolak.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-ban me-1"></i>Tolak';
        });
    });

    // Submit
    const submitForm = function (btn) {
        if (!form.checkValidity()) { form.reportValidity(); return; }
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';
        const formData = new FormData(form);
        fetch(signedUrl, {
            method: 'POST', body: formData,
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => {
            if (r.redirected) { window.location.href = r.url; return; }
            return r.text().then(() => { window.location.reload(); });
        })
        .catch(() => {
            alert('Gagal menyimpan.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan sebagai Draft';
        });
    };

    document.getElementById('btnSubmitSpBalasanBapenda').addEventListener('click', function() {
        submitForm(this);
    });

    document.getElementById('btnSubmitSpBalasanBapendaPreview').addEventListener('click', function() {
        submitForm(this);
    });

    // Reset on modal close
    document.getElementById('modalSpBalasanBapenda').addEventListener('hidden.bs.modal', function () {
        if (currentBlobUrl) {
            URL.revokeObjectURL(currentBlobUrl);
            currentBlobUrl = null;
            iframePreview.src = '';
        }
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });
});
</script>