
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
                    {{-- Tombol Dinamis SP (Hanya Admin) — Standalone Modal --}}
                    @if(!empty($permissionSurat['canAjukanSP']))
                        @if(Auth::user()->unit_kerja == 'Samsat')
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSpDefault">
                                <i class="fas fa-paper-plane me-1"></i> Buat Pengajuan ke Polda
                            </button>
                        @else
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSpPolda">
                                <i class="fas fa-file-signature me-1"></i> Buat Pengajuan ke Bapenda/Jasa Raharja
                            </button>
                        @endif
                    @elseif(!empty($permissionSurat['canRespondSP']))
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalSpBapendaJr">
                            <i class="fas fa-reply me-1"></i> Review & Balas SP
                        </button>
                    @endif

                    @if(!empty($skTypeOptions) && count($skTypeOptions) > 0)
                        <button class="btn text-dark fw-bold" style="background-color: #FEC014; border: 1px solid #FEC014;"
                                data-bs-toggle="modal" data-bs-target="#modalPilihJenisSK">
                            <i class="fas fa-file-contract me-1"></i> Buat Surat Keputusan
                        </button>
                    @endif
                @endif

                {{-- Tombol Buat Aksi (Hanya untuk Wajib Pajak, Samsat, dan Superadmin) --}}
                @if(auth()->user()->hasAnyRole(['wajib_pajak', 'samsat', 'superadmin']))
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createLogModal">
                    <i class="fas fa-plus-circle me-1"></i> Buat Aksi / Komentar
                </button>
                @endif
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
                        $currentUser = auth()->user();
                        $allLogs = $pengajuan->kendaraans->flatMap(fn($k) => $k->logs)
                            ->filter(fn($log) => $log->isVisibleToUser($currentUser))
                            ->sortByDesc('created_at')->values();
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
                                @if($log->isSkDraft())
                                    <span class="badge px-3 py-2" style="background-color: #6c757d; color: #fff;">
                                        <i class="fas fa-pen-ruler me-1"></i>Draft SK
                                    </span>
                                @elseif($log->isSkPublished())
                                    <span class="badge px-3 py-2" style="background-color: #198754; color: #fff;">
                                        <i class="fas fa-stamp me-1"></i>Terbit
                                    </span>
                                @elseif(in_array($log->tipe, ['komentar', 'admin']))
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
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    @if(!empty($admin) && $admin)
                                        <a href="{{ route('admin.pengajuan.log.show', [$pengajuan, $log->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail Log">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    @else
                                        <a href="{{ route('pengajuan.log.show', [$pengajuan, $log->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail Log">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    @endif
                                    @if($log->isSkDraft() && $log->user && $log->user->unit_kerja === auth()->user()->unit_kerja)
                                        <button class="btn btn-sm btn-success fw-semibold btn-publish-sk"
                                                data-bs-toggle="modal" data-bs-target="#modalPublishSK"
                                                data-log-id="{{ $log->id }}"
                                                data-sk-id="{{ $log->sk_id }}">
                                            <i class="fas fa-stamp me-1"></i>Terbitkan SK
                                        </button>
                                    @endif
                                    @if($log->isSpDraft() && $log->user && $log->user->unit_kerja === auth()->user()->unit_kerja)
                                        <button class="btn btn-sm btn-success fw-semibold btn-publish-sp"
                                                data-bs-toggle="modal" data-bs-target="#modalPublishSP"
                                                data-log-id="{{ $log->id }}"
                                                data-sp-id="{{ $log->sp_id }}">
                                            <i class="fas fa-stamp me-1"></i>Terbitkan SP
                                        </button>
                                    @endif
                                    {{-- Tombol Kirim Revisi — muncul jika log ini revisi pending & user punya permission --}}
                                    @if($log->isRevisionPending() && auth()->user()->can('submit_revision'))
                                        <button class="btn btn-sm btn-warning text-dark fw-semibold btn-open-revision-modal"
                                                data-bs-toggle="modal" data-bs-target="#revisionModal"
                                                data-log-id="{{ $log->id }}"
                                                data-kendaraan-id="{{ $log->kendaraan_id }}"
                                                data-revisi-fields="{{ json_encode($log->revisi_fields) }}">
                                            <i class="fas fa-edit me-1"></i> Kirim Revisi
                                        </button>
                                    @endif
                                </div>
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
                                <select name="tipe" id="tipeAksiSelect" class="form-select" required>
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

                            {{-- Panel Accordion Bagian Revisi --}}
                            <div class="col-12" id="revisiFieldsPanel" style="display: none;">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-list-check me-1"></i> Pilih Bagian yang Perlu Direvisi
                                </label>
                                <div class="accordion accordion-flush border rounded" id="revisiAccordion">
                                    {{-- Group: Identitas Pemilik --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-3" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapsePemilik">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <span>Identitas Pemilik</span>
                                                <span class="badge bg-secondary ms-2 revisi-group-count" data-group="pemilik">0 dipilih</span>
                                            </button>
                                        </h2>
                                        <div id="collapsePemilik" class="accordion-collapse collapse">
                                            <div class="accordion-body py-2">
                                                @php
                                                    $pemilikFields = [
                                                        'nama_pemilik' => 'Nama Pemilik',
                                                        'nik_pemilik' => 'NIK / TDP / NIB',
                                                        'alamat_pemilik' => 'Alamat',
                                                        'telp_pemilik' => 'No. Telepon / HP',
                                                        'email_pemilik' => 'Email',
                                                    ];
                                                @endphp
                                                @foreach($pemilikFields as $key => $label)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input revisi-cb" type="checkbox" name="revisi_fields[]" value="{{ $key }}" id="rf_{{ $key }}" data-group="pemilik">
                                                        <label class="form-check-label" for="rf_{{ $key }}">{{ $label }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Group: Identitas Kendaraan --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-3" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseKendaraan">
                                                <i class="fas fa-car me-2 text-info"></i>
                                                <span>Identitas Kendaraan</span>
                                                <span class="badge bg-secondary ms-2 revisi-group-count" data-group="kendaraan">0 dipilih</span>
                                            </button>
                                        </h2>
                                        <div id="collapseKendaraan" class="accordion-collapse collapse">
                                            <div class="accordion-body py-2">
                                                @php
                                                    $kendaraanFields = [
                                                        'nrkb' => 'NRKB',
                                                        'merk_kendaraan' => 'Merk',
                                                        'tipe_kendaraan' => 'Tipe',
                                                        'jenis_kendaraan' => 'Jenis',
                                                        'model_kendaraan' => 'Model',
                                                        'tahun_pembuatan' => 'Tahun Pembuatan',
                                                        'isi_silinder' => 'Isi Silinder / Daya Listrik',
                                                        'nomor_rangka' => 'Nomor Rangka',
                                                        'nomor_mesin' => 'Nomor Mesin',
                                                        'warna_kendaraan' => 'Warna Kendaraan',
                                                        'jenis_bahan_bakar' => 'Bahan Bakar / Sumber Energi',
                                                        'warna_tnkb' => 'Warna TNKB',
                                                        'nomor_bpkb' => 'Nomor BPKB',
                                                    ];
                                                @endphp
                                                @foreach($kendaraanFields as $key => $label)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input revisi-cb" type="checkbox" name="revisi_fields[]" value="{{ $key }}" id="rf_{{ $key }}" data-group="kendaraan">
                                                        <label class="form-check-label" for="rf_{{ $key }}">{{ $label }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Group: Dokumen Persyaratan --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-3" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseDokumen">
                                                <i class="fas fa-folder-open me-2 text-warning"></i>
                                                <span>Dokumen Persyaratan</span>
                                                <span class="badge bg-secondary ms-2 revisi-group-count" data-group="dokumen">0 dipilih</span>
                                            </button>
                                        </h2>
                                        <div id="collapseDokumen" class="accordion-collapse collapse">
                                            <div class="accordion-body py-2">
                                                @php
                                                    $revisiDokumenOptions = [
                                                        'surat_permohonan' => 'Surat Permohonan Penghapusan',
                                                        'surat_pernyataan' => 'Surat Pernyataan Kepemilikan',
                                                        'ktp' => 'KTP',
                                                        'bpkb' => 'BPKB',
                                                        'tbpkp' => 'TBPKP',
                                                        'cek_fisik' => 'Hasil Pemeriksaan Cek Fisik',
                                                        'foto_ranmor' => 'Foto Kendaraan',
                                                        'stnk' => 'STNK',
                                                    ];
                                                @endphp
                                                @foreach($revisiDokumenOptions as $key => $label)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input revisi-cb" type="checkbox" name="revisi_fields[]" value="{{ $key }}" id="rf_{{ $key }}" data-group="dokumen">
                                                        <label class="form-check-label" for="rf_{{ $key }}">{{ $label }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted small mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Klik grup untuk membuka, lalu centang field spesifik yang perlu direvisi.
                                </div>
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

<!-- Modal: Kirim Revisi -->
<div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('pengajuan.revision.submit', $pengajuan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="revision_log_id" id="revisionLogIdInput">
                <input type="hidden" name="kendaraan_id" id="revisionKendaraanIdInput">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="revisionModalLabel">
                        <i class="fas fa-edit me-2"></i>Kirim Revisi Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Lengkapi bagian yang diminta revisi di bawah ini. Data yang dikirim akan <strong>menggantikan</strong> data sebelumnya.
                    </div>

                    {{-- Section: Identitas Pemilik (per-field) --}}
                    <div class="card border-warning mb-4 revision-group" data-group="pemilik" style="display:none;">
                        <div class="card-header bg-warning bg-opacity-25">
                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Identitas Pemilik <span class="badge bg-danger ms-2">Perlu Direvisi</span></h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 revision-field" data-field="nama_pemilik" style="display:none;">
                                    <label class="form-label">Nama Pemilik</label>
                                    <input type="text" name="nama_pemilik" class="form-control" placeholder="Nama pemilik">
                                </div>
                                <div class="col-md-6 revision-field" data-field="nik_pemilik" style="display:none;">
                                    <label class="form-label">NIK/TDP/NIB/Kitas/Kitab</label>
                                    <input type="text" name="nik_pemilik" class="form-control" placeholder="NIK Pemilik">
                                </div>
                                <div class="col-12 revision-field" data-field="alamat_pemilik" style="display:none;">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat_pemilik" class="form-control" rows="2" placeholder="Alamat pemilik"></textarea>
                                </div>
                                <div class="col-md-6 revision-field" data-field="telp_pemilik" style="display:none;">
                                    <label class="form-label">No. Telepon/HP</label>
                                    <input type="text" name="telp_pemilik" class="form-control" placeholder="08xxx">
                                </div>
                                <div class="col-md-6 revision-field" data-field="email_pemilik" style="display:none;">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email_pemilik" class="form-control" placeholder="email@contoh.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Identitas Kendaraan (per-field) --}}
                    <div class="card border-warning mb-4 revision-group" data-group="kendaraan" style="display:none;">
                        <div class="card-header bg-warning bg-opacity-25">
                            <h6 class="mb-0"><i class="fas fa-car me-2"></i>Identitas Kendaraan <span class="badge bg-danger ms-2">Perlu Direvisi</span></h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 revision-field" data-field="nrkb" style="display:none;">
                                    <label class="form-label">NRKB</label>
                                    <input type="text" name="nrkb" class="form-control" placeholder="AB 1234 CD">
                                </div>
                                <div class="col-md-4 revision-field" data-field="merk_kendaraan" style="display:none;">
                                    <label class="form-label">Merk</label>
                                    <input type="text" name="merk_kendaraan" class="form-control" placeholder="Toyota">
                                </div>
                                <div class="col-md-4 revision-field" data-field="tipe_kendaraan" style="display:none;">
                                    <label class="form-label">Tipe</label>
                                    <input type="text" name="tipe_kendaraan" class="form-control" placeholder="Avanza">
                                </div>
                                <div class="col-md-4 revision-field" data-field="jenis_kendaraan" style="display:none;">
                                    <label class="form-label">Jenis</label>
                                    <input type="text" name="jenis_kendaraan" class="form-control" placeholder="Minibus">
                                </div>
                                <div class="col-md-4 revision-field" data-field="model_kendaraan" style="display:none;">
                                    <label class="form-label">Model</label>
                                    <input type="text" name="model_kendaraan" class="form-control" placeholder="MPV">
                                </div>
                                <div class="col-md-4 revision-field" data-field="tahun_pembuatan" style="display:none;">
                                    <label class="form-label">Tahun Pembuatan</label>
                                    <input type="text" name="tahun_pembuatan" class="form-control" placeholder="2020">
                                </div>
                                <div class="col-md-4 revision-field" data-field="isi_silinder" style="display:none;">
                                    <label class="form-label">Isi Silinder / Daya Listrik</label>
                                    <input type="text" name="isi_silinder" class="form-control" placeholder="1500">
                                </div>
                                <div class="col-md-4 revision-field" data-field="nomor_rangka" style="display:none;">
                                    <label class="form-label">Nomor Rangka</label>
                                    <input type="text" name="nomor_rangka" class="form-control">
                                </div>
                                <div class="col-md-4 revision-field" data-field="nomor_mesin" style="display:none;">
                                    <label class="form-label">Nomor Mesin</label>
                                    <input type="text" name="nomor_mesin" class="form-control">
                                </div>
                                <div class="col-md-4 revision-field" data-field="warna_kendaraan" style="display:none;">
                                    <label class="form-label">Warna Kendaraan</label>
                                    <input type="text" name="warna_kendaraan" class="form-control" placeholder="Hitam">
                                </div>
                                <div class="col-md-4 revision-field" data-field="jenis_bahan_bakar" style="display:none;">
                                    <label class="form-label">Bahan Bakar / Sumber Energi</label>
                                    <input type="text" name="jenis_bahan_bakar" class="form-control" placeholder="Bensin">
                                </div>
                                <div class="col-md-4 revision-field" data-field="warna_tnkb" style="display:none;">
                                    <label class="form-label">Warna TNKB</label>
                                    <input type="text" name="warna_tnkb" class="form-control" placeholder="Hitam">
                                </div>
                                <div class="col-md-4 revision-field" data-field="nomor_bpkb" style="display:none;">
                                    <label class="form-label">Nomor BPKB</label>
                                    <input type="text" name="nomor_bpkb" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Dokumen (per-dokumen) --}}
                    @php
                        $revisiDocSections = [
                            'surat_permohonan' => ['label' => 'Surat Permohonan Penghapusan', 'icon' => 'fa-file-alt'],
                            'surat_pernyataan' => ['label' => 'Surat Pernyataan Kepemilikan', 'icon' => 'fa-file-signature'],
                            'ktp' => ['label' => 'KTP', 'icon' => 'fa-id-card'],
                            'bpkb' => ['label' => 'BPKB', 'icon' => 'fa-book'],
                            'tbpkp' => ['label' => 'TBPKP', 'icon' => 'fa-file-invoice'],
                            'cek_fisik' => ['label' => 'Hasil Pemeriksaan Cek Fisik', 'icon' => 'fa-clipboard-check'],
                            'foto_ranmor' => ['label' => 'Foto Kendaraan', 'icon' => 'fa-camera'],
                            'stnk' => ['label' => 'STNK', 'icon' => 'fa-id-card-alt'],
                        ];
                    @endphp
                    @foreach($revisiDocSections as $docKey => $docMeta)
                        <div class="revision-field" data-field="{{ $docKey }}" style="display:none;">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning bg-opacity-25">
                                    <h6 class="mb-0">
                                        <i class="fas {{ $docMeta['icon'] }} me-2"></i>{{ $docMeta['label'] }}
                                        <span class="badge bg-danger ms-2">Perlu Direvisi</span>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Upload file baru (max 10MB)</label>
                                    <input type="file" name="doc_{{ $docKey }}" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
                                    <div class="text-muted small mt-1">File baru akan menggantikan file lama.</div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Revisi
                    </button>
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
        
        // === Toggle Revisi Fields Panel (Admin modal) ===
        const tipeSelect = document.getElementById('tipeAksiSelect');
        const revisiPanel = document.getElementById('revisiFieldsPanel');
        if (tipeSelect && revisiPanel) {
            // Update badge count per accordion group
            function updateRevisiGroupCounts() {
                document.querySelectorAll('.revisi-group-count').forEach(badge => {
                    const group = badge.getAttribute('data-group');
                    const checked = revisiPanel.querySelectorAll(`.revisi-cb[data-group="${group}"]:checked`).length;
                    badge.textContent = checked + ' dipilih';
                    badge.className = 'badge ms-2 revisi-group-count ' + (checked > 0 ? 'bg-warning text-dark' : 'bg-secondary');
                });
            }

            // Listen for checkbox changes inside accordion
            revisiPanel.addEventListener('change', function(e) {
                if (e.target.classList.contains('revisi-cb')) {
                    updateRevisiGroupCounts();
                }
            });

            tipeSelect.addEventListener('change', function () {
                revisiPanel.style.display = this.value === 'revisi' ? 'block' : 'none';
                // Jika bukan revisi, uncheck semua dan reset counters
                if (this.value !== 'revisi') {
                    revisiPanel.querySelectorAll('.revisi-cb').forEach(cb => cb.checked = false);
                    updateRevisiGroupCounts();
                }
            });
        }

        // === Populate Revision Modal (granular per-field) ===
        const revisionModal = document.getElementById('revisionModal');
        if (revisionModal) {
            // Map field names to their parent group
            const fieldGroupMap = {
                'nama_pemilik': 'pemilik', 'nik_pemilik': 'pemilik',
                'alamat_pemilik': 'pemilik', 'telp_pemilik': 'pemilik', 'email_pemilik': 'pemilik',
                'nrkb': 'kendaraan', 'merk_kendaraan': 'kendaraan', 'tipe_kendaraan': 'kendaraan',
                'jenis_kendaraan': 'kendaraan', 'model_kendaraan': 'kendaraan',
                'tahun_pembuatan': 'kendaraan', 'isi_silinder': 'kendaraan',
                'nomor_rangka': 'kendaraan', 'nomor_mesin': 'kendaraan',
                'warna_kendaraan': 'kendaraan', 'jenis_bahan_bakar': 'kendaraan',
                'warna_tnkb': 'kendaraan', 'nomor_bpkb': 'kendaraan',
            };

            revisionModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const logId = button.getAttribute('data-log-id');
                const kendaraanId = button.getAttribute('data-kendaraan-id');
                const revisiFields = JSON.parse(button.getAttribute('data-revisi-fields') || '[]');

                // Set hidden inputs
                revisionModal.querySelector('#revisionLogIdInput').value = logId;
                revisionModal.querySelector('#revisionKendaraanIdInput').value = kendaraanId;

                // Hide all field inputs and group cards
                revisionModal.querySelectorAll('.revision-field').forEach(el => el.style.display = 'none');
                revisionModal.querySelectorAll('.revision-group').forEach(el => el.style.display = 'none');

                // Track which groups need to be shown
                const activeGroups = new Set();

                // Show only requested fields
                revisiFields.forEach(field => {
                    // Individual field (pemilik/kendaraan)
                    const fieldEl = revisionModal.querySelector(`.revision-field[data-field="${field}"]`);
                    if (fieldEl) {
                        fieldEl.style.display = '';
                        // Track parent group
                        if (fieldGroupMap[field]) {
                            activeGroups.add(fieldGroupMap[field]);
                        }
                    }
                });

                // Show parent group cards that have visible fields
                activeGroups.forEach(group => {
                    const groupCard = revisionModal.querySelector(`.revision-group[data-group="${group}"]`);
                    if (groupCard) groupCard.style.display = '';
                });
            });

            // Reset on close
            revisionModal.addEventListener('hidden.bs.modal', function () {
                revisionModal.querySelectorAll('.revision-field').forEach(el => el.style.display = 'none');
                revisionModal.querySelectorAll('.revision-group').forEach(el => el.style.display = 'none');
                revisionModal.querySelectorAll('input, textarea').forEach(el => {
                    if (el.type !== 'hidden') el.value = '';
                });
            });
        }
    });
