<!-- Modal Form SK POLDA -->
<div class="modal fade" id="modalSkPolda" tabindex="-1" aria-labelledby="modalSkPoldaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formSkPoldaDraft" method="POST">
                @csrf
                <input type="hidden" name="draft_mode" value="1">
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkPoldaLabel">Input Data SK POLDA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formSkPoldaContainer">
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

                    <div id="previewSkPoldaContainer" style="display:none;">
                        <iframe id="iframePreviewSkPolda" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>

                </div>
                <div class="modal-footer" id="footerFormSkPolda">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnShowPreviewSkPolda">Lihat Preview</button>
                </div>
                <div class="modal-footer" id="footerPreviewSkPolda" style="display:none;">
                    <button type="button" class="btn btn-warning" id="btnEditSkPolda">Kembali Edit</button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkPoldaDraft">
                        <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
       const form = document.getElementById('formSkPoldaDraft');
       const formContainer = document.getElementById('formSkPoldaContainer');
       const previewContainer = document.getElementById('previewSkPoldaContainer');
       const footerForm = document.getElementById('footerFormSkPolda');
       const footerPreview = document.getElementById('footerPreviewSkPolda');
       const iframePreview = document.getElementById('iframePreviewSkPolda');
       let currentBlobUrl = null;

       document.getElementById('btnShowPreviewSkPolda').addEventListener('click', function () {
           if (!form.checkValidity()) {
               form.reportValidity();
               return;
           }

           const formData = new FormData(form);
           formData.append('preview', '1');
           
           const btn = this;
           btn.disabled = true;
           btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

           const url = `{{ route('pengajuan.generate_sk_polda') }}`;
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
               formContainer.style.display = 'none';
               footerForm.style.display = 'none';
               previewContainer.style.display = 'block';
               footerPreview.style.display = 'flex';
               btn.disabled = false;
               btn.innerText = 'Lihat Preview';
           })
           .catch(error => {
               console.error('Preview load failed:', error);
               alert('Gagal memuat preview PDF. Silakan coba lagi.');
               btn.disabled = false;
               btn.innerText = 'Lihat Preview';
           });
       });

       document.getElementById('btnEditSkPolda').addEventListener('click', function () {
           previewContainer.style.display = 'none';
           footerPreview.style.display = 'none';
           formContainer.style.display = 'block';
           footerForm.style.display = 'flex';
       });

       // Submit as Draft (AJAX)
       document.getElementById('btnSubmitSkPoldaDraft').addEventListener('click', function () {
           if (!form.checkValidity()) {
               form.reportValidity();
               return;
           }
           const btn = this;
           btn.disabled = true;
           btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

           const formData = new FormData(form);
           const url = `{{ route('admin.pengajuan.draft_sk', $pengajuan->id) }}`;

           fetch(url, {
               method: 'POST',
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}',
                   'Accept': 'application/json'
               }
           })
           .then(response => response.json())
           .then(data => {
               if (data.success && data.redirect) {
                   window.location.href = data.redirect;
               } else {
                   alert(data.message || 'Terjadi kesalahan saat menyimpan draft.');
                   btn.disabled = false;
                   btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
               }
           })
           .catch(error => {
               console.error('Draft save failed:', error);
               alert('Gagal menyimpan draft. Silakan coba lagi.');
               btn.disabled = false;
               btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
           });
       });
       
       document.getElementById('modalSkPolda').addEventListener('hidden.bs.modal', function () {
           if (currentBlobUrl) {
               URL.revokeObjectURL(currentBlobUrl);
               currentBlobUrl = null;
               iframePreview.src = '';
           }
           previewContainer.style.display = 'none';
           footerPreview.style.display = 'none';
           formContainer.style.display = 'block';
           footerForm.style.display = 'flex';
       });
    });
    </script>
</div>
