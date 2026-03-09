<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Detail Akses Group: {{ $role->name }}</h2>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-key me-2"></i>Daftar Hak Akses (Permissions)</h5>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-secondary p-2">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Akses group ini belum memiliki hak akses (permissions) apapaun.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Anggota (Pengguna)</h5>
                </div>
                <div class="card-body">
                    @if($role->users && $role->users->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($role->users as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $user->unit_kerja ?? 'Admin' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Belum ada pengguna yang memiliki akses grup ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>