</script>

{{-- Modal: Pilih Jenis SK --}}
@if(!empty($admin) && $admin && !empty($skTypeOptions))
<div class="modal fade" id="modalPilihJenisSK" tabindex="-1" aria-labelledby="modalPilihJenisSKLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, #FEC014, #f0a500); border: none;">
                <h5 class="modal-title fw-bold text-dark" id="modalPilihJenisSKLabel">
                    <i class="fas fa-file-contract me-2"></i>Pilih Jenis Surat Keputusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted mb-4">Pilih jenis SK yang akan dibuat untuk pengajuan ini:</p>
                <div class="row g-3">
                    @foreach($skTypeOptions as $option)
                        <div class="col-6">
                            <button type="button" class="btn w-100 py-4 fw-bold text-dark shadow-sm border-0 btn-pilih-sk-type"
                                    style="background: linear-gradient(145deg, #fff8e1, #fff3cd); border-radius: 12px; transition: all 0.2s ease;"
                                    data-target-modal="{{ $option['modal'] }}"
                                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(254,192,20,0.3)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                                <i class="{{ $option['icon'] }} d-block text-center mb-2" style="font-size: 2rem; color: #b8860b;"></i>
                                <span class="d-block text-center" style="font-size: 0.85rem; line-height: 1.3;">{{ $option['label'] }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include existing SK modals (sebelumnya di pilih_sk, sekarang inline di sini) --}}
