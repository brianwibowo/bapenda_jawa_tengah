<div class="modal-body">
    <form id="formSpPolda2bapendajrTest">
    @csrf
        <div class="container-fluid" id="formSpPolda2bapendajrContainerTest">
            <h6 class="fw-bold mb-3">Data Surat</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Surat</label>
                    <input type="text" class="form-control" name="nomor_surat" value="B/9660-QE/IV/YAN.1./2025/DITLANTAS">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Pembuat Pernyataan</label>
                    <input type="text" class="form-control" name="nama_pembuat" value="Dwiyanto Setyo Budi">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tempat</label>
                    <input type="text" class="form-control" name="tempat" value="Semarang">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Dikeluarkan SP</label>
                    <input type="date" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Direktur (Beserta Gelar)</label>
                    <input type="text" class="form-control" name="nama_direktur" value="M. PRATAMA ADHYASASTRA, S.I.K., S.H., M.H.">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Pangkat Direktur</label>
                    <input type="text" class="form-control" name="pangkat_direktur" value="KOMBES POL">
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
                    <input type="number"
                        class="form-control"
                        name="tahun_pembuatan"
                        value="2015"
                        min="1900"
                        max="{{ \Carbon\Carbon::now()->year }}"
                        oninput="if(this.value.length > 4) this.value = this.value.slice(0,4);"
                        placeholder="Contoh: 2015">
                    {{-- <input type="text" class="form-control" name="tahun_pembuatan" value="2015"> --}}
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">Rujukan Surat</h6>
            <div class="repeater">
                <div data-repeater-list="group-rujukan">
                    @foreach ($rujukan as $idx => $item)
                        <div data-repeater-item class="row align-items-center mb-3">
                            <div class="col">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light text-muted">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                    <input type="text" class="form-control" name="rujukan" placeholder="Masukkan rujukan (contoh: Undang-Undang No...)" value="{{$item}}">
                                </div>
                            </div>
                            <div class="col-auto ps-0">
                                <button data-repeater-delete type="button" class="btn btn-outline-danger btn-border d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;" title="Hapus Rujukan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <button data-repeater-create type="button" class="btn btn-sm btn-info shadow-sm px-3">
                            <i class="fas fa-plus me-1"></i> Tambah Rujukan
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">Tembusan Surat</h6>
            <div class="repeater-terusan">
                <div data-repeater-list="group-tembusan">
                    @foreach ($tembusan as $idx => $item)
                        <div data-repeater-item class="row align-items-center mb-3">
                            <div class="col">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light text-muted">
                                        <i class="fas fa-file-alt"></i>
                                    </span>
                                    <input type="text" class="form-control" name="tembusan" placeholder="Masukkan terusan (contoh: Kapolda...)" value="{{$item}}">
                                </div>
                            </div>
                            <div class="col-auto ps-0">
                                <button data-repeater-delete type="button" class="btn btn-outline-danger btn-border d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;" title="Hapus Rujukan">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <button data-repeater-create type="button" class="btn btn-sm btn-info shadow-sm px-3">
                            <i class="fas fa-plus me-1"></i> Tambah Tembusan Surat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="previewSpPolda2bapendajrContainerTest" style="display:none;">
            <iframe id="iframePreviewSpPolda2bapendajrTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
        </div>
    </form>
</div>
<div class="modal-footer" id="footerFormSpPolda2bapendajrTest">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-info text-white" id="btnPreviewSpPolda2bapendajrTest">
        <i class="fas fa-eye me-1"></i>Lihat Preview
    </button>
</div>
<div class="modal-footer" id="footerPreviewSpPolda2bapendajrTest" style="display:none;">
    <button type="button" class="btn btn-warning" id="btnEditSpPolda2bapendajrTest">Kembali Edit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
