
<div class="card mt-4 border-0 shadow-sm log-panel">
    <div class="card-header bg-white border-bottom">
        <h4 class="card-title mb-0">Log & Diskusi</h4>
    </div>
    <div class="card-body">
        {{-- Display SK POLDA PDFs --}}
        @php
            $skPoldaPdfs = $pengajuan->getMedia('sk_polda_pdf');
        @endphp
        @if($skPoldaPdfs->isNotEmpty())
            <div class="mb-4">
                <h5 class="mb-3">Dokumen SK POLDA</h5>
                <div class="row">
                    @foreach($skPoldaPdfs as $pdf)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    <h6 class="card-title">{{ $pdf->name }}</h6>
                                    <p class="card-text small text-muted">
                                        Dibuat: {{ $pdf->created_at->format('d M Y H:i') }}
                                    </p>
                                    @php
                                        // Build a safe URL: if host is localhost or 127.0.0.1, force port 8000
                                        $originalUrl = $pdf->getUrl();
                                        $viewUrl = $originalUrl;
                                        $downloadUrl = $originalUrl;
                                        $parts = @parse_url($originalUrl);
                                        if ($parts && isset($parts['host']) && in_array($parts['host'], ['localhost', '127.0.0.1'])) {
                                            $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
                                            $host = $parts['host'];
                                            $port = 8000;
                                            $path = isset($parts['path']) ? $parts['path'] : '';
                                            $query = isset($parts['query']) ? ('?'.$parts['query']) : '';
                                            $fragment = isset($parts['fragment']) ? ('#'.$parts['fragment']) : '';
                                            $viewUrl = $scheme.'://'.$host.':'.$port.$path.$query.$fragment;
                                            $downloadUrl = $viewUrl;
                                        }
                                    @endphp
                                    <a href="{{ $viewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat PDF
                                    </a>
                                    <a href="{{ $downloadUrl }}" download class="btn btn-sm btn-outline-secondary ms-1">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr>
        @endif

        <div class="mb-3 d-flex justify-content-end align-items-center">
            <div class="d-flex justify-content-end gap-2 w-100">
                @if(!empty($admin) && $admin)
                    @php
                    $isAdmin = !empty($admin) && $admin;
                    $hasSuratAction = $isAdmin && isset($permissionSurat);
                    $type = '';
                    $label = '';
                    if ($hasSuratAction) {
                        if ($permissionSurat['canAjukanSP']) {
                            $type = 'sp';
                            $label = 'Buat Pengajuan ke ' . (Auth::user()->unit_kerja == 'Samsat' ? 'Polda' : 'Bapenda/Jasa Raharja');
                        } elseif ($permissionSurat['canRespondSP']) {
                            $type = 'sp';
                            $label = 'Review & Balas SP';
                        } elseif ($permissionSurat['canAjukanSK']) {
                            $type = 'sk';
                            $label = 'Terbitkan Surat Keputusan';
                        } else {
                            $hasSuratAction = false; // Tidak ada aksi surat yang tersedia
                        }
                    }
                    @endphp

                    @php
                        $currentSp = $pengajuan->getCurrentSuratPengajuan();
                        $currentSk = $pengajuan->suratKeputusans->last();
                    @endphp

                    {{-- Tombol Dinamis SP/SK (Hanya Admin) --}}
                    @if($hasSuratAction)
                        <button class="btn btn-outline-primary" 
                                onclick="openSecureFrame('{{ $type }}', 'form', {{ $pengajuan->id }})">
                            <i class="fas fa-file-signature me-1"></i> {{ $label }}
                        </button>
                    @endif

                    @if($currentSp)
                        <button class="btn btn-outline-secondary"
                                onclick="openSecureFrame('sp', 'pdf', {{ $currentSp->id }})">
                            <i class="fas fa-file-pdf me-1"></i> Lihat PDF SP
                        </button>
                    @endif

                    @if($currentSk)
                        <button class="btn btn-outline-secondary"
                                onclick="openSecureFrame('sk', 'pdf', {{ $currentSk->id }})">
                            <i class="fas fa-file-pdf me-1"></i> Lihat PDF SK
                        </button>
                    @endif

                    <a href="{{ route('admin.pengajuan.pilih_sk', $pengajuan->id) }}" class="btn text-dark fw-bold" style="background-color: #FEC014; border: 1px solid #FEC014;">
                        <i class="fas fa-file-contract me-1"></i> Buat Surat Keputusan
                    </a>
                @endif

                {{-- Tombol Buat Aksi (Selalu Ada untuk Log - Admin & Wajib Pajak) --}}
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createLogModal">
                    <i class="fas fa-plus-circle me-1"></i> Buat Aksi / Komentar
                </button>
            </div>
        </div>

        <hr>

        <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Waktu (WIB)</th>
                        <th>NRKB</th>
                        <th>Log</th>
                        <th>Tipe / Label</th>
                        <th>Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allLogs = $pengajuan->kendaraans->flatMap(fn($k) => $k->logs)->sortByDesc('created_at')->values();
                        $logPerPage = 7;
                        $logCurrentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('log_page');
                        $logCurrentItems = $allLogs->forPage($logCurrentPage, $logPerPage);
                        $paginatedLogs = new \Illuminate\Pagination\LengthAwarePaginator(
                            $logCurrentItems,
                            $allLogs->count(),
                            $logPerPage,
                            $logCurrentPage,
                            [
                                'path' => request()->url(),
                                'pageName' => 'log_page',
                            ]
                        );
                        $paginatedLogs->appends(request()->except('log_page'));
                    @endphp

                    @forelse($paginatedLogs as $log)
                        <tr data-kendaraan-id="{{ $log->kendaraan_id }}">
                            <td class="text-nowrap">{{ $log->created_at->timezone('Asia/Jakarta')->format('H:i, d M Y') }}</td>
                            <td><strong>{{ $log->kendaraan->nrkb ?? 'N/A' }}</strong></td>
                            <td>
                                {{ $log->aksi }}
                                @if($log->catatan)
                                    <br><small class="text-muted"><strong>Komentar:</strong> {{ $log->catatan }}</small>
                                @endif
                            </td>
                            <td>
                                @if(in_array($log->tipe, ['komentar', 'admin']))
                                    <span class="badge bg-secondary px-3 py-2">Catatan / Komentar</span>
                                @elseif($log->status_baru === 'selesai' || $log->tipe === 'system')
                                    @php
                                        $status_pascal = str($log->status_baru === 'selesai' ? $log->status_baru : $log->tipe)->studly();
                                    @endphp
                                    <span class="badge bg-success px-3 py-2">{{ $status_pascal }}</span>
                                @elseif($log->tipe === 'revisi')
                                    <span class="badge bg-warning text-dark px-3 py-2">Revisi / Penolakan Berkas</span>
                                @elseif($log->status_baru === 'pengajuan')
                                    <span class="badge bg-warning text-dark px-3 py-2">Baru (Pengajuan)</span>
                                @elseif($log->status_baru === 'diproses')
                                    <span class="badge bg-info text-dark px-3 py-2">Diproses</span>
                                @elseif($log->status_baru === 'ditolak')
                                    <span class="badge bg-danger px-3 py-2">Ditolak / Dikembalikan</span>
                                @else
                                    <span class="badge bg-light text-dark px-3 py-2">{{ ucfirst($log->tipe) }}</span>
                                @endif
                            </td>
                            <td>{{ $log->user->name ?? 'N/A' }} @if($log->user && $log->user->unit_kerja) <br><small class="text-muted">{{ $log->user->unit_kerja }}</small>@endif</td>
                            <td class="text-center">
                                @if(!empty($admin) && $admin)
                                    <a href="{{ route('admin.pengajuan.log.show', [$pengajuan, $log->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail Log">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                @else
                                    <a href="{{ route('pengajuan.log.show', [$pengajuan, $log->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail Log">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">Belum ada histori tindakan atau komentar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paginatedLogs->hasPages())
            <div class="mt-3">
                {{ $paginatedLogs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Buat Aksi (Komentar / Revisi) -->
<div class="modal fade" id="createLogModal" tabindex="-1" aria-labelledby="createLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @if(!empty($admin) && $admin)
                <form action="{{ route('admin.pengajuan.log.store', $pengajuan) }}" method="POST" enctype="multipart/form-data">
            @else
                <form action="{{ route('pengajuan.log.store', $pengajuan) }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createLogModalLabel">Buat Aksi / Komentar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pilih Kendaraan</label>
                            <select name="kendaraan_id" id="modalKendaraanSelect" class="form-select" required>
                                @foreach($pengajuan->kendaraans as $kend)
                                    <option value="{{ $kend->id }}">{{ $kend->nrkb }} — {{ $kend->merk_kendaraan }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!empty($admin) && $admin)
                            <div class="col-md-12">
                                <label class="form-label">Tipe Aksi / Label Status</label>
                                <select name="tipe" class="form-select" required>
                                    <optgroup label="Hanya Tambah Catatan">
                                        <option value="catatan_admin">Catatan Internal Admin</option>
                                        <option value="komentar">Komentar</option>
                                        <option value="revisi">Meminta Revisi Dokumen</option>
                                    </optgroup>
                                    <optgroup label="Menandai Perubahan Tindakan (Tidak merubah status asli kendaraan)">
                                        <option value="status_pengajuan">Tandai Status: Baru (Pengajuan)</option>
                                        <option value="status_diproses">Tandai Status: Diproses</option>
                                        <option value="status_selesai">Tandai Status: Selesai</option>
                                        <option value="status_ditolak">Tandai Status: Ditolak / Kasih Revisi</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan / Pesan Revisi</label>
                                <textarea name="catatan" id="modalCatatan" class="form-control" rows="4" placeholder="Catatan untuk penulis atau riwayat status..."></textarea>
                            </div>
                        @else
                            <div class="col-md-6">
                                <label class="form-label">Tipe</label>
                                <select name="tipe" class="form-select" required>
                                    <option value="komentar">Komentar / Balasan</option>
                                    <option value="revisi">Setor Revisi Dokumen</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan / Komentar</label>
                                <textarea name="catatan" class="form-control" rows="4" placeholder="Tulis komentar atau penjelasan berkas revisi yang diupload..."></textarea>
                            </div>
                        @endif
                        
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold"><i class="fas fa-paperclip me-1"></i> Lampiran Dokumen Tambahan/Revisi</label>
                            <div id="fileInputsContainer">
                                <div class="input-group mb-2 file-input-row">
                                    <input type="file" name="lampiran[]" class="form-control" accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif">
                                    <button type="button" class="btn btn-outline-danger btn-remove-file"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="btnAddFile">
                                <i class="fas fa-plus"></i> Tambah Kolom File Lain
                            </button>
                            <div class="text-muted small mt-2">Max 5MB per file. (Gunakan tombol tambah untuk memisahkan file upload).</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Aksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Prefill modal kendaraan select when "Buat Aksi" from a row is clicked
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-open-log-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const kendId = this.getAttribute('data-kendaraan-id');
                const sel = document.getElementById('modalKendaraanSelect');
                if (sel && kendId) sel.value = kendId;
            });
        });
        
        // Dynamic Multiple File Input Logic
        const btnAddFile = document.getElementById('btnAddFile');
        const container = document.getElementById('fileInputsContainer');
        
        if (btnAddFile) {
            btnAddFile.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'input-group mb-2 file-input-row';
                newRow.innerHTML = `
                    <input type="file" name="lampiran[]" class="form-control" accept=".pdf,.docx,.jpg,.jpeg,.png,.heic,.heif">
                    <button type="button" class="btn btn-outline-danger btn-remove-file"><i class="fas fa-times"></i></button>
                `;
                container.appendChild(newRow);
            });
        }

        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-remove-file');
            if (removeBtn) {
                const rows = document.querySelectorAll('.file-input-row');
                if (rows.length > 1) {
                    removeBtn.closest('.file-input-row').remove();
                } else {
                    // Kalau sisa 1 input, bersihkan isinya
                    removeBtn.closest('.file-input-row').querySelector('input').value = '';
                }
            }
        });
    });
</script>