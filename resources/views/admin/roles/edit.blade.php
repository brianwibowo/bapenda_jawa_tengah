<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Ubah Akses Group (Role)</h2>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nama Grup Akses</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Hak Akses (Permissions) untuk grup ini:</label>
                    @foreach($permissions as $group => $perms)
                        @php $groupLabel = $group ?: 'Fitur Lainnya (Belum Dikategorikan)'; @endphp
                        <div class="card mb-3 shadow-none border">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold border-start border-3 border-primary ps-2">{{ $groupLabel }}</h6>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-2">
                                     @foreach($perms as $permission)
                                         <div class="col-xl-4 col-md-6 col-12 mb-2">
                                             <div class="form-check d-flex align-items-start gap-2 ps-0">
                                                 <input class="form-check-input ms-0 mt-1 flex-shrink-0" type="checkbox" name="permissions[]"
                                                     value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                                     {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                 <label class="form-check-label lh-sm text-wrap" for="perm_{{ $permission->id }}">
                                                     {{ $permission->alias }}
                                                 </label>
                                             </div>
                                         </div>
                                     @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Akses Group</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
