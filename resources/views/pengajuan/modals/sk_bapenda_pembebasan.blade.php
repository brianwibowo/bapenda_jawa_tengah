<!-- Modal Form SK Pembebasan -->
<div class="modal fade" id="modalSkPembebasan" tabindex="-1" aria-labelledby="modalSkPembebasanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.generate_sk_pembebasan', $pengajuan->id) }}" method="POST"
                target="_blank">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSkRegidentLabel">Input Data Surat Keputusan Pembebasan</h5>
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
                            <input type="text" class="form-control" name="nomor_surat_Pembebasan" required>
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
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-pdf me-1"></i> Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>