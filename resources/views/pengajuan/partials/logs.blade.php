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
                        <th>Waktu</th>
                        <th>NRKB</th>
                        <th>Log</th>
                        <th>Tipe</th>
                        <th>Oleh</th>
                        <th>Tanggal</th>
                        <th class="text-center">Lampiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allLogs = $pengajuan->kendaraans->flatMap(fn($k) => $k->logs)->sortByDesc('created_at');
                    @endphp

                    @forelse($allLogs as $log)
                        <tr data-kendaraan-id="{{ $log->kendaraan_id }}">
                            <td>{{ $log->created_at->format('H:i, d M Y') }}</td>
                            <td><strong>{{ $log->kendaraan->nrkb ?? 'N/A' }}</strong></td>
                            <td>{{ $log->aksi }}<br><small class="text-muted">{{ $log->catatan ?? '-' }}</small></td>
                            <td>
                                @if($log->tipe === 'komentar')
                                    <span class="badge bg-secondary">Komentar</span>
                                @elseif($log->tipe === 'revisi')
                                    <span class="badge bg-warning text-dark">Revisi</span>
                                @else
                                    <span class="badge bg-light text-dark">-</span>
                                @endif
                            </td>
                            <td>{{ $log->user->name ?? 'N/A' }} @if($log->user && $log->user->unit_kerja) <br><small class="text-muted">{{ $log->user->unit_kerja }}</small>@endif</td>
                            <td>{{ $log->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @php $medias = $log->getMedia('lampiran_log'); @endphp
                                @foreach($medias as $m)
                                    <a href="{{ $m->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                                        <i class="fas fa-paperclip"></i>
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary btn-open-log-modal" data-kendaraan-id="{{ $log->kendaraan_id }}" data-bs-toggle="modal" data-bs-target="#createLogModal">Buat Aksi</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">Belum ada histori tindakan atau komentar.</td>
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
      <form action="{{ route('pengajuan.log.store', $pengajuan) }}" method="POST" enctype="multipart/form-data">
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
                <div class="col-md-6">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select" required>
                        <option value="komentar">Komentar</option>
                        <option value="revisi">Revisi</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan / Komentar</label>
                    <textarea name="catatan" class="form-control" rows="4" placeholder="Tulis komentar atau permintaan revisi..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lampiran (opsional, bisa pilih lebih dari 1)</label>
                    <input type="file" name="lampiran[]" class="form-control" multiple>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Prefill modal kendaraan select when "Buat Aksi" from a row is clicked
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-open-log-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                const kendId = this.getAttribute('data-kendaraan-id');
                const sel = document.getElementById('modalKendaraanSelect');
                if (sel && kendId) sel.value = kendId;
            });

        });

        // If user chooses a kendaraan from filter, filter log rows
        const filter = document.getElementById('filterKendaraan');
        filter.addEventListener('change', function() {
            const val = this.value;
            document.querySelectorAll('tbody tr[data-kendaraan-id]').forEach(row => {
                if (!val) { row.style.display = ''; return; }
                row.style.display = row.getAttribute('data-kendaraan-id') === val ? '' : 'none';
            });
        });
    });
</script>
