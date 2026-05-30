<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="gap: 29.3rem;">
            <span>Tambah Kendaraan Baru ke Pengajuan: {{ $pengajuan->nomor_pengajuan }}</span>
            <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-secondary flex-shrink-0">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
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

     {{-- Info Persyaratan --}}
     <div class="alert alert-info mb-4">
         <strong>Persyaratan sesuai dengan KEP KAKOR Nomor: KEP/172/XI/2024 Tanggal 18 November 2024</strong>
     </div>

    {{-- 2. INI PERBAIKAN UTAMA: Arahkan ke 'pengajuan.kendaraan.store' --}}
    <form action="{{ route('pengajuan.kendaraan.store', $pengajuan) }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
        @csrf
        <div class="row">
            <!-- Kolom Identitas Pemilik -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header text-white" style="background-color: #0d6efd;">
                        <h4 class="card-title mb-0">1. Identitas Pemilik (Kendaraan Ini)</h4>
                    </div>
                    <div class="card-body">
                        {{-- Input Identitas Pemilik --}}
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label">Atas Nama</label>
                            <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nik_pemilik" class="form-label">NIK/TDP/NIB/Kitas/Kitab</label>
                            <input type="text" class="form-control" id="nik_pemilik" name="nik_pemilik" value="{{ old('nik_pemilik') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_pemilik" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat_pemilik" name="alamat_pemilik" rows="3" required>{{ old('alamat_pemilik') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telp_pemilik" class="form-label">No. TLP/HP</label>
                            <input type="text" class="form-control" id="telp_pemilik" name="telp_pemilik" value="{{ old('telp_pemilik') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_pemilik" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_pemilik" name="email_pemilik" value="{{ old('email_pemilik') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Identitas Kendaraan -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header text-white" style="background-color: #0d6efd;">
                         <h4 class="card-title mb-0">2. Identitas Kendaraan Bermotor</h4>
                    </div>
                    <div class="card-body">
                        {{-- Input Identitas Kendaraan --}}
                        <div class="mb-3">
                            <label for="nrkb" class="form-label">NRKB</label>
                            <input type="text" class="form-control" id="nrkb" name="nrkb" value="{{ old('nrkb') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kendaraan" class="form-label">Jenis</label>
                                <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="model_kendaraan" class="form-label">Model</label>
                                <input type="text" class="form-control" id="model_kendaraan" name="model_kendaraan" value="{{ old('model_kendaraan') }}" required>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="merk_kendaraan" class="form-label">Merk</label>
                                <input type="text" class="form-control" id="merk_kendaraan" name="merk_kendaraan" value="{{ old('merk_kendaraan') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipe_kendaraan" class="form-label">Tipe</label>
                                <input type="text" class="form-control" id="tipe_kendaraan" name="tipe_kendaraan" value="{{ old('tipe_kendaraan') }}" required>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                                <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" required min="1901" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="isi_silinder" class="form-label">Isi Silinder / Daya Listrik</label>
                                <input type="text" class="form-control" id="isi_silinder" name="isi_silinder" value="{{ old('isi_silinder') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_bahan_bakar" class="form-label">Jenis Bahan Bakar / Sumber Energi</label>
                            <input type="text" class="form-control" id="jenis_bahan_bakar" name="jenis_bahan_bakar" value="{{ old('jenis_bahan_bakar') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
                            <input type="text" class="form-control" id="nomor_rangka" name="nomor_rangka" value="{{ old('nomor_rangka') }}" required>
                        </div>
                         <div class="mb-3">
                            <label for="nomor_mesin" class="form-label">Nomor Mesin</label>
                            <input type="text" class="form-control" id="nomor_mesin" name="nomor_mesin" value="{{ old('nomor_mesin') }}" required>
                        </div>
                         <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="warna_tnkb" class="form-label">Warna TNKB</label>
                                <input type="text" class="form-control" id="warna_tnkb" name="warna_tnkb" value="{{ old('warna_tnkb') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nomor_bpkb" class="form-label">Nomor BPKB</label>
                                <input type="text" class="form-control" id="nomor_bpkb" name="nomor_bpkb" value="{{ old('nomor_bpkb') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Upload Dokumen -->
        <div class="card mb-4">
             <div class="card-header text-white" style="background-color: #0d6efd;">
                 <h4 class="card-title mb-0">3. Upload Dokumen Persyaratan (Max 10MB per file)</h4>
             </div>
             <div class="card-body">
                 <div class="row">
                     <!-- Surat Permohonan -->
                     <div class="col-md-6 mb-4">
                         <label class="form-label fw-bold">Surat permohonan penghapusan (PDF/DOCX)</label>
                         <div id="container_surat_permohonan">
                             <div class="file-input-group mb-2">
                                 <input type="file" class="form-control file-input" name="surat_permohonan[]" accept=".pdf,.docx" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="surat_pernyataan[]" accept=".pdf,.docx" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="ktp[]" accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="bpkb[]" accept=".pdf,.docx" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="tbpkp[]" accept=".pdf,.docx" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="cek_fisik[]" accept=".pdf,.docx" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="foto_ranmor[]" accept=".jpg,.jpeg,.png" data-max-size="10240" required>
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
                                 <input type="file" class="form-control file-input" name="stnk[]" accept=".pdf,.docx" data-max-size="10240" required>
                                 <small class="text-muted file-preview"></small>
                             </div>
                         </div>
                         <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput('stnk', '.pdf,.docx', 10240)">+ Tambah File</button>
                     </div>
                 </div>
             </div>
        </div>

        {{-- 3. PERBAIKI TEKS TOMBOL SUBMIT --}}
        <button type="submit" class="btn btn-primary w-100 btn-lg mb-4" id="btnSubmit">
             <span id="btnText">Simpan Kendaraan</span>
             <span id="btnLoading" class="d-none">
                 <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                 Menyimpan data...
             </span>
        </button>
    </form>

    {{-- Script JS (Lengkap dan Sudah Benar) --}}
    <script>
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

         function removeFileInput(button) {
             const fileInputGroup = button.closest('.file-input-group');
             const container = fileInputGroup.parentElement;
             
             // Cek apakah ini file input terakhir
             const remainingInputs = container.querySelectorAll('.file-input-group');
             if (remainingInputs.length > 1) {
                 fileInputGroup.remove();
             } else {
                 // Ganti pesan alert agar lebih jelas
                 alert('Minimal harus ada 1 file untuk kategori dokumen ini.');
             }
         }

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

         document.addEventListener('DOMContentLoaded', function() {
             document.querySelectorAll('.file-input').forEach(input => {
                 attachFileValidation(input);
             });
         });

         document.getElementById('formPengajuan').addEventListener('submit', function(e) {
             e.preventDefault();
            
             // Validasi ulang minimal 1 file (JS sisi klien)
             let isValid = true;
             const requiredFields = [
                 'surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 
                 'tbpkp', 'cek_fisik', 'foto_ranmor', 'stnk'
             ];
             
             for (let field of requiredFields) {
                 const inputs = document.querySelectorAll(`input[name="${field}[]"]`);
                 let hasFile = false;
                 
                 inputs.forEach(input => {
                     if (input.files.length > 0) {
                         hasFile = true;
                     }
                 });
                 
                 // Jika ini adalah file 'required', tapi tidak ada file, tandai tidak valid
                 if (!hasFile && inputs.length > 0 && inputs[0].hasAttribute('required')) {
                     alert(`Dokumen ${field.replace(/_/g, ' ')} wajib diisi!`);
                     isValid = false;
                     break;
                 }
             }
             
             if (!isValid) return;
            
             if (!confirm('Apakah Anda yakin semua data dan dokumen kendaraan ini sudah benar?')) {
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

    {{-- Style CSS --}}
    <style>
         .file-input-group { 
             animation: slideIn 0.3s ease-out; 
         }
         @keyframes slideIn { 
             from { 
                 opacity: 0; 
                 transform: translateY(-10px); 
             } 
             to { 
                 opacity: 1; 
                 transform: translateY(0); 
             } 
         }
         .file-preview {
             font-size: 0.875rem;
         }
    </style>
</x-app-layout>