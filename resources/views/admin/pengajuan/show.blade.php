<x-app-layout>
    <x-slot name="header">
        Detail Pengajuan: {{ $pengajuan->nomor_pengajuan }}
    </x-slot>

    <div class="row">
        <!-- Kolom Kiri: Dokumen & Status -->
        <div class="col-md-5">
            <!-- Form Update Status -->
            <div class="card">
                 <div class="card-header">
                    <h4 class="card-title">Update Status Pengajuan</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <form action="{{ route('admin.pengajuan.updateStatus', $pengajuan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Ubah Status Menjadi:</label>
                            <select name="status" id="status" class="form-select">
                                <!-- Tambahkan status 'pengajuan' (Baru) sebagai opsi -->
                                <option value="pengajuan" {{ $pengajuan->status == 'pengajuan' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ $pengajuan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</Ditolak>
                                <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label">Catatan untuk Penulis (Opsional)</label>
                            <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3">{{ $pengajuan->catatan_admin }}</textarea>
                            <small class="form-text text-muted">Catatan ini akan bisa dilihat oleh penulis.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Daftar Dokumen -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dokumen Terlampir</h4>
                    <p class="mb-0">Diajukan oleh: <strong>{{ $pengajuan->user->name }}</strong></p>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($dokumen as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <a href="{{ $doc->getUrl() }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-file-alt me-2 text-primary"></i>{{ $doc->file_name }}
                                </a>
                                <span class="badge bg-secondary">{{ $doc->human_readable_size }}</span>
                            </li>
                        @empty
                            <li class="list-group-item px-0">Tidak ada dokumen yang dilampirkan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Data Identitas -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Identitas</h4>
                </div>
                <div class="card-body">
                    <!-- Identitas Pemilik -->
                    <h5>Identitas Pemilik</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Atas Nama</th>
                                <td>{{ $pengajuan->nama_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>NIK/TDP</th>
                                <td>{{ $pengajuan->nik_pemilik }}</td>
                            </tr>
                             <tr>
                                <th>Alamat</th>
                                <td>{{ $pengajuan->alamat_pemilik }}</td>
                            </tr>
                             <tr>
                                <th>No. TLP/HP</th>
                                <td>{{ $pengajuan->telp_pemilik }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pengajuan->email_pemilik }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Identitas Kendaraan -->
                    <h5 class="mt-4">Identitas Kendaraan</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>NRKB</th>
                                <td>{{ $pengajuan->nrkb }}</td>
                            </tr>
                            <tr>
                                <th>Merk / Tipe</th>
                                <td>{{ $pengajuan->merk_kendaraan }} / {{ $pengajuan->tipe_kendaraan }}</td>
                            </tr>
                            <tr>
                                <th>Jenis / Model</th>
                                <td>{{ $pengajuan->jenis_kendaraan }} / {{ $pengajuan->model_kendaraan }}</td>
                            </tr>
                            <tr>
                                <th>Tahun Pembuatan</th>
                                <td>{{ $pengajuan->tahun_pembuatan }}</td>
                            </tr>
                             <tr>
                                <th>Isi Silinder</th>
                                <td>{{ $pengajuan->isi_silinder }}</td>
                            </tr>
                            <tr>
                                <th>Bahan Bakar</th>
                                <td>{{ $pengajuan->jenis_bahan_bakar }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Rangka</th>
                                <td>{{ $pengajuan->nomor_rangka }}</td>
                            </tr>
                            <tr>
                                <th>Nomor Mesin</th>
                                <td>{{ $pengajuan->nomor_mesin }}</td>
                            </tr>
                             <tr>
                                <th>Warna TNKB</th>
                                <td>{{ $pengajuan->warna_tnkb }}</td>
                            </tr>
                            <tr>
                                <th>Nomor BPKB</th>
                                <td>{{ $pengajuan->nomor_bpkb }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
