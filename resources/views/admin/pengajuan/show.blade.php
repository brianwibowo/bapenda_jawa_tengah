<x-app-layout>
    @php
        $canUpdateBatch = auth()->user()->can('approve_status_pengajuan');
        $totalSurat = 9;
        $progressValue = max(0, min((int) ($progress ?? 0), $totalSurat));
        $progressPercent = (int) round(($progressValue / $totalSurat) * 100);
        $unitkerja = Auth::user()->unit_kerja;

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
            <div class="small text-muted mb-1">Nomor Pengajuan</div>
            <div class="h3 mb-3 fw-semibold text-dark">{{ $pengajuan->nomor_pengajuan }}</div>
            <div class="progress-label">Progres ({{ $progressValue }} / {{ $totalSurat }})</div>
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
        @can('view_daftar_kendaraan')
        <div class="col-12">
            
            <div class="card border-0 shadow-sm panel-card mb-3">
                @if($canUpdateBatch && $unitkerja != 'Samsat')
                {{-- Form 'batchUpdate' SEKARANG DIMULAI DI SINI --}}
                <form action="{{ route('admin.pengajuan.batchUpdate', $pengajuan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="card-header panel-header">
                        <h4 class="card-title mb-0">
                            Daftar Kendaraan (Total: {{ $pengajuan->kendaraans->count() }})
                        </h4>
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
                                        <th style="width: 25%;">Ubah Status Pengajuan</th>
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
 
                                             {{-- Kolom Aksi (Submit Status) --}}
                                             <td class="text-center">
                                                 <button type="submit" class="btn btn-sm btn-primary">
                                                     <i class="fas fa-save me-1"></i>Simpan Status
                                                 </button>
                                             </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
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
        @endcan

        <!-- === TABS: Log & Diskusi | Detail Kendaraan | Dokumen === -->
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
                                <i class="fas fa-car me-2"></i>Detail Kendaraan
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
                        @includeWhen(true, 'pengajuan.partials.logs', ['admin' => true])
                    </div>
                </div>

                {{-- Tab 2: Detail Kendaraan --}}
                <div class="tab-pane fade" id="panel-detail" role="tabpanel" aria-labelledby="tab-detail">
                    <div class="pt-0">
                        {{-- Pilih Kendaraan --}}
                        <div class="card mb-4 border-0 shadow-sm vehicle-switcher-card">
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

                        {{-- Detail Kendaraan --}}
                        <div id="kendaraanDetails">
                            @foreach ($pengajuan->kendaraans as $index => $kendaraan)
                                @php
                                    $kendaraan->load(['pemilik', 'media']);
                                @endphp
                                <div class="kendaraan-detail" data-kendaraan-id="{{ $kendaraan->id }}">
                                    @include('admin.pengajuan._kendaraan_detail', ['kendaraan' => $kendaraan])
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
    </div>
</div>

    <script>
        function applyKendaraanFilter(kendaraanId) {
            const normalizedId = kendaraanId ? String(kendaraanId) : '';

            document.querySelectorAll('.kendaraan-detail').forEach(function (detail) {
                const detailId = detail.getAttribute('data-kendaraan-id');
                detail.style.display = !normalizedId || detailId === normalizedId ? 'block' : 'none';
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
    </style>

</x-app-layout>