<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Hak Akses</h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form method="GET" action="{{ route('admin.permissions.index') }}" class="d-flex w-50">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama hak akses..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
            </div>

            <div class="accordion" id="permissionsAccordion">
                @forelse($groupedPermissions as $group => $permissions)
                    @php 
                        $groupLabel = $group ?: 'Fitur Lainnya (Belum Dikategorikan)'; 
                        $groupId = 'group_' . md5($groupLabel);
                    @endphp
                    <div class="accordion-item shadow-sm border mb-3 rounded">
                        <h2 class="accordion-header" id="heading_{{ $groupId }}">
                            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse_{{ $groupId }}" aria-expanded="false" aria-controls="collapse_{{ $groupId }}">
                                <i class="fas fa-folder text-warning me-2 fs-5"></i> 
                                {{ $groupLabel }} 
                                <span class="badge bg-primary ms-3 rounded-pill">{{ $permissions->count() }} Sub-Fitur</span>
                            </button>
                        </h2>
                        <div id="collapse_{{ $groupId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $groupId }}">
                            <div class="accordion-body p-0">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%">&nbsp;</th>
                                            <th style="width: 45%;">Nama Aksi (Readable)</th>
                                            <th style="width: 35%;">Kode Variabel Sistem</th>
                                            <th style="width: 15%;" class="text-center">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            @php
                                                // Human readable transformer: "view_dashboard" -> "View Dashboard"
                                                $humanReadable = $permission->alias;
                                            @endphp
                                            <tr>
                                                <td class="text-end text-muted"><i class="fas fa-angle-right"></i></td>
                                                <td>
                                                    <strong>{{ $humanReadable }}</strong>
                                                </td>
                                                <td><span class="badge bg-secondary px-2 py-1">{{ $permission->name }}</span></td>
                                                <td class="text-center">
                                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus permission {{ $permission->name }} ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fs-3 mb-2"></i><br>
                        Tidak ada data Hak Akses.
                    </div>
                @endforelse
            </div>

            @if($groupedPermissions->hasPages())
                <div class="mt-4">
                    {{ $groupedPermissions->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>