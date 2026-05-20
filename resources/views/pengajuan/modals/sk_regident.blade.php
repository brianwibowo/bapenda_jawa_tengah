<!-- Modal Form SK Regident -->
<div class="modal fade" id="modalSkRegident" tabindex="-1" aria-labelledby="modalSkRegidentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.generate_sk_regident', $pengajuan->id) }}" method="POST"
                target="_blank">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkRegidentLabel">Input Data SK Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="formSkRegidentContainer">
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

                    <div id="previewSkRegidentContainer" style="display:none;">
                        <iframe id="iframePreviewSkRegident" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>

                </div>
                <div class="modal-footer" id="footerFormSkRegident">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnShowPreviewSkRegident">Lihat Preview</button>
                </div>
                <div class="modal-footer" id="footerPreviewSkRegident" style="display:none;">
                    <button type="button" class="btn btn-warning" id="btnEditSkRegident">Kembali Edit</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitSkRegident">
                        <i class="fas fa-paper-plane me-1"></i> Kirim & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
       const form = document.querySelector('#modalSkRegident form');

       form.addEventListener('submit', function(e) {
           setTimeout(() => {
               window.location.href = '{{ route("admin.pengajuan.show", $pengajuan->id) }}';
           }, 300);
       });

       const formContainer = document.getElementById('formSkRegidentContainer');
       const previewContainer = document.getElementById('previewSkRegidentContainer');
       const footerForm = document.getElementById('footerFormSkRegident');
       const footerPreview = document.getElementById('footerPreviewSkRegident');
       const iframePreview = document.getElementById('iframePreviewSkRegident');
       let currentBlobUrl = null;

       document.getElementById('btnShowPreviewSkRegident').addEventListener('click', function () {
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
               if (currentBlobUrl) {
                   URL.revokeObjectURL(currentBlobUrl);
               }
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

       document.getElementById('btnEditSkRegident').addEventListener('click', function () {
           previewContainer.style.display = 'none';
           footerPreview.style.display = 'none';
           formContainer.style.display = 'block';
           footerForm.style.display = 'flex';
       });
       
       document.getElementById('modalSkRegident').addEventListener('hidden.bs.modal', function () {
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