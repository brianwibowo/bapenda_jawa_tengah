<div class="card mt-4">
    <div class="card-header">
        <h4 class="card-title mb-0">Log & Diskusi</h4>
    </div>
    <div class="card-body">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <label class="form-label mb-0">Pilih Kendaraan</label>
                <select id="filterKendaraan" class="form-select" style="width: 260px; display: inline-block;">
                    <option value="">Semua Kendaraan</option>
                    @foreach($pengajuan->kendaraans as $kend)
                        <option value="{{ $kend->id }}">{{ $kend->nrkb }} — {{ $kend->merk_kendaraan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createLogModal">
                    <i class="fas fa-plus-circle me-1"></i> Buat Aksi
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
                        $allLogs = $pengajuan->kendaraans->flatMap(fn($k) => $k->logs)->sortByDesc('created_at');
                    @endphp

                    @forelse($allLogs as $log)
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
                                @if(in_array($log->tipe, ['komentar', 'catatan_admin']))
                                    <span class="badge bg-secondary">Catatan / Komentar</span>
                                @elseif($log->tipe === 'revisi')
                                    <span class="badge bg-warning text-dark">Revisi Dokumen</span>
                                @elseif($log->tipe === 'status_pengajuan')
                                    <span class="badge bg-warning text-dark">Baru (Pengajuan)</span>
                                @elseif($log->tipe === 'status_diproses')
                                    <span class="badge bg-info text-dark">Diproses</span>
                                @elseif($log->tipe === 'status_selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($log->tipe === 'status_ditolak')
                                    <span class="badge bg-danger">Ditolak / Revisi</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $log->tipe ?? '-' }}</span>
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
                            <label class="form-label">Kendaraan</label>
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
                                        <option value="catatan_admin">Catatan Internal Admin (Hanya teks log)</option>
                                        <option value="komentar">Komentar Biasa</option>
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
                                    <input type="file" name="lampiran[]" class="form-control" accept=".pdf,.docx,.jpg,.jpeg,.png">
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

        // If user chooses a kendaraan from filter, filter log rows
        const filter = document.getElementById('filterKendaraan');
        if (filter) {
            filter.addEventListener('change', function () {
                const val = this.value;
                document.querySelectorAll('tbody tr[data-kendaraan-id]').forEach(row => {
                    if (!val) { row.style.display = ''; return; }
                    row.style.display = row.getAttribute('data-kendaraan-id') === val ? '' : 'none';
                });
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
                    <input type="file" name="lampiran[]" class="form-control" accept=".pdf,.docx,.jpg,.jpeg,.png">
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