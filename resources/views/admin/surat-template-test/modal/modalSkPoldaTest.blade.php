<div class="modal-body">
    <form method="POST">
    @csrf
	    <div class="container-fluid" id="formSkPoldaContainerTest">
		<div class="row">
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nomor Surat</label>
			<input type="text" class="form-control" name="nomor_surat" value="SKET/01/VI/YAN.1/2025/Ditlantas">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nama Pembuat Pernyataan</label>
			<input type="text" class="form-control" name="nama_pembuat" value="Dwiyanto Setyo Budi">
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Tempat Dikeluarkan</label>
			<input type="text" class="form-control" name="tempat" value="Semarang">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Tanggal Dikeluarkan</label>
			<input type="text" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
		    </div>
		</div>
		<hr>
		<h6 class="fw-bold mb-3">Data Penandatangan (Direktur)</h6>
		<div class="row">
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nama Direktur</label>
			<input type="text" class="form-control" name="nama_direktur" value="M. PRATAMA ADHYASASTRA, S.I.K., S.H., M.H.">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Pangkat / NRP</label>
			<input type="text" class="form-control" name="pangkat_direktur" value="KOMISARIS BESAR POLISI NRP 680903">
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
		    <div class="col-md-4 mb-3">
			<label class="form-label fw-bold">Tahun Pembuatan</label>
			<input type="text" class="form-control" name="tahun_pembuatan" value="2015">
		    </div>
		    <div class="col-md-4 mb-3">
			<label class="form-label fw-bold">Isi Silinder</label>
			<input type="text" class="form-control" name="isi_silinder" value="150 CC">
		    </div>
		    <div class="col-md-4 mb-3">
			<label class="form-label fw-bold">Bahan Bakar</label>
			<input type="text" class="form-control" name="jenis_bahan_bakar" value="BENSIN">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nomor Rangka</label>
			<input type="text" class="form-control" name="nomor_rangka" value="MGRVR15TAFL207980">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nomor Mesin</label>
			<input type="text" class="form-control" name="nomor_mesin" value="YX161FMG15207805">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Warna Kendaraan</label>
			<input type="text" class="form-control" name="warna_kendaraan" value="BIRU">
		    </div>
		    <div class="col-md-6 mb-3">
			<label class="form-label fw-bold">Nomor BPKB</label>
			<input type="text" class="form-control" name="nomor_bpkb" value="M01679715">
		    </div>
		</div>
	    </div>
	    <div id="previewSkPoldaContainerTest" style="display:none;">
		<iframe id="iframePreviewSkPoldaTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
	    </div>
    </form>
</div>
<div class="modal-footer" id="footerFormSkPoldaTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger text-white" id="btnPreviewSkPoldaTest">
	<i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSkPoldaTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSkPoldaTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>