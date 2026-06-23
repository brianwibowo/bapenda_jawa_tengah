{{-- Modal Full Form: SP Balasan JR (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpBalasanJR" tabindex="-1" aria-labelledby="modalSpBalasanJRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 980px;">
        <form id="formSpBalasanJR" class="modal-content border-0 shadow" style="min-height: 75vh;" method="POST">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSpBalasanJRLabel">
                    <i class="fas fa-reply me-2"></i>Balas Surat Pengajuan (Jasa Raharja)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.25rem;">
                <div class="container-fluid" id="formSpBalasanJRContainer">
                    <hr>
                    <h6 class="fw-bold mb-3">Data Surat</h6>

                    {{-- Nomor Surat + Nomor Surat Regident --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Surat</label>
                            <input type="text" class="form-control" name="nomor_surat" required>
                            <small class="text-muted d-block mt-1">Contoh: AS/R/21/2025</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Surat Regident (Polda)</label>
                            <input type="text" class="form-control" name="nomor_surat_regident" required>
                            <small class="text-muted d-block mt-1">Contoh: B/4188/IV/YAN.1/2025/Ditlantas</small>
                        </div>
                    </div>

                    {{-- Nomor Surat Bapenda --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Surat Bapenda</label>
                            <input type="text" class="form-control" name="nomor_surat_bapenda" required>
                            <small class="text-muted d-block mt-1">Contoh: S/900.1.13.1/53/2025</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Data Penandatangan</h6>

                    {{-- Tempat + Tanggal Surat --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tempat Surat</label>
                            <input type="text" class="form-control" name="tempat_surat" value="Semarang" required>
                            <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Surat</label>
                            <input type="text" class="form-control" name="tanggal_surat" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                            <small class="text-muted d-block mt-1">Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
                        </div>
                    </div>

                    {{-- Nama + Jabatan Penandatangan --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Penandatangan</label>
                            <input type="text" class="form-control" name="nama_penandatangan" required>
                            <small class="text-muted d-block mt-1">Contoh: Triadi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jabatan Penandatangan</label>
                            <input type="text" class="form-control" name="jabatan_penandatangan" value="Kepala Kantor Wilayah Utama" required>
                            <small class="text-muted d-block mt-1">Contoh: Kepala Kantor Wilayah Utama</small>
                        </div>
                    </div>
                </div>

                {{-- Container Preview PDF --}}
                <div id="previewSpBalasanJRContainer" style="display:none;"></div>
            </div>

            {{-- Footer: Mode Form --}}
            <div class="modal-footer" id="footerFormSpBalasanJR">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnTolakSpBalasanJR">
                    <i class="fas fa-ban me-1"></i>Tolak
                </button>
                <button type="button" class="btn btn-outline-primary" id="btnPreviewSpBalasanJR">
                    <i class="fas fa-eye me-1"></i>Lihat Preview
                </button>
            </div>

            {{-- Footer: Mode Preview --}}
            <div class="modal-footer" id="footerPreviewSpBalasanJR" style="display:none;">
                <button type="button" class="btn btn-secondary" id="btnEditSpBalasanJR">Kembali Edit</button>
                <button type="button" class="btn btn-success" id="btnSubmitSpBalasanJRPreview">
                    <i class="fas fa-save me-1"></i>Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formSpBalasanJR');
    const formContainer = document.getElementById('formSpBalasanJRContainer');
    const previewContainer = document.getElementById('previewSpBalasanJRContainer');
    const footerForm = document.getElementById('footerFormSpBalasanJR');
    const footerPreview = document.getElementById('footerPreviewSpBalasanJR');
    // Custom PDF.js Viewer will render here
    
    const signedUrl = @json($signedUrls['sp_terima'] ?? '');
    const tolakUrl = @json($signedUrls['sp_tolak'] ?? '');
    let currentBlobUrl = null;

    if (!signedUrl) return; // No active SP to respond to

    // Preview
    document.getElementById('btnPreviewSpBalasanJR').addEventListener('click', async function() {
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
            window.BapendaPdfViewer.render('previewSpBalasanJRContainer', currentBlobUrl, 'sp_balasan_jasa_raharja.pdf');

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
    document.getElementById('btnEditSpBalasanJR').addEventListener('click', function () {
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });

    // Tolak SP
    document.getElementById('btnTolakSpBalasanJR').addEventListener('click', function() {
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

    document.getElementById('btnSubmitSpBalasanJRPreview').addEventListener('click', function() {
        submitForm(this);
    });

    // Reset on modal close
    document.getElementById('modalSpBalasanJR').addEventListener('hidden.bs.modal', function () {
        if (currentBlobUrl) {
            URL.revokeObjectURL(currentBlobUrl);
            currentBlobUrl = null;
        }
        window.BapendaPdfViewer.cleanup('previewSpBalasanJRContainer');
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });
});
</script>
