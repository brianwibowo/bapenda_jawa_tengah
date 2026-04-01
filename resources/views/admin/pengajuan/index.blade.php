<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Kelola Pengajuan</h2>
    </x-slot>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Status Tabs -->
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-filter me-2"></i>Filter Status
                    </label>
                    <ul class="nav nav-pills nav-fill flex-wrap gap-2" role="tablist">
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                               href="{{ route('admin.pengajuan.index', ['search' => request('search')]) }}">
                                <i class="fas fa-list me-1"></i> Semua
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'pengajuan' ? 'active' : '' }}" 
                               href="{{ route('admin.pengajuan.index', ['status' => 'pengajuan', 'search' => request('search')]) }}">
                                <i class="fas fa-file-alt me-1"></i> Baru
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'diproses' ? 'active' : '' }}" 
                               href="{{ route('admin.pengajuan.index', ['status' => 'diproses', 'search' => request('search')]) }}">
                                <i class="fas fa-spinner me-1"></i> Diproses
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" 
                               href="{{ route('admin.pengajuan.index', ['status' => 'selesai', 'search' => request('search')]) }}">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'ditolak' ? 'active' : '' }}" 
                               href="{{ route('admin.pengajuan.index', ['status' => 'ditolak', 'search' => request('search')]) }}">
                                <i class="fas fa-times-circle me-1"></i> Ditolak
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Search -->
                <div class="col-12">
                    <form method="GET" action="{{ route('admin.pengajuan.index') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari berdasarkan Nomor Pengajuan atau Nama..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.pengajuan.index', ['status' => request('status')]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Pengajuan</p>
                            <h3 class="mb-0 fw-bold">{{ $pengajuans->total() }}</h3>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="fas fa-folder-open fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Baru</p>
                            <h3 class="mb-0 fw-bold text-warning">
                                {{ $pengajuans->where('status', 'pengajuan')->count() }}
                            </h3>
                        </div>
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Sedang Diproses</p>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ $pengajuans->where('status', 'diproses')->count() }}
                            </h3>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info">
                            <i class="fas fa-spinner fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Selesai</p>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ $pengajuans->where('status', 'selesai')->count() }}
                            </h3>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengajuan -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table me-2"></i>Daftar Pengajuan
                </h5>
                @if(request('status'))
                    <span class="badge bg-secondary">
                        Filter: {{ ucfirst(request('status')) }}
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Nomor Pengajuan</th>
                            <th class="px-4 py-3">Tanggal Masuk</th>
                            <th class="px-4 py-3">Update Terakhir</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $pengajuan)
                            <tr>
                                <td class="px-4 py-3">
                                    <strong class="text-primary">{{ $pengajuan->nomor_pengajuan }}</strong>
                                </td>
                                <td class="px-4 py-3">
                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                    {{ $pengajuan->created_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $pengajuan->created_at->format('H:i') }}</small>
                                </td>
                                <td class="px-4 py-3">
                                    <i class="fas fa-clock text-muted me-2"></i>
                                    {{ $pengajuan->updated_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $pengajuan->updated_at->format('H:i') }}</small>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($pengajuan->status == 'pengajuan')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-file-alt me-1"></i> Baru
                                        </span>
                                    @elseif($pengajuan->status == 'diproses')
                                        <span class="badge bg-info text-dark px-3 py-2">
                                            <i class="fas fa-spinner me-1"></i> Diproses
                                        </span>
                                    @elseif($pengajuan->status == 'selesai')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> Selesai
                                        </span>
                                    @elseif($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.pengajuan.show', $pengajuan) }}" 
                                           class="btn btn-primary btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $pengajuan->id }}"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $pengajuan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                                        <h6>Apakah Anda yakin ingin menghapus pengajuan ini?</h6>
                                                        <p class="text-muted mb-0">
                                                            <strong>{{ $pengajuan->nomor_pengajuan }}</strong>
                                                        </p>
                                                        <small class="text-danger">
                                                            Data yang dihapus tidak dapat dikembalikan!
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.pengajuan.destroy', $pengajuan) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash me-1"></i> Hapus Permanen
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>Tidak ada data pengajuan</h5>
                                        <p>Belum ada pengajuan yang sesuai dengan filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Paginasi -->
        @if($pengajuans->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan <strong>{{ $pengajuans->firstItem() ?? 0 }}</strong> 
                    - <strong>{{ $pengajuans->lastItem() ?? 0 }}</strong> 
                    dari <strong>{{ $pengajuans->total() }}</strong> hasil
                </div>
                <div>
                    {{ $pengajuans->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        /* Nav Pills */
        .nav-pills .nav-link {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link:not(.active):hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }
        
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        /* Table */
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        /* Badge */
        .badge {
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        /* Buttons */
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn {
            border-radius: 0;
        }
        
        .btn-group .btn:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }
        
        .btn-group .btn:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        
        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        /* Input Group */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        
        /* Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
        }
        
        .modal-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
    </style>

    @push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-app-layout>