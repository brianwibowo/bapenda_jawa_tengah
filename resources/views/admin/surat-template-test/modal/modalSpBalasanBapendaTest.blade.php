<div class="modal-body">
    <form method="POST">
    @csrf
	    <div class="container-fluid" id="formSpBalasanBapendaContainerTest">
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat</label>
		        <input type="text" class="form-control" name="nomor_surat" value="SKET/01/06/2025/Ditlantas">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Sifat</label>
		        <input type="text" class="form-control" name="sifat" value="Segera">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Lampiran</label>
		        <input type="text" class="form-control" name="lampiran" value="1 Berkas">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Hal</label>
		        <input type="text" class="form-control" name="hal" value="Pembebasan Pajak Kendaraan Bermotor">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Penandatangan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Provinsi</label>
		        <input type="text" class="form-control" name="provinsi" value="Jawa Tengah">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nama Penandatangan</label>
		        <input type="text" class="form-control" name="nama_penandatangan" value="NADIATUL ANWARAH, S.H., M.H.">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Jabatan</label>
		        <input type="text" class="form-control" name="jabatan" value="Kepala Bidang Pajak Kendaraan Bermotor">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">NIP</label>
		        <input type="text" class="form-control" name="nip" value="19780211 200501 2 007">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Kendaraan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Keluar</label>
		        <input type="date" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->translatedFormat('Y-m-d') }}">
		    </div>
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
		</div>
	    </div>
	    <div id="previewSpBalasanBapendaContainerTest" style="display:none;">
		<iframe id="iframePreviewSpBalasanBapendaTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
	    </div>
     </form>
</div>
<div class="modal-footer" id="footerFormSpBalasanBapendaTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-success" id="btnPreviewSpBalasanBapendaTest">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSpBalasanBapendaTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSpBalasanBapendaTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>