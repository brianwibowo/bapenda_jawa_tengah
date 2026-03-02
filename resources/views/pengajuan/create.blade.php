<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>Buat Pengajuan Baru</span>
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
                        <span class="badge bg-warning">Kendaraan ID: {{ $kendaraand }}</span>
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
            <div class="d-flex flex-wrap gap-2 mb-3" id="kendaraanTabs">
                {{-- Tab kendaraan akan ditambahkan secara dinamis --}}
            </div>
            <button type="button" class="btn btn-outline-primary" id="btnTambahKendaraan">
                <i class="fas fa-plus me-1"></i> + Tambah kendaraan
            </button>
        </div>
    </div>

    {{-- Form Container --}}
    <div id="formContainer">
        {{-- Form kendaraan akan ditambahkan secara dinamis --}}
    </div>

    {{-- Tombol Finalisasi Pengajuan --}}
    <div class="card mb-4" id="finalizeCard" style="display: none;">
        <div class="card-body text-center">
            <button type="button" class="btn btn-success btn-lg" id="btnFinalize">
                <i class="fas fa-check-circle me-2"></i> Selesai & Buat Nomor Pengajuan
            </button>
        </div>
    </div>

    {{-- Template Form Kendaraan (Hidden) --}}
    <template id="kendaraanFormTemplate">
        <div class="kendaraan-form" data-kendaraan-index="">
            <div class="card mb-4 position-relative">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" style="z-index: 10;" onclick="hapusKendaraan(this)" title="Hapus Kendaraan">
                    <i class="fas fa-times"></i>
                </button>
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h4 class="card-title mb-0">Detail Kendaraan <span class="kendaraan-number"></span></h4>
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
                                        <h4 class="card-title mb-0">1. Identitas Pemilik (Kendaraan Ini)</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Atas Nama</label>
                                            <input type="text" class="form-control" name="nama_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NIK/TDP/NIB/Kitas/Kitab</label>
                                            <input type="text" class="form-control" name="nik_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea class="form-control" name="alamat_pemilik" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. TLP/HP</label>
                                            <input type="text" class="form-control" name="telp_pemilik" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email_pemilik" required>
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
                                        <div class="mb-3">
                                            <label class="form-label">NRKB</label>
                                            <input type="text" class="form-control" name="nrkb" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jenis</label>
                                                <input type="text" class="form-control" name="jenis_kendaraan" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Model</label>
                                                <input type="text" class="form-control" name="model_kendaraan" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Merk</label>
                                                <input type="text" class="form-control" name="merk_kendaraan" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipe</label>
                                                <input type="text" class="form-control" name="tipe_kendaraan" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tahun Pembuatan</label>
                                                <input type="number" class="form-control" name="tahun_pembuatan" required min="1901" max="{{ date('Y') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Isi Silinder / Daya Listrik</label>
                                                <input type="text" class="form-control" name="isi_silinder" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Bahan Bakar / Sumber Energi</label>
                                            <input type="text" class="form-control" name="jenis_bahan_bakar" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Rangka</label>
                                            <input type="text" class="form-control" name="nomor_rangka" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nomor Mesin</label>
                                            <input type="text" class="form-control" name="nomor_mesin" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Warna TNKB</label>
                                                <input type="text" class="form-control" name="warna_tnkb" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nomor BPKB</label>
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
                                <h4 class="card-title mb-0">3. Upload Dokumen Persyaratan (Max 10MB per file)</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Surat Permohonan -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Surat permohonan penghapusan (PDF/DOCX)</label>
                                        <div class="file-container" data-field="surat_permohonan" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="surat_permohonan[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- Surat Pernyataan -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Surat pernyataan kepemilikan (PDF/DOCX)</label>
                                        <div class="file-container" data-field="surat_pernyataan" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="surat_pernyataan[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- KTP -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Tanda bukti identitas pemilik (KTP) (PDF/DOCX/JPG/PNG)</label>
                                        <div class="file-container" data-field="ktp" data-accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="ktp[]" accept=".pdf,.docx,.jpg,.jpeg,.png" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- BPKB -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">BPKB (PDF/DOCX)</label>
                                        <div class="file-container" data-field="bpkb" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="bpkb[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- TBPKP -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">TBPKP (PDF/DOCX)</label>
                                        <div class="file-container" data-field="tbpkp" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="tbpkp[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- Cek Fisik -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Hasil pemeriksaan cek fisik (PDF/DOCX)</label>
                                        <div class="file-container" data-field="cek_fisik" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="cek_fisik[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- Foto Ranmor -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Foto Kendaraan (JPG/PNG)</label>
                                        <div class="file-container" data-field="foto_ranmor" data-accept=".jpg,.jpeg,.png" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="foto_ranmor[]" accept=".jpg,.jpeg,.png" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>

                                    <!-- STNK -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">STNK (PDF/DOCX)</label>
                                        <div class="file-container" data-field="stnk" data-accept=".pdf,.docx" data-max-size="10240">
                                            <div class="file-input-group mb-2">
                                                <input type="file" class="form-control file-input" name="stnk[]" accept=".pdf,.docx" data-max-size="10240" required>
                                                <small class="text-muted file-preview"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFileInput(this)">+ Tambah File</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid" style="grid-template-columns: 40% 40% 20%; gap: 0; align-items: center;">
                            <button type="button" class="btn btn-primary btn-lg btn-save-kendaraan">
                                <i class="fas fa-save me-2"></i> Simpan Kendaraan
                            </button>
                            <div></div>
                            <button type="button" class="btn btn-primary btn-lg btn-pdf-preview" data-pdf-preview="true" target="_blank" style="display: none; justify-self: end;">
                                <i class="fas fa-file-pdf me-2"></i> Preview PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <script>
        let kendaraanCount = 0;
        let currentPengajuanId = null;
        let savedKendaraans = {}; 
        const STORAGE_KEY = 'pengajuan_draft';
        const DRAFT_ACTIVE_KEY = 'pengajuan_draft_active';
        const AUTO_SAVE_DELAY = 2500;
        const autoSaveTimers = {};
        const formDirtyState = {};
        const savingState = {};
        const REQUIRED_FILE_FIELDS = ['surat_permohonan', 'surat_pernyataan', 'ktp', 'bpkb', 'tbpkp', 'cek_fisik', 'foto_ranmor', 'stnk'];
        let beforeUnloadHandler = null;

        function clearDraftStorage() {
            try {
                sessionStorage.removeItem(STORAGE_KEY);
                sessionStorage.removeItem(DRAFT_ACTIVE_KEY);
            } catch (error) {
                console.warn('Draft pengajuan tidak dapat dihapus dari storage:', error);
            }
            savedKendaraans = {};
            currentPengajuanId = null;
            kendaraanCount = 0;
            Object.keys(formDirtyState).forEach(key => delete formDirtyState[key]);
            Object.keys(autoSaveTimers).forEach(key => clearTimeout(autoSaveTimers[key]));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navigationEntry = performance.getEntriesByType('navigation')[0];
            const isReload = navigationEntry && navigationEntry.type === 'reload';
            
            if (!isReload) {
                clearDraftStorage();
            } else {
                loadFromStorage();
            }

            if (kendaraanCount === 0) {
                tambahKendaraan();
            }

            setupGlobalListeners();
        });

        /**
         * Utility function untuk menampilkan toast notification
         */
        function showToast(message, type = 'info') {
            // Buat toast container jika belum ada
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
                document.body.appendChild(toastContainer);
            }
            
            // Tentukan warna berdasarkan type
            const bgColor = {
                'success': '#28a745',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8'
            }[type] || '#17a2b8';
            
            const textColor = type === 'warning' ? '#000' : '#fff';
            
            // Buat toast element
            const toast = document.createElement('div');
            toast.style.cssText = `
                background-color: ${bgColor};
                color: ${textColor};
                padding: 12px 20px;
                margin-bottom: 10px;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                animation: slideIn 0.3s ease-in;
            `;
            toast.textContent = message;
            
            toastContainer.appendChild(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Add CSS animations for toast
        if (!document.getElementById('toastStyles')) {
            const style = document.createElement('style');
            style.id = 'toastStyles';
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        function setupGlobalListeners() {
            document.getElementById('btnTambahKendaraan').addEventListener('click', tambahKendaraan);
            document.getElementById('btnFinalize').addEventListener('click', finalizePengajuan);
            
            beforeUnloadHandler = function(event) {
                if (hasUnsavedChanges()) {
                    event.preventDefault();
                    event.returnValue = '';
                }
            };
            window.addEventListener('beforeunload', beforeUnloadHandler);
            setupNavigationGuards();

            document.addEventListener('input', debounce(saveToStorage, 1000));
            document.addEventListener('change', debounce(saveToStorage, 1000));
            document.addEventListener('input', debounce(updatePdfPreviewButtonVisibility, 500));
            document.addEventListener('change', debounce(updatePdfPreviewButtonVisibility, 500));
        }

        function updatePdfPreviewButtonVisibility() {
            const activeForm = document.querySelector('.kendaraan-form[style="display: block;"]') || document.querySelector('.kendaraan-form:first-of-type');
            if (!activeForm) return;
            
            const index = activeForm.getAttribute('data-kendaraan-index');
            const btnPdfPreview = activeForm.querySelector('.btn-pdf-preview');
            
            if (btnPdfPreview) {
                const pengajuanId = savedKendaraans[index]?.pengajuan_id;
                if (pengajuanId && !hasUnsavedChanges()) {
                    btnPdfPreview.style.display = 'block';
                    btnPdfPreview.href = `{{ url('pdf/view') }}/${pengajuanId}`;
                    btnPdfPreview.addEventListener('click', function(e) {
                        if (!this.href || this.href === `{{ url('cetak/spopd') }}/undefined`) {
                            e.preventDefault();
                            alert('Kendaraan belum memiliki ID yang valid.');
                        } else {
                            window.open(this.href, '_blank');
                        }
                    });
                } else {
                    btnPdfPreview.style.display = 'none';
                }
            }
        }

        function setupNavigationGuards() {
            const guardedLinks = document.querySelectorAll('.sidebar-content a');
            guardedLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    if (!hasUnsavedChanges()) {
                        return;
                    }
                    event.preventDefault();
                    if (confirm('Data draft belum disimpan sepenuhnya. Tinggalkan halaman dan hapus draft?')) {
                        window.removeEventListener('beforeunload', beforeUnloadHandler);
                        clearDraftStorage();
                        window.location.href = this.href;
                    }
                });
            });
        }

        function hasUnsavedChanges() {
            const forms = document.querySelectorAll('.kendaraan-form');
            if (!forms.length) return false;

            return Array.from(forms).some(form => {
                const index = form.getAttribute('data-kendaraan-index');
                const isSaved = savedKendaraans[index] && !formDirtyState[index];
                return !isSaved && hasFormContent(form);
            });
        }

        function hasFormContent(form) {
            return Array.from(form.querySelectorAll('input:not([type="hidden"]), textarea')).some(input => {
                if (input.type === 'file') {
                    return input.files && input.files.length > 0;
                }
                return input.value && input.value.trim() !== '';
            });
        }

        function tambahKendaraan() {
            // Cari index terbesar yang ada
            const existingForms = document.querySelectorAll('.kendaraan-form');
            let maxIndex = 0;
            existingForms.forEach(form => {
                const idx = parseInt(form.getAttribute('data-kendaraan-index'));
                if (idx > maxIndex) maxIndex = idx;
            });
            
            // Index baru adalah maxIndex + 1
            const index = maxIndex + 1;
            kendaraanCount = index;
            
            // Clone template
            const template = document.getElementById('kendaraanFormTemplate');
            const clone = template.content.cloneNode(true);
            const formDiv = clone.querySelector('.kendaraan-form');
            formDiv.setAttribute('data-kendaraan-index', index);
            formDiv.querySelector('.kendaraan-number').textContent = index;
            
            // Update semua input names dengan index (kecuali file inputs yang sudah punya [])
            formDiv.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.name) {
                    // File inputs dengan [] tetap pakai [] tapi tambahkan prefix
                    if (input.name.includes('[]')) {
                        const baseName = input.name.replace('[]', '');
                        input.name = `kendaraan_${index}_${baseName}[]`;
                    } else {
                        input.name = `kendaraan_${index}_${input.name}`;
                    }
                }
            });
            
            // Setup file inputs
            formDiv.querySelectorAll('.file-input').forEach(input => {
                attachFileValidation(input);
            });
            attachAutoSaveHandlers(formDiv, index);
            formDirtyState[index] = false;
            
            // Setup save button
            formDiv.querySelector('.btn-save-kendaraan').addEventListener('click', function() {
                simpanKendaraan(index, { showSuccess: true, force: true });
            });
            
            // Tambahkan ke DOM
            document.getElementById('formContainer').appendChild(clone);
            
            // Buat tab
            buatTab(index);
            
            // Aktifkan tab baru
            aktifkanTab(index);
            
            // Update finalize button visibility
            updateFinalizeButton();
            
            saveToStorage();
            
            // Tampilkan notifikasi
            showToast(`✓ Kendaraan ${index} ditambahkan`, 'info');
        }

        function attachAutoSaveHandlers(formDiv, index) {
            const inputs = formDiv.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const eventType = input.type === 'file' ? 'change' : 'input';
                input.addEventListener(eventType, function() {
                    formDirtyState[index] = true;
                    scheduleAutoSave(index);
                });
            });
        }

        function scheduleAutoSave(index) {
            clearTimeout(autoSaveTimers[index]);
            autoSaveTimers[index] = setTimeout(() => autoSaveKendaraan(index), AUTO_SAVE_DELAY);
        }

        async function autoSaveKendaraan(index) {
            await simpanKendaraan(index, { isAuto: true, showSuccess: false });
        }

        function buatTab(index) {
            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'btn btn-kendaraan-tab';
            tab.textContent = `Kendaraan ${index}`;
            tab.setAttribute('data-index', index);
            tab.addEventListener('click', () => aktifkanTab(index));
            
            document.getElementById('kendaraanTabs').appendChild(tab);
            updateTabStyles();
        }

        function aktifkanTab(index) {
            // Hide semua form
            document.querySelectorAll('.kendaraan-form').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show form yang dipilih
            const selectedForm = document.querySelector(`[data-kendaraan-index="${index}"]`);
            if (selectedForm) {
                selectedForm.style.display = 'block';
            }
            
            // Update tab styles
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-index') == index) {
                    btn.classList.add('active');
                }
            });
            
            updateTabStyles();
        }

        function updateTabStyles() {
            document.querySelectorAll('.btn-kendaraan-tab').forEach((btn, idx) => {
                const index = btn.getAttribute('data-index');
                const isActive = btn.classList.contains('active');
                const isSaved = savedKendaraans[index] && savedKendaraans[index].kendaraan_id;
                
                if (isActive) {
                    btn.style.backgroundColor = '#ffc107';
                    btn.style.color = '#000';
                    btn.style.fontWeight = 'bold';
                } else if (isSaved) {
                    btn.style.backgroundColor = '#d4edda';
                    btn.style.color = '#000';
                } else {
                    btn.style.backgroundColor = '#f5f5f5';
                    btn.style.color = '#000';
                }
            });
        }

        async function hapusKendaraan(button) {
            const formDiv = button.closest('.kendaraan-form');
            const index = parseInt(formDiv.getAttribute('data-kendaraan-index'));
            const savedInfo = savedKendaraans[index];
            
            console.log(`[hapusKendaraan] Hapus kendaraan index=${index}, savedInfo:`, savedInfo);
            
            if (savingState[index]) {
                alert('Sedang menyimpan kendaraan ini. Mohon tunggu beberapa saat.');
                return;
            }

            if (!confirm(`Yakin ingin menghapus Kendaraan ${index}?`)) {
                return;
            }

            if (savedInfo && savedInfo.kendaraan_id) {
                const deleteConfirm = confirm('⚠️ Kendaraan ini sudah tersimpan di server.\n\nMenghapusnya akan menghapus data dari draft DAN server secara permanen.\n\nLanjutkan?');
                if (!deleteConfirm) {
                    return;
                }

                console.log(`[hapusKendaraan] Calling DELETE API untuk kendaraan_id=${savedInfo.kendaraan_id}`);
                const deleted = await deleteSavedKendaraan(savedInfo.kendaraan_id);
                if (!deleted) {
                    return;
                }
                delete savedKendaraans[index];
            } else {
                console.log(`[hapusKendaraan] Kendaraan belum ter-save ke DB (savedInfo=${savedInfo}), hanya hapus dari draft`);
            }
            
            // Get current active tab untuk reference
            const currentActiveTab = document.querySelector('.btn-kendaraan-tab.active');
            const isCurrentTabBeingDeleted = currentActiveTab && parseInt(currentActiveTab.getAttribute('data-index')) === index;
            
            // Hapus dari DOM
            formDiv.remove();
            
            // Hapus tab
            const tab = document.querySelector(`.btn-kendaraan-tab[data-index="${index}"]`);
            if (tab) tab.remove();
            
            clearTimeout(autoSaveTimers[index]);
            delete autoSaveTimers[index];
            delete formDirtyState[index];
            delete savingState[index];
            
            console.log(`[hapusKendaraan] Cleanup state untuk index=${index}`);
            
            // Renumber semua kendaraan yang tersisa
            renumberKendaraans();
            
            // Tentukan kendaraan mana yang harus di-fokus setelah delete
            let nextTabToFocus = null;
            const remainingTabs = document.querySelectorAll('.btn-kendaraan-tab');
            const remainingForms = document.querySelectorAll('.kendaraan-form');
            
            if (remainingForms.length === 0) {
                // Tidak ada kendaraan tersisa, buat baru
                tambahKendaraan();
                nextTabToFocus = 1;
            } else if (remainingForms.length > 0) {
                // Ada kendaraan tersisa, tentukan mana yang harus di-fokus
                if (isCurrentTabBeingDeleted) {
                    // Tab yang di-delete adalah tab yang aktif
                    // Cari tab berikutnya atau sebelumnya
                    if (remainingTabs.length > 0) {
                        // Default fokus ke tab pertama dari yang tersisa
                        const firstRemainingTab = remainingTabs[0];
                        nextTabToFocus = parseInt(firstRemainingTab.getAttribute('data-index'));
                    }
                } else {
                    // Tab yang di-delete bukan yang aktif, maintain active tab
                    const activeTab = document.querySelector('.btn-kendaraan-tab.active');
                    if (activeTab) {
                        nextTabToFocus = parseInt(activeTab.getAttribute('data-index'));
                    } else if (remainingTabs.length > 0) {
                        nextTabToFocus = parseInt(remainingTabs[0].getAttribute('data-index'));
                    }
                }
            }
            
            // Fokus ke tab yang ditentukan
            if (nextTabToFocus !== null) {
                console.log(`[hapusKendaraan] Fokus ke kendaraan index=${nextTabToFocus}`);
                aktifkanTab(nextTabToFocus);
            }
            
            updateFinalizeButton();
            saveToStorage();
            console.log(`[hapusKendaraan] Selesai. savedKendaraans sekarang:`, savedKendaraans);
            
            // Tampilkan info bahwa kendaraan sudah dihapus
            showToast(`Kendaraan ${index} berhasil dihapus`, 'info');
        }

        async function deleteSavedKendaraan(kendaraanId) {
            try {
                console.log(`[deleteSavedKendaraan] DELETE request untuk kendaraan_id=${kendaraanId}`);
                const response = await fetch(`{{ url('kendaraan') }}/${kendaraanId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    console.error(`[deleteSavedKendaraan] DELETE gagal, status=${response.status}`, data);
                    throw new Error(data.error || 'Gagal menghapus kendaraan tersimpan.');
                }

                console.log(`[deleteSavedKendaraan] DELETE berhasil untuk kendaraan_id=${kendaraanId}`);
                return true;
            } catch (error) {
                console.error(`[deleteSavedKendaraan] Error:`, error);
                alert(error.message);
                return false;
            }
        }

        function renumberKendaraans() {
            const forms = Array.from(document.querySelectorAll('.kendaraan-form')).sort((a, b) => {
                return parseInt(a.getAttribute('data-kendaraan-index')) - parseInt(b.getAttribute('data-kendaraan-index'));
            });
            
            // Create mapping dari old index ke new index
            const indexMapping = {};
            
            // Renumber dari 1
            forms.forEach((form, newIndex) => {
                const newNumber = newIndex + 1;
                const oldIndex = parseInt(form.getAttribute('data-kendaraan-index'));
                
                // Jika oldIndex != newNumber, perlu di-update
                if (oldIndex !== newNumber) {
                    indexMapping[oldIndex] = newNumber;
                    
                    // Update form index
                    form.setAttribute('data-kendaraan-index', newNumber);
                    form.querySelector('.kendaraan-number').textContent = newNumber;
                    
                    // Update semua input names
                    form.querySelectorAll('input, textarea, select').forEach(input => {
                        if (input.name) {
                            if (input.name.includes('[]')) {
                                const baseName = input.name.replace(`kendaraan_${oldIndex}_`, '').replace('[]', '');
                                input.name = `kendaraan_${newNumber}_${baseName}[]`;
                            } else {
                                const baseName = input.name.replace(`kendaraan_${oldIndex}_`, '');
                                input.name = `kendaraan_${newNumber}_${baseName}`;
                            }
                        }
                    });
                    
                    // Update save button event listener
                    const saveBtn = form.querySelector('.btn-save-kendaraan');
                    saveBtn.replaceWith(saveBtn.cloneNode(true)); // Remove old listener
                    form.querySelector('.btn-save-kendaraan').addEventListener('click', function() {
                        simpanKendaraan(newNumber, { showSuccess: true, force: true });
                    });
                    
                    // Update saved data jika ada
                    if (savedKendaraans[oldIndex]) {
                        savedKendaraans[newNumber] = savedKendaraans[oldIndex];
                        delete savedKendaraans[oldIndex];
                    }

                    if (Object.prototype.hasOwnProperty.call(formDirtyState, oldIndex)) {
                        formDirtyState[newNumber] = formDirtyState[oldIndex];
                        delete formDirtyState[oldIndex];
                    } else {
                        formDirtyState[newNumber] = false;
                    }
                    
                    // Hapus saving state yang lama
                    if (Object.prototype.hasOwnProperty.call(savingState, oldIndex)) {
                        delete savingState[oldIndex];
                    }
                    
                    // Hapus auto save timer yang lama
                    if (Object.prototype.hasOwnProperty.call(autoSaveTimers, oldIndex)) {
                        clearTimeout(autoSaveTimers[oldIndex]);
                        delete autoSaveTimers[oldIndex];
                    }
                }
            });
            
            // Renumber tabs
            const tabs = Array.from(document.querySelectorAll('.btn-kendaraan-tab')).sort((a, b) => {
                return parseInt(a.getAttribute('data-index')) - parseInt(b.getAttribute('data-index'));
            });
            
            tabs.forEach((tab, newIndex) => {
                const newNumber = newIndex + 1;
                const oldIndex = parseInt(tab.getAttribute('data-index'));
                
                if (oldIndex !== newNumber) {
                    tab.setAttribute('data-index', newNumber);
                    tab.textContent = `Kendaraan ${newNumber}`;
                    
                    // Remove old listener and add new one
                    const newTab = tab.cloneNode(true);
                    tab.parentNode.replaceChild(newTab, tab);
                    newTab.addEventListener('click', () => aktifkanTab(newNumber));
                }
            });
            
            // Aktifkan tab pertama jika ada
            if (forms.length > 0) {
                aktifkanTab(1);
            }

            kendaraanCount = forms.length;
        }

        async function simpanKendaraan(index, options = {}) {
            const { isAuto = false, showSuccess = false, force = false } = options;
            const formDiv = document.querySelector(`[data-kendaraan-index="${index}"]`);
            if (!formDiv) {
                return false;
            }
            const form = formDiv.querySelector('.kendaraan-form-data');
            if (!form) {
                return false;
            }
            
            if (!form.checkValidity()) {
                if (!isAuto || force) {
                    form.reportValidity();
                }
                return false;
            }
            
            const missingFileField = getMissingRequiredFile(formDiv);
            if (missingFileField) {
                if (!isAuto || force) {
                    alert(`Dokumen ${missingFileField.replace(/_/g, ' ')} wajib diisi!`);
                }
                return false;
            }

            if (savingState[index]) {
                return savingState[index];
            }
            
            // Prepare FormData
            const formData = new FormData();
            
            // Ambil semua input dari form
            form.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.type === 'file' && input.files) {
                    // Handle multiple files - extract base name and append with []
                    const nameWithPrefix = input.name;
                    if (nameWithPrefix.includes('[]')) {
                        const baseName = nameWithPrefix.replace(`kendaraan_${index}_`, '').replace('[]', '');
                        for (let i = 0; i < input.files.length; i++) {
                            formData.append(baseName + '[]', input.files[i]);
                        }
                    }
                } else if (input.name && input.type !== 'file' && input.type !== 'hidden') {
                    // Skip hidden inputs that are kendaraan_id and pengajuan_id (we'll add them separately)
                    if (input.name !== `kendaraan_${index}_kendaraan_id` && input.name !== `kendaraan_${index}_pengajuan_id`) {
                        const cleanName = input.name.replace(`kendaraan_${index}_`, '');
                        formData.append(cleanName, input.value);
                    }
                }
            });
            
            if (currentPengajuanId) {
                formData.append('pengajuan_id', currentPengajuanId);
            }
            
            if (savedKendaraans[index] && savedKendaraans[index].kendaraan_id) {
                formData.append('kendaraan_id', savedKendaraans[index].kendaraan_id);
            }
            
            const saveBtn = formDiv.querySelector('.btn-save-kendaraan');
            let originalText = null;
            if (!isAuto && saveBtn) {
                originalText = saveBtn.innerHTML;
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            }

            const savePromise = (async () => {
                try {
                    const response = await fetch('{{ route("pengajuan.kendaraan.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok || !data.success) {
                        if (!isAuto || force) {
                            alert(data.error || 'Gagal menyimpan kendaraan');
                        }
                        return false;
                    }

                    savedKendaraans[index] = {
                        kendaraan_id: data.kendaraan_id,
                        pengajuan_id: data.pengajuan_id
                    };
                    currentPengajuanId = data.pengajuan_id;
                    
                    formDiv.querySelector('.kendaraan-id-input').value = data.kendaraan_id;
                    formDiv.querySelector('.pengajuan-id-input').value = data.pengajuan_id;
                    formDirtyState[index] = false;
                    
                    updateTabStyles();
                    updateFinalizeButton();
                    updatePdfPreviewButtonVisibility();
                    saveToStorage();
                    
                    if (!isAuto && showSuccess) {
                        showToast('✓ Kendaraan berhasil disimpan!', 'success');
                    } else if (isAuto) {
                        console.log(`[simpanKendaraan] Auto-save berhasil untuk kendaraan ${index}`);
                    }
                    return true;
                } catch (error) {
                    if (!isAuto || force) {
                        showToast('✗ Error: ' + error.message, 'error');
                    }
                    return false;
                } finally {
                    if (!isAuto && saveBtn && originalText !== null) {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalText;
                    }
                    savingState[index] = null;
                }
            })();

            savingState[index] = savePromise;
            return savePromise;
        }

        async function finalizePengajuan() {
            const totalForms = document.querySelectorAll('.kendaraan-form').length;
            if (totalForms === 0) {
                alert('Tambahkan minimal 1 kendaraan terlebih dahulu.');
                return;
            }

            const allSaved = await saveAllKendaraans(true);
            if (!allSaved || !currentPengajuanId) {
                return;
            }
            
            window.removeEventListener('beforeunload', beforeUnloadHandler);
            clearDraftStorage();
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("pengajuan.store") }}';
            form.innerHTML = `
                @csrf
                <input type="hidden" name="pengajuan_id" value="${currentPengajuanId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        async function saveAllKendaraans(force = false) {
            const forms = Array.from(document.querySelectorAll('.kendaraan-form'));
            let anySaved = false;

            for (const form of forms) {
                const index = form.getAttribute('data-kendaraan-index');
                const needsSave = !savedKendaraans[index] || formDirtyState[index];

                if (needsSave) {
                    const success = await simpanKendaraan(index, { force, showSuccess: !force });
                    if (!success) {
                        aktifkanTab(index);
                        return false;
                    }
                }

                if (savedKendaraans[index]) {
                    anySaved = true;
                }
            }

            if (!anySaved && !force) {
                alert('Belum ada kendaraan yang disimpan.');
                return false;
            }

            return anySaved;
        }

        function updateFinalizeButton() {
            const totalForms = document.querySelectorAll('.kendaraan-form').length;
            document.getElementById('finalizeCard').style.display = totalForms > 0 ? 'block' : 'none';
        }

        function saveToStorage() {
            const data = {
                kendaraanCount: kendaraanCount,
                currentPengajuanId: currentPengajuanId,
                savedKendaraans: savedKendaraans,
                forms: {}
            };
            
            // Save form values
            document.querySelectorAll('.kendaraan-form').forEach(form => {
                const index = form.getAttribute('data-kendaraan-index');
                const formData = {};
                form.querySelectorAll('input:not([type="file"]), textarea').forEach(input => {
                    if (input.name) {
                        formData[input.name] = input.value;
                    }
                });
                data.forms[index] = formData;
            });
            
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        }

        function loadFromStorage() {
            const stored = sessionStorage.getItem(STORAGE_KEY);
            if (!stored) return;
            
            try {
                const data = JSON.parse(stored);
                currentPengajuanId = data.currentPengajuanId;
                savedKendaraans = data.savedKendaraans || {};
                
                // Restore forms - urutkan berdasarkan index
                if (data.forms) {
                    const sortedIndices = Object.keys(data.forms).map(Number).sort((a, b) => a - b);
                    sortedIndices.forEach(index => {
                        tambahKendaraan();
                        const form = document.querySelector(`[data-kendaraan-index="${index}"]`);
                        if (form) {
                            Object.keys(data.forms[index]).forEach(name => {
                                const input = form.querySelector(`[name="${name}"]`);
                                if (input && input.type !== 'file') {
                                    input.value = data.forms[index][name];
                                }
                            });
                        }
                    });
                    
                    // Renumber setelah load untuk memastikan urutan benar
                    renumberKendaraans();
                }
            } catch (e) {
                console.error('Error loading from storage:', e);
            }
        }

        function addFileInput(button) {
            const container = button.previousElementSibling;
            const fieldName = container.dataset.field;
            const accept = container.dataset.accept;
            const maxSize = container.dataset.maxSize;
            
            // Find the parent form to get the kendaraan index
            const formDiv = button.closest('.kendaraan-form');
            const index = formDiv ? formDiv.getAttribute('data-kendaraan-index') : '';
            const fullFieldName = index ? `kendaraan_${index}_${fieldName}[]` : `${fieldName}[]`;
            
            const fileInputGroup = document.createElement('div');
            fileInputGroup.className = 'file-input-group mb-2';
            fileInputGroup.innerHTML = `
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="file" class="form-control file-input" name="${fullFieldName}" accept="${accept}" data-max-size="${maxSize}">
                        <small class="text-muted file-preview"></small>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFileInput(this)" style="height: 38px;">
                        <span>&times;</span>
                    </button>
                </div>
            `;
            
            container.appendChild(fileInputGroup);
            attachFileValidation(fileInputGroup.querySelector('.file-input'));
        }

        function removeFileInput(button) {
            const container = button.closest('.file-container');
            const remainingInputs = container.querySelectorAll('.file-input-group');
            if (remainingInputs.length > 1) {
                button.closest('.file-input-group').remove();
            } else {
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
                        return;
                    }
                    
                    const allowedTypes = this.accept.split(',').map(type => type.trim());
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                    
                    if (!allowedTypes.includes(fileExt)) {
                        alert(`Format file tidak valid! Gunakan: ${this.accept}`);
                        this.value = '';
                        previewElement.textContent = '';
                        return;
                    }
                    
                    const fileSizeKB = (file.size / 1024).toFixed(2);
                    previewElement.textContent = `✓ ${file.name} (${fileSizeKB} KB)`;
                    previewElement.className = 'text-success file-preview d-block mt-1';
                }
            });
        }

        function getMissingRequiredFile(formDiv) {
            for (const field of REQUIRED_FILE_FIELDS) {
                if (!hasRequiredFile(formDiv, field)) {
                    return field;
                }
            }
            return null;
        }

        function hasRequiredFile(formDiv, field) {
            const inputs = formDiv.querySelectorAll(`input[name*="${field}"]`);
            return Array.from(inputs).some(input => input.files && input.files.length > 0);
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>

    <style>
        .btn-kendaraan-tab {
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-kendaraan-tab.active {
            transform: scale(1.05);
        }
        
        .kendaraan-form {
            display: none;
        }
        
        .kendaraan-form:first-of-type {
            display: block;
        }
        
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
    </style>
</x-app-layout>
