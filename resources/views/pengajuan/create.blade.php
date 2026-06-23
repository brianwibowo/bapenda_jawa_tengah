<x-app-layout>
    <x-slot name="title">Buat Pengajuan Baru</x-slot>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Buat Pengajuan Baru</h2>
            <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <h4 class="alert-title">Gagal!</h4>
            <p>{{ session('error') }}</p>
            @if (session('incomplete_kendaraans'))
                <p>Kendaraan yang belum lengkap:
                    @foreach (session('incomplete_kendaraans') as $kendaraanId)
                        <span class="badge bg-warning">Kendaraan ID: {{ $kendaraanId }}</span>
                    @endforeach
                </p>
            @endif
        </div>
    @endif

    {{-- Info Persyaratan --}}
    <div class="alert alert-info mb-4">
        <strong>Persyaratan sesuai dengan KEP KAKOR Nomor: KEP/172/XI/2024 Tanggal 18 November 2024</strong>
    </div>

    {{-- Tab Kendaraan --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3" id="kendaraanTabs">
                {{-- Tab kendaraan akan ditambahkan secara dinamis --}}
                <button type="button" class="btn btn-outline-primary" id="btnTambahKendaraan">
                    <i class="fas fa-plus me-1"></i> Tambah kendaraan
                </button>
            </div>
        </div>
    </div>

    {{-- Form Container --}}
    <div id="formContainer"></div>

    {{-- Tombol Finalisasi Pengajuan --}}
    <div class="card mb-4" id="finalizeCard" style="display: none;">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Wilayah Samsat <span class="text-danger">*</span></label>
                <select id="pengajuanCabangSelect" class="form-select searchable-select" required>
                    <option value="">-- Pilih Wilayah Samsat --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">
                            {{ $branch->nama }} - {{ $branch->wilayah }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Pilih wilayah yang terkait dengan pengajuan ini.</div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-success btn-lg" id="btnFinalize">
                    <i class="fas fa-check-circle me-2"></i> Selesai & Buat Nomor Pengajuan
                </button>
            </div>
        </div>
    </div>

    {{-- Template Form Kendaraan (Hidden) --}}
    <template id="kendaraanFormTemplate">
        <div class="kendaraan-form" data-kendaraan-index="">
            <div class="card mb-4 position-relative">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                    style="z-index: 10;" onclick="hapusKendaraan(this)" title="Hapus Kendaraan">
                    <i class="fas fa-times"></i>
                </button>
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h4 class="card-title text-white mb-0">Detail Kendaraan <span class="kendaraan-number"></span></h4>
                </div>
                <div class="card-body">
                    <form class="kendaraan-form-data" enctype="multipart/form-data">
                        <input type="hidden" name="kendaraan_id" class="kendaraan-id-input">
                        <input type="hidden" name="pengajuan_id" class="pengajuan-id-input">

                        <div class="row">
                            <!-- Kolom Identitas Pemilik -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header text-white" style="background-color: #0d6efd;">
                                        <h4 class="card-title text-white mb-0">1. Identitas Pemilik</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NIK/TDP/NIB/Kitas/Kitab <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nik_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="alamat_pemilik" rows="3"
                                                required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. TLP/HP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="telp_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email_pemilik" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Identitas Kendaraan -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header text-white" style="background-color: #0d6efd;">
                                        <h4 class="card-title text-white mb-0">2. Identitas Kendaraan Bermotor</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">NRKB <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nrkb" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Merk <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="merk_kendaraan" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipe <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="tipe_kendaraan" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="jenis_kendaraan" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="model_kendaraan" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="tahun_pembuatan"
                                                    required min="1901" max="{{ date('Y') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Isi Silinder / Daya Listrik <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="isi_silinder" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Rangka <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nomor_rangka" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nomor_mesin" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Warna Kendaraan Bermotor <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="warna_kendaraan" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bahan Bakar / Sumber Energi <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="jenis_bahan_bakar" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Warna TNKB <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="warna_tnkb" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nomor BPKB <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nomor_bpkb" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Upload Dokumen -->
                        <div class="card mb-4">
                            <div class="card-header text-white" style="background-color: #0d6efd;">
                                <h4 class="card-title text-white mb-0">3. Upload Dokumen Persyaratan (Max 10MB per file)
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Surat Permohonan -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">Surat permohonan penghapusan <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="surat_permohonan"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input"
                                                    name="surat_permohonan[]" accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif"
                                                    data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- Surat Pernyataan -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">Surat pernyataan kepemilikan <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="surat_pernyataan"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input"
                                                    name="surat_pernyataan[]" accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif"
                                                    data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- KTP -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">Tanda bukti identitas pemilik (KTP) <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="ktp"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="ktp[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- BPKB -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">BPKB <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="bpkb"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="bpkb[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- TBPKP -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">TBPKP <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="tbpkp"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="tbpkp[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- Cek Fisik -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">Hasil pemeriksaan cek fisik <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="cek_fisik"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="cek_fisik[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- Foto Ranmor -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">Foto Kendaraan <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="foto_ranmor"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="foto_ranmor[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
 
                                    <!-- STNK -->
                                    <div class="col-md-6 mb-4">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold mb-0">STNK <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="file-container file-dropzone" data-field="stnk"
                                            data-accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="stnk[]"
                                                    accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                            <div class="file-hint d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>PDF, DOCX, JPG, PNG · Maks 10MB</span>
                                                </div>
                                                <button type="button" class="btn-add-file" onclick="addFileInput(this)"
                                                    title="Tambah file lainnya">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary w-100 btn-lg mb-4 btn-save-kendaraan">
                            <i class="fas fa-save me-2"></i> Simpan Kendaraan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>


    <script>
        window.PengajuanConfig = {
            urlKendaraan: "{{ url('kendaraan') }}",
            csrfToken: "{{ csrf_token() }}",
            routeKendaraanStore: "{{ route('kendaraan.store') }}",
            routePengajuanStore: "{{ route('pengajuan.store') }}"
        };
    </script>
    <script src="{{ asset('js/pengajuan/create_pengajuan.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/pengajuan/create_pengajuan.css') }}">
</x-app-layout>