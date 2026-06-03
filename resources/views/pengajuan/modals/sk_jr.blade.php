{{-- Modal Sederhana: SK JR (Jasa Raharja) --}}
<div class="modal fade" id="modalSkJR" tabindex="-1" aria-labelledby="modalSkJRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalSkJRLabel">
                    <i class="fas fa-file-contract me-2"></i>Terbitkan SK Jasa Raharja
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSkJR" method="POST" action="{{ route('admin.pengajuan.draft_sk', $pengajuan->id) }}">
                @csrf
                <input type="hidden" name="kendaraan_id" value="all">
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Anda akan menerbitkan Surat Keputusan Jasa Raharja untuk pengajuan
                        <strong>{{ $pengajuan->nomor_pengajuan }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label for="skJrKendaraan" class="form-label fw-bold">Pilih Kendaraan</label>
                        <select class="form-select" name="kendaraan_id" id="skJrKendaraan" required>
                            <option value="all">Semua Kendaraan</option>
                            @foreach($pengajuan->kendaraans as $k)
                                <option value="{{ $k->id }}">{{ $k->nrkb }} - {{ $k->merk_kendaraan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="skJrCatatan" class="form-label fw-bold">Catatan / Deskripsi <small class="text-muted">(opsional)</small></label>
                        <textarea class="form-control" id="skJrCatatan" name="catatan" rows="3"
                                  placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-info text-white" id="btnKirimSkJR">
                        <i class="fas fa-paper-plane me-1"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSkJR = document.getElementById('formSkJR');
    if (formSkJR) {
        formSkJR.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnKirimSkJR');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
    }
});
</script>
