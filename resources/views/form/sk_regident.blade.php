{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.generate_sk_regident', $pengajuan->id) }}"
      method="POST">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formSkRegidentContainer" style="padding: 0.25rem 0.25rem;">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Pilih Kendaraan</label>
                    <select class="form-select" name="kendaraan_id" required>
                        <option value="">-- Pilih Kendaraan (NRKB) --</option>
                        <option value="all">Semua Kendaraan</option>
                        @foreach($pengajuan->kendaraans as $k)
                            @if ($k->suratKeputusans->where('unit_kerja', $normalizedUnitKerja)->count() > 0)
                                @continue
                            @endif
                            <option value="{{ $k->id }}">{{ $k->nrkb }} - {{ $k->merk_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" required>
                    <small class="text-muted d-block mt-1">Contoh: SKET/ {{ date('m') }}/{{ date('m/Y') }}/Ditlantas</small>
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

    </div>
</form>