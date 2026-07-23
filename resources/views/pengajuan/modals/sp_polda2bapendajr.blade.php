{{-- Modal Full Form: SP Polda ke Bapenda/JR (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpPolda2bapendajr" tabindex="-1" aria-labelledby="modalSpPolda2bapendajrLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 980px;">
        <form id="formSpPolda2bapendajr" class="modal-content border-0 shadow" style="min-height: 75vh;" method="POST">
            @csrf
            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSpPolda2bapendajrLabel">Input Data Surat Pengajuan Polda ke Bapenda/JR</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="formSpPolda2bapendajrContainer">
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
                            <label class="form-label fw-bold">Tempat</label>
                            <input type="text" class="form-control" name="tempat" value="Semarang" required>
                            <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Dikeluarkan SP</label>
                            <input type="text" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                            <small class="text-muted d-block mt-1">Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Direktur (Beserta Gelar)</label>
                            <input type="text" class="form-control" name="nama_direktur" required>
                            <small class="text-muted d-block mt-1">Contoh: M. PRATAMA ADHYASASTRA, S.I.K., S.H., M.H.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pangkat Direktur</label>
                            <input type="text" class="form-control" name="pangkat_direktur" value="KOMBES POL" required>
                            <small class="text-muted d-block mt-1">Contoh: KOMBES POL</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Rujukan Surat</h6>
                    <div class="repeater-rujukan-sp">
                        <div data-repeater-list="group-rujukan">
                            <div data-repeater-item class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light text-muted">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                        <input type="text" class="form-control" name="rujukan" placeholder="Masukkan rujukan (contoh: Undang-Undang No...)" value="Undang-Undang Nomor 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan;">
                                    </div>
                                </div>
                                <div class="col-auto ps-0">
                                    <button data-repeater-delete type="button" class="btn btn-outline-danger btn-border d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;" title="Hapus Rujukan">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button data-repeater-create type="button" class="btn btn-sm btn-info shadow-sm px-3 text-white">
                                    <i class="fas fa-plus me-1"></i> Tambah Rujukan
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Tembusan Surat</h6>
                    <div class="repeater-tembusan-sp">
                        <div data-repeater-list="group-tembusan">
                            <div data-repeater-item class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-light text-muted">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                        <input type="text" class="form-control" name="tembusan" placeholder="Masukkan terusan (contoh: Kapolda...)" value="Kapolda Jateng.">
                                    </div>
                                </div>
                                <div class="col-auto ps-0">
                                    <button data-repeater-delete type="button" class="btn btn-outline-danger btn-border d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;" title="Hapus Tembusan">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button data-repeater-create type="button" class="btn btn-sm btn-info shadow-sm px-3 text-white">
                                    <i class="fas fa-plus me-1"></i> Tambah Tembusan Surat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Container Preview PDF --}}
                <div id="previewSpPolda2bapendajrContainer" style="display:none;"></div>
            </div>

            {{-- Footer: Mode Form --}}
            <div class="modal-footer" id="footerFormSpPolda2bapendajr">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-outline-primary" id="btnPreviewSpPolda2bapendajr" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat pratinjau dokumen PDF Surat Pengajuan">Lihat Preview</button>
            </div>

            {{-- Footer: Mode Preview --}}
            <div class="modal-footer" id="footerPreviewSpPolda2bapendajr" style="display:none;">
                <button type="button" class="btn btn-secondary" id="btnEditSpPolda2bapendajr">Kembali Edit</button>
                <button type="button" class="btn btn-success" id="btnSubmitSpPolda2bapendajrPreview" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan Surat Pengajuan ini sebagai draft">
                    <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formSpPolda2bapendajr');
        const formContainer = document.getElementById('formSpPolda2bapendajrContainer');
        const previewContainer = document.getElementById('previewSpPolda2bapendajrContainer');
        const footerForm = document.getElementById('footerFormSpPolda2bapendajr');
        const footerPreview = document.getElementById('footerPreviewSpPolda2bapendajr');
        
        if (typeof $ !== 'undefined' && $.fn.repeater) {
            $('.repeater-rujukan-sp').repeater({ initEmpty: false });
            $('.repeater-tembusan-sp').repeater({ initEmpty: false });
        }

        const signedUrl = @json($signedUrls['sp_ajukan'] ?? '');
        let currentBlobUrl = null;

        // Preview: fetch JSON with pdf_url
        document.getElementById('btnPreviewSpPolda2bapendajr').addEventListener('click', async function () {
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
                const pdfUrl = result.data?.pdf_url || null;
                if (!pdfUrl) throw new Error('No PDF URL returned');

                if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                
                const pdfResponse = await fetch(pdfUrl);
                const blob = await pdfResponse.blob();
                currentBlobUrl = URL.createObjectURL(blob);
                window.BapendaPdfViewer.render('previewSpPolda2bapendajrContainer', currentBlobUrl, 'sp_polda_ke_bapenda_jr.pdf');

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
        document.getElementById('btnEditSpPolda2bapendajr').addEventListener('click', function () {
            previewContainer.style.display = 'none';
            footerPreview.style.display = 'none';
            formContainer.style.display = 'block';
            footerForm.style.display = 'flex';
        });

        // Submit function
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
                    const msg = res.message || 'Surat Pengajuan berhasil disimpan sebagai draft.';
                    sessionStorage.setItem('toast_success', msg);
                    window.location.href = res.redirect_url || window.location.href;
                } else {
                    alert(res.error || res.message || 'Gagal menyimpan.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
                }
            })
            .catch(err => {
                console.error('Submit error:', err);
                alert('Gagal menyimpan.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
            });
        };

        document.getElementById('btnSubmitSpPolda2bapendajrPreview').addEventListener('click', function () {
            submitForm(this);
        });

        // Reset on modal close
        document.getElementById('modalSpPolda2bapendajr').addEventListener('hidden.bs.modal', function () {
            if (currentBlobUrl) {
                URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = null;
            }
            window.BapendaPdfViewer.cleanup('previewSpPolda2bapendajrContainer');
            previewContainer.style.display = 'none';
            footerPreview.style.display = 'none';
            formContainer.style.display = 'block';
            footerForm.style.display = 'flex';
        });
    });
    </script>
</div>
