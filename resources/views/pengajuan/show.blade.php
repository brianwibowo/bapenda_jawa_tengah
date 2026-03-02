<x-app-layout>
    <x-slot name="header">
        <div class="d-grid" style="grid-template-columns: 40% 20% 40%; gap: 0; align-items: center;">
            <div>
                <h2 class="fw-bold mb-1">Detail Pengajuan</h2>
                <p class="text-muted mb-0">{{ $pengajuan->nomor_pengajuan }}</p>
            </div>
            <div></div>
            <div class="d-grid" style="grid-template-columns: 50% 15% 35%; gap: 0; align-items: center;">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <div></div>
                <a href="{{ route('pdf.view', $pengajuan->id) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tab Kendaraan --}}
    @if($pengajuan->kendaraans->count() > 0)
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h6 class="text-muted mb-3">Pilih Kendaraan</h6>
            <div class="d-flex flex-wrap gap-2" id="kendaraanTabs">
                @foreach ($pengajuan->kendaraans as $index => $kendaraan)
                    <button type="button" 
                            class="btn btn-kendaraan-tab {{ $index === 0 ? 'active' : '' }}" 
                            data-index="{{ $index + 1 }}">
                        <i class="fas fa-car me-2"></i>Kendaraan {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Include shared logs partial for penulis/admin interaction --}}
    @includeIf('pengajuan.partials.logs')
    
    {{-- Detail Kendaraan --}}
    <div id="kendaraanDetails">
        @foreach ($pengajuan->kendaraans as $index => $kendaraan)
            @php
                $kendaraan->load(['pemilik', 'media']);
            @endphp
            <div class="kendaraan-detail" data-kendaraan-index="{{ $index + 1 }}" @if($index !== 0) style="display: none;" @endif>
                
                {{-- Identitas Pemilik & Kendaraan --}}
                <div class="row mb-4">
                    <!-- Identitas Pemilik -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Identitas Pemilik</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table">
                                    <tr>
                                        <td class="text-muted" width="45%">Atas Nama</td>
                                        <td class="fw-semibold">{{ $kendaraan->pemilik->nama_pemilik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">NIK/TDP/NIB/Kitas/Kitab</td>
                                        <td class="fw-semibold">{{ $kendaraan->pemilik->nik_pemilik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Alamat</td>
                                        <td class="fw-semibold">{{ $kendaraan->pemilik->alamat_pemilik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. Telepon/HP</td>
                                        <td class="fw-semibold">{{ $kendaraan->pemilik->telp_pemilik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td class="fw-semibold">{{ $kendaraan->pemilik->email_pemilik ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Identitas Kendaraan -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Identitas Kendaraan</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table">
                                    <tr>
                                        <td class="text-muted" width="45%">NRKB</td>
                                        <td><span class="badge bg-dark fs-6 px-3">{{ $kendaraan->nrkb }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis</td>
                                        <td class="fw-semibold">{{ $kendaraan->jenis_kendaraan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Model</td>
                                        <td class="fw-semibold">{{ $kendaraan->model_kendaraan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Merk</td>
                                        <td class="fw-semibold">{{ $kendaraan->merk_kendaraan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tipe</td>
                                        <td class="fw-semibold">{{ $kendaraan->tipe_kendaraan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tahun Pembuatan</td>
                                        <td class="fw-semibold">{{ $kendaraan->tahun_pembuatan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Isi Silinder / Daya Listrik</td>
                                        <td class="fw-semibold">{{ $kendaraan->isi_silinder }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Bahan Bakar</td>
                                        <td class="fw-semibold">{{ $kendaraan->jenis_bahan_bakar }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nomor Rangka</td>
                                        <td class="fw-semibold">{{ $kendaraan->nomor_rangka }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nomor Mesin</td>
                                        <td class="fw-semibold">{{ $kendaraan->nomor_mesin }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Warna TNKB</td>
                                        <td class="fw-semibold">{{ $kendaraan->warna_tnkb }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nomor BPKB</td>
                                        <td class="fw-semibold">{{ $kendaraan->nomor_bpkb }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td>
                                            @if($kendaraan->status == 'pengajuan')
                                                <span class="badge bg-warning text-dark">Diajukan</span>
                                            @elseif($kendaraan->status == 'diproses')
                                                <span class="badge bg-info text-dark">Diproses</span>
                                            @elseif($kendaraan->status == 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($kendaraan->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Persyaratan -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Dokumen Persyaratan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @php
                                $dokumenList = [
                                    'surat_permohonan' => ['label' => 'Surat Permohonan Penghapusan', 'icon' => 'fa-file-alt'],
                                    'surat_pernyataan' => ['label' => 'Surat Pernyataan Kepemilikan', 'icon' => 'fa-file-signature'],
                                    'ktp' => ['label' => 'Tanda Bukti Identitas Pemilik (KTP)', 'icon' => 'fa-id-card'],
                                    'bpkb' => ['label' => 'BPKB', 'icon' => 'fa-book'],
                                    'tbpkp' => ['label' => 'TBPKP', 'icon' => 'fa-file-invoice'],
                                    'cek_fisik' => ['label' => 'Hasil Pemeriksaan Cek Fisik', 'icon' => 'fa-clipboard-check'],
                                    'foto_ranmor' => ['label' => 'Foto Kendaraan', 'icon' => 'fa-camera'],
                                    'stnk' => ['label' => 'STNK', 'icon' => 'fa-id-card-alt']
                                ];
                            @endphp
                            @foreach ($dokumenList as $collection => $data)
                                <div class="col-md-6">
                                    <div class="dokumen-item p-3 rounded bg-light">
                                        <label class="form-label fw-semibold mb-2">
                                            <i class="fas {{ $data['icon'] }} me-2 text-primary"></i>
                                            {{ $data['label'] }}
                                        </label>
                                        <div>
                                            @php
                                                $media = $kendaraan->getMedia($collection);
                                            @endphp
                                            @if($media->count() > 0)
                                                @foreach($media as $file)
                                                    <div class="mb-2">
                                                        <a href="{{ $file->getUrl() }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                                            <i class="fas fa-file-pdf me-2"></i>
                                                            <span class="text-truncate" style="max-width: 200px;">
                                                                {{ $file->name }}
                                                            </span>
                                                            <i class="fas fa-external-link-alt ms-2"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">
                                                    <i class="fas fa-times-circle me-1"></i>Belum diunggah
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($pengajuan->kendaraans->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada kendaraan dalam pengajuan ini.</h5>
                </div>
            </div>
        @endif
    </div>

    <script>
        function aktifkanTab(index) {
            // Hide semua detail
            document.querySelectorAll('.kendaraan-detail').forEach(detail => {
                detail.style.display = 'none';
            });
            
            // Show detail yang dipilih
            const selectedDetail = document.querySelector(`[data-kendaraan-index="${index}"]`);
            if (selectedDetail) {
                selectedDetail.style.display = 'block';
            }
            
            // Update tab styles
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-index') == index) {
                    btn.classList.add('active');
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all tab buttons
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    aktifkanTab(index);
                });
            });
        });
    </script>

    <style>
        /* Tab Buttons */
        .btn-kendaraan-tab {
            padding: 12px 24px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            background-color: #ffffff;
            color: #495057;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-kendaraan-tab:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-kendaraan-tab.active {
            background-color: #0d6efd;
            color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        /* Cards */
        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        /* Detail Table */
        .detail-table tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-table tr:last-child {
            border-bottom: none;
        }

        .detail-table td {
            padding: 0.75rem 0;
            vertical-align: top;
        }

        /* Dokumen Item */
        .dokumen-item {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .dokumen-item:hover {
            background-color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-color: #0d6efd;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Empty State */
        .fa-inbox {
            opacity: 0.3;
        }
    </style>
</x-app-layout>