{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.generate_sk_regident', $pengajuan->id) }}"
      method="POST">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formSkRegidentContainer" style="padding: 0.25rem 0.25rem;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Pilih Kendaraan</label>
                    <select class="form-select" name="kendaraan_id" required>
                        <option value="">-- Pilih Kendaraan (NRKB) --</option>
                        <option value="all">Semua Kendaraan</option>
                        @foreach($pengajuan->kendaraans as $k)
                            <option value="{{ $k->id }}">{{ $k->nrkb }} - {{ $k->merk_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" required>
                    <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }}/{{ date('m/Y') }}/Ditlantas</small>
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

        {{-- Container: Preview PDF --}}
        <div id="previewSkRegidentContainer" style="display:none; padding: 0.25rem 0;">
            <iframe id="iframePreviewSkRegident" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
        </div>

    </div>

    {{-- ===== FOOTER (diambil oleh frame.js dan dipindah ke .modal-footer) ===== --}}
    <div data-frame-footer style="display:none;">
        {{-- Footer: saat form ditampilkan --}}
        <div id="footerFormSkRegident" style="display:flex; gap:0.5rem; width:100%; justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" id="frameFormCancelBtn">Batal</button>
            <button type="button" class="btn btn-primary" id="btnShowPreviewSkRegident">Lihat Preview</button>
        </div>
        {{-- Footer: saat preview ditampilkan --}}
        <div id="footerPreviewSkRegident" style="display:none; gap:0.5rem; width:100%; justify-content:flex-end;">
            <button type="button" class="btn btn-warning" id="btnEditSkRegident">Kembali Edit</button>
            <button type="submit" class="btn btn-success" form="frameForm">
                <i class="fas fa-paper-plane me-1"></i> Kirim &amp; Simpan
            </button>
        </div>
    </div>
</form>

<script data-form-script>
(function () {
    const form              = document.getElementById('frameForm');
    const formContainer     = document.getElementById('formSkRegidentContainer');
    const previewContainer  = document.getElementById('previewSkRegidentContainer');
    const iframePreview     = document.getElementById('iframePreviewSkRegident');
    const footerForm        = document.getElementById('footerFormSkRegident');
    const footerPreview     = document.getElementById('footerPreviewSkRegident');
    const modalFooter       = document.querySelector('#ViewerModal .modal-footer');
    let currentBlobUrl      = null;

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
    const btnPreview = document.getElementById('btnShowPreviewSkRegident');
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

            const url = `{{ route('admin.pengajuan.generate_sk_regident', $pengajuan->id) }}`;
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
    const btnEdit = document.getElementById('btnEditSkRegident');
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