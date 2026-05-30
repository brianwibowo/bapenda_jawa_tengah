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
                        <td class="text-muted">Merek</td>
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
                        <td class="text-muted">Nomor Rangka</td>
                        <td class="fw-semibold">{{ $kendaraan->nomor_rangka }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Mesin</td>
                        <td class="fw-semibold">{{ $kendaraan->nomor_mesin }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Warna Kendaraan Bermotor</td>
                        <td class="fw-semibold">{{ $kendaraan->warna_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bahan Bakar / Sumber Energi</td>
                        <td class="fw-semibold">{{ $kendaraan->jenis_bahan_bakar }}</td>
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
