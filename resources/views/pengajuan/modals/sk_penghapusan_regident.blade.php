<!-- Modal Form SK Penghapusan Regident -->
<div class="modal fade" id="modalSkPenghapusanRegident" tabindex="-1" aria-labelledby="modalSkPenghapusanRegidentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.generate_sk_penghapusan_regident', $pengajuan->id) }}" method="POST"
                target="_blank">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkPenghapusanRegidentLabel">Input Data SK Penghapusan Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                            <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }}
                                /{{ date('m/Y') }}/Ditlantas</small>
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
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-pdf me-1"></i> Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#modalSkPenghapusanRegident form');
        form.addEventListener('submit', function(e) {
            setTimeout(() => {
                window.location.href = '{{ route("admin.pengajuan.show", $pengajuan->id) }}';
            }, 300);
        });
    });
    </script>
</div>