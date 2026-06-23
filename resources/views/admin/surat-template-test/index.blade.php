<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Template Surat — Preview</h2>
    </x-slot>

    <style>
        .surat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .surat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
        }
        .surat-card .card-icon {
            font-size: 2.2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>

    <div class="row g-4">
        {{-- CARD 1: SP Samsat → Polda --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100" onclick="openModal('modalSpDefaultTest')">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Samsat → Polda</h5>
                        <p class="text-muted mb-0 small">Surat Pemberitahuan standar dari Samsat ke Polda</p>
                        <span class="btn btn-sm btn-outline-primary mt-2" onclick="event.stopPropagation(); openModal('modalSpDefaultTest')">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: SP Polda → Bapenda & JR --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100" onclick="openModal('modalSpPolda2bapendajrTest')">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-info bg-opacity-10 text-info flex-shrink-0">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Polda → Bapenda & JR</h5>
                        <p class="text-muted mb-0 small">Pemberitahuan penghapusan data kendaraan dari Polda</p>
                        <span class="btn btn-sm btn-outline-info mt-2" onclick="event.stopPropagation(); openModal('modalSpPolda2bapendajrTest')">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3: SP Balasan Bapenda --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100" onclick="openModal('modalSpBalasanBapendaTest')">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-success bg-opacity-10 text-success flex-shrink-0">
                        <i class="fas fa-reply"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Balasan Bapenda</h5>
                        <p class="text-muted mb-0 small">Balasan surat pemberitahuan dari Bapenda</p>
                        <span class="btn btn-sm btn-outline-success mt-2" onclick="event.stopPropagation(); openModal('modalSpBalasanBapendaTest')">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: SP Balasan Jasa Raharja --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100" onclick="openModal('modalSpBalasanJRTest')">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-warning bg-opacity-10 text-warning flex-shrink-0">
                        <i class="fas fa-reply-all"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SP Balasan Jasa Raharja</h5>
                        <p class="text-muted mb-0 small">Balasan surat pemberitahuan dari Jasa Raharja</p>
                        <span class="btn btn-sm btn-outline-warning mt-2" onclick="event.stopPropagation(); openModal('modalSpBalasanJRTest')">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 5: SK Samsat Default --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100" onclick="openModal('modalSkDefaultTest')">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-secondary bg-opacity-10 text-secondary flex-shrink-0">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SK Samsat (Default)</h5>
                        <p class="text-muted mb-0 small">Surat Keputusan standar Samsat</p>
                        <span class="btn btn-sm btn-outline-secondary mt-2" onclick="event.stopPropagation(); openModal('modalSkDefaultTest')">
                            <i class="fas fa-eye me-1"></i>Lihat Template
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 6: SK Instansi (Polda / Bapenda / JR) --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 shadow-sm surat-card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="card-icon bg-danger bg-opacity-10 text-danger flex-shrink-0">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">SK Instansi</h5>
                        <p class="text-muted mb-0 small">SK Polda Regident / Bapenda Pembebasan / JR Pembebasan</p>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); openModal('modalSkPoldaTest')">
                                <i class="fas fa-eye me-1"></i>Polda
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); openModal('modalSkBapendaTest')">
                                <i class="fas fa-eye me-1"></i>Bapenda
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation(); openModal('modalSkJRTest')">
                                <i class="fas fa-eye me-1"></i>JR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- HTML / CSS / BLADE EDITOR WITH LIVE PREVIEW --}}
    {{-- ============================================================ --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-code me-2"></i>HTML / CSS / Blade Editor</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-light" id="btnRenderBlade">
                    <i class="fas fa-play me-1"></i>Render Blade
                </button>
                <button class="btn btn-sm btn-outline-light" id="btnResetEditor">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-6 border-end">
                    <div class="p-2 bg-light border-bottom">
                        <small class="text-muted fw-bold"><i class="fas fa-edit me-1"></i>Editor</small>
                    </div>
                    <textarea id="codeEditor" class="form-control border-0 rounded-0" style="font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.5; min-height: 600px; resize: vertical; tab-size: 2;"
                    placeholder="Tulis HTML / CSS / Blade di sini...">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;style&gt;
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        h1 { color: #1a56db; border-bottom: 2px solid #1a56db; padding-bottom: 10px; }
        .content { margin-top: 20px; line-height: 1.6; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #888; }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;Contoh Template Surat&lt;/h1&gt;
    &lt;div class="content"&gt;
        &lt;p&gt;Yang bertanda tangan di bawah ini:&lt;/p&gt;
        &lt;table style="width:100%;"&gt;
            &lt;tr&gt;&lt;td style="width:120px;"&gt;Nama&lt;/td&gt;&lt;td&gt;: {{ '{{' }} \$nama ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
            &lt;tr&gt;&lt;td&gt;Jabatan&lt;/td&gt;&lt;td&gt;: {{ '{{' }} \$jabatan ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
            &lt;tr&gt;&lt;td&gt;NRKB&lt;/td&gt;&lt;td&gt;: {{ '{{' }} \$nrkb ?? '-' }}&lt;/td&gt;&lt;/tr&gt;
        &lt;/table&gt;
        &lt;p&gt;Dengan ini menerangkan bahwa...&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="footer"&gt;
        &lt;p&gt;Dokumen ini dicetak pada {{ '{{' }} date('d F Y') }}&lt;/p&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
                </div>
                <div class="col-md-6">
                    <div class="p-2 bg-light border-bottom">
                        <small class="text-muted fw-bold"><i class="fas fa-eye me-1"></i>Preview</small>
                        <small class="text-muted ms-2" id="previewStatus"></small>
                    </div>
                    <div style="position: relative; min-height: 600px; background: #fff;">
                        <iframe id="previewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                        <div id="previewLoading" class="position-absolute top-50 start-50 translate-none" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpDefaultTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSpDefaultTest" class="modal-content border-0 shadow" method="POST" action="/surat-template-test/preview/sp_default">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>SP Samsat → Polda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                    <div id="previewSpDefaultTestContainer" style="display:none;">
                        <iframe id="iframePreviewSpDefaultTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>
                <div class="modal-footer" id="footerFormSpDefaultTest">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnPreviewSpDefaultTest">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                </div>
                <div class="modal-footer" id="footerPreviewSpDefaultTest" style="display:none;">
                    <button type="button" class="btn btn-warning" id="btnEditSpDefaultTest">Kembali Edit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 2: SP Polda → Bapenda & JR --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpPolda2bapendajrTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSpPolda2bapendajrTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>SP Polda → Bapenda & JR</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                                <input type="text" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
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
                                <input type="text" class="form-control" name="tahun_pembuatan" value="2015">
                            </div>
                        </div>
                    </div>
                    <div id="previewSpPolda2bapendajrContainerTest" style="display:none;">
                        <iframe id="iframePreviewSpPolda2bapendajrTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 3: SP Balasan Bapenda --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpBalasanBapendaTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSpBalasanBapendaTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-reply me-2"></i>SP Balasan Bapenda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                                <input type="text" class="form-control" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 4: SP Balasan Jasa Raharja --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSpBalasanJRTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSpBalasanJRTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-reply-all me-2"></i>SP Balasan Jasa Raharja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                                <input type="text" class="form-control" name="tanggal_surat" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}">
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 5: SK Samsat Default --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkDefaultTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSkDefaultTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>SK Samsat (Default)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Template Surat Keputusan standar Samsat. Isi data kendaraan untuk melihat preview.</p>
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
                    <div id="previewSkDefaultTestContainer" style="display:none;">
                        <iframe id="iframePreviewSkDefaultTest" src="" style="width:100%; height:500px; border:1px solid #ddd; border-radius:8px;"></iframe>
                    </div>
                </div>
                <div class="modal-footer" id="footerFormSkDefaultTest">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-secondary" id="btnPreviewSkDefaultTest">
                        <i class="fas fa-eye me-1"></i>Lihat Preview
                    </button>
                </div>
                <div class="modal-footer" id="footerPreviewSkDefaultTest" style="display:none;">
                    <button type="button" class="btn btn-warning" id="btnEditSkDefaultTest">Kembali Edit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6a: SK Polda Regident --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkPoldaTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSkPoldaTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-file-signature me-2"></i>SK Polda Regident</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6b: SK Bapenda Pembebasan --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkBapendaTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSkBapendaTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>SK Bapenda Pembebasan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 6c: SK Jasa Raharja Pembebasan --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="modalSkJRTest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="formSkJRTest" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>SK Jasa Raharja Pembebasan SWDKLLJ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT: Preview logic for all modals --}}
    {{-- ============================================================ --}}
    <script>
    function openModal(id) {
        const modal = new bootstrap.Modal(document.getElementById(id));
        modal.show();
    }

    function setupPreviewModal(formId, containerId, previewId, iframeId, footerFormId, footerPreviewId, btnPreviewId, btnEditId, type) {
        const form = document.getElementById(formId);
        const formContainer = document.getElementById(containerId);
        const previewContainer = document.getElementById(previewId);
        const footerForm = document.getElementById(footerFormId);
        const footerPreview = document.getElementById(footerPreviewId);
        const iframePreview = document.getElementById(iframeId);
        const btnPreview = document.getElementById(btnPreviewId);
        const btnEdit = document.getElementById(btnEditId);
        let currentBlobUrl = null;

        if (!btnPreview || !btnEdit) return;

        // Reset on modal close
        form.closest('.modal').addEventListener('hidden.bs.modal', function () {
            if (currentBlobUrl) {
                URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = null;
                iframePreview.src = '';
            }
            previewContainer.style.display = 'none';
            footerPreview.style.display = 'none';
            formContainer.style.display = 'block';
            footerForm.style.display = 'flex';
        });

        btnPreview.addEventListener('click', async function () {
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const btn = this;
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...';

            try {
                const formData = new FormData(form);
                const response = await fetch('/surat-template-test/preview/' + type, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/pdf',
                    }
                });
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                
                const blob = await response.blob();
                if (currentBlobUrl) URL.revokeObjectURL(currentBlobUrl);
                currentBlobUrl = URL.createObjectURL(blob);
                iframePreview.src = currentBlobUrl;

                formContainer.style.display = 'none';
                footerForm.style.display = 'none';
                previewContainer.style.display = 'block';
                footerPreview.style.display = 'flex';
            } catch (error) {
                console.error('Preview failed:', error);
                alert('Gagal memuat preview PDF.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });

        btnEdit.addEventListener('click', function () {
            previewContainer.style.display = 'none';
            footerPreview.style.display = 'none';
            formContainer.style.display = 'block';
            footerForm.style.display = 'flex';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupPreviewModal('formSpDefaultTest', 'formSpDefaultTest', 'previewSpDefaultTestContainer', 'iframePreviewSpDefaultTest', 'footerFormSpDefaultTest', 'footerPreviewSpDefaultTest', 'btnPreviewSpDefaultTest', 'btnEditSpDefaultTest', 'sp_default');
        setupPreviewModal('formSpPolda2bapendajrTest', 'formSpPolda2bapendajrContainerTest', 'previewSpPolda2bapendajrContainerTest', 'iframePreviewSpPolda2bapendajrTest', 'footerFormSpPolda2bapendajrTest', 'footerPreviewSpPolda2bapendajrTest', 'btnPreviewSpPolda2bapendajrTest', 'btnEditSpPolda2bapendajrTest', 'sp_polda2bapendajr');
        setupPreviewModal('formSpBalasanBapendaTest', 'formSpBalasanBapendaContainerTest', 'previewSpBalasanBapendaContainerTest', 'iframePreviewSpBalasanBapendaTest', 'footerFormSpBalasanBapendaTest', 'footerPreviewSpBalasanBapendaTest', 'btnPreviewSpBalasanBapendaTest', 'btnEditSpBalasanBapendaTest', 'sp_balasan_bapenda');
        setupPreviewModal('formSpBalasanJRTest', 'formSpBalasanJRContainerTest', 'previewSpBalasanJRContainerTest', 'iframePreviewSpBalasanJRTest', 'footerFormSpBalasanJRTest', 'footerPreviewSpBalasanJRTest', 'btnPreviewSpBalasanJRTest', 'btnEditSpBalasanJRTest', 'sp_balasan_jr');
        setupPreviewModal('formSkDefaultTest', 'formSkDefaultTest', 'previewSkDefaultTestContainer', 'iframePreviewSkDefaultTest', 'footerFormSkDefaultTest', 'footerPreviewSkDefaultTest', 'btnPreviewSkDefaultTest', 'btnEditSkDefaultTest', 'sk_default');
        setupPreviewModal('formSkPoldaTest', 'formSkPoldaContainerTest', 'previewSkPoldaContainerTest', 'iframePreviewSkPoldaTest', 'footerFormSkPoldaTest', 'footerPreviewSkPoldaTest', 'btnPreviewSkPoldaTest', 'btnEditSkPoldaTest', 'sk_polda');
        setupPreviewModal('formSkBapendaTest', 'formSkBapendaContainerTest', 'previewSkBapendaContainerTest', 'iframePreviewSkBapendaTest', 'footerFormSkBapendaTest', 'footerPreviewSkBapendaTest', 'btnPreviewSkBapendaTest', 'btnEditSkBapendaTest', 'sk_bapenda');
        setupPreviewModal('formSkJRTest', 'formSkJRContainerTest', 'previewSkJRContainerTest', 'iframePreviewSkJRTest', 'footerFormSkJRTest', 'footerPreviewSkJRTest', 'btnPreviewSkJRTest', 'btnEditSkJRTest', 'sk_jr');

        // ── HTML / CSS / Blade Editor ──
        const editor = document.getElementById('codeEditor');
        const frame = document.getElementById('previewFrame');
        const status = document.getElementById('previewStatus');
        const loading = document.getElementById('previewLoading');
        const btnRender = document.getElementById('btnRenderBlade');
        const btnReset = document.getElementById('btnResetEditor');

        const DEFAULT_CODE = editor.value;

        function updatePreview() {
            const code = editor.value;
            const hasBlade = /@\w+|@{{.*?}}|@{!!.*?!!}/.test(code);

            if (hasBlade) {
                status.textContent = '⏳ Gunakan "Render Blade" untuk Blade syntax';
                frame.srcdoc = '<html><body style="font-family:sans-serif;padding:40px;color:#999;text-align:center;"><h2>Blade Syntax Terdeteksi</h2><p>Klik tombol <strong>Render Blade</strong> untuk memproses.</p></body></html>';
                return;
            }

            status.textContent = '✓ Live';
            frame.srcdoc = code;
        }

        let debounceTimer;
        editor.addEventListener('input', function () {
            status.textContent = '⏳ Mengetik...';
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updatePreview, 400);
        });

        btnRender.addEventListener('click', async function () {
            const code = editor.value;
            if (!code.trim()) return;

            btnRender.disabled = true;
            btnRender.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Merender...';
            status.textContent = '⏳ Merender Blade...';

            try {
                const response = await fetch('/surat-template-test/render-code', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'text/html',
                    },
                    body: JSON.stringify({ code: code }),
                });

                if (!response.ok) {
                    const err = await response.text();
                    throw new Error(err.substring(0, 200));
                }

                const html = await response.text();
                frame.srcdoc = html;
                status.textContent = '✓ Blade rendered';
            } catch (error) {
                console.error('Render error:', error);
                frame.srcdoc = '<html><body style="font-family:sans-serif;padding:40px;color:red;"><h2>Error</h2><pre style="white-space:pre-wrap;">' + error.message + '</pre></body></html>';
                status.textContent = '✗ Error';
            } finally {
                btnRender.disabled = false;
                btnRender.innerHTML = '<i class="fas fa-play me-1"></i>Render Blade';
            }
        });

        btnReset.addEventListener('click', function () {
            editor.value = DEFAULT_CODE;
            updatePreview();
        });

        // Initial render
        updatePreview();
    });
    </script>
</x-app-layout>
