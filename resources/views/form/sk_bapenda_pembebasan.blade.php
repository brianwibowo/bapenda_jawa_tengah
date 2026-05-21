{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.buat_sk', $pengajuan->id) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formSkPembebasanContainer" style="padding: 0.25rem 0.25rem;">
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

            <h6 class="fw-bold mb-3">Data Surat Permohonan Regident</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
                    <input type="text" class="form-control" name="nama_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: Dwiyanto Setyo Budi</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tempat_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: Temanggung</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tanggal_pembuat_surat_permohonan" required>
                    <small class="text-muted d-block mt-1">Contoh: 13 Mei 2024</small>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Data Surat Keputusan Regident</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat Keterangan Penghapusan Regident</label>
                    <input type="text" class="form-control" name="nomor_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: SKET/ 01 /VI/YAN.1/2025/Ditlantas</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
                    <input type="text" class="form-control" name="nama_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: Dwiyanto Setyo Budi</small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tempat_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: Temanggung</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
                    <input type="text" class="form-control" name="tanggal_pembuat_surat_regident" required>
                    <small class="text-muted d-block mt-1">Contoh: 20 Juni 2025</small>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-3">Data Surat Keputusan Pembebasan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat Keterangan Pembebasan</label>
                    <input type="text" class="form-control" name="nomor_surat_pembebasan" required>
                    <small class="text-muted d-block mt-1">Contoh: 900.1.13.1 /1865/2025</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat Dikeluarkan SK</label>
                    <input type="text" class="form-control" name="tempat_sk" value="Semarang" required>
                    <small class="text-muted d-block mt-1">Contoh: Semarang</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Dikeluarkan SK</label>
                    <input type="text" class="form-control" name="tanggal_sk"
                           value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
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
            </div>

            <h6 class="fw-bold mb-3">Metode Penanda Tangan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="metode_penanda_tangan" class="form-label fw-bold">Metode Penanda Tangan</label>
                    <select name="metode_penanda_tangan" id="metode_penanda_tangan" class="form-select" required>
                        <option value="" selected>Pilih Metode Penanda Tangan </option>
                        <option value="ttd_elektronik">TTD Elektronik</option>
                        <option value="ttd_basah">TTD Basah</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3" style="display:none;" id="sk_pembebasan_ttd_basah_container">
                    <label class="form-label fw-bold">Upload SK (Setelah TTD Basah)</label>
                    <div class="file-container" data-field="sk_pembebasan_ttd_basah"
                         data-accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240">
                        <div class="file-input-group mb-2">
                            <input type="file" class="form-control file-input" name="sk_pembebasan_ttd_basah"
                                   accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240"
                                   id="sk_pembebasan_ttd_basah">
                            <small class="text-muted file-preview"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Container: Preview PDF --}}
        <div id="previewSkPembebasanContainer" style="display:none; padding: 0.25rem 0;">
            <iframe id="iframePreviewSkPembebasan" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
        </div>

    </div>

    {{-- ===== FOOTER (diambil oleh frame.js dan dipindah ke .modal-footer) ===== --}}
    <div data-frame-footer style="display:none;">
        {{-- Footer: saat form ditampilkan --}}
        <div id="footerFormSkPembebasan" style="display:flex; gap:0.5rem; width:100%; justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" id="frameFormCancelBtn">Batal</button>
            <button type="button" class="btn btn-primary" id="btnShowPreviewSkPembebasan">Lihat Preview</button>
        </div>
        {{-- Footer: saat preview ditampilkan --}}
        <div id="footerPreviewSkPembebasan" style="display:none; gap:0.5rem; width:100%; justify-content:flex-end;">
            <button type="button" class="btn btn-warning" id="btnEditSkPembebasan">Kembali Edit</button>
            <button type="submit" class="btn btn-success" form="frameForm">
                <i class="fas fa-paper-plane me-1"></i> Kirim &amp; Simpan
            </button>
        </div>
    </div>
</form>

<script data-form-script>
(function () {
    // ── Metode TTD toggle ────────────────────────────────────────────────────
    const metodePenandaTangan = document.getElementById('metode_penanda_tangan');
    if (metodePenandaTangan) {
        metodePenandaTangan.addEventListener('change', function () {
            const ttdBasahContainer = document.getElementById('sk_pembebasan_ttd_basah_container');
            const ttdBasahInput     = document.getElementById('sk_pembebasan_ttd_basah');
            if (this.value === 'ttd_basah') {
                if (ttdBasahContainer) ttdBasahContainer.style.display = 'block';
                if (ttdBasahInput)     ttdBasahInput.required = true;
            } else {
                if (ttdBasahContainer) ttdBasahContainer.style.display = 'none';
                if (ttdBasahInput)     ttdBasahInput.required = false;
            }
        });
    }

    // ── Preview / Edit logic ─────────────────────────────────────────────────
    const form             = document.getElementById('frameForm');
    const formContainer    = document.getElementById('formSkPembebasanContainer');
    const previewContainer = document.getElementById('previewSkPembebasanContainer');
    const iframePreview    = document.getElementById('iframePreviewSkPembebasan');
    const footerForm       = document.getElementById('footerFormSkPembebasan');
    const footerPreview    = document.getElementById('footerPreviewSkPembebasan');
    const modalFooter      = document.querySelector('#ViewerModal .modal-footer');
    let currentBlobUrl     = null;

    // Pindahkan kedua footer ke .modal-footer ViewerModal
    if (modalFooter && footerForm && footerPreview) {
        modalFooter.innerHTML = '';
        modalFooter.appendChild(footerForm);
        modalFooter.appendChild(footerPreview);
        footerForm.style.display    = 'flex';
        footerPreview.style.display = 'none';
    }

    // Cancel button
    const cancelBtn = document.getElementById('frameFormCancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            if (typeof $ !== 'undefined') {
                $('#ViewerModal').modal('hide');
            }
        });
    }

    // Lihat Preview
    const btnPreview = document.getElementById('btnShowPreviewSkPembebasan');
    if (btnPreview) {
        btnPreview.addEventListener('click', function () {
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            formData.append('preview', '1');

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

            const url = `{{ route('admin.pengajuan.generate_sk_pembebasan', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/pdf'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                return response.blob();
            })
            .then(blob => {
                if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = URL.createObjectURL(blob);
                iframePreview.src = currentBlobUrl;

                formContainer.style.display    = 'none';
                previewContainer.style.display = 'block';
                footerForm.style.display       = 'none';
                footerPreview.style.display    = 'flex';

                btn.disabled  = false;
                btn.innerHTML = 'Lihat Preview';
            })
            .catch(error => {
                console.error('Preview load failed:', error);
                alert('Gagal memuat preview PDF. Silakan coba lagi.');
                btn.disabled  = false;
                btn.innerHTML = 'Lihat Preview';
            });
        });
    }

    // Kembali Edit
    const btnEdit = document.getElementById('btnEditSkPembebasan');
    if (btnEdit) {
        btnEdit.addEventListener('click', function () {
            previewContainer.style.display = 'none';
            formContainer.style.display    = 'block';
            footerPreview.style.display    = 'none';
            footerForm.style.display       = 'flex';
        });
    }

    // Reset saat modal ditutup
    const viewerModal = document.getElementById('ViewerModal');
    if (viewerModal) {
        viewerModal.addEventListener('hidden.bs.modal', function () {
            if (currentBlobUrl) {
                URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl    = null;
                iframePreview.src = '';
            }
            previewContainer.style.display = 'none';
            formContainer.style.display    = 'block';
            footerPreview.style.display    = 'none';
            if (footerForm) footerForm.style.display = 'flex';
        });
    }
})();
</script>