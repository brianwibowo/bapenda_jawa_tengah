<div class="modal-body">
    <form method="POST" action="/surat-template-test/preview/sp_default">
    @csrf
	<div class="container-fluid" id="formSpPolda2bapendajrContainerTest">
            <p class="text-muted">Berikut adalah template Surat Pemberitahuan standar dari Samsat ke Polda. Isi data kendaraan untuk melihat preview.</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NRKB</label>
                    <input type="text" class="form-control" name="nrkb" value="AA 9660 QE">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pemilik</label>
                    <input type="text" class="form-control" name="nama_pemilik" value="PEMERINTAH DESA GANDUWETAN">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Merk Kendaraan</label>
                    <input type="text" class="form-control" name="merk_kendaraan" value="VIAR">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tipe Kendaraan</label>
                    <input type="text" class="form-control" name="tipe_kendaraan" value="V 15 RL">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tahun Pembuatan</label>
                    <input type="text" class="form-control" name="tahun_pembuatan" value="2015">
                </div>
            </div>
	</div>
        <div id="previewSpDefaultTestContainer" style="display:none;">
            <iframe id="iframePreviewSpDefaultTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-info text-white">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" style="display:none;">
    <button type="button" class="btn btn-warning" >Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
