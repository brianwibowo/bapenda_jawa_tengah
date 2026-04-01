<x-app-layout>
    @php
        // Menyesuaikan back route berdasarkan hak akses (Role)
        if (isset($admin) && $admin) {
            $backUrl = route('admin.pengajuan.show', $pengajuan);
            $titlePrefix = 'Admin - ';
        } else {
            $backUrl = route('pengajuan.show', $pengajuan);
            $titlePrefix = '';
        }
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
                                    @if(in_array($log->tipe, ['komentar', 'catatan_admin']))
                                        <span class="badge bg-secondary px-3 py-2">Catatan / Komentar</span>
                                    @elseif($log->tipe === 'revisi')
                                        <span class="badge bg-warning text-dark px-3 py-2">Revisi / Penolakan Berkas</span>
                                    @elseif($log->tipe === 'status_pengajuan')
                                        <span class="badge bg-warning text-dark px-3 py-2">Baru (Pengajuan)</span>
                                    @elseif($log->tipe === 'status_diproses')
                                        <span class="badge bg-info text-dark px-3 py-2">Diproses</span>
                                    @elseif($log->tipe === 'status_selesai')
                                        <span class="badge bg-success px-3 py-2">Selesai</span>
                                    @elseif($log->tipe === 'status_ditolak')
                                        <span class="badge bg-danger px-3 py-2">Ditolak / Dikembalikan</span>
                                    @else
                                        <span class="badge bg-light text-dark px-3 py-2">{{ ucfirst($log->tipe) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ringkasan Log</td>
                                <td class="fw-bold text-dark">{{ $log->aksi }}</td>
                            </tr>
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
                                        <div class="col-md-6 col-md-4">
                                            <div class="card h-100 border-0 shadow-sm attachment-card">
                                                @if(Str::startsWith($m->mime_type, 'image/'))
                                                    <div class="position-relative bg-light">
                                                        <img src="{{ $m->getUrl() }}" class="card-img-top object-fit-cover"
                                                            alt="Lampiran Gambar"
                                                            style="height: 160px; border-bottom: 3px solid #f8f9fa;">
                                                        <span
                                                            class="position-absolute top-0 end-0 badge bg-primary m-2 shadow-sm rounded-pill px-3">GAMBAR</span>
                                                    </div>
                                                @else
                                                    <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center text-secondary position-relative"
                                                        style="height: 160px; border-bottom: 3px solid #e9ecef;">
                                                        @if(Str::contains($m->mime_type, 'pdf'))
                                                            <i class="far fa-file-pdf text-danger mb-2" style="font-size: 4rem;"></i>
                                                            <span
                                                                class="position-absolute top-0 end-0 badge bg-danger m-2 shadow-sm rounded-pill px-3">PDF</span>
                                                        @elseif(Str::contains($m->mime_type, 'word'))
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
                                                        <a href="{{ $m->getUrl() }}" target="_blank"
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
</x-app-layout>