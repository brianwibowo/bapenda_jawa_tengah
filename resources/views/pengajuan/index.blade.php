<x-app-layout>
    <x-slot name="header">
        Riwayat Pengajuan Saya
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- [Langkah 1 UX] Tombol untuk "Buat Nomor Pengajuan Baru" -->
    <div class="d-flex justify-content-end mb-3">
        {{-- Ini adalah form, bukan link, karena kita POST untuk membuat record baru --}}
        <form action="{{ route('pengajuan.store') }}" method="POST" onsubmit="return confirm('Anda akan membuat bundel pengajuan baru. Lanjutkan?')">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Nomor Pengajuan Baru
            </button>
        </form>
    </div>

    <!-- [Langkah 2 UX] Tabel Daftar Bundel Pengajuan -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Bundel Pengajuan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nomor Pengajuan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Jumlah Kendaraan</th>
                            <th>Status Bundel</th>
                            <th>Catatan Terakhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $pengajuan)
                            <tr>
                                <td><strong>{{ $pengajuan->nomor_pengajuan }}</strong></td>
                                <td>{{ $pengajuan->created_at->format('d M Y, H:i') }} WIB</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $pengajuan->kendaraans_count }} Kendaraan</span>
                                </td>
                                <td>
                                    @if($pengajuan->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($pengajuan->status == 'pengajuan')
                                        <span class="badge bg-warning text-dark">Diajukan</span>
                                    @elseif($pengajuan->status == 'diproses')
                                        <span class="badge bg-info text-dark">Sedang Diproses</span>
                                    @elseif($pengajuan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $pengajuan->catatan_admin ?? '-' }}</td>
                                <td>
                                    {{-- [Langkah 3 UX] Tombol "View / Tambah Kendaraan" --}}
                                    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-sm btn-info mb-1" title="Lihat Detail / Tambah Kendaraan">
                                        <i class="fas fa-eye me-1"></i> View / Tambah
                                    </a>
                                    
                                    {{-- Hanya bisa hapus bundel jika masih DRAFT --}}
                                    @if($pengajuan->status == 'draft')
                                        <form action="{{ route('pengajuan.destroy', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bundel pengajuan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Bundel">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Anda belum memiliki pengajuan. Silakan buat nomor pengajuan baru.</td>
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