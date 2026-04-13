<x-app-layout>
    @php
        $canUpdateBatch = auth()->user()->can('approve_status_pengajuan');
        $totalSurat = 9;
        $progressValue = max(0, min((int) ($progress ?? 0), $totalSurat));
        $progressPercent = (int) round(($progressValue / $totalSurat) * 100);
    @endphp
    <x-slot name="header">
       <div class="card border-0 shadow-sm mb-3 top-summary-card">
        <div class="card-body py-3 px-4">
            <div class="small text-muted mb-1">Nomor Pengajuan</div>
            <div class="h3 mb-3 fw-semibold text-dark">{{ $pengajuan->nomor_pengajuan }}</div>
            <div class="progress-label">Progres</div>
            <div class="progress slim-progress">
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
            </div>
        </div>
    </div>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
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

    <div class="row">
        <!-- === KOLOM UTAMA: TABEL INTERAKTIF KENDARAAN === -->
        <div class="col-12">
            
            <div class="card border-0 shadow-sm panel-card">
                @if($canUpdateBatch)
                {{-- Form 'batchUpdate' SEKARANG DIMULAI DI SINI --}}
                <form action="{{ route('admin.pengajuan.batchUpdate', $pengajuan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="card-header panel-header d-flex flex-wrap justify-content-between align-items-center">
                        <h4 class="card-title mb-2 mb-md-0">
                            Daftar Kendaraan (Total: {{ $pengajuan->kendaraans->count() }})
                        </h4>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Status
                        </button>
                    </div>
                    <div class="card-body panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle table-dashboard">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">NRKB</th>
                                        <th style="width: 15%;">Merk / Tipe</th>
                                        <th style="width: 15%;">Pemilik</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 15%;">Ubah Status</th>
                                        <th style="width: 20%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuan->kendaraans as $kendaraan)
                                        <tr>
                                            {{-- Info Kendaraan --}}
                                            <td><strong>{{ $kendaraan->nrkb }}</strong></td>
                                            <td>{{ $kendaraan->merk_kendaraan }} / {{ $kendaraan->tipe_kendaraan }}</td>
                                            <td>{{ $kendaraan->pemilik->nama_pemilik ?? '-' }}</td>
                                            
                                            {{-- Status Saat Ini --}}
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

                                            {{-- Kolom Aksi (Form Input) --}}
                                            <td>
                                                <select name="status[{{ $kendaraan->id }}]" class="form-select form-select-sm">
                                                    <option value="pengajuan" {{ $kendaraan->status == 'pengajuan' ? 'selected' : '' }}>Diajukan</option>
                                                    <option value="diproses" {{ $kendaraan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="selesai" {{ $kendaraan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="ditolak" {{ $kendaraan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </td>

                                            {{-- Kolom Aksi (View) --}}
                                            <td class="text-center">
                                                <a href="{{ route('kendaraan.show', $kendaraan) }}" class="btn btn-sm btn-info" title="Lihat Detail & Dokumen" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-3"> {{-- Colspan jadi 8 --}}
                                                Bundel ini belum memiliki kendaraan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                {{-- Form 'batchUpdate' SEKARANG BERAKHIR DI SINI --}}
                </form>
                @endif
            </div>
        </div>

        <!-- Log Histori Gabungan (Opsional) -->
        <div class="col-12 mt-4">
            @includeWhen(true, 'pengajuan.partials.logs', ['admin' => true])

            <div class="card mb-4 border-0 shadow-sm vehicle-switcher-card mt-3">
                <div class="card-body p-4">
                    <h6 class="text-muted mb-3">Pilih Kendaraan</h6>
                    <div class="d-flex flex-wrap gap-2 vehicle-chip-group" id="vehicleFilterGroup">
                        <button type="button" class="btn btn-kendaraan-tab active" data-kendaraan-id="">
                            <i class="fas fa-car-side me-2"></i>
                            <span>Semua Kendaraan</span>
                        </button>
                        @foreach ($pengajuan->kendaraans as $index => $kend)
                            <button type="button" class="btn btn-kendaraan-tab" data-kendaraan-id="{{ $kend->id }}">
                                <i class="fas fa-car me-2"></i>
                                <span class="d-flex flex-column align-items-start lh-sm">
                                    <span class="fw-semibold">Kendaraan {{ $index + 1 }}</span>
                                    <small class="text-muted">{{ $kend->nrkb }}</small>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Kendaraan --}}
    <div id="kendaraanDetails">
        @foreach ($pengajuan->kendaraans as $index => $kendaraan)
            @php
                $kendaraan->load(['pemilik', 'media']);
            @endphp
            <div class="kendaraan-detail" data-kendaraan-id="{{ $kendaraan->id }}">
                
                {{-- Identitas Pemilik & Kendaraan --}}
                <div class="row mb-4">
                    <!-- Identitas Pemilik -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm info-card">
                            <div class="card-header info-card-header text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Identitas Pemilik</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table mb-0">
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
                        <div class="card h-100 border-0 shadow-sm info-card">
                            <div class="card-header info-card-header text-white">
                                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Identitas Kendaraan</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table mb-0">
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
                <div class="card border-0 shadow-sm mb-4 attachment-section">
                    <div class="card-header info-card-header text-white">
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
                                    <div class="dokumen-item attachment-card p-3 rounded">
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
                                                        <!-- <a href="{{ $file->getUrl() }}"  -->
                                                                          <a href="{{ $fileUrl }}"  
                                                           target="_blank" 
                                                                              class="btn btn-sm btn-outline-primary d-inline-flex align-items-center attachment-link">
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
        function applyKendaraanFilter(kendaraanId) {
            const normalizedId = kendaraanId ? String(kendaraanId) : '';

            document.querySelectorAll('.kendaraan-detail').forEach(function (detail) {
                const detailId = detail.getAttribute('data-kendaraan-id');
                detail.style.display = !normalizedId || detailId === normalizedId ? 'block' : 'none';
            });

            document.querySelectorAll('.log-panel tbody tr[data-kendaraan-id]').forEach(function (row) {
                const rowId = row.getAttribute('data-kendaraan-id');
                row.style.display = !normalizedId || rowId === normalizedId ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const chipButtons = document.querySelectorAll('#vehicleFilterGroup .btn-kendaraan-tab');
            chipButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    chipButtons.forEach(function (chip) {
                        chip.classList.remove('active');
                    });

                    btn.classList.add('active');
                    applyKendaraanFilter(btn.getAttribute('data-kendaraan-id'));
                });
            });
        });
    </script>

    <!-- === MODAL HAPUS KENDARAAN (DI LUAR FORM UTAMA) === -->
    @foreach ($pengajuan->kendaraans as $kendaraan)
        <div class="modal fade" id="deleteModal-{{ $kendaraan->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $kendaraan->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $kendaraan->id }}">Hapus Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus kendaraan <strong>{{ $kendaraan->nrkb }}</strong>? Aksi ini akan dicatat dan tidak bisa dibatalkan.
                    </div>
                    <div class="modal-footer">
                        {{-- Ini adalah form hapus yang aman (tidak nested) --}}
                        <form action="{{ route('kendaraan.destroy', $kendaraan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus Kendaraan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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

        .panel-card,
        .info-card,
        .attachment-section {
            border-radius: 12px;
        }

        .panel-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .panel-body {
            background: #ffffff;
        }

        .table-dashboard thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #64748b;
            background: #f8fafc;
        }

        .table-dashboard tbody td {
            vertical-align: middle;
        }

        .info-card-header {
            background: linear-gradient(90deg, #2f86df 0%, #3b94ee 100%);
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .detail-table tr td {
            padding-top: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eef2f7;
            font-size: 13px;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }

        .attachment-card {
            background: #f8fafc;
            border: 1px solid #e6edf5;
            min-height: 110px;
        }

        .attachment-link {
            max-width: 100%;
            white-space: nowrap;
        }

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
    </style>

</x-app-layout>