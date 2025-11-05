<x-app-layout>
    <x-slot name="header">
        Detail Pengajuan: {{ $pengajuan->nomor_pengajuan }}
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Kendaraan</h4>
                    <a href="{{ route('pengajuan.kendaraan.create', $pengajuan) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Kendaraan
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>NRKB</th>
                                    <th>Merk / Tipe</th>
                                    <th>Pemilik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuan->kendaraans as $kendaraan)
                                    <tr>
                                        <td><strong>{{ $kendaraan->nrkb }}</strong></td>
                                        <td>{{ $kendaraan->merk_kendaraan }} / {{ $kendaraan->tipe_kendaraan }}</td>
                                        <td>{{ $kendaraan->nama_pemilik }}</td>
                                        <td>
                                            {{-- TOMBOL VIEW (Menuju Level 3) --}}
                                            <a href="{{ route('kendaraan.show', $kendaraan) }}" class="btn btn-sm btn-info" title="Lihat Detail Kendaraan">
                                                <i class="fas fa-eye"></i> Lihat Status/Kendaraan
                                            </a>
                                            
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="btn btn-sm btn-warning" title="Edit Kendaraan">
                                                <i class="fas fa-edit"></i> Edit Kendaraan
                                            </a>

                                            {{-- Tombol Hapus Dihilangkan sesuai permintaanmu --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Belum ada kendaraan. Silakan klik "+ Tambah Kendaraan".
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>