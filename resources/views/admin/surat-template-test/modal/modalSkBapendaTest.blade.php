<div class="modal-body">
    <form method="POST">
    @csrf
	    <div class="container-fluid" id="formSkBapendaContainerTest">
		<h6 class="fw-bold mb-3">Data Surat Permohonan Regident</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
		        <input type="text" class="form-control" name="nama_pembuat_surat_permohonan" value="Dwiyanto Setyo Budi">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
		        <input type="text" class="form-control" name="tempat_pembuat_surat_permohonan" value="Temanggung">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
		        <input type="text" class="form-control" name="tanggal_pembuat_surat_permohonan" value="13 Mei 2024">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Surat Keputusan Regident</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Keterangan Penghapusan Regident</label>
		        <input type="text" class="form-control" name="nomor_surat_regident" value="SKET/01/VI/YAN.1/2025/Ditlantas">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nama Pembuat Surat Keterangan</label>
		        <input type="text" class="form-control" name="nama_pembuat_surat_regident" value="Dwiyanto Setyo Budi">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tempat Pembuatan Surat</label>
		        <input type="text" class="form-control" name="tempat_pembuat_surat_regident" value="Temanggung">
		    </div>
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Tanggal Pembuatan Surat</label>
		        <input type="text" class="form-control" name="tanggal_pembuat_surat_regident" value="30 Juni 2025">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Surat Keputusan Pembebasan</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
		        <label class="form-label fw-bold">Nomor Surat Keterangan Pembebasan</label>
		        <input type="text" class="form-control" name="nomor_surat_pembebasan" value="900.1.13.1/1865/2025">
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
		        <label class="form-label fw-bold">Nama Direktur</label>
		        <input type="text" class="form-control" name="nama_direktur" value="NADI SANTOSO">
		    </div>
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
	    <div id="previewSkBapendaContainerTest" style="display:none;">
		<iframe id="iframePreviewSkBapendaTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
	    </div>
    </form>
</div>
<div class="modal-footer" id="footerFormSkBapendaTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-primary" id="btnPreviewSkBapendaTest">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSkBapendaTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSkBapendaTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>