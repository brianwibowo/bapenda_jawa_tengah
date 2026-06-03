{{--
    Modal: Surat Balasan Jasa Raharja (SP Pembebasan SWDKLLJ)
    Diakses dari "Buat Surat Keputusan" → pilih "Surat Balasan Jasa Raharja"
    Pola sama dengan sk_jasa_raharja.blade.php: form → AJAX preview → draft save
--}}
<div class="modal fade" id="modalSkBalasanJR" tabindex="-1" aria-labelledby="modalSkBalasanJRLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.generate_sp_balasan_jr', $pengajuan->id) }}" id="formSpBalasanJR" method="POST">
                @csrf

                {{-- Header --}}
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalSkBalasanJRLabel">Input Data Surat Balasan Jasa Raharja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- === Container Form Input === --}}
                    <div id="formSpBalasanJRContainer">

                        {{-- Pilih Kendaraan --}}
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
                        <h6 class="fw-bold mb-3">Data Surat</h6>

                        {{-- Nomor Surat + Nomor Surat Regident --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat</label>
                                <input type="text" class="form-control" name="nomor_surat" required placeholder="Contoh: AS/R/21/2025">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Regident (Polda)</label>
                                <input type="text" class="form-control" name="nomor_surat_regident" required placeholder="Contoh: B/4188/IV/YAN.1/2025/Ditlantas">
                            </div>
                        </div>

                        {{-- Nomor Surat Bapenda --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nomor Surat Bapenda</label>
                                <input type="text" class="form-control" name="nomor_surat_bapenda" required placeholder="Contoh: S/900.1.13.1/53/2025">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Data Penandatangan</h6>

                        {{-- Tempat + Tanggal Surat --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tempat Surat</label>
                                <input type="text" class="form-control" name="tempat_surat" value="Semarang" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Surat</label>
                                <input type="text" class="form-control" name="tanggal_surat" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
                            </div>
                        </div>

                        {{-- Nama + Jabatan Penandatangan --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Penandatangan</label>
                                <input type="text" class="form-control" name="nama_penandatangan" required placeholder="Contoh: Triadi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jabatan Penandatangan</label>
                                <input type="text" class="form-control" name="jabatan_penandatangan" value="Kepala Kantor Wilayah" required>
                            </div>
                        </div>
                    </div>

                    {{-- === Container Preview PDF (hidden by default) === --}}
                    <div id="previewSpBalasanJRContainer" style="display:none;">
                        <iframe id="iframePreviewSpBalasanJR" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>

                {{-- Footer: Mode Form --}}
                <div class="modal-footer" id="footerFormSpBalasanJR">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning text-dark" id="btnShowPreviewSpBalasanJR">Lihat Preview</button>
                </div>

                {{-- Footer: Mode Preview --}}
                <div class="modal-footer" id="footerPreviewSpBalasanJR" style="display:none;">
                    <button type="button" class="btn btn-secondary" id="btnEditSpBalasanJR">Kembali Edit</button>
                    <button type="button" class="btn btn-success" id="btnSubmitSpBalasanJRDraft">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ JAVASCRIPT ============ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form             = document.getElementById('formSpBalasanJR');
        const formContainer    = document.getElementById('formSpBalasanJRContainer');
        const previewContainer = document.getElementById('previewSpBalasanJRContainer');
        const footerForm       = document.getElementById('footerFormSpBalasanJR');
        const footerPreview    = document.getElementById('footerPreviewSpBalasanJR');
        const iframePreview    = document.getElementById('iframePreviewSpBalasanJR');
        let currentBlobUrl     = null;

        // -- Preview PDF via AJAX --
        document.getElementById('btnShowPreviewSpBalasanJR').addEventListener('click', function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const formData = new FormData(form);
            formData.append('preview', '1');
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

            const url = `{{ route('admin.pengajuan.generate_sp_balasan_jr', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/pdf' }
            })
            .then(response => {
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                return response.blob();
            })
            .then(blob => {
                if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = URL.createObjectURL(blob);
                iframePreview.src = currentBlobUrl;

                // Toggle: form → preview
                formContainer.style.display    = 'none';  footerForm.style.display    = 'none';
                previewContainer.style.display = 'block'; footerPreview.style.display = 'flex';
                btn.disabled = false; btn.innerText = 'Lihat Preview';
            })
            .catch(error => {
                console.error('Preview load failed:', error);
                alert('Gagal memuat preview PDF.');
                btn.disabled = false; btn.innerText = 'Lihat Preview';
            });
        });

        // -- Kembali ke mode edit --
        document.getElementById('btnEditSpBalasanJR').addEventListener('click', function () {
            previewContainer.style.display = 'none';  footerPreview.style.display = 'none';
            formContainer.style.display    = 'block'; footerForm.style.display    = 'flex';
        });

        // -- Simpan sebagai Draft (AJAX) --
        document.getElementById('btnSubmitSpBalasanJRDraft').addEventListener('click', function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

            const formData = new FormData(form);
            const url = `{{ route('admin.pengajuan.generate_sp_balasan_jr', $pengajuan->id) }}`;
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(response => {
                // Jika response PDF (stream), anggap berhasil → reload
                const contentType = response.headers.get('content-type') || '';
                if (contentType.includes('application/pdf') || response.ok) {
                    window.location.reload();
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.error) {
                    alert(data.error);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
                }
            })
            .catch(error => {
                console.error('Draft save failed:', error);
                // Jika error terjadi tapi halaman sudah mau reload, abaikan
                window.location.reload();
            });
        });

        // -- Cleanup saat modal ditutup --
        document.getElementById('modalSkBalasanJR').addEventListener('hidden.bs.modal', function () {
            if (currentBlobUrl) { URL.revokeObjectURL(currentBlobUrl); currentBlobUrl = null; iframePreview.src = ''; }
            previewContainer.style.display = 'none';  footerPreview.style.display = 'none';
            formContainer.style.display    = 'block'; footerForm.style.display    = 'flex';
        });
    });
    </script>
</div>