@include('pengajuan.modals.sk_regident')
@include('pengajuan.modals.sk_polda')
@include('pengajuan.modals.sk_bapenda_pembebasan')
@include('pengajuan.modals.sk_penghapusan_regident')
@include('pengajuan.modals.sk_default')
@include('pengajuan.modals.sk_jr')

{{-- Include SP modals --}}
@include('pengajuan.modals.sp_default')
@include('pengajuan.modals.sp_polda')
@include('pengajuan.modals.sp_bapenda_jr')
@endif

{{-- Modal: Upload & Terbitkan SK --}}
@if(!empty($admin) && $admin)
<div class="modal fade" id="modalPublishSK" tabindex="-1" aria-labelledby="modalPublishSKLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formPublishSK" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="log_id" id="publishLogId">
                <input type="hidden" name="sk_id" id="publishSkId">

                <div class="modal-header" style="background: linear-gradient(135deg, #198754, #157347); border: none;">
                    <h5 class="modal-title fw-bold text-white" id="modalPublishSKLabel">
                        <i class="fas fa-stamp me-2"></i>Terbitkan Surat Keputusan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="alert alert-info border-0 d-flex align-items-start mb-4" style="border-radius: 10px;">
                        <i class="fas fa-info-circle me-3 mt-1 fs-5"></i>
                        <div>
                            <strong>Informasi:</strong> Unggah dokumen SK yang telah ditandatangani secara resmi. Setelah diterbitkan, SK ini akan terlihat oleh seluruh instansi terkait.
                        </div>
                    </div>

                    {{-- Upload Area --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Unggah Dokumen Bertandatangan
                        </label>
                        <div class="border rounded-3 p-4 text-center position-relative" id="publishDropZone"
                             style="border: 2px dashed #dee2e6; border-radius: 12px !important; cursor: pointer; transition: all 0.2s ease; background: #f8f9fa;">
                            <input type="file" name="file" id="publishFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;"
                                   accept=".pdf,.jpg,.jpeg,.png,.heic,.heif,.docx" required>
                            <div id="publishDropContent">
                                <i class="fas fa-cloud-upload-alt d-block mb-2" style="font-size: 2.5rem; color: #adb5bd;"></i>
                                <p class="mb-1 fw-semibold text-dark">Seret file ke sini atau klik untuk memilih</p>
                                <small class="text-muted">PDF, DOCX, JPG, PNG · Maks 10MB</small>
                            </div>
                            <div id="publishFilePreview" style="display: none;">
                                <i class="fas fa-file-check d-block mb-2 text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 fw-semibold text-success" id="publishFileName"></p>
                                <small class="text-muted" id="publishFileSize"></small>
                            </div>
                        </div>
                    </div>

                    {{-- Checkbox Pernyataan --}}
                    <div class="form-check mb-3 p-3 rounded-3" style="background: #f0f9f4; border: 1px solid #c3e6cb;">
                        <input class="form-check-input" type="checkbox" name="pernyataan" value="1" id="publishPernyataan" required>
                        <label class="form-check-label fw-semibold text-dark" for="publishPernyataan" style="cursor: pointer;">
                            Dengan ini menyatakan bahwa dokumen telah lengkap, ditandatangani secara sah oleh pejabat berwenang 
                            <br>sesuai ketentuan birokrasi yang berlaku, dan dinyatakan resmi diterbitkan.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold px-4" id="btnPublishSK" disabled>
                        <i class="fas fa-stamp me-1"></i> Terbitkan SK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal: Upload & Terbitkan SP --}}
