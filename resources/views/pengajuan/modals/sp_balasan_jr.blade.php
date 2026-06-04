{{-- Modal Sederhana: SP Balasan JR (Default, no PDF yet) --}}
<div class="modal fade" id="modalSpBalasanJR" tabindex="-1" aria-labelledby="modalSpBalasanJRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalSpBalasanJRLabel">
                    <i class="fas fa-reply me-2"></i>Balas Surat Pengajuan (Jasa Raharja)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSpBalasanJR" method="POST" action="{{ $signedUrls['sp_terima'] ?? '#' }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Anda akan membalas Surat Pengajuan sebagai perwakilan Jasa Raharja
                        untuk pengajuan <strong>{{ $pengajuan->nomor_pengajuan }}</strong>.
                    </p>
                    <div class="mb-3">
                        <label for="spBalasanJrCatatan" class="form-label fw-bold">Catatan / Deskripsi <small class="text-muted">(opsional)</small></label>
                        <textarea class="form-control" id="spBalasanJrCatatan" name="catatan" rows="3"
                                  placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-success" id="btnKirimSpBalasanJR">
                        <i class="fas fa-paper-plane me-1"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formSpBalasanJR');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnKirimSpBalasanJR');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
    }
});
</script>
