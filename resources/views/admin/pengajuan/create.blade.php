<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Form Pengajuan Berkas') }}
                </h2>
                <p class="text-muted mb-0 text-sm">Nomor Pengajuan: <strong>{{ $pengajuan->nomor_pengajuan }}</strong></p>
            </div>
            
            {{-- Tombol Final (Kirim Semua) --}}
            <form action="{{ route('pengajuan.submit_bundel', $pengajuan) }}" method="POST" id="formSubmitBundel">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane me-2"></i> Selesai & Kirim Pengajuan
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Area Notifikasi AJAX --}}
            <div id="ajaxAlert" class="alert d-none" role="alert"></div>

            {{-- Container Utama Wizard --}}
            <div class="card shadow-sm" style="min-height: 600px;">
                
                {{-- 1. Header Tab Navigasi --}}
                <div class="card-header bg-light border-bottom pt-3 pb-0 px-4">
                    <ul class="nav nav-tabs card-header-tabs" id="kendaraanTabs" role="tablist">
                        {{-- Tab akan di-generate oleh JS --}}
                        
                        {{-- Tombol Tambah Tab --}}
                        <li class="nav-item ms-2">
                            <button class="btn btn-sm btn-outline-primary mt-1" id="btnAddTab" type="button">
                                <i class="fas fa-plus"></i> Tambah Kendaraan
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- 2. Body Content (Form Kendaraan) --}}
                <div class="card-body p-4">
                    <div class="tab-content" id="kendaraanTabsContent">
                        
                        {{-- TEMPLATE FORM KENDARAAN (Hidden) --}}
                        <div id="templateForm" class="d-none">
                            <form class="form-kendaraan" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="kendaraan_id" class="input-kendaraan-id">

                                {{-- Bagian 1: Identitas Pemilik --}}
                                <h5 class="text-primary mb-3 border-bottom pb-2"><i class="fas fa-user me-2"></i>1. Identitas Pemilik</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">NIK / No. KTP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nik_pemilik" required placeholder="Masukkan NIK">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_pemilik" required placeholder="Nama Pemilik">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat_pemilik" rows="2" required placeholder="Alamat domisili"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. Telepon / WA <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="telp_pemilik" required placeholder="08xxx">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_pemilik" required placeholder="email@contoh.com">
                                    </div>
                                </div>

                                {{-- Bagian 2: Identitas Kendaraan --}}
                                <h5 class="text-primary mb-3 border-bottom pb-2 mt-5"><i class="fas fa-car me-2"></i>2. Identitas Kendaraan</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label">NRKB (Plat Nomor) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nrkb" required placeholder="Cth: H 1234 AB">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jenis_kendaraan" required placeholder="Cth: Sepeda Motor">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Merk <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="merk_kendaraan" required placeholder="Cth: Honda">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tipe_kendaraan" required placeholder="Cth: Vario 125">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Model <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="model_kendaraan" required placeholder="Cth: Scooter">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tahun Pembuatan <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="tahun_pembuatan" required min="1900" max="{{ date('Y') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Isi Silinder (CC) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="isi_silinder" required placeholder="Cth: 125 cc">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Warna TNKB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="warna_tnkb" required placeholder="Cth: Hitam">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Bahan Bakar <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jenis_bahan_bakar" required placeholder="Cth: Bensin">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Rangka (VIN) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nomor_rangka" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Mesin <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nomor_mesin" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor BPKB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nomor_bpkb" required>
                                    </div>
                                </div>

                                {{-- Bagian 3: Upload Dokumen --}}
                                <h5 class="text-primary mb-3 border-bottom pb-2 mt-5"><i class="fas fa-file-upload me-2"></i>3. Dokumen Persyaratan</h5>
                                <div class="alert alert-info text-sm">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Format yang didukung: PDF, DOCX, JPG, PNG. Maksimal 10MB per file.
                                </div>

                                <div class="row g-3">
                                    @php
                                        $docs = [
                                            'surat_permohonan' => 'Surat Permohonan',
                                            'surat_pernyataan' => 'Surat Pernyataan',
                                            'ktp' => 'KTP Pemilik',
                                            'bpkb' => 'BPKB Asli',
                                            'stnk' => 'STNK Asli',
                                            'cek_fisik' => 'Cek Fisik Kendaraan',
                                            'foto_ranmor' => 'Foto Kendaraan',
                                            'tbpkp' => 'TBPKP (Bukti Lunas Pajak)'
                                        ];
                                    @endphp

                                    @foreach ($docs as $key => $label)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">{{ $label }} <span class="text-danger">*</span></label>
                                            
                                            {{-- Wrapper Input File Dinamis --}}
                                            <div class="file-input-container" data-field="{{ $key }}">
                                                <div class="input-group mb-2">
                                                    <input type="file" class="form-control" name="{{ $key }}[]" required>
                                                    <button type="button" class="btn btn-outline-secondary btn-add-file" title="Tambah file lain">+</button>
                                                </div>
                                            </div>
                                            <div class="form-text text-xs text-muted">Upload minimal 1 file.</div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Tombol Aksi Bawah --}}
                                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                                    <button type="button" class="btn btn-outline-danger btn-delete-vehicle">
                                        <i class="fas fa-trash me-1"></i> Hapus Kendaraan Ini
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-save-vehicle px-5">
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                        <i class="fas fa-save me-1"></i> Simpan Kendaraan Ini
                                    </button>
                                </div>
                            </form>
                        </div>
                        {{-- END TEMPLATE --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Data Holder (Hidden) untuk dikirim ke JavaScript --}}
    <div id="appConfig" 
         data-pengajuan-id="{{ $pengajuan->id }}"
         data-ajax-store-url="{{ route('pengajuan.kendaraan.ajax_store', $pengajuan->id) }}"
         data-saved-vehicles='@json($pengajuan->kendaraans ?? [])'
         class="d-none">
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    @push('scripts')
    <script>
        // --- KONFIGURASI (Ambil dari data attribute) ---
        const appConfigElement = document.getElementById('appConfig');
        const CONFIG = {
            pengajuanId: appConfigElement.dataset.pengajuanId,
            urlAjaxStore: appConfigElement.dataset.ajaxStoreUrl,
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            savedVehicles: JSON.parse(appConfigElement.dataset.savedVehicles || '[]')
        };
        
        let vehicleCount = 0; 
        
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Load Kendaraan yang sudah ada atau Buat Baru
            if (CONFIG.savedVehicles && CONFIG.savedVehicles.length > 0) {
                CONFIG.savedVehicles.forEach((vehicle) => {
                    addVehicleTab(vehicle); 
                });
            } else {
                addVehicleTab(); 
            }

            // 2. Listener Tombol Tambah Tab
            document.getElementById('btnAddTab').addEventListener('click', function() {
                addVehicleTab();
            });

            // 3. Konfirmasi sebelum Submit Final
            document.getElementById('formSubmitBundel').addEventListener('submit', function(e) {
                if (!confirm('Yakin ingin mengirim semua pengajuan? Data tidak dapat diubah setelah dikirim.')) {
                    e.preventDefault();
                }
            });
        });

        // --- FUNGSI UTAMA ---
        function addVehicleTab(data = null) {
            vehicleCount++;
            const tabId = `vehicle-${vehicleCount}`;
            const tabLabel = data ? `Kendaraan ${vehicleCount} (${data.nrkb})` : `Kendaraan ${vehicleCount} (Baru)`;
            
            // 1. Buat Navigasi Tab
            const tabNavHtml = `
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${vehicleCount === 1 ? 'active' : ''}" id="${tabId}-tab" data-bs-toggle="tab" data-bs-target="#${tabId}" type="button" role="tab">
                        ${tabLabel}
                    </button>
                </li>
            `;
            const btnAddLi = document.getElementById('btnAddTab').parentNode;
            btnAddLi.insertAdjacentHTML('beforebegin', tabNavHtml);

            // 2. Buat Konten Form
            const template = document.getElementById('templateForm');
            const newFormContainer = document.createElement('div');
            newFormContainer.className = `tab-pane fade ${vehicleCount === 1 ? 'show active' : ''}`;
            newFormContainer.id = tabId;
            newFormContainer.setAttribute('role', 'tabpanel');
            newFormContainer.innerHTML = template.innerHTML;
            document.getElementById('kendaraanTabsContent').appendChild(newFormContainer);

            // 3. Inisialisasi Form
            const form = newFormContainer.querySelector('form');
            form.setAttribute('id', `form-${tabId}`); 
            form.setAttribute('data-tab-id', tabId);

            if (data) {
                fillForm(form, data);
                const btnDelete = form.querySelector('.btn-delete-vehicle');
                btnDelete.setAttribute('data-id', data.id);
                
                if (data.media && data.media.length > 0) {
                    renderExistingFiles(form, data.media);
                }
            } else {
                attachLocalStorageListener(form, tabId);
            }

            attachFormListeners(form, tabId);
        }

        function attachFormListeners(form, tabId) {
            // Submit Handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitVehicleForm(this, tabId);
            });

            // Delete Handler
            const btnDelete = form.querySelector('.btn-delete-vehicle');
            btnDelete.addEventListener('click', function() {
                deleteVehicle(form, tabId);
            });

            // Add File Handler
            const addFileBtns = form.querySelectorAll('.btn-add-file');
            addFileBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    addFileInputField(this);
                });
            });

            // File Validation
            const firstFileInputs = form.querySelectorAll('input[type="file"]');
            firstFileInputs.forEach(input => {
                attachFileValidation(input);
            });
        }

        function submitVehicleForm(form, tabId) {
            const btnSave = form.querySelector('.btn-save-vehicle');
            const spinner = btnSave.querySelector('.spinner-border');
            const alertBox = document.getElementById('ajaxAlert');
            
            btnSave.disabled = true;
            spinner.classList.remove('d-none');
            alertBox.classList.add('d-none');

            const formData = new FormData(form);

            fetch(CONFIG.urlAjaxStore, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CONFIG.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(({status, body}) => {
                if (status === 200) {
                    // SUKSES
                    showAlert('success', body.message);
                    
                    form.querySelector('.input-kendaraan-id').value = body.kendaraan_id;
                    
                    const btnDelete = form.querySelector('.btn-delete-vehicle');
                    btnDelete.setAttribute('data-id', body.kendaraan_id);

                    const tabButton = document.getElementById(`${tabId}-tab`);
                    const currentLabel = tabButton.innerText.split('(')[0].trim();
                    tabButton.innerText = `${currentLabel} (${body.nrkb})`;

                    clearLocalStorage(tabId);
                } else if (status === 422) {
                    // ERROR VALIDASI
                    showAlert('danger', 'Terdapat kesalahan input. Mohon periksa kembali form.');
                    displayValidationErrors(form, body.errors);
                } else {
                    // ERROR LAIN
                    showAlert('danger', body.message || 'Terjadi kesalahan server.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan jaringan.');
            })
            .finally(() => {
                btnSave.disabled = false;
                spinner.classList.add('d-none');
            });
        }

        function deleteVehicle(form, tabId) {
            const dbId = form.querySelector('.btn-delete-vehicle').getAttribute('data-id');
            
            if (!confirm("Yakin ingin menghapus tab kendaraan ini?")) return;

            if (dbId) {
                const urlDelete = `{{ url('pengajuan-saya/kendaraan/ajax-destroy') }}/${dbId}`;
                fetch(urlDelete, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        removeTabUI(tabId);
                        showAlert('success', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Gagal menghapus kendaraan.');
                });
            } else {
                removeTabUI(tabId);
                clearLocalStorage(tabId);
                showAlert('info', 'Tab kendaraan dihapus (belum tersimpan).');
            }
        }

        function removeTabUI(tabId) {
            const tabNav = document.getElementById(`${tabId}-tab`);
            const tabContent = document.getElementById(tabId);
            
            if (tabNav) tabNav.parentNode.remove();
            if (tabContent) tabContent.remove();
            
            const firstTab = document.querySelector('.nav-link');
            if (firstTab) new bootstrap.Tab(firstTab).show();
        }

        function addFileInputField(button) {
            const container = button.closest('.file-input-container');
            const fieldName = container.getAttribute('data-field');
            
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="file" class="form-control" name="${fieldName}[]">
                <button type="button" class="btn btn-outline-danger btn-remove-file" onclick="this.parentElement.remove()">x</button>
            `;
            
            container.insertBefore(div, button.parentNode);
            attachFileValidation(div.querySelector('input'));
        }

        function attachFileValidation(input) {
            input.addEventListener('change', function() {
                if (this.files[0] && this.files[0].size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 10MB.');
                    this.value = '';
                }
            });
        }

        function showAlert(type, message) {
            const alertBox = document.getElementById('ajaxAlert');
            alertBox.className = `alert alert-${type} alert-dismissible fade show`;
            alertBox.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertBox.classList.remove('d-none');
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function displayValidationErrors(form, errors) {
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            for (const [field, messages] of Object.entries(errors)) {
                let inputName = field;
                if (field.includes('.')) {
                    const parts = field.split('.');
                    inputName = `${parts[0]}[]`; 
                }
                
                const input = form.querySelector(`[name="${inputName}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.innerText = messages[0];
                    input.parentNode.appendChild(feedback);
                }
            }
        }

        function fillForm(form, data) {
            form.querySelector('.input-kendaraan-id').value = data.id;
            const setVal = (name, val) => {
                const input = form.querySelector(`[name="${name}"]`);
                if (input) input.value = val || '';
            };

            // Isi data pemilik
            if (data.pemilik) {
                setVal('nik_pemilik', data.pemilik.nik_pemilik);
                setVal('nama_pemilik', data.pemilik.nama_pemilik);
                setVal('alamat_pemilik', data.pemilik.alamat_pemilik);
                setVal('telp_pemilik', data.pemilik.telp_pemilik);
                setVal('email_pemilik', data.pemilik.email_pemilik);
            }
            
            // Isi data kendaraan
            setVal('nrkb', data.nrkb);
            setVal('jenis_kendaraan', data.jenis_kendaraan);
            setVal('merk_kendaraan', data.merk_kendaraan);
            setVal('tipe_kendaraan', data.tipe_kendaraan);
            setVal('model_kendaraan', data.model_kendaraan);
            setVal('tahun_pembuatan', data.tahun_pembuatan);
            setVal('isi_silinder', data.isi_silinder);
            setVal('jenis_bahan_bakar', data.jenis_bahan_bakar);
            setVal('nomor_rangka', data.nomor_rangka);
            setVal('nomor_mesin', data.nomor_mesin);
            setVal('warna_tnkb', data.warna_tnkb);
            setVal('nomor_bpkb', data.nomor_bpkb);
        }
        
        function renderExistingFiles(form, mediaItems) {
            mediaItems.forEach(media => {
                const container = form.querySelector(`.file-input-container[data-field="${media.collection_name}"]`);
                if (container) {
                    // Hapus atribut required dari input file pertama
                    const firstFileInput = container.querySelector('input[type="file"]');
                    if (firstFileInput) {
                        firstFileInput.removeAttribute('required');
                    }

                    const fileHtml = `
                        <div class="mb-2 small text-success">
                            <i class="fas fa-check-circle me-1"></i> Terupload: 
                            <a href="${media.original_url}" target="_blank" class="text-decoration-none fw-bold">${media.file_name}</a>
                        </div>
                    `;
                    container.insertAdjacentHTML('afterbegin', fileHtml);
                }
            });
        }

        // --- LOCAL STORAGE ---
        function attachLocalStorageListener(form, tabId) {
            form.addEventListener('input', function(e) {
                if (e.target.type !== 'file' && e.target.type !== 'submit') {
                    const data = { name: e.target.name, value: e.target.value };
                    saveToLS(tabId, data);
                }
            });
            loadFromLS(form, tabId);
        }

        function saveToLS(tabId, data) {
            const key = `draft_${CONFIG.pengajuanId}_${tabId}`;
            let currentData = JSON.parse(localStorage.getItem(key)) || {};
            currentData[data.name] = data.value;
            localStorage.setItem(key, JSON.stringify(currentData));
        }

        function loadFromLS(form, tabId) {
            const key = `draft_${CONFIG.pengajuanId}_${tabId}`;
            const savedData = JSON.parse(localStorage.getItem(key));
            if (savedData) {
                for (const [name, val] of Object.entries(savedData)) {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input) input.value = val;
                }
            }
        }

        function clearLocalStorage(tabId) {
            const key = `draft_${CONFIG.pengajuanId}_${tabId}`;
            localStorage.removeItem(key);
        }

    </script>
    @endpush

    <style>
        .file-input-group { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>