@if(!empty($admin) && $admin)
<div class="modal fade" id="modalPublishSP" tabindex="-1" aria-labelledby="modalPublishSPLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formPublishSP" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="log_id" id="publishSpLogId">
                <input type="hidden" name="sp_id" id="publishSpId">

                <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd, #0a58ca); border: none;">
                    <h5 class="modal-title fw-bold text-white" id="modalPublishSPLabel">
                        <i class="fas fa-stamp me-2"></i>Terbitkan Surat Pengajuan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="alert alert-info border-0 d-flex align-items-start mb-4" style="border-radius: 10px;">
                        <i class="fas fa-info-circle me-3 mt-1 fs-5"></i>
                        <div>
                            <strong>Informasi:</strong> Unggah dokumen SP yang telah ditandatangani secara resmi. Setelah diterbitkan, SP ini akan terlihat oleh seluruh instansi terkait.
                        </div>
                    </div>

                    {{-- Upload Area --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Unggah Dokumen Bertandatangan
                        </label>
                        <div class="border rounded-3 p-4 text-center position-relative" id="publishSpDropZone"
                             style="border: 2px dashed #dee2e6; border-radius: 12px !important; cursor: pointer; transition: all 0.2s ease; background: #f8f9fa;">
                            <input type="file" name="file" id="publishSpFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;"
                                   accept=".pdf,.jpg,.jpeg,.png,.heic,.heif,.docx" required>
                            <div id="publishSpDropContent">
                                <i class="fas fa-cloud-upload-alt d-block mb-2" style="font-size: 2.5rem; color: #adb5bd;"></i>
                                <p class="mb-1 fw-semibold text-dark">Seret file ke sini atau klik untuk memilih</p>
                                <small class="text-muted">PDF, DOCX, JPG, PNG · Maks 10MB</small>
                            </div>
                            <div id="publishSpFilePreview" style="display: none;">
                                <i class="fas fa-file-check d-block mb-2 text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 fw-semibold text-success" id="publishSpFileName"></p>
                                <small class="text-muted" id="publishSpFileSize"></small>
                            </div>
                        </div>
                    </div>

                    {{-- Checkbox Pernyataan --}}
                    <div class="form-check mb-3 p-3 rounded-3" style="background: #e8f0fe; border: 1px solid #b6d4fe;">
                        <input class="form-check-input" type="checkbox" name="pernyataan" value="1" id="publishSpPernyataan" required>
                        <label class="form-check-label fw-semibold text-dark" for="publishSpPernyataan" style="cursor: pointer;">
                            Dengan ini menyatakan bahwa dokumen telah lengkap, ditandatangani secara sah oleh pejabat berwenang 
                            <br>sesuai ketentuan birokrasi yang berlaku, dan dinyatakan resmi diterbitkan.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="btnPublishSP" disabled>
                        <i class="fas fa-stamp me-1"></i> Terbitkan SP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Pilih Jenis SK → buka modal input yang sesuai ===
    document.querySelectorAll('.btn-pilih-sk-type').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetModal = this.getAttribute('data-target-modal');
            // Tutup modal Pilih Jenis SK
            const pilihModal = bootstrap.Modal.getInstance(document.getElementById('modalPilihJenisSK'));
            if (pilihModal) pilihModal.hide();

            // Buka modal target setelah jeda singkat
            setTimeout(() => {
                const target = document.querySelector(targetModal);
                if (target) {
                    const modal = new bootstrap.Modal(target);
                    modal.show();
                }
            }, 350);
        });
    });

    // === Publish SK Modal: Dynamic data binding ===
    document.querySelectorAll('.btn-publish-sk').forEach(btn => {
        btn.addEventListener('click', function() {
            const logId = this.getAttribute('data-log-id');
            const skId = this.getAttribute('data-sk-id');
            document.getElementById('publishLogId').value = logId;
            document.getElementById('publishSkId').value = skId;

            // Set form action URL
            const form = document.getElementById('formPublishSK');
            form.action = '{{ url("admin/pengajuan/publish-sk") }}/' + logId;

            // Reset state
            document.getElementById('publishPernyataan').checked = false;
            document.getElementById('publishFileInput').value = '';
            document.getElementById('publishDropContent').style.display = '';
            document.getElementById('publishFilePreview').style.display = 'none';
            document.getElementById('btnPublishSK').disabled = true;
        });
    });

    // === File upload preview ===
    const publishFileInput = document.getElementById('publishFileInput');
    if (publishFileInput) {
        publishFileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                document.getElementById('publishDropContent').style.display = 'none';
                document.getElementById('publishFilePreview').style.display = '';
                document.getElementById('publishFileName').textContent = file.name;
                document.getElementById('publishFileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                checkPublishReady();
            }
        });

        // Drag & drop
        const dropZone = document.getElementById('publishDropZone');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = '#198754'; dropZone.style.background = '#f0f9f4'; });
            dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = '#dee2e6'; dropZone.style.background = '#f8f9fa'; });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = '#dee2e6';
                dropZone.style.background = '#f8f9fa';
                if (e.dataTransfer.files.length) {
                    publishFileInput.files = e.dataTransfer.files;
                    publishFileInput.dispatchEvent(new Event('change'));
                }
            });
        }
    }

    // === Checkbox toggle ===
    const publishCheckbox = document.getElementById('publishPernyataan');
    if (publishCheckbox) {
        publishCheckbox.addEventListener('change', checkPublishReady);
    }

    function checkPublishReady() {
        const hasFile = publishFileInput && publishFileInput.files.length > 0;
        const hasCheck = publishCheckbox && publishCheckbox.checked;
        const btn = document.getElementById('btnPublishSK');
        if (btn) btn.disabled = !(hasFile && hasCheck);
    }

    // === Publish SP Modal: Dynamic data binding ===
    document.querySelectorAll('.btn-publish-sp').forEach(btn => {
        btn.addEventListener('click', function() {
            const logId = this.getAttribute('data-log-id');
            const spId = this.getAttribute('data-sp-id');
            document.getElementById('publishSpLogId').value = logId;
            document.getElementById('publishSpId').value = spId;

            // Set form action URL
            const form = document.getElementById('formPublishSP');
            form.action = '{{ url("admin/pengajuan/publish-sp") }}/' + logId;

            // Reset state
            const spPernyataan = document.getElementById('publishSpPernyataan');
            const spFileInput = document.getElementById('publishSpFileInput');
            if (spPernyataan) spPernyataan.checked = false;
            if (spFileInput) spFileInput.value = '';
            document.getElementById('publishSpDropContent').style.display = '';
            document.getElementById('publishSpFilePreview').style.display = 'none';
            document.getElementById('btnPublishSP').disabled = true;
        });
    });

    // === SP File upload preview ===
    const publishSpFileInput = document.getElementById('publishSpFileInput');
    if (publishSpFileInput) {
        publishSpFileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                document.getElementById('publishSpDropContent').style.display = 'none';
                document.getElementById('publishSpFilePreview').style.display = '';
                document.getElementById('publishSpFileName').textContent = file.name;
                document.getElementById('publishSpFileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                checkPublishSpReady();
            }
        });

        // Drag & drop for SP
        const spDropZone = document.getElementById('publishSpDropZone');
        if (spDropZone) {
            spDropZone.addEventListener('dragover', (e) => { e.preventDefault(); spDropZone.style.borderColor = '#0d6efd'; spDropZone.style.background = '#e8f0fe'; });
            spDropZone.addEventListener('dragleave', () => { spDropZone.style.borderColor = '#dee2e6'; spDropZone.style.background = '#f8f9fa'; });
            spDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                spDropZone.style.borderColor = '#dee2e6';
                spDropZone.style.background = '#f8f9fa';
                if (e.dataTransfer.files.length) {
                    publishSpFileInput.files = e.dataTransfer.files;
                    publishSpFileInput.dispatchEvent(new Event('change'));
                }
            });
        }
    }

    // === SP Checkbox toggle ===
    const publishSpCheckbox = document.getElementById('publishSpPernyataan');
    if (publishSpCheckbox) {
        publishSpCheckbox.addEventListener('change', checkPublishSpReady);
    }

    function checkPublishSpReady() {
        const hasFile = publishSpFileInput && publishSpFileInput.files.length > 0;
        const hasCheck = publishSpCheckbox && publishSpCheckbox.checked;
        const btn = document.getElementById('btnPublishSP');
        if (btn) btn.disabled = !(hasFile && hasCheck);
    }
});
</script>