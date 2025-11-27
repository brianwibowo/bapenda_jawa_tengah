<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="fw-bold mb-1">Detail Kendaraan</h2>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-dark fs-6 px-3 py-2">{{ $kendaraan->nrkb }}</span>
                    <span class="text-muted">dari Bundel: {{ $kendaraan->pengajuan->nomor_pengajuan }}</span>
                </div>
            </div>
            
            {{-- Tombol Kembali (Dinamis) --}}
            @auth
                @if(Auth::user()->hasRole('admin|superadmin'))
                    <a href="{{ route('admin.pengajuan.show', $kendaraan->pengajuan) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                @else
                    <a href="{{ route('pengajuan.show', $kendaraan->pengajuan) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="row">
        <!-- === KOLOM KIRI: DATA IDENTITAS === -->
        <div class="col-lg-8 mb-4">
            <!-- Identitas Pemilik -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Identitas Pemilik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless detail-table mb-0">
                        <tr>
                            <td class="text-muted" width="35%">Atas Nama</td>
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

            <!-- Identitas Kendaraan -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Identitas Kendaraan Bermotor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless detail-table mb-0">
                        <tr>
                            <td class="text-muted" width="35%">NRKB</td>
                            <td><span class="badge bg-dark px-3 py-2">{{ $kendaraan->nrkb }}</span></td>
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
                    </table>
                </div>
            </div>
        </div>

        <!-- === KOLOM KANAN: DOKUMEN & AKSI === -->
        <div class="col-lg-4">
            <!-- Status Kendaraan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Status Kendaraan</h6>
                    <div>
                        @if($kendaraan->status == 'pengajuan')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                <i class="fas fa-file-alt me-1"></i> Diajukan
                            </span>
                        @elseif($kendaraan->status == 'diproses')
                            <span class="badge bg-info text-dark fs-6 px-3 py-2">
                                <i class="fas fa-spinner me-1"></i> Diproses
                            </span>
                        @elseif($kendaraan->status == 'selesai')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </span>
                        @elseif($kendaraan->status == 'ditolak')
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="fas fa-times-circle me-1"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dokumen Terlampir -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Dokumen Terlampir</h5>
                </div>
                <div class="card-body">
                    @php
                        $labels = [
                            'surat_permohonan' => ['label' => 'Surat Permohonan', 'icon' => 'fa-file-alt'],
                            'surat_pernyataan' => ['label' => 'Surat Pernyataan', 'icon' => 'fa-file-signature'],
                            'ktp' => ['label' => 'KTP Pemilik', 'icon' => 'fa-id-card'],
                            'bpkb' => ['label' => 'BPKB', 'icon' => 'fa-book'],
                            'tbpkp' => ['label' => 'TBPKP', 'icon' => 'fa-file-invoice'],
                            'cek_fisik' => ['label' => 'Cek Fisik', 'icon' => 'fa-clipboard-check'],
                            'foto_ranmor' => ['label' => 'Foto Kendaraan', 'icon' => 'fa-camera'],
                            'stnk' => ['label' => 'STNK', 'icon' => 'fa-id-card-alt'],
                        ];
                    @endphp

                    @foreach ($labels as $collectionName => $data)
                        <div class="dokumen-section mb-3 pb-3 border-bottom">
                            <h6 class="fw-semibold mb-2">
                                <i class="fas {{ $data['icon'] }} me-2 text-primary"></i>
                                {{ $data['label'] }}
                            </h6>
                            @forelse ($kendaraan->getMedia($collectionName) as $doc)
                                <div class="dokumen-file d-flex justify-content-between align-items-center p-2 rounded bg-light mb-2">
                                    <a href="{{ $doc->getUrl() }}" 
                                       target="_blank" 
                                       class="text-decoration-none text-truncate me-2 flex-grow-1">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        <span class="small">{{ Str::limit($doc->file_name, 20) }}</span>
                                    </a>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-secondary small">{{ $doc->human_readable_size }}</span>
                                        <a href="{{ $doc->getUrl() }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-times-circle me-1"></i>Belum ada file
                                </p>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Edit (Hanya tampil untuk Penulis dan jika status masih 'pengajuan') --}}
            @if(Auth::id() === $kendaraan->pengajuan->user_id && $kendaraan->status == 'pengajuan')
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Status masih <strong>Pengajuan</strong>, Anda dapat mengedit data kendaraan ini.</small>
                        </div>
                        <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="btn btn-warning w-100">
                            <i class="fas fa-edit me-2"></i>Edit Kendaraan Ini
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
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

        /* Dokumen Section */
        .dokumen-section:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        .dokumen-file {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
        }

        .dokumen-file:hover {
            background-color: #ffffff !important;
            border-color: #0d6efd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Badges */
        .badge {
            font-weight: 500;
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

        .btn-sm {
            padding: 0.35rem 0.7rem;
        }

        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
        }

        /* Text utilities */
        .text-truncate {
            max-width: 150px;
        }
    </style>
</x-app-layout>