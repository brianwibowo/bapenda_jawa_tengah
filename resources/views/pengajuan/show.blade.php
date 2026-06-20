<x-app-layout>
    @php
        $totalSurat = 9;
        $progressValue = max(0, min((int) ($progress ?? 0), $totalSurat));
        $progressPercent = (int) round(($progressValue / $totalSurat) * 100);

        // Hitung total dokumen resmi
        $docsCount = 0;
        if (!empty($pengajuan->suratKeputusan)) {
            foreach ($pengajuan->suratKeputusan as $sk) {
                if (!empty($sk->pdf_url)) {
                    $docsCount++;
                }
            }
        }
        if (!empty($pengajuan->suratPengajuan)) {
            foreach ($pengajuan->suratPengajuan as $sp) {
                if (!empty($sp->pdf_url)) {
                    $docsCount++;
                }
                if (!empty($sp->persetujuan_unit_kerja) && is_array($sp->persetujuan_unit_kerja)) {
                    foreach ($sp->persetujuan_unit_kerja as $item) {
                        if (!empty($item['pdf_url'])) {
                            $docsCount++;
                        }
                    }
                }
            }
        }
    @endphp
    <x-slot name="header">
        <div class="card border-0 shadow-sm mb-3 top-summary-card">
            <div class="card-body py-3 px-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <div class="small text-muted mb-1">Nomor Pengajuan</div>
                        <div class="h3 mb-0 fw-semibold text-dark">{{ $pengajuan->nomor_pengajuan }}</div>
                    </div>
                    <div>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="progress-label mt-3">Progres ({{ $progressPercent }}%)</div>
                <div class="progress slim-progress">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                </div>
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

    <!-- === TABS: Log & Diskusi | Pilih Kendaraan | Dokumen === -->
    <div class="col-12 mt-0">
        <div class="shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 p-3 pb-0" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <ul class="nav nav-tabs nav-tabs-custom mb-0 mt-0" id="pengajuanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-log" data-bs-toggle="tab" data-bs-target="#panel-log" type="button" role="tab" aria-controls="panel-log" aria-selected="true">
                            <i class="fas fa-comments me-2"></i>Log & Diskusi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-detail" data-bs-toggle="tab" data-bs-target="#panel-detail" type="button" role="tab" aria-controls="panel-detail" aria-selected="false">
                            <i class="fas fa-car me-2"></i>Pilih Kendaraan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-dokumen" data-bs-toggle="tab" data-bs-target="#panel-dokumen" type="button" role="tab" aria-controls="panel-dokumen" aria-selected="false">
                            <i class="fas fa-file-pdf me-2"></i>Dokumen <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $docsCount }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4 pt-2 bg-white" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <div class="tab-content" id="pengajuanTabContent">
            {{-- Tab 1: Log & Diskusi --}}
            <div class="tab-pane fade show active" id="panel-log" role="tabpanel" aria-labelledby="tab-log">
                <div class="pt-0">
                    @includeIf('pengajuan.partials.logs')
                </div>
            </div>

            {{-- Tab 2: Pilih Kendaraan --}}
            <div class="tab-pane fade" id="panel-detail" role="tabpanel" aria-labelledby="tab-detail">
                <div class="pt-0">
                    {{-- Tab Kendaraan --}}
                    @if($pengajuan->kendaraans->count() > 0)
                    <div class="card mb-4 border-0 shadow-sm vehicle-switcher-card">
                        <div class="card-body p-4">
                            <h6 class="text-muted mb-3">Pilih Kendaraan</h6>
                            <div class="d-flex flex-wrap gap-2 vehicle-chip-group" id="kendaraanTabs">
                                @foreach ($pengajuan->kendaraans as $index => $kendaraan)
                                    <button type="button" 
                                            class="btn btn-kendaraan-tab {{ $index === 0 ? 'active' : '' }}" 
                                            data-index="{{ $index + 1 }}">
                                        <i class="fas fa-car me-2"></i>
                                        <span class="d-flex flex-column align-items-start lh-sm">
                                            <span class="fw-semibold">Kendaraan {{ $index + 1 }}</span>
                                            <small class="text-muted">{{ $kendaraan->nrkb }}</small>
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

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
                                                        <td class="text-muted">Merek</td>
                                                        <td class="fw-semibold">{{ $kendaraan->merk_kendaraan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Tipe</td>
                                                        <td class="fw-semibold">{{ $kendaraan->tipe_kendaraan }}</td>
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
                                                        <td class="text-muted">Tahun Pembuatan</td>
                                                        <td class="fw-semibold">{{ $kendaraan->tahun_pembuatan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Isi Silinder / Daya Listrik</td>
                                                        <td class="fw-semibold">{{ $kendaraan->isi_silinder }}</td>
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
                                                        <td class="text-muted">Warna Kendaraan Bermotor</td>
                                                        <td class="fw-semibold">{{ $kendaraan->warna_kendaraan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Bahan Bakar / Sumber Energi</td>
                                                        <td class="fw-semibold">{{ $kendaraan->jenis_bahan_bakar }}</td>
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
                                                            @if($kendaraan->status == 'draft')
                                                                <span class="badge bg-secondary">Draft</span>
                                                            @elseif($kendaraan->status == 'pengajuan')
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
                                                                    @php
                                                                        // Build a safe URL: if host is localhost or 127.0.0.1, force port 8000
                                                                        $originalUrl = $file->getUrl();
                                                                        $fileUrl = $originalUrl;
                                                                        $parts = @parse_url($originalUrl);
                                                                        if ($parts && isset($parts['host']) && in_array($parts['host'], ['localhost', '127.0.0.1'])) {
                                                                            $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
                                                                            $host = $parts['host'];
                                                                            $port = 8000;
                                                                            $path = isset($parts['path']) ? $parts['path'] : '';
                                                                            $query = isset($parts['query']) ? ('?'.$parts['query']) : '';
                                                                            $fragment = isset($parts['fragment']) ? ('#'.$parts['fragment']) : '';
                                                                            $fileUrl = $scheme.'://'.$host.':'.$port.$path.$query.$fragment;
                                                                        }
                                                                    @endphp
                                                                    <div class="mb-2">
                                                                        <a href="{{ $fileUrl }}"  
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
                </div>
            </div>
            
            {{-- Tab 3: Dokumen --}}
            <div class="tab-pane fade" id="panel-dokumen" role="tabpanel" aria-labelledby="tab-dokumen">
                <div class="pt-0">
                    @includeIf('pengajuan.partials.dokumen')
                </div>
            </div>
        </div>
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
        .top-summary-card {
            border-radius: 12px;
        }

        .progress-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .slim-progress {
            height: 8px;
            border-radius: 999px;
            background-color: #d4d8de;
            overflow: hidden;
        }

        .slim-progress .progress-bar {
            border-radius: 999px;
        }

        /* Custom Tabs */
        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0 !important;
            gap: 1.5rem;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
        }

        .nav-tabs-custom::-webkit-scrollbar {
            display: none; /* Safari/Chrome */
        }

        .nav-tabs-custom .nav-link {
            border: none !important;
            color: #64748b;
            font-weight: 600;
            padding: 0.75rem 0.5rem;
            position: relative;
            background: transparent !important;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0f172a !important;
            border: none !important;
        }

        .nav-tabs-custom .nav-link.active {
            color: #2f86df !important;
            background: transparent !important;
            border: none !important;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2f86df 0%, #4b9cf0 100%);
            border-radius: 999px;
        }

        /* Tab Buttons (Vehicle Selector Chips) */
        .vehicle-switcher-card {
            border-radius: 16px;
        }

        .vehicle-chip-group {
            align-items: stretch;
        }

        .btn-kendaraan-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 64px;
            padding: 0.95rem 1.2rem;
            border-radius: 16px;
            border: 1px solid #d8e5f3;
            background: linear-gradient(180deg, #f9fcff 0%, #eef6ff 100%);
            color: #334155;
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.1;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }

        .btn-kendaraan-tab i {
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .btn-kendaraan-tab:hover {
            border-color: #8ab8ea;
            color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(47, 134, 223, 0.12);
        }

        .btn-kendaraan-tab.active {
            background: linear-gradient(135deg, #2f86df 0%, #4b9cf0 100%);
            color: #ffffff;
            border-color: #2f86df;
            box-shadow: 0 10px 20px rgba(47, 134, 223, 0.24);
        }

        .btn-kendaraan-tab.active small {
            color: rgba(255, 255, 255, 0.82) !important;
        }

        .btn-kendaraan-tab small {
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* Mobile & Tablet Responsiveness */
        @media (max-width: 768px) {
            .nav-tabs-custom {
                gap: 1rem;
            }
            
            .nav-tabs-custom .nav-link {
                font-size: 0.9rem;
                padding: 0.6rem 0.25rem;
            }
            
            .btn-kendaraan-tab {
                min-height: 52px;
                padding: 0.65rem 0.95rem;
                border-radius: 12px;
                font-size: 0.9rem;
            }
            
            .btn-kendaraan-tab i {
                font-size: 1rem;
            }
            
            .btn-kendaraan-tab small {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .nav-tabs-custom {
                gap: 0.75rem;
            }
            
            .btn-kendaraan-tab {
                width: 100%;
                justify-content: flex-start;
            }
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