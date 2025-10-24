<x-app-layout>
    <x-slot name="header">
        Riwayat Pengajuan Saya
    </x-slot>

    {{-- Pesan Sukses setelah submit form --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol untuk membuat pengajuan baru -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat Pengajuan Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Pengajuan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nomor Pengajuan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Catatan dari Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $item)
                            <tr>
                                <td><strong>{{ $item->nomor_pengajuan }}</strong></td>
                                <td>{{ $item->created_at->format('d M Y, H:i') }} WIB</td>
                                <td>
                                    @if($item->status == 'pengajuan')
                                        <span class="badge bg-warning text-dark">Baru</span>
                                    @elseif($item->status == 'diproses')
                                        <span class="badge bg-info text-dark">Sedang Diproses</span>
                                    @elseif($item->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($item->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $item->catatan_admin ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Anda belum memiliki riwayat pengajuan.</td>
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
