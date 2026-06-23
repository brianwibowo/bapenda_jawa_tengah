{{-- Modal Sederhana: SP Default  --}}
<div class="modal fade" id="modalSpDefault" tabindex="-1" aria-labelledby="modalSpDefaultLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSpDefaultLabel">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Persetujuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSpDefault" method="POST" action="{{ $signedUrls['sp_terima'] ?? '#' }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Anda akan mengirim Persetujuan kepada {{ $pengajuan->cabang?->nama ?? 'Samsat Jawa Tengah' }}
                        untuk pengajuan <strong>{{ $pengajuan->nomor_pengajuan }}</strong>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </button>
                    <button type="button" class="btn btn-danger" id="btnTolakSpDefault">
                        <i class="fas fa-paper-plane me-1"></i>Tolak
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
    const btnTolakSpDefault = document.getElementById('btnTolakSpDefault');
    const btnKirimSpDefault = document.getElementById('btnKirimSpDefault');
    if (formSpDefault) {
        formSpDefault.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnKirimSpDefault');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...';
        });
        btnTolakSpDefault.addEventListener('click', function(e){
            formSpDefault.action = "{{ $signedUrls['sp_tolak'] ?? '#' }}";
            btnKirimSpDefault.click();
        })
    }
});
</script>
