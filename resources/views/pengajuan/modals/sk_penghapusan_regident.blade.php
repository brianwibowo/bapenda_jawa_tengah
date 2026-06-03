<!-- Modal Form SK Penghapusan Regident (Non-Default, Draft) -->
<div class="modal fade" id="modalSkPenghapusanRegident" tabindex="-1" aria-labelledby="modalSkPenghapusanRegidentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="formSkPenghapusanRegidentDraft" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkPenghapusanRegidentLabel">Input Data SK Penghapusan Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Surat</label>
                            <input type="text" class="form-control" name="nomor_surat" required>
                            <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }} /{{ date('m/Y') }}/Ditlantas</small>
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
                            <small class="text-muted d-block mt-1">Contoh: 1</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Hal</label>
                            <input type="text" class="form-control" name="hal" required>
                            <small class="text-muted d-block mt-1">Contoh: Penghapusan Regident AA 9660 QE</small>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Data Penandatangan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Provinsi</label>
                            <input type="text" class="form-control" name="provinsi" required>
                            <small class="text-muted d-block mt-1">Contoh: Jawa Tengah</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama (Beserta Pangkat/Gelar)</label>
                            <input type="text" class="form-control" name="nama_penandatangan" required>
                            <small class="text-muted d-block mt-1">Contoh: Nadi Santoso, SP, M.Si</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jabatan</label>
                            <input type="text" class="form-control" name="jabatan" required>
                            <small class="text-muted d-block mt-1">Contoh: Pembina Utama Muda</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP</label>
                            <input type="text" class="form-control" name="nip" required>
                            <small class="text-muted d-block mt-1">Contoh: 197009191996031003</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-primary" id="btnShowPreviewSkPenghapusanRegident">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkPenghapusanRegidentDraft">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formSkPenghapusanRegidentDraft');

        document.getElementById('btnShowPreviewSkPenghapusanRegident').addEventListener('click', async function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

            try {
                const formData = new FormData(form);
                formData.append('preview', '1');
                const url = `{{ route('admin.pengajuan.generate_sk_penghapusan_regident', $pengajuan->id) }}`;
                const response = await fetch(url, {
                    method: 'POST', body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/pdf' }
                });
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                const blob = await response.blob();
                const blobUrl = URL.createObjectURL(blob);

                const modal = bootstrap.Modal.getInstance(document.getElementById('modalSkPenghapusanRegident'));
                modal.hide();
                if (typeof openPdfViewer === 'function') {
                    openPdfViewer(blobUrl, { title: 'Preview SK Penghapusan Regident', onBack: () => modal.show(), onClose: () => URL.revokeObjectURL(blobUrl) });
                } else { window.open(blobUrl, '_blank'); }
            } catch (error) {
                console.error('Preview load failed:', error);
                alert('Gagal memuat preview PDF.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
            }
        });

        document.getElementById('btnSubmitSkPenghapusanRegidentDraft').addEventListener('click', function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            const formData = new FormData(form);
            fetch(`{{ route('admin.pengajuan.draft_sk', $pengajuan->id) }}`, {
                method: 'POST', body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.redirect) { window.location.href = data.redirect; }
                else { alert(data.message || 'Terjadi kesalahan.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft'; }
            })
            .catch(() => { alert('Gagal menyimpan draft.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft'; });
        });
    });
    </script>
</div>