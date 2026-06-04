<!-- Modal Form SK Pembebasan (Non-Default, with TTD method choice) -->
<div class="modal fade" id="modalSkPembebasan" tabindex="-1" aria-labelledby="modalSkPembebasanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="formSkPembebasanDraft" class="modal-content border-0 shadow" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkPembebasanLabel">Input Data Surat Keputusan Pembebasan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" id="formSkPembebasanContainer">
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
                                <input type="text" class="form-control" name="tanggal_sk" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" required>
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
                        <hr>
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
                        </div>
                    </div>

                    {{-- Container Preview PDF --}}
                    <div id="previewSkPembebasanContainer" style="display:none;">
                        <iframe id="iframePreviewSkPembebasan" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>

                {{-- Footer: Mode Form --}}
                <div class="modal-footer" id="footerFormSkPembebasan">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-primary" id="btnShowPreviewSkPembebasan">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                </div>

                {{-- Footer: Mode Preview --}}
                <div class="modal-footer" id="footerPreviewSkPembebasan" style="display:none;">
                    <button type="button" class="btn btn-warning" id="btnEditSkPembebasan">Kembali Edit</button>
                    <button type="button" class="btn btn-success" id="btnSubmitSkPembebasanDraftPreview">
                        <i class="fas fa-save me-1"></i> Terbitkan SK
                    </button>
                </div>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
       const form = document.getElementById('formSkPembebasanDraft');
       const metodePenandaTangan = document.getElementById('metode_penanda_tangan');
       const formContainer = document.getElementById('formSkPembebasanContainer');
       const previewContainer = document.getElementById('previewSkPembebasanContainer');
       const footerForm = document.getElementById('footerFormSkPembebasan');
       const footerPreview = document.getElementById('footerPreviewSkPembebasan');
       const iframePreview = document.getElementById('iframePreviewSkPembebasan');

       const signedUrl = @json($signedUrls['sk_buat'] ?? '');
       let currentBlobUrl = null;

       // Preview
       document.getElementById('btnShowPreviewSkPembebasan').addEventListener('click', async function () {
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

               let pdfUrl = null;
               if (result.data) {
                   if (result.data.pdf_url) { pdfUrl = result.data.pdf_url; }
                   else {
                       const firstKey = Object.keys(result.data)[0];
                       if (firstKey && result.data[firstKey]) { pdfUrl = result.data[firstKey].pdf_url || null; }
                   }
               }
               if (!pdfUrl) throw new Error('No PDF URL returned');

               if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
               
               const pdfResponse = await fetch(pdfUrl);
               const blob = await pdfResponse.blob();
               currentBlobUrl = URL.createObjectURL(blob);
               iframePreview.src = currentBlobUrl;

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
       document.getElementById('btnEditSkPembebasan').addEventListener('click', function () {
           previewContainer.style.display = 'none';
           footerPreview.style.display = 'none';
           formContainer.style.display = 'block';
           footerForm.style.display = 'flex';
       });

       // Submit
       const submitForm = function (btn) {
           if (!form.checkValidity()) { form.reportValidity(); return; }
           btn.disabled = true;
           btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
           const formData = new FormData(form);
           fetch(signedUrl, {
               method: 'POST', body: formData,
               headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'text/html' }
           })
           .then(r => {
               if (r.redirected) { window.location.href = r.url; return; }
               return r.text().then(() => { window.location.reload(); });
           })
           .catch(() => {
               alert('Gagal menyimpan draft.');
               btn.disabled = false;
               btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan sebagai Draft';
           });
       };

       document.getElementById('btnSubmitSkPembebasanDraftPreview').addEventListener('click', function () {
           submitForm(this);
       });

       // Reset on modal close
       document.getElementById('modalSkPembebasan').addEventListener('hidden.bs.modal', function () {
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