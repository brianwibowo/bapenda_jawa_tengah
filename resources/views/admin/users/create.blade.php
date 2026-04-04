<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Tambah Pengguna</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                Kembali</a>
        </div>
    </x-slot>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control"
                            placeholder="Misal: Kepala Divisi Keuangan..." value="{{ old('jabatan') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Unit Kerja (Instansi)</label>
                        <select name="unit_kerja" id="unit_kerja_select" class="form-select" required
                            onchange="toggleNewUnitKerja(this.value)">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}
                                </option>
                            @endforeach
                            <option value="Lainnya" {{ old('unit_kerja') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                (Buat Baru...)</option>
                        </select>
                        <div id="new_unit_kerja_wrapper"
                            class="mt-2 {{ old('unit_kerja') == 'Lainnya' ? '' : 'd-none' }}">
                            <input type="text" name="new_unit_kerja" id="new_unit_kerja_input" class="form-control"
                                placeholder="Ketik nama instansi baru..." value="{{ old('new_unit_kerja') }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Akses Group (Wewenang Sistem)</label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        id="role_{{ $role->id }}">
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleNewUnitKerja(val) {
        const wrapper = document.getElementById('new_unit_kerja_wrapper');
        const input = document.getElementById('new_unit_kerja_input');
        if (val === 'Lainnya') {
            wrapper.classList.remove('d-none');
            input.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('d-none');
            input.removeAttribute('required');
            input.value = '';
        }
    }
</script>