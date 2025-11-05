<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="gap: 19.3rem;">
            <span>Detail Kendaraan: {{ $kendaraan->nrkb }} (dari Pengajuan: {{ $kendaraan->pengajuan->nomor_pengajuan }})</span>
            <a href="{{ route('pengajuan.show', $kendaraan->pengajuan) }}" class="btn btn-secondary flex-shrink-0">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri: History Log & Data Identitas --}}
        <div class="col-lg-8 mb-4">
            {{-- Card History Pengajuan (READ-ONLY) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">History Pengajuan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="29%">Log</th>
                                    <th width="10%">Status</th>
                                    <th width="18%">Oleh</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="8%" class="text-center">File</th>
                                    <th width="20%">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kendaraan->pengajuan->logs()->orderBy('created_at', 'desc')->get() as $log)
                                    <tr>
                                        <td>
                                            {{ $log->catatan ?? '-' }}
                                            @if($log->catatan && strlen($log->catatan) > 60)
                                                <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                                    Lihat Selengkapnya
                                                </button>
                                                
                                                {{-- Modal Log Lengkap --}}
                                                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Log Lengkap</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{ $log->catatan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status_baru == 'pengajuan')
                                                <span class="badge bg-warning text-dark">Baru</span>
                                            @elseif($log->status_baru == 'diproses')
                                                <span class="badge bg-info text-dark">Diproses</span>
                                            @elseif($log->status_baru == 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($log->status_baru == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->user->name ?? 'N/A' }}
                                            @if($log->user && $log->user->unit_kerja)
                                                <br><small class="text-muted">{{ $log->user->unit_kerja }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->created_at->format('d M Y') }}
                                            <br><small class="text-muted">{{ $log->created_at->format('H:i') }} WIB</small>
                                        </td>
                                        <td class="text-center">
                                            @if($log->getFirstMedia('lampiran_log'))
                                                <a href="{{ $log->getFirstMediaUrl('lampiran_log') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-paperclip"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->aksi ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Belum ada histori tindakan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Card Data Identitas --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Data Identitas</h5>
                </div>
                <div class="card-body">
                    {{-- Identitas Pemilik --}}
                    <h6 class="fw-bold mb-3">Identitas Pemilik</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th width="30%">Atas Nama</th>
                                <td>{{ $kendaraan->nama_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>NIK/TDP/NIB</th>
                                <td>{{ $kendaraan->nik_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $kendaraan->alamat_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon/HP</th>
                                <td>{{ $kendaraan->telp_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $kendaraan->email_pemilik }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Identitas Kendaraan --}}
                    <h6 class="fw-bold mb-3">Identitas Kendaraan Bermotor</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th width="30%">NRKB</th>
                                <td>{{ $kendaraan->nrkb }}</td>
                            </tr>
                            <tr>
                                <th>Merk / Tipe</th>
                                <td>{{ $kendaraan->merk_kendaraan }} / {{ $kendaraan->tipe_kendaraan }}</td>
                            </tr>
                            <tr>
                                <th>Jenis / Model</th>
                                <td>{{ $kendaraan->jenis_kendaraan }} / {{ $kendaraan->model_kendaraan }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Pembuatan</th>
                                <td>{{ $kendaraan->tahun_pembuatan }}</td>
                            </tr>
                            <tr>
                                <th>Isi Silinder</th>
                                <td>{{ $kendaraan->isi_silinder }}</td>
                            </tr>
                            <tr>
                                <th>Bahan Bakar</th>
                                <td>{{ $kendaraan->jenis_bahan_bakar }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Rangka</th>
                                <td>{{ $kendaraan->nomor_rangka }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Mesin</th>
                                <td>{{ $kendaraan->nomor_mesin }}</td>
                            </tr>
                            <tr>
                                <th>Warna TNKB</th>
                                <td>{{ $kendaraan->warna_tnkb }}</td>
                            </tr>
                            <tr>
                                <th>Nomor BPKB</th>
                                <td>{{ $kendaraan->nomor_bpkb }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Dokumen --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dokumen Terlampir</h5>
                </div>
                <div class="card-body">
                    @php
                        $labels = [
                            'surat_permohonan' => 'Surat Permohonan',
                            'surat_pernyataan' => 'Surat Pernyataan',
                            'ktp' => 'KTP Pemilik',
                            'bpkb' => 'BPKB',
                            'tbpkp' => 'TBPKP',
                            'cek_fisik' => 'Cek Fisik',
                            'foto_ranmor' => 'Foto Kendaraan',
                            'stnk' => 'STNK',
                        ];
                    @endphp

                    @foreach ($labels as $collectionName => $label)
                        <div class="mb-3">
                            <h6 class="fw-bold">{{ $label }}</h6>
                            @forelse ($kendaraan->getMedia($collectionName) as $doc)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="{{ $doc->getUrl() }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-file me-2"></i>{{ Str::limit($doc->file_name, 25) }}
                                    </a>
                                    <span class="badge bg-secondary">{{ $doc->human_readable_size }}</span>
                                </div>
                            @empty
                                <p class="text-danger small mb-2">Tidak ada file</p>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Edit (Jika diperlukan) --}}
            <div class="card mt-3">
                <div class="card-body">
                    <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="btn btn-warning w-100">
                        <i class="fas fa-edit me-2"></i>Edit Kendaraan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>