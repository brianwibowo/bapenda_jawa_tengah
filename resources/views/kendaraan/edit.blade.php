<x-app-layout>
    <x-slot name="header">
        Edit Kendaraan: {{ $kendaraan->nrkb }} (dari Pengajuan: {{ $kendaraan->pengajuan->nomor_pengajuan }})
    </x-slot>

    {{-- Menampilkan pesan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h4 class="alert-title">Gagal Menyimpan!</h4>
            <ul class="ps-3 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form action diubah ke route 'kendaraan.update' dengan method PATCH --}}
    <form action="{{ route('kendaraan.update', $kendaraan) }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
        @csrf
        @method('PATCH')
        <div class="row">
            <!-- Kolom Identitas Pemilik (Data diisi dari $kendaraan) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header text-white" style="background-color: #0d6efd;">
                        <h4 class="card-title mb-0">1. Identitas Pemilik (Kendaraan Ini)</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label">Atas Nama</label>
                            <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik', $kendaraan->nama_pemilik) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nik_pemilik" class="form-label">NIK/TDP/NIB/Kitas/Kitab</label>
                            <input type="text" class="form-control" id="nik_pemilik" name="nik_pemilik" value="{{ old('nik_pemilik', $kendaraan->nik_pemilik) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_pemilik" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat_pemilik" name="alamat_pemilik" rows="3" required>{{ old('alamat_pemilik', $kendaraan->alamat_pemilik) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telp_pemilik" class="form-label">No. TLP/HP</label>
                            <input type="text" class="form-control" id="telp_pemilik" name="telp_pemilik" value="{{ old('telp_pemilik', $kendaraan->telp_pemilik) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_pemilik" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_pemilik" name="email_pemilik" value="{{ old('email_pemilik', $kendaraan->email_pemilik) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Identitas Kendaraan (Data diisi dari $kendaraan) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header text-white" style="background-color: #0d6efd;">
                        <h4 class="card-title mb-0">2. Identitas Kendaraan Bermotor</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nrkb" class="form-label">NRKB</label>
                            <input type="text" class="form-control" id="nrkb" name="nrkb" value="{{ old('nrkb', $kendaraan->nrkb) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kendaraan" class="form-label">Jenis</label>
                                <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" value="{{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="model_kendaraan" class="form-label">Model</label>
                                <input type="text" class="form-control" id="model_kendaraan" name="model_kendaraan" value="{{ old('model_kendaraan', $kendaraan->model_kendaraan) }}" required>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="merk_kendaraan" class="form-label">Merk</label>
                                <input type="text" class="form-control" id="merk_kendaraan" name="merk_kendaraan" value="{{ old('merk_kendaraan', $kendaraan->merk_kendaraan) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipe_kendaraan" class="form-label">Tipe</label>
                                <input type="text" class="form-control" id="tipe_kendaraan" name="tipe_kendaraan" value="{{ old('tipe_kendaraan', $kendaraan->tipe_kendaraan) }}" required>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" required min="1901" max="{{ date('Y') }}">                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isi_silinder" class="form-label">Isi Silinder / Daya Listrik</label>
                                <input type="text" class="form-control" id="isi_silinder" name="isi_silinder" value="{{ old('isi_silinder', $kendaraan->isi_silinder) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_bahan_bakar" class="form-label">Jenis Bahan Bakar / Sumber Energi</label>
                            <input type="text" class="form-control" id="jenis_bahan_bakar" name="jenis_bahan_bakar" value="{{ old('jenis_bahan_bakar', $kendaraan->jenis_bahan_bakar) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
                            <input type="text" class="form-control" id="nomor_rangka" name="nomor_rangka" value="{{ old('nomor_rangka', $kendaraan->nomor_rangka) }}" required>
                        </div>
                         <div class="mb-3">
                            <label for="nomor_mesin" class="form-label">Nomor Mesin</label>
                            <input type="text" class="form-control" id="nomor_mesin" name="nomor_mesin" value="{{ old('nomor_mesin', $kendaraan->nomor_mesin) }}" required>
                        </div>
                         <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="warna_tnkb" class="form-label">Warna TNKB</label>
                                <input type="text" class="form-control" id="warna_tnkb" name="warna_tnkb" value="{{ old('warna_tnkb', $kendaraan->warna_tnkb) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nomor_bpkb" class="form-label">Nomor BPKB</label>
                                <input type="text" class="form-control" id="nomor_bpkb" name="nomor_bpkb" value="{{ old('nomor_bpkb', $kendaraan->nomor_bpkb) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Upload Dokumen (File tidak 'required' saat edit) -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color: #0d6efd;">
                <h4 class="card-title mb-0">3. Upload Dokumen Persyaratan (Opsional, Max 10MB per file)</h4>
                <small class="text-white-50">Upload file baru hanya jika Anda ingin menggantikan dokumen lama.</small>
            </div>
            <div class="card-body">
                {{-- TODO: Tampilkan daftar file yang sudah ada di sini --}}
                <div class="row">
                    <!-- Surat Permohonan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Surat permohonan penghapusan (PDF/DOCX)</label>
                        <div id="container_surat_permohonan">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="surat_permohonan[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('surat_permohonan', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>

                    <!-- Surat Pernyataan -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Surat pernyataan kepemilikan (PDF/DOCX)</label>
                        <div id="container_surat_pernyataan">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="surat_pernyataan[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('surat_pernyataan', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>

                    <!-- KTP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Tanda bukti identitas pemilik (KTP) (PDF/DOCX/JPG/PNG)</label>
                        <div id="container_ktp">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="ktp[]" accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('ktp', '.pdf,.docx,.jpg,.jpeg,.png', 10240)">+ Tambah File</button>
                    </div>

                    <!-- BPKB -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">BPKB (PDF/DOCX)</label>
                        <div id="container_bpkb">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="bpkb[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('bpkb', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>

                    <!-- TBPKP -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">TBPKP (PDF/DOCX)</label>
                        <div id="container_tbpkp">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="tbpkp[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('tbpkp', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>

                    <!-- Cek Fisik -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Hasil pemeriksaan cek fisik (PDF/DOCX)</label>
                        <div id="container_cek_fisik">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="cek_fisik[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('cek_fisik', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>

                    <!-- Foto Ranmor -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Foto Kendaraan (JPG/PNG)</label>
                        <div id="container_foto_ranmor">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="foto_ranmor[]" accept=".jpg,.jpeg,.png" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('foto_ranmor', '.jpg,.jpeg,.png', 10240)">+ Tambah File</button>
                    </div>

                    <!-- STNK -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">STNK (PDF/DOCX)</label>
                        <div id="container_stnk">
                            <div class="file-input-group mb-2">
                                <input type="file" class="form-control file-input" name="stnk[]" accept=".pdf,.docx" data-max-size="10240">
                                <small class="text-muted file-preview"></small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('stnk', '.pdf,.docx', 10240)">+ Tambah File</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg mb-4" id="btnSubmit">
            <span id="btnText">Update Kendaraan</span>
            <span id="btnLoading" class="d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Menyimpan data...
            </span>
        </button>
    </form>
    
    {{-- Script JS-nya identik dengan create.blade.php --}}
    <script>
        // Fungsi untuk menambah input file baru
        function addFileInput(fieldName, accept, maxSize) {
            const container = document.getElementById('container_' + fieldName);
            const fileInputGroup = document.createElement('div');
            fileInputGroup.className = 'file-input-group mb-2 position-relative';
            
            fileInputGroup.innerHTML = `
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="file" class="form-control file-input" name="${fieldName}[]" accept="${accept}" data-max-size="${maxSize}">
                        <small class="text-muted file-preview"></small>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFileInput(this)" style="height: 38px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            container.appendChild(fileInputGroup);
            attachFileValidation(fileInputGroup.querySelector('.file-input'));
        }

        // Fungsi untuk menghapus input file
        function removeFileInput(button) {
            button.closest('.file-input-group').remove();
        }

        // Fungsi validasi file (ukuran & tipe)
        function attachFileValidation(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const maxSizeKB = parseInt(this.dataset.maxSize);
                const maxSize = maxSizeKB * 1024;
                const maxSizeMB = maxSizeKB / 1024;
                const previewElement = this.nextElementSibling;
               
                if (file) {
                    if (file.size > maxSize) {
                        alert(`File ${file.name} terlalu besar! Maksimal ${maxSizeMB}MB`);
                        this.value = '';
                        previewElement.textContent = '';
                        previewElement.className = 'text-muted file-preview';
                        return;
                    }
                   
                    const allowedTypes = this.accept.split(',').map(type => type.trim());
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                   
                    if (!allowedTypes.includes(fileExt)) {
                        alert(`Format file tidak valid! Gunakan: ${this.accept}`);
                        this.value = '';
                        previewElement.textContent = '';
                        previewElement.className = 'text-muted file-preview';
                        return;
                    }
                   
                    const fileSizeKB = (file.size / 1024).toFixed(2);
                    previewElement.textContent = `✓ ${file.name} (${fileSizeKB} KB)`;
                    previewElement.className = 'text-success file-preview d-block mt-1';
                } else {
                    previewElement.textContent = '';
                    previewElement.className = 'text-muted file-preview';
                }
            });
        }

        // Attach validasi ke semua input file yang ada saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.file-input').forEach(input => {
                attachFileValidation(input);
            });
        });

        // Konfirmasi sebelum submit + Loading state
        document.getElementById('formPengajuan').addEventListener('submit', function(e) {
            e.preventDefault();
           
            if (!confirm('Apakah Anda yakin ingin memperbarui data kendaraan ini?')) {
                return;
            }
           
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
           
            btnSubmit.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
           
            this.submit();
        });

        // Mencegah double-submit saat tombol back browser
         window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const btnSubmit = document.getElementById('btnSubmit');
                const btnText = document.getElementById('btnText');
                const btnLoading = document.getElementById('btnLoading');
               
                btnSubmit.disabled = false;
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }
        });
    </script>

    <style>
         .file-input-group { animation: slideIn 0.3s ease-out; }
         @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>