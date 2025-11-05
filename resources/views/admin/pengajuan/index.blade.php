<x-app-layout>
    <x-slot name="header">
        Daftar Pengajuan
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Tombol Filter Status -->
                        <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.pengajuan.index', ['search' => request('search')]) }}">Semua</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'pengajuan' ? 'active' : '' }}" href="{{ route('admin.pengajuan.index', ['status' => 'pengajuan', 'search' => request('search')]) }}">Baru</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'diproses' ? 'active' : '' }}" href="{{ route('admin.pengajuan.index', ['status' => 'diproses', 'search' => request('search')]) }}">Sedang Diproses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" href="{{ route('admin.pengajuan.index', ['status' => 'selesai', 'search' => request('search')]) }}">Selesai</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link {{ request('status') == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.pengajuan.index', ['status' => 'ditolak', 'search' => request('search')]) }}">Ditolak</a>
                            </li>
                        </ul>
                        
                        <!-- Form Pencarian -->
                        <form method="GET" action="{{ route('admin.pengajuan.index') }}" class="d-flex">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari No. Pengajuan / Nama..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
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
                                            @if($pengajuan->status == 'pengajuan')
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
                                            <!-- Tombol Aksi View -->
                                            <a href="{{ route('admin.pengajuan.show', $pengajuan) }}" class="btn btn-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            
                                            <!-- Tombol Aksi Delete -->
                                            <form action="{{ route('admin.pengajuan.destroy', $pengajuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data pengajuan ditemukan.</td>
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
        </div>
    </div>
</x-app-layout>
