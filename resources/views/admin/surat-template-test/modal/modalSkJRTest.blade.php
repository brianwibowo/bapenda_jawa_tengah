<div class="modal-body">
    <form method="POST">
    @csrf
	    <div class="container-fluid" id="formSkJRContainerTest">
		<h6 class="fw-bold mb-3">Data Surat Permohonan Pembebasan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Surat Permohonan</label>
		        <input type="text" class="form-control" name="tanggal_surat_permohonan" value="20 Mei 2025">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Surat Regident (Ditlantas)</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Regident</label>
		        <input type="text" class="form-control" name="nomor_surat_regident" value="SKET/01VI/YAN.1/2025/Ditlantas">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Surat Regident</label>
		        <input type="text" class="form-control" name="tanggal_surat_regident" value="30 Juni 2025">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Surat Bapenda</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Bapenda</label>
		        <input type="text" class="form-control" name="nomor_surat_bapenda" value="900.1.13.1/1865/2025">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Surat Bapenda</label>
		        <input type="text" class="form-control" name="tanggal_surat_bapenda" value="8 Juli 2025">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Surat Keputusan Jasa Raharja</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Keputusan</label>
		        <input type="text" class="form-control" name="nomor_keputusan" value="KEP/20/2025">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tempat Dikeluarkan SK</label>
		        <input type="text" class="form-control" name="tempat_sk" value="Semarang">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Dikeluarkan SK</label>
		        <input type="text" class="form-control" name="tanggal_sk" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Penandatangan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nama Penandatangan</label>
		        <input type="text" class="form-control" name="nama_penandatangan" value="Triadi, S.H., M.H.">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Jabatan Penandatangan</label>
		        <input type="text" class="form-control" name="jabatan_penandatangan" value="Kepala Kantor Wilayah PT Jasa Raharja Jawa Tengah">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Metode Penanda Tangan</label>
		        <select name="metode_penanda_tangan" class="form-select">
		            <option value="ttd_elektronik">TTD Elektronik</option>
		            <option value="ttd_basah">TTD Basah</option>
		        </select>
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
		</div>
	    </div>
	    <div id="previewSkJRContainerTest" style="display:none;">
		<iframe id="iframePreviewSkJRTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
	    </div>
    </form>
</div>
<div class="modal-footer" id="footerFormSkJRTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-warning text-dark" id="btnPreviewSkJRTest">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSkJRTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSkJRTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>