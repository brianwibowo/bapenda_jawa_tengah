<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold mb-0">Manajemen Pengguna</h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex w-50">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama, email, atau unit kerja..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                    Tambah User
                </a>
            </div>


            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Akses Group</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="fw-semibold">{{ $user->jabatan ?? '-' }}</span></td>
                                <td><span class="badge bg-info text-dark">{{ $user->unit_kerja ?? 'N/A' }}</span></td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span
                                            class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $role->name)) }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm"
                                        title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada data Pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>