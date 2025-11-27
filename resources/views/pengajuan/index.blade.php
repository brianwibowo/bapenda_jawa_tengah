<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Daftar Bundel Pengajuan</h2>
    </x-slot>

    {{-- Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
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
                               href="{{ route('pengajuan.index', array_merge(request()->except('page','status','q','per_page'), ['status' => null])) }}">
                                <i class="fas fa-list me-1"></i> Semua
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'pengajuan' ? 'active' : '' }}" 
                               href="{{ route('pengajuan.index', array_merge(request()->except('page','status'), ['status' => 'pengajuan'])) }}">
                                <i class="fas fa-file-alt me-1"></i> Baru
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'diproses' ? 'active' : '' }}" 
                               href="{{ route('pengajuan.index', array_merge(request()->except('page','status'), ['status' => 'diproses'])) }}">
                                <i class="fas fa-spinner me-1"></i> Diproses
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'selesai' ? 'active' : '' }}" 
                               href="{{ route('pengajuan.index', array_merge(request()->except('page','status'), ['status' => 'selesai'])) }}">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link {{ request('status') == 'ditolak' ? 'active' : '' }}" 
                               href="{{ route('pengajuan.index', array_merge(request()->except('page','status'), ['status' => 'ditolak'])) }}">
                                <i class="fas fa-times-circle me-1"></i> Ditolak
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Search and Per Page -->
                <div class="col-12">
                    <form id="filterForm" method="GET" action="{{ route('pengajuan.index') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="row g-2">
                            <div class="col-md-9 col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input id="searchInput" 
                                           type="text" 
                                           name="q" 
                                           class="form-control" 
                                           placeholder="Cari berdasarkan Nomor Pengajuan atau Nama..." 
                                           value="{{ request('q') }}">
                                    <button id="searchBtn" class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                    @foreach ([10,25,50,100] as $n)
                                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                                            {{ $n }} / halaman
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Debounce helper
        function debounce(fn, wait) {
            let t;
            return function(...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), wait);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchInput');
            const form = document.getElementById('filterForm');
            const submitDebounced = debounce(() => form.submit(), 400);
            input.addEventListener('input', submitDebounced);
        });
    </script>
    @endpush

    <!-- Tabel Daftar Bundel Pengajuan -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-folder-open me-2"></i>Daftar Bundel Pengajuan
                </h5>
                <span class="badge bg-primary fs-6">
                    Total: {{ $pengajuans->total() }} pengajuan
                </span>
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
                                    @if($pengajuan->status == 'draft')
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="fas fa-pencil-alt me-1"></i> Draft
                                        </span>
                                    @elseif($pengajuan->status == 'pengajuan')
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
                                    <a href="{{ route('pengajuan.show', $pengajuan) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
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
        
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
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
        
        .badge {
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
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
    </style>
</x-app-layout>