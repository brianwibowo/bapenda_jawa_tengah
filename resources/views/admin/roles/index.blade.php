<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Kelola Akses Group (Roles)</h2>
    </x-slot>

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

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form method="GET" action="{{ route('admin.roles.index') }}" class="d-flex w-50">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari Akses Group..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Grup Akses
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Grup (Role)</th>
                            <th>Total Permission</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                            <tr>
                                <td>{{ $roles->firstItem() + $index }}</td>
                                <td><strong class="text-primary">{{ $role->name }}</strong></td>
                                <td><span class="badge bg-info">{{ $role->permissions->count() }} Permission</span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus role ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus" {{ $role->name === 'superadmin' ? 'disabled' : '' }}><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data Akses Group.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $roles->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>