{{-- Modal Sederhana: SK Default (Samsat) --}}
<div class="modal fade" id="modalSkDefault" tabindex="-1" aria-labelledby="modalSkDefaultLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalSkDefaultLabel">
                    <i class="fas fa-file-alt me-2"></i>Terbitkan Surat Keputusan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSkDefault" method="POST" action="{{ $signedUrls['sk_buat'] ?? '#' }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Anda akan menerbitkan Surat Keputusan untuk pengajuan
                        <strong>{{ $pengajuan->nomor_pengajuan }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label for="skDefaultKendaraan" class="form-label fw-bold">Pilih Kendaraan</label>
                        <select class="form-select" name="kendaraan_id" id="skDefaultKendaraan" required>
                            <option value="">-- Pilih Kendaraan (NRKB) --</option>
                            @foreach($pengajuan->kendaraans as $index => $k)
                                <option value="{{ $k->id }}">Kendaraan {{ $index + 1 }} — {{ $k->nrkb }}{{ !empty($k->merk_kendaraan) ? ' (' . $k->merk_kendaraan . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="skDefaultCatatan" class="form-label fw-bold">Catatan / Deskripsi <small class="text-muted">(opsional)</small></label>
                        <textarea class="form-control" id="skDefaultCatatan" name="catatan" rows="3"
                                  placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-info text-white" id="btnKirimSkDefault">
                        <i class="fas fa-paper-plane me-1"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSkDefault = document.getElementById('formSkDefault');
    if (formSkDefault) {
        formSkDefault.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnKirimSkDefault');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
    }
});
</script>
