<!-- Modal Form SK Regident (Non-Default, Draft) -->
<div class="modal fade" id="modalSkRegident" tabindex="-1" aria-labelledby="modalSkRegidentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="formSkRegidentDraft" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkRegidentLabel">Input Data SK Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }}
                                /{{ date('m/Y') }}/Ditlantas</small>
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
                            <small class="text-muted d-block mt-1">Contoh:
                                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Data Penandatangan (Direktur)</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Direktur (Beserta Pangkat/Gelar)</label>
                            <input type="text" class="form-control" name="nama_direktur" required>
                            <small class="text-muted d-block mt-1">Contoh: M. PRATAMA ADHYASASTRA, S.I.K., S.H.,
                                M.H.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pangkat / NRP</label>
                            <input type="text" class="form-control" name="pangkat_direktur" required>
                            <small class="text-muted d-block mt-1">Contoh: KOMISARIS BESAR POLISI NRP 680903</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-primary" id="btnShowPreviewSkRegident">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkRegidentDraft">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
       const form = document.getElementById('formSkRegidentDraft');

       // Preview: fetch PDF blob → openPdfViewer (no internal iframe)
       document.getElementById('btnShowPreviewSkRegident').addEventListener('click', async function () {
           if (!form.checkValidity()) { form.reportValidity(); return; }

           const btn = this;
           btn.disabled = true;
           btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

           try {
               const formData = new FormData(form);
               formData.append('preview', '1');
               const url = `{{ route('admin.pengajuan.generate_sk_regident', $pengajuan->id) }}`;
               const response = await fetch(url, {
                   method: 'POST', body: formData,
                   headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/pdf' }
               });
               if (!response.ok) throw new Error('Request failed: ' + response.status);
               const blob = await response.blob();
               const blobUrl = URL.createObjectURL(blob);

               const modal = bootstrap.Modal.getInstance(document.getElementById('modalSkRegident'));
               modal.hide();

               if (typeof openPdfViewer === 'function') {
                   openPdfViewer(blobUrl, {
                       title: 'Preview SK Regident',
                       onBack: function() { modal.show(); },
                       onClose: function() { URL.revokeObjectURL(blobUrl); }
                   });
               } else { window.open(blobUrl, '_blank'); }
           } catch (error) {
               console.error('Preview load failed:', error);
               alert('Gagal memuat preview PDF. Silakan coba lagi.');
           } finally {
               btn.disabled = false;
               btn.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
           }
       });

       // Submit as Draft
       document.getElementById('btnSubmitSkRegidentDraft').addEventListener('click', function () {
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