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
            <!-- <div class="col-md-6 mb-3" style="display:none;" id="sk_pembebasan_ttd_basah_container">
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
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-6 mb-3" style="display:none;" id="preview_pdf_container">
                <button type="button" class="btn btn-primary" id="btnPreviewPDF">Download Preview PDF</button>
            </div>
        </div>
    </div>

    {{-- ===== FOOTER (diambil oleh frame.js dan dipindah ke .modal-footer) ===== --}}
    <div data-frame-footer style="display:none;">
        <button type="button" class="btn btn-secondary" id="frameFormCancelBtn">Batal</button>
        <button type="submit" class="btn btn-success" form="frameForm">
            <i class="fas fa-file-pdf me-1"></i> Ajukan Surat
        </button>
    </div>
</form>

<script data-form-script>
    const metodePenandaTangan = document.getElementById('metode_penanda_tangan');
    if (metodePenandaTangan) {
        metodePenandaTangan.addEventListener('change', function () {
            const ttdBasahContainer = document.getElementById('sk_pembebasan_ttd_basah_container');
            const previewContainer  = document.getElementById('preview_pdf_container');
            const ttdBasahInput     = document.getElementById('sk_pembebasan_ttd_basah');
            if (this.value === 'ttd_basah') {
                previewContainer.style.display  = 'block';
                ttdBasahContainer.style.display = 'block';
                if (ttdBasahInput) ttdBasahInput.required = true;
            } else {
                previewContainer.style.display  = 'none';
                ttdBasahContainer.style.display = 'none';
                if (ttdBasahInput) ttdBasahInput.required = false;
            }
    });
    }

    const btnPreviewPDF = document.getElementById('btnPreviewPDF');
    if (btnPreviewPDF) {
        btnPreviewPDF.addEventListener('click', function () {
            const form     = document.getElementById('frameForm');
            const formData = new FormData(form);
            formData.append('preview', '1');

            const url = `{{ route('admin.pengajuan.generate_sk_pembebasan', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => {
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                return response.blob();
            })
            .then(blob => {
                const blobUrl = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = 'SK_PEMBEBASAN_PREVIEW.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(blobUrl);
            })
            .catch(error => {
                console.error('Preview download failed:', error);
                alert('Gagal mengunduh preview PDF. Silakan coba lagi.');
            });
        });
    }
</script>