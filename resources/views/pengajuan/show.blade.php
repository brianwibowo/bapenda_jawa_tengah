<x-app-layout>
    <x-slot name="header">
        Detail Pengajuan - {{ $pengajuan->nomor_pengajuan }}
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Tab Kendaraan --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3" id="kendaraanTabs">
                @foreach ($pengajuan->kendaraans as $index => $kendaraan)
                    <button type="button" class="btn btn-kendaraan-tab {{ $index === 0 ? 'active' : '' }}" 
                            data-index="{{ $index + 1 }}">
                        Kendaraan {{ $index + 1 }}
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
            <div class="kendaraan-detail" data-kendaraan-index="{{ $index + 1 }}" @if($index !== 0) style="display: none;" @endif>
                <div class="card mb-4">
                    <div class="card-header text-white" style="background-color: #0d6efd;">
                        <h4 class="card-title mb-0">Detail Kendaraan {{ $index + 1 }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom Identitas Pemilik -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header text-white" style="background-color: #0d6efd;">
                                        <h4 class="card-title mb-0">1. Identitas Pemilik</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Atas Nama</th>
                                                <td>{{ $kendaraan->pemilik->nama_pemilik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK/TDP/NIB/Kitas/Kitab</th>
                                                <td>{{ $kendaraan->pemilik->nik_pemilik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>{{ $kendaraan->pemilik->alamat_pemilik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. TLP/HP</th>
                                                <td>{{ $kendaraan->pemilik->telp_pemilik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $kendaraan->pemilik->email_pemilik ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Identitas Kendaraan -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header text-white" style="background-color: #0d6efd;">
                                        <h4 class="card-title mb-0">2. Identitas Kendaraan Bermotor</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">NRKB</th>
                                                <td><strong>{{ $kendaraan->nrkb }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Jenis</th>
                                                <td>{{ $kendaraan->jenis_kendaraan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Model</th>
                                                <td>{{ $kendaraan->model_kendaraan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Merk</th>
                                                <td>{{ $kendaraan->merk_kendaraan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tipe</th>
                                                <td>{{ $kendaraan->tipe_kendaraan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tahun Pembuatan</th>
                                                <td>{{ $kendaraan->tahun_pembuatan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Isi Silinder / Daya Listrik</th>
                                                <td>{{ $kendaraan->isi_silinder }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Bahan Bakar</th>
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
                                            <tr>
                                                <th>Status</th>
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
                        <div class="card mb-4">
                            <div class="card-header text-white" style="background-color: #0d6efd;">
                                <h4 class="card-title mb-0">3. Dokumen Persyaratan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $dokumenList = [
                                            'surat_permohonan' => 'Surat Permohonan Penghapusan',
                                            'surat_pernyataan' => 'Surat Pernyataan Kepemilikan',
                                            'ktp' => 'Tanda Bukti Identitas Pemilik (KTP)',
                                            'bpkb' => 'BPKB',
                                            'tbpkp' => 'TBPKP',
                                            'cek_fisik' => 'Hasil Pemeriksaan Cek Fisik',
                                            'foto_ranmor' => 'Foto Kendaraan',
                                            'stnk' => 'STNK'
                                        ];
                                    @endphp
                                    @foreach ($dokumenList as $collection => $label)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">{{ $label }}</label>
                                            <div>
                                                @php
                                                    $media = $kendaraan->getMedia($collection);
                                                @endphp
                                                @if($media->count() > 0)
                                                    @foreach($media as $file)
                                                        <div class="mb-2">
                                                            <a href="{{ $file->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-file me-1"></i> {{ $file->name }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Belum ada dokumen</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($pengajuan->kendaraans->isEmpty())
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    Belum ada kendaraan dalam pengajuan ini.
                </div>
            </div>
        @endif
    </div>

    <script>
        function aktifkanTab(index) {
            // Hide semua detail
            document.querySelectorAll('.kendaraan-detail').forEach(detail => {
                detail.style.display = 'none';
            });
            
            // Show detail yang dipilih
            const selectedDetail = document.querySelector(`[data-kendaraan-index="${index}"]`);
            if (selectedDetail) {
                selectedDetail.style.display = 'block';
            }
            
            // Update tab styles
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-index') == index) {
                    btn.classList.add('active');
                }
            });
            
            updateTabStyles();
        }

        function updateTabStyles() {
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                const isActive = btn.classList.contains('active');
                
                if (isActive) {
                    btn.style.backgroundColor = '#ffc107';
                    btn.style.color = '#000';
                    btn.style.fontWeight = 'bold';
                } else {
                    btn.style.backgroundColor = '#f5f5f5';
                    btn.style.color = '#000';
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTabStyles();
            
            // Add event listeners to all tab buttons
            document.querySelectorAll('.btn-kendaraan-tab').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    aktifkanTab(index);
                });
            });
        });
    </script>

    <style>
        .btn-kendaraan-tab {
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-kendaraan-tab.active {
            transform: scale(1.05);
        }
    </style>
</x-app-layout>