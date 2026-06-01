<!-- Modal Form SK Jasa Raharja Pembebasan SWDKLLJ -->
<div class="modal fade" id="modalSkJasaRaharja" tabindex="-1" aria-labelledby="modalSkJasaRaharjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.generate_sk_jasa_raharja', $pengajuan->id) }}" id="formSkJasaRaharjaDraft" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="draft_mode" value="1">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalSkJasaRaharjaLabel">Input Data SK Jasa Raharja (Pembebasan SWDKLLJ)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formSkJasaRaharjaContainer">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Pilih Kendaraan</label>
                                <select class="form-select" name="kendaraan_id" required>
                                    <option value="">-- Pilih Kendaraan (NRKB) --</option>
                                    @foreach($pengajuan->kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->nrkb }} - {{ $k->merk_kendaraan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Permohonan Pembebasan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Permohonan</label>
                                <input type="text" class="form-control" name="tanggal_surat_permohonan" required placeholder="Contoh: 20 Mei 2025">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Regident (Ditlantas)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Regident</label>
                                <input type="text" class="form-control" name="nomor_surat_regident" required placeholder="Contoh: SKET/01VI/YAN.1/2025/Ditlantas">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Regident</label>
                                <input type="text" class="form-control" name="tanggal_surat_regident" required placeholder="Contoh: 30 Juni 2025">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Bapenda (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Bapenda</label>
                                <input type="text" class="form-control" name="nomor_surat_bapenda" placeholder="Contoh: 900.1.13.1/1865/2025">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat Bapenda</label>
                                <input type="text" class="form-control" name="tanggal_surat_bapenda" placeholder="Contoh: 8 Juli 2025">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Surat Keputusan Jasa Raharja</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Keputusan</label>
                                <input type="text" class="form-control" name="nomor_keputusan" required placeholder="Contoh: KEP/20/2025">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tempat Dikeluarkan SK</label>
                                <input type="text" class="form-control" name="tempat_sk" value="Semarang" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Dikeluarkan SK</label>
                                <input type="text" class="form-control" name="tanggal_sk" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Penandatangan</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nama Penandatangan (Beserta Gelar)</label>
                                <input type="text" class="form-control" name="nama_penandatangan" required placeholder="Contoh: Triadi, S.H., M.H.">
                                <small class="text-muted d-block mt-1">Jabatan: Kepala Kantor Wilayah PT Jasa Raharja Jawa Tengah</small>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Metode Penanda Tangan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="metode_penanda_tangan_jr" class="form-label fw-bold">Metode Penanda Tangan</label>
                                <select name="metode_penanda_tangan" id="metode_penanda_tangan_jr" class="form-select" required>
                                    <option value="" selected>Pilih Metode Penanda Tangan</option>
                                    <option value="ttd_elektronik">TTD Elektronik</option>
                                    <option value="ttd_basah">TTD Basah</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" style="display:none;" id="sk_jr_ttd_basah_container">
                                <label class="form-label fw-bold">Upload SK (Setelah TTD Basah)</label>
                                <div class="file-container" data-field="sk_jr_ttd_basah" data-accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240">
                                    <div class="file-input-group mb-2">
                                        <input type="file" class="form-control file-input" name="sk_jr_ttd_basah" accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240" id="sk_jr_ttd_basah">
                                        <small class="text-muted file-preview"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="previewSkJasaRaharjaContainer" style="display:none;">
                        <iframe id="iframePreviewSkJasaRaharja" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>

                <div class="modal-footer" id="footerFormSkJasaRaharja">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning text-dark" id="btnShowPreviewSkJasaRaharja">Lihat Preview</button>
                </div>
                <div class="modal-footer" id="footerPreviewSkJasaRaharja" style="display:none;">
                    <button type="button" class="btn btn-secondary" id="btnEditSkJasaRaharja">Kembali Edit</button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkJasaRaharjaDraft">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formSkJasaRaharjaDraft');
        const metodePenandaTangan = document.getElementById('metode_penanda_tangan_jr');
        const formContainer = document.getElementById('formSkJasaRaharjaContainer');
        const previewContainer = document.getElementById('previewSkJasaRaharjaContainer');
        const footerForm = document.getElementById('footerFormSkJasaRaharja');
        const footerPreview = document.getElementById('footerPreviewSkJasaRaharja');
        const iframePreview = document.getElementById('iframePreviewSkJasaRaharja');
        let currentBlobUrl = null;

        // Toggle upload field untuk TTD Basah
        metodePenandaTangan.addEventListener('change', function () {
            if (this.value === 'ttd_basah') {
                document.getElementById('sk_jr_ttd_basah_container').style.display = 'block';
                document.getElementById('sk_jr_ttd_basah').required = true;
            } else {
                document.getElementById('sk_jr_ttd_basah_container').style.display = 'none';
                document.getElementById('sk_jr_ttd_basah').required = false;
            }
        });

        // Preview PDF via AJAX
        document.getElementById('btnShowPreviewSkJasaRaharja').addEventListener('click', function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const formData = new FormData(form);
            formData.append('preview', '1');
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

            const url = `{{ route('admin.pengajuan.generate_sk_jasa_raharja', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/pdf' }
            })
            .then(response => { if (!response.ok) throw new Error('Request failed: ' + response.status); return response.blob(); })
            .then(blob => {
                if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = URL.createObjectURL(blob);
                iframePreview.src = currentBlobUrl;
                formContainer.style.display = 'none'; footerForm.style.display = 'none';
                previewContainer.style.display = 'block'; footerPreview.style.display = 'flex';
                btn.disabled = false; btn.innerText = 'Lihat Preview';
            })
            .catch(error => { console.error('Preview load failed:', error); alert('Gagal memuat preview PDF.'); btn.disabled = false; btn.innerText = 'Lihat Preview'; });
        });

        // Kembali ke mode edit
        document.getElementById('btnEditSkJasaRaharja').addEventListener('click', function () {
            previewContainer.style.display = 'none'; footerPreview.style.display = 'none';
            formContainer.style.display = 'block'; footerForm.style.display = 'flex';
        });

        // Submit sebagai Draft (AJAX)
        document.getElementById('btnSubmitSkJasaRaharjaDraft').addEventListener('click', function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            const formData = new FormData(form);
            const url = `{{ route('admin.pengajuan.draft_sk', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST', body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect) { window.location.href = data.redirect; }
                else { alert(data.message || 'Terjadi kesalahan.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft'; }
            })
            .catch(error => { console.error('Draft save failed:', error); alert('Gagal menyimpan draft.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft'; });
        });
        
        // Cleanup saat modal ditutup
        document.getElementById('modalSkJasaRaharja').addEventListener('hidden.bs.modal', function () {
            if (currentBlobUrl) { URL.revokeObjectURL(currentBlobUrl); currentBlobUrl = null; iframePreview.src = ''; }
            previewContainer.style.display = 'none'; footerPreview.style.display = 'none';
            formContainer.style.display = 'block'; footerForm.style.display = 'flex';
        });
    });
    </script>
</div>