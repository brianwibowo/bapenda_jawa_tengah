{{--
    Tampilan ini dirender oleh FrameController::render() dan diinjek ke ViewerModal via fetch().
    Jangan tambahkan wrapper modal Bootstrap di sini — hanya konten form.
    Frame.js akan:
      1. Mengambil elemen [data-frame-body] dan meletakkannya di .modal-body ViewerModal.
      2. Mengambil elemen [data-frame-footer] dan meletakkannya di .modal-footer ViewerModal.
--}}

<form id="frameForm"
      action="{{ route('admin.pengajuan.buat_sk', $pengajuan->id) }}"
      method="POST">
    @csrf

    {{-- ===== BODY FORM ===== --}}
    <div data-frame-body>

        {{-- Container: Form Input --}}
        <div id="formSkDefaultContainer" style="padding: 0.25rem 0.25rem;">

            {{-- Pilih Kendaraan --}}
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
                    <small class="text-muted d-block mt-1">Pilih kendaraan spesifik atau "Semua Kendaraan" untuk membuat SK sekaligus.</small>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Catatan / Keterangan</label>
                    <textarea class="form-control" name="catatan" rows="3" placeholder="Masukkan catatan atau keterangan untuk Surat Keputusan ini..."></textarea>
                </div>
            </div>

        </div>

    </div>
</form>