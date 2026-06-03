<x-app-layout>
    @php
        if (auth()->user()->can('view_menu_manajemen_pengajuan')) {
            $backUrl = route('admin.pengajuan.show', $pengajuan);
            $titlePrefix = 'Verifikator - ';
        } else {
            $backUrl = route('pengajuan.show', $pengajuan);
            $titlePrefix = '';
        }
        $kendaraanIndexMap = $pengajuan->kendaraans->values()->pluck('id')->flip()->map(fn($i) => $i + 1);
        $kendNum = $kendaraanIndexMap[$log->kendaraan_id] ?? null;
    @endphp

    <x-slot name="header">
        <div class="d-flex justify-content-end align-items-start w-100">
            <div class="text-end me-3">
                <h2 class="fw-bold mb-1 text-dark" style="font-size: 1.5rem;">
                    <i class="fas fa-history me-2 text-primary"></i>{{ $titlePrefix }}Detail Log & Diskusi
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">NRKB: <span
                        class="badge bg-dark px-2">{{ $log->kendaraan->nrkb ?? '-' }}</span> | Bundel:
                    {{ $pengajuan->nomor_pengajuan }}
                </p>
            </div>
            <div>
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">

                <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem; overflow: hidden;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Aksi</h5>
                    </div>
                    <div class="card-body p-4">
                        <table class="table table-borderless detail-table mb-4">
                            <tr>
                                <td class="text-muted" width="30%">Waktu Aktivitas</td>
                                <td class="fw-semibold">
                                    <i class="far fa-clock me-1 text-primary"></i>
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Aktor / Pembuat</td>
                                <td class="fw-semibold">
                                    <i class="fas fa-user-circle me-1 text-secondary"></i>
                                    {{ $log->user->name ?? 'Sistem' }}
                                    @if($log->user && $log->user->unit_kerja)
                                        <span class="text-muted fw-normal ms-1">({{ $log->user->unit_kerja }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tipe Aksi</td>
                                <td>
                                    @if($log->isSkDraft())
                                        <span class="badge px-3 py-2" style="background-color: #6c757d; color: #fff;">
                                            <i class="fas fa-pen-ruler me-1"></i>Draft SK
                                        </span>
                                    @elseif($log->isSkPublished())
                                        <span class="badge px-3 py-2" style="background-color: #198754; color: #fff;">
                                            <i class="fas fa-stamp me-1"></i>Terbit
                                        </span>
                                    @elseif(in_array($log->tipe, ['komentar', 'catatan_admin']))
                                        <span class="badge bg-secondary px-3 py-2">Catatan / Komentar</span>
                                    @elseif($log->tipe === 'revisi')
                                        <span class="badge bg-warning text-dark px-3 py-2">Revisi / Penolakan Berkas</span>
                                    @elseif($log->status_baru === 'pengajuan')
                                        <span class="badge bg-warning text-dark px-3 py-2">Baru (Pengajuan)</span>
                                    @elseif($log->status_baru === 'diproses')
                                        <span class="badge bg-info text-dark px-3 py-2">Diproses</span>
                                    @elseif($log->status_baru === 'selesai' || $log->tipe === 'system')
                                        @php
                                            $status_pascal = str($log->status_baru === 'selesai' ? $log->status_baru : $log->tipe)->studly();
                                        @endphp
                                        <span class="badge bg-success px-3 py-2">{{ $status_pascal }}</span>
                                    @elseif($log->status_baru === 'ditolak')
                                        <span class="badge bg-danger px-3 py-2">Ditolak / Dikembalikan</span>
                                    @else
                                        <span class="badge bg-light text-dark px-3 py-2">{{ ucfirst($log->tipe) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if($kendNum)
                            <tr>
                                <td class="text-muted">Kendaraan</td>
                                <td class="fw-semibold">
                                    <span class="badge bg-dark bg-opacity-75 px-3 py-2">Kendaraan {{ $kendNum }}</span>
                                    <span class="ms-2 text-dark">{{ $log->kendaraan->nrkb ?? 'N/A' }}</span>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Ringkasan Log</td>
                                <td class="fw-bold text-dark">{{ $log->aksi }}</td>
                            </tr>
                            @if($log->tipe === 'revisi' && !empty($log->revisi_fields) && is_array($log->revisi_fields))
                            <tr>
                                <td class="text-muted">Bagian Revisi</td>
                                <td>
                                    @php
                                        $revisiLabelMap = [
                                            'identitas_pemilik' => 'Identitas Pemilik',
                                            'identitas_kendaraan' => 'Identitas Kendaraan',
                                            'surat_permohonan' => 'Surat Permohonan',
                                            'surat_pernyataan' => 'Surat Pernyataan',
                                            'ktp' => 'KTP', 'bpkb' => 'BPKB', 'tbpkp' => 'TBPKP',
                                            'cek_fisik' => 'Cek Fisik', 'foto_ranmor' => 'Foto Kendaraan', 'stnk' => 'STNK',
                                        ];
                                    @endphp
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($log->revisi_fields as $rf)
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-pen me-1"></i>{{ $revisiLabelMap[$rf] ?? $rf }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Revisi</td>
                                <td>
                                    @if($log->revisi_resolved_at)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Sudah Direvisi
                                            <small class="ms-1">({{ $log->revisi_resolved_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }})</small>
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Menunggu Revisi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>

                        <div class="card bg-light border-0 mb-4 shadow-sm" style="border-radius: 0.75rem;">
                            <div class="card-body p-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                    <i class="fas fa-comment-dots me-2"></i>Pesan / Catatan Lengkap
                                </h6>
                                @if($log->catatan)
                                    <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.6;">
                                        {{ $log->catatan }}
                                    </p>
                                @else
                                    <div class="text-muted fst-italic">
                                        <i class="fas fa-minus me-1"></i> Tidak ada rincian catatan teks tambahan.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <h6 class="text-primary fw-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-paperclip me-2"></i>Daftar Lampiran / Berkas Revisi
                            </h6>

                            @php $medias = $log->getMedia('lampiran_log'); @endphp

                            @if($medias->count() > 0)
                                <div class="row g-4">
                                    @foreach($medias as $m)
                                        @php
                                            $mediaUrl = $m->disk === 'public'
                                                ? '/storage/' . ltrim($m->getPathRelativeToRoot(), '/')
                                                : $m->getUrl();
                                        @endphp
                                        <div class="col-md-6 col-md-4">
                                            <div class="card h-100 border-0 shadow-sm attachment-card">
                                                @if(\Illuminate\Support\Str::startsWith($m->mime_type, 'image/'))
                                                    <div class="position-relative bg-light">
                                                        <img src="{{ $mediaUrl }}" class="card-img-top object-fit-cover"
                                                            alt="Lampiran Gambar"
                                                            style="height: 160px; border-bottom: 3px solid #f8f9fa;">
                                                        <span
                                                            class="position-absolute top-0 end-0 badge bg-primary m-2 shadow-sm rounded-pill px-3">GAMBAR</span>
                                                    </div>
                                                @else
                                                    <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center text-secondary position-relative"
                                                        style="height: 160px; border-bottom: 3px solid #e9ecef;">
                                                        @if(\Illuminate\Support\Str::contains($m->mime_type, 'pdf'))
                                                            <i class="far fa-file-pdf text-danger mb-2" style="font-size: 4rem;"></i>
                                                            <span
                                                                class="position-absolute top-0 end-0 badge bg-danger m-2 shadow-sm rounded-pill px-3">PDF</span>
                                                        @elseif(\Illuminate\Support\Str::contains($m->mime_type, 'word'))
                                                            <i class="far fa-file-word text-primary mb-2" style="font-size: 4rem;"></i>
                                                            <span
                                                                class="position-absolute top-0 end-0 badge bg-primary m-2 shadow-sm rounded-pill px-3">DOCX</span>
                                                        @else
                                                            <i class="far fa-file-alt mb-2" style="font-size: 4rem;"></i>
                                                            <span
                                                                class="position-absolute top-0 end-0 badge bg-secondary m-2 shadow-sm rounded-pill px-3">FILE</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="card-body p-3 d-flex flex-column">
                                                    <h6 class="card-title text-truncate small mb-1 fw-bold text-dark"
                                                        title="{{ $m->file_name }}">{{ $m->file_name }}</h6>
                                                    <p class="card-text small text-muted mb-3"><i class="fas fa-hdd me-1"></i>
                                                        {{ round($m->size / 1024, 1) }} KB</p>
                                                    <div class="mt-auto">
                                                        <a href="{{ $mediaUrl }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary w-100 stretched-link fw-bold btn-download">
                                                            <i class="fas fa-external-link-alt me-1"></i> Buka / Unduh File
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-secondary border-0 bg-light d-flex align-items-center mb-0 py-4 px-4 shadow-sm"
                                    style="border-radius: 0.75rem;">
                                    <i class="fas fa-folder-open me-3 text-muted" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Tidak ada file yang dilampirkan</h6>
                                        <p class="mb-0 text-muted small">Aksi ini tidak menyertakan berkas lampiran sama
                                            sekali.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol Upload & Terbitkan SK (untuk draft milik role yang sama) --}}
                            @if($log->isSkDraft() && $log->user && $log->user->unit_kerja === auth()->user()->unit_kerja)
                                <div class="mt-4 pt-2">
                                    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem; background: linear-gradient(145deg, #f0f9f4, #e8f5e9);">
                                        <div class="card-body p-4 text-center">
                                            <i class="fas fa-cloud-upload-alt d-block mb-2" style="font-size: 2.5rem; color: #198754;"></i>
                                            <h6 class="fw-bold text-dark mb-2">Upload Dokumen Bertandatangan</h6>
                                            <p class="text-muted small mb-3">Unggah dokumen SK yang telah ditandatangani resmi untuk menerbitkan SK ini.</p>
                                            <button class="btn btn-success fw-bold px-4 btn-publish-sk"
                                                    data-bs-toggle="modal" data-bs-target="#modalPublishSKDetail"
                                                    data-log-id="{{ $log->id }}"
                                                    data-sk-id="{{ $log->sk_id }}">
                                                <i class="fas fa-stamp me-1"></i> Upload & Terbitkan SK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .detail-table td {
            padding: 0.75rem 0;
            vertical-align: top;
        }

        .attachment-card {
            border-radius: 0.75rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .attachment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .attachment-card .btn-download {
            transition: all 0.2s;
        }

        .attachment-card:hover .btn-download {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>

{{-- Modal: Upload & Terbitkan SK (dari halaman detail log) --}}
@if($log->isSkDraft() && $log->user && $log->user->unit_kerja === auth()->user()->unit_kerja)
<div class="modal fade" id="modalPublishSKDetail" tabindex="-1" aria-labelledby="modalPublishSKDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formPublishSKDetail" method="POST" enctype="multipart/form-data"
                  action="{{ route('admin.pengajuan.publish_sk', $log->id) }}">
                @csrf
                <input type="hidden" name="log_id" value="{{ $log->id }}">
                <input type="hidden" name="sk_id" value="{{ $log->sk_id }}">

                <div class="modal-header" style="background: linear-gradient(135deg, #198754, #157347); border: none;">
                    <h5 class="modal-title fw-bold text-white" id="modalPublishSKDetailLabel">
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

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Unggah Dokumen Bertandatangan
                        </label>
                        <div class="border rounded-3 p-4 text-center position-relative" id="publishDropZoneDetail"
                             style="border: 2px dashed #dee2e6; border-radius: 12px !important; cursor: pointer; transition: all 0.2s ease; background: #f8f9fa;">
                            <input type="file" name="file" id="publishFileInputDetail" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;"
                                   accept=".pdf,.jpg,.jpeg,.png,.heic,.heif,.docx" required>
                            <div id="publishDropContentDetail">
                                <i class="fas fa-cloud-upload-alt d-block mb-2" style="font-size: 2.5rem; color: #adb5bd;"></i>
                                <p class="mb-1 fw-semibold text-dark">Seret file ke sini atau klik untuk memilih</p>
                                <small class="text-muted">PDF, DOCX, JPG, PNG · Maks 10MB</small>
                            </div>
                            <div id="publishFilePreviewDetail" style="display: none;">
                                <i class="fas fa-file-check d-block mb-2 text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 fw-semibold text-success" id="publishFileNameDetail"></p>
                                <small class="text-muted" id="publishFileSizeDetail"></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3 p-3 rounded-3" style="background: #f0f9f4; border: 1px solid #c3e6cb;">
                        <input class="form-check-input" type="checkbox" name="pernyataan" value="1" id="publishPernyataanDetail" required>
                        <label class="form-check-label fw-semibold text-dark" for="publishPernyataanDetail" style="cursor: pointer;">
                            Dengan ini menyatakan bahwa dokumen telah lengkap, ditandatangani secara sah oleh pejabat berwenang 
                            <br>sesuai ketentuan birokrasi yang berlaku, dan dinyatakan resmi diterbitkan.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold px-4" id="btnPublishSKDetail" disabled>
                        <i class="fas fa-stamp me-1"></i> Terbitkan SK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('publishFileInputDetail');
    const checkbox = document.getElementById('publishPernyataanDetail');
    const btn = document.getElementById('btnPublishSKDetail');

    function checkReady() {
        const hasFile = fileInput && fileInput.files.length > 0;
        const hasCheck = checkbox && checkbox.checked;
        if (btn) btn.disabled = !(hasFile && hasCheck);
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                document.getElementById('publishDropContentDetail').style.display = 'none';
                document.getElementById('publishFilePreviewDetail').style.display = '';
                document.getElementById('publishFileNameDetail').textContent = file.name;
                document.getElementById('publishFileSizeDetail').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                checkReady();
            }
        });

        const dropZone = document.getElementById('publishDropZoneDetail');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = '#198754'; dropZone.style.background = '#f0f9f4'; });
            dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = '#dee2e6'; dropZone.style.background = '#f8f9fa'; });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = '#dee2e6'; dropZone.style.background = '#f8f9fa';
                if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; fileInput.dispatchEvent(new Event('change')); }
            });
        }
    }
    if (checkbox) checkbox.addEventListener('change', checkReady);

    // === Publish SK Form Confirmation ===
    const formPublishSKDetail = document.getElementById('formPublishSKDetail');
    if (formPublishSKDetail) {
        formPublishSKDetail.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menerbitkan Surat Keputusan ini? Setelah diterbitkan, dokumen tidak dapat diubah.')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endif

</x-app-layout>