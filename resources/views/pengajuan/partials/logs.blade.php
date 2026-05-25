
<div class="card mt-4 border-0 shadow-sm log-panel">
    <div class="card-header bg-white border-bottom">
        <h4 class="card-title mb-0">Log & Diskusi</h4>
    </div>
    <div class="card-body">
        @php
            $docs = collect();

            // Extract SK PDFs
            if (!empty($pengajuan->suratKeputusan)) {
                foreach ($pengajuan->suratKeputusan as $sk) {
                    if (!empty($sk->pdf_url)) {
                        $docs->push((object)[
                            'pdf_url' => $sk->pdf_url,
                            'display_name' => basename($sk->pdf_url),
                            'created_at' => $sk->created_at,
                        ]);
                    }
                }
            }

            // Extract SP PDFs & SP Balasan PDFs
            if (!empty($pengajuan->suratPengajuan)) {
                foreach ($pengajuan->suratPengajuan as $sp) {
                    // SP Utama (Pengajuan)
                    if (!empty($sp->pdf_url)) {
                        $docs->push((object)[
                            'pdf_url' => $sp->pdf_url,
                            'display_name' => basename($sp->pdf_url),
                            'created_at' => $sp->created_at,
                        ]);
                    }

                    // SP Balasan from persetujuan_unit_kerja array
                    if (!empty($sp->persetujuan_unit_kerja) && is_array($sp->persetujuan_unit_kerja)) {
                        foreach ($sp->persetujuan_unit_kerja as $item) {
                            if (!empty($item['pdf_url'])) {
                                $docs->push((object)[
                                    'pdf_url' => $item['pdf_url'],
                                    'display_name' => 'SP Balasan (' . ($item['instansi'] ?? 'Instansi') . ') - ' . basename($item['pdf_url']),
                                    'created_at' => !empty($item['updated_at']) ? \Carbon\Carbon::parse($item['updated_at']) : $sp->updated_at,
                                ]);
                            }
                        }
                    }
                }
            }

            $all_docs = $docs->sortByDesc('created_at');
        @endphp
        @if($all_docs->isNotEmpty())
            <div class="mb-4">
                <h5 class="mb-3">Dokumen</h5>
                <div class="row">
                    @foreach($all_docs as $doc)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    @php
                                        $docPdfUrl = $doc->pdf_url;
                                        // Ambil nama file dari URL
                                        $name = $doc->display_name ?? basename($docPdfUrl);
                                        $viewUrl = $docPdfUrl;
                                        $downloadUrl = $docPdfUrl;
                                        // Build safe URL: if localhost/127.0.0.1, force port 8000
                                        $parts = @parse_url($docPdfUrl);
                                        if ($parts && isset($parts['host']) && in_array($parts['host'], ['localhost', '127.0.0.1'])) {
                                            $scheme = $parts['scheme'] ?? 'http';
                                            $path = $parts['path'] ?? '';
                                            $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
                                            $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';
                                            $viewUrl = $scheme . '://' . $parts['host'] . ':8000' . $path . $query . $fragment;
                                            $downloadUrl = $viewUrl;
                                        }
                                    @endphp
                                    <h6 class="card-title text-truncate" title="{{ $name }}">{{ $name }}</h6>
                                    <p class="card-text small text-muted">
                                        Dibuat: {{ $doc->created_at->format('d M Y H:i') }}
                                    </p>
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
                        if (!empty($permissionSurat['canAjukanSP'])) {
                            $type = 'sp';
                            $label = 'Buat Pengajuan ke ' . (Auth::user()->unit_kerja == 'Samsat' ? 'Polda' : 'Bapenda/Jasa Raharja');
                        } elseif (!empty($permissionSurat['canRespondSP'])) {
                            $type = 'sp';
                            $label = 'Review & Balas SP';
                        } elseif (!empty($permissionSurat['canAjukanSK'])) {
                            $type = 'sk';
                            $label = 'Terbitkan Surat Keputusan';
                        } else {
                            $hasSuratAction = false;
                        }
                    }
                    @endphp

                    {{-- Tombol Dinamis SP/SK (Hanya Admin) --}}
                    @if($hasSuratAction)
                        <button class="btn btn-outline-primary" 
                                onclick="openSecureFrame('{{ $type }}', 'form', {{ $pengajuan->id }})">
                            <i class="fas fa-file-signature me-1"></i> {{ $label }}
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
                        $kendaraanIndexMap = $pengajuan->kendaraans->values()->pluck('id')->flip()->map(fn($i) => $i + 1);
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
                                <div class="d-flex flex-wrap gap-1 align-items-center">
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

                                @if(isset($kendaraanIndexMap[$log->kendaraan_id]))
                                    <span class="badge bg-dark bg-opacity-75 px-3 py-2">Kendaraan {{ $kendaraanIndexMap[$log->kendaraan_id] }}</span>
                                @endif
                                </div>
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
                                @if (isset($isUpload) && (($log->sk_id && isset($isUpload['sk'][$log->sk_id])) || ($log->sp_id && isset($isUpload['sp'][$log->sp_id]))))
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#upload{{ $log->sk_id ? 'SK' : 'SP' }}Modal" data-log-id="{{ $log->id }}" data-surat-id="{{ $log->sk_id ?? $log->sp_id }}">
                                        <i class="fas fa-file-contract me-1"></i>Upload
                                    </button>
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

<!-- Modal Upload SK -->
<div class="modal fade" id="uploadSKModal" tabindex="-1" aria-labelledby="uploadSKModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.sk.upload_media') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSKModalLabel">Upload File Surat Keputusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="log_id" id="skLogIdInput">
                    <input type="hidden" name="sk_id" id="skIdInput">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File (PDF/Image, Max 10MB)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.docx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload SP -->
<div class="modal fade" id="uploadSPModal" tabindex="-1" aria-labelledby="uploadSPModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pengajuan.sp.upload_media') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSPModalLabel">Upload File Surat Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="log_id" id="spLogIdInput">
                    <input type="hidden" name="sp_id" id="spIdInput">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File (PDF/Image, Max 10MB)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.docx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Prefill modal kendaraan select when "Buat Aksi" from a row is clicked
        document.querySelectorAll('.btn-open-log-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const kendId = this.getAttribute('data-kendaraan-id');
                const sel = document.getElementById('modalKendaraanSelect');
                if (sel && kendId) sel.value = kendId;
            });
        });
        
        // Populate SK Upload Modal
        const uploadSKModal = document.getElementById('uploadSKModal');
        if (uploadSKModal) {
            uploadSKModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const logId = button.getAttribute('data-log-id');
                const suratId = button.getAttribute('data-surat-id');
                uploadSKModal.querySelector('#skLogIdInput').value = logId;
                uploadSKModal.querySelector('#skIdInput').value = suratId;
            });
        }

        // Populate SP Upload Modal
        const uploadSPModal = document.getElementById('uploadSPModal');
        if (uploadSPModal) {
            uploadSPModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const logId = button.getAttribute('data-log-id');
                const suratId = button.getAttribute('data-surat-id');
                uploadSPModal.querySelector('#spLogIdInput').value = logId;
                uploadSPModal.querySelector('#spIdInput').value = suratId;
            });
        }
        
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