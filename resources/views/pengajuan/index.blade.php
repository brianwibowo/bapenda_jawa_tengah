<x-app-layout>
    <x-slot name="header">
        Daftar Bundel Pengajuan
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabel Daftar Bundel Pengajuan (Mirip Admin) -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Bundel Pengajuan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nomor Pengajuan</th>
                            <th>Tanggal Masuk</th>
                            <th>Update Terakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $pengajuan)
                            <tr>
                                <td><strong>{{ $pengajuan->nomor_pengajuan }}</strong></td>
                                <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                                <td>{{ $pengajuan->updated_at->format('d M Y') }}</td>
                                <td>
                                    @if($pengajuan->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($pengajuan->status == 'pengajuan')
                                        <span class="badge bg-warning text-dark">Baru</span>
                                    @elseif($pengajuan->status == 'diproses')
                                        <span class="badge bg-info text-dark">Sedang Diproses</span>
                                    @elseif($pengajuan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Tombol Aksi View (Hanya View untuk Penulis) -->
                                    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data pengajuan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginasi -->
            <div class="mt-3">
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
</x-app-layout>