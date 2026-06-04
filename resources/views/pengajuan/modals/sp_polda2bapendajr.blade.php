{{-- Modal Full Form: SP Polda ke Bapenda/JR (Non-Default, Draft) --}}
<div class="modal fade" id="modalSpPolda2bapendajr" tabindex="-1" aria-labelledby="modalSpPolda2bapendajrLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="formSpPolda2bapendajr" method="POST">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSpPolda2bapendajrLabel">Input Data SP Polda ke Bapenda/JR</h5>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-primary" id="btnPreviewSpPolda2bapendajr">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                    <button type="button" class="btn btn-success" id="btnSubmitSpPolda2bapendajr">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
       const form = document.getElementById('formSpPolda2bapendajr');
       const signedUrl = @json($signedUrls['sp_ajukan'] ?? '');

       // Preview: fetch JSON with pdf_url → openPdfViewer
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

               const modal = bootstrap.Modal.getInstance(document.getElementById('modalSpPolda2bapendajr'));
               modal.hide();
               if (typeof openPdfViewer === 'function') {
                   openPdfViewer(pdfUrl, { title: 'Preview SP Polda ke Bapenda/JR', onBack: () => modal.show() });
               } else { window.open(pdfUrl, '_blank'); }
           } catch (error) {
               console.error('Preview load failed:', error);
               alert('Gagal memuat preview PDF.');
           } finally {
               btn.disabled = false;
               btn.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Preview';
           }
       });

       // Submit (no preview param → actual submission)
       document.getElementById('btnSubmitSpPolda2bapendajr').addEventListener('click', function () {
           if (!form.checkValidity()) { form.reportValidity(); return; }
           const btn = this;
           btn.disabled = true;
           btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
           const formData = new FormData(form);
           fetch(signedUrl, {
               method: 'POST', body: formData,
               headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'text/html' }
           })
           .then(r => {
               if (r.redirected) { window.location.href = r.url; return; }
               return r.text().then(html => { window.location.reload(); });
           })
           .catch(() => { alert('Gagal menyimpan.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft'; });
       });
    });
    </script>
</div>
