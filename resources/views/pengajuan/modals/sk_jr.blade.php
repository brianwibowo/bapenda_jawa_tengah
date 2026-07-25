{{-- Modal Form SK Jasa Raharja Pembebasan SWDKLLJ --}}
<div class="modal fade" id="modalSkJR" tabindex="-1" aria-labelledby="modalSkJRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 980px;">
        <form id="formSkJR" class="modal-content border-0 shadow" style="min-height: 75vh;" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalSkJRLabel">
                        <i class="fas fa-file-contract me-2"></i>Input Data SK Jasa Raharja (Pembebasan SWDKLLJ)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" id="formSkJRContainer">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Pilih Kendaraan</label>
                                <select class="form-select" name="kendaraan_id" required>
                                    <option value="">-- Pilih Kendaraan (NRKB) --</option>
                                    @foreach($pengajuan->kendaraans as $index => $k)
                                        <option value="{{ $k->id }}">Kendaraan {{ $index + 1 }} — {{ $k->nrkb }}{{ !empty($k->merk_kendaraan) ? ' (' . $k->merk_kendaraan . ')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Permohonan Pembebasan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Permohonan</label>
                                <input type="text" class="form-control" name="tanggal_surat_permohonan" value="20 Mei 2025" placeholder="Contoh: 20 Mei 2025" required>
                                <small class="text-muted d-block mt-1">Contoh: 20 Mei 2025</small>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Regident (Ditlantas)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Regident</label>
                                <input type="text" class="form-control" name="nomor_surat_regident" value="SKET/01VI/YAN.1/2025/Ditlantas" placeholder="Contoh: SKET/01VI/YAN.1/2025/Ditlantas" required>
                                <small class="text-muted d-block mt-1">Contoh: SKET/01VI/YAN.1/2025/Ditlantas</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Regident</label>
                                <input type="text" class="form-control" name="tanggal_surat_regident" value="30 Juni 2025" placeholder="Contoh: 30 Juni 2025" required>
                                <small class="text-muted d-block mt-1">Contoh: 30 Juni 2025</small>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Bapenda (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Bapenda</label>
                                <input type="text" class="form-control" name="nomor_surat_bapenda" value="900.1.13.1/1865/2025" placeholder="Contoh: 900.1.13.1/1865/2025">
                                <small class="text-muted d-block mt-1">Contoh: 900.1.13.1/1865/2025</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Bapenda</label>
                                <input type="text" class="form-control" name="tanggal_surat_bapenda" value="8 Juli 2025" placeholder="Contoh: 8 Juli 2025">
                                <small class="text-muted d-block mt-1">Contoh: 8 Juli 2025</small>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Keputusan Jasa Raharja</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Keputusan</label>
                                <input type="text" class="form-control" name="nomor_keputusan" value="KEP/20/2025" placeholder="Contoh: KEP/20/2025" required>
                                <small class="text-muted d-block mt-1">Contoh: KEP/20/2025</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tempat Dikeluarkan SK</label>
                                <input type="text" class="form-control" name="tempat_sk" value="Semarang" placeholder="Contoh: Semarang" required>
                                <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Dikeluarkan SK</label>
                                <input type="text" class="form-control" name="tanggal_sk" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" placeholder="Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                                <small class="text-muted d-block mt-1">Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Penandatangan</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nama Penandatangan (Beserta Gelar)</label>
                                <input type="text" class="form-control" name="nama_penandatangan" value="Triadi, S.H., M.H." placeholder="Contoh: Triadi, S.H., M.H." required>
                                <small class="text-muted d-block mt-1">Contoh: Triadi, S.H., M.H.</small>
                                <small class="text-muted d-block mt-1">Jabatan: Kepala Kantor Wilayah PT Jasa Raharja Jawa Tengah</small>
                        </div>
                        <input type="hidden" name="metode_penanda_tangan" id="metode_penanda_tangan_jr" value="ttd_basah">
                    </div>
                    </div>

                    {{-- Container Preview PDF --}}
                    <div id="previewSkJRContainer" style="display:none;">
                        <iframe id="iframePreviewSkJR" src="" style="width:100%; height:520px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>

                {{-- Footer: Mode Form --}}
                <div class="modal-footer" id="footerFormSkJR">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning text-dark" id="btnShowPreviewSkJR" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat pratinjau Surat Keputusan Jasa Raharja sebelum diterbitkan">Lihat Preview</button>
                </div>
                
                {{-- Footer: Mode Preview --}}
                <div class="modal-footer" id="footerPreviewSkJR" style="display:none;">
                    <button type="button" class="btn btn-secondary" id="btnEditSkJR">Kembali Edit</button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkJRDraft" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan sebagai draf Surat Keputusan Jasa Raharja">
                        <i class="fas fa-save me-1"></i> Simpan Draf SK
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formSkJR');
    const metodePenandaTangan = document.getElementById('metode_penanda_tangan_jr');
    const formContainer = document.getElementById('formSkJRContainer');
    const previewContainer = document.getElementById('previewSkJRContainer');
    const iframePreview = document.getElementById('iframePreviewSkJR');
    const footerForm = document.getElementById('footerFormSkJR');
    const footerPreview = document.getElementById('footerPreviewSkJR');
    
    const signedUrl = @json($signedUrls['sk_buat'] ?? '');
    let currentBlobUrl = null;

    // Preview
    document.getElementById('btnShowPreviewSkJR').addEventListener('click', async function () {
        if (!form.checkValidity()) { form.reportValidity(); return; }
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

        try {
            const formData = new FormData(form);
            formData.append('preview', '1');
            const response = await fetch(signedUrl, {
                method: 'POST', body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Request failed: ' + response.status);
            const result = await response.json();

            let pdfUrl = null;
            if (result.data) {
                if (result.data.pdf_url) { pdfUrl = result.data.pdf_url; }
                else {
                    const firstKey = Object.keys(result.data)[0];
                    if (firstKey && result.data[firstKey]) { pdfUrl = result.data[firstKey].pdf_url || null; }
                }
            }
            if (!pdfUrl) throw new Error('No PDF URL returned');

            if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
            
            const pdfResponse = await fetch(pdfUrl);
            const blob = await pdfResponse.blob();
            currentBlobUrl = URL.createObjectURL(blob);

            previewContainer.innerHTML = '<iframe id="iframePreviewSkJR" src="' + currentBlobUrl + '" style="width:100%; height:520px; border:1px solid #ddd; border-radius:8px;"></iframe>';

            formContainer.style.display = 'none';
            footerForm.style.display = 'none';
            previewContainer.style.display = 'block';
            footerPreview.style.display = 'flex';
        } catch (error) {
            console.error('Preview load failed:', error);
            alert('Gagal memuat preview PDF.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
        }
    });

    // Kembali Edit
    document.getElementById('btnEditSkJR').addEventListener('click', function () {
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });

    // Submit
    const submitForm = function (btn) {
        if (!form.checkValidity()) { form.reportValidity(); return; }
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        const formData = new FormData(form);
        fetch(signedUrl, {
            method: 'POST', body: formData,
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async r => {
            const res = await r.json().catch(() => ({}));
            if (r.ok || res.success) {
                const msg = res.message || 'Surat Keputusan Jasa Raharja berhasil disimpan.';
                sessionStorage.setItem('toast_success', msg);
                window.location.href = res.redirect_url || window.location.href;
            } else {
                alert(res.error || res.message || 'Gagal menyimpan.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Draf SK';
            }
        })
        .catch(err => {
            console.error('Submit error:', err);
            alert('Gagal menyimpan.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Draf SK';
        });
    };

    document.getElementById('btnSubmitSkJRDraft').addEventListener('click', function () {
        submitForm(this);
    });

    // Reset on modal close
    document.getElementById('modalSkJR').addEventListener('hidden.bs.modal', function () {
        if (currentBlobUrl) {
            URL.revokeObjectURL(currentBlobUrl);
            currentBlobUrl = null;
        }
        previewContainer.innerHTML = '';
        previewContainer.style.display = 'none';
        footerPreview.style.display = 'none';
        formContainer.style.display = 'block';
        footerForm.style.display = 'flex';
    });
});
</script>
