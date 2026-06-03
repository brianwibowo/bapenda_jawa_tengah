{{-- Modal Sederhana: SP Default (Samsat → Polda) --}}
<div class="modal fade" id="modalSpDefault" tabindex="-1" aria-labelledby="modalSpDefaultLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSpDefaultLabel">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Surat Pengajuan ke Polda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSpDefault" method="POST" action="{{ route('admin.pengajuan.ajukan', $pengajuan->id) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Anda akan mengirim Surat Pengajuan Penghapusan Regident kepada Polda Jawa Tengah
                        untuk pengajuan <strong>{{ $pengajuan->nomor_pengajuan }}</strong>.
                    </p>
                    <div class="mb-3">
                        <label for="spDefaultCatatan" class="form-label fw-bold">Catatan / Deskripsi <small class="text-muted">(opsional)</small></label>
                        <textarea class="form-control" id="spDefaultCatatan" name="catatan" rows="3" 
                                  placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnKirimSpDefault">
                        <i class="fas fa-paper-plane me-1"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formSpDefault = document.getElementById('formSpDefault');
    if (formSpDefault) {
        formSpDefault.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnKirimSpDefault');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
    }
});
</script>
