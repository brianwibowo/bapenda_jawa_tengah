<div class="modal-body">
    <form method="POST">
    @csrf
	    <div class="container-fluid" id="formSpBalasanJRContainerTest">
		<h6 class="fw-bold mb-3">Data Surat</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat</label>
		        <input type="text" class="form-control" name="nomor_surat" value="AS/R/21/2025">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Regident (Polda)</label>
		        <input type="text" class="form-control" name="nomor_surat_regident" value="B/4188/IV/YAN.1/2025/Ditlantas">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Bapenda</label>
		        <input type="text" class="form-control" name="nomor_surat_bapenda" value="S/900.1.13.1/53/2025">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Penandatangan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tempat Surat</label>
		        <input type="text" class="form-control" name="tempat_surat" value="Semarang">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Surat</label>
		        <input type="date" class="form-control" name="tanggal_surat" value="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nama Penandatangan</label>
		        <input type="text" class="form-control" name="nama_penandatangan" value="Triadi">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Jabatan Penandatangan</label>
		        <input type="text" class="form-control" name="jabatan_penandatangan" value="Kepala Kantor Wilayah Utama">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Kendaraan</h6>
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
		        <label class="form-label fw-bold">Merk / Tipe</label>
		        <input type="text" class="form-control" name="merk_kendaraan" value="VIAR / V 15 RL">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tahun Pembuatan</label>
		        <input type="text" class="form-control" name="tahun_pembuatan" value="2015">
		    </div>
		</div>
	    </div>
	    <div id="previewSpBalasanJRContainerTest" style="display:none;">
		<iframe id="iframePreviewSpBalasanJRTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
	    </div>
    </form>
</div>
<div class="modal-footer" id="footerFormSpBalasanJRTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-warning text-dark" id="btnPreviewSpBalasanJRTest">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSpBalasanJRTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSpBalasanJRTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>