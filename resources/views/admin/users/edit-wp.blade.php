<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0">Edit Wajib Pajak</h2>
            <a href="{{ route('admin.users.wp.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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

            <form method="POST" action="{{ route('admin.users.updateWp', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor HP (WhatsApp)</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" required
                            placeholder="081234567890">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Domisili (Kab/Kota Jawa Tengah)</label>
                    <select name="domisili_regency_id" class="form-select" required>
                        <option value="">— Pilih Domisili —</option>
                        @foreach($regencies as $regency)
                            <option value="{{ $regency->id }}" @selected(old('domisili_regency_id', $user->domisili_regency_id) === $regency->id)>
                                {{ $regency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password Baru (Kosongkan bila tidak ganti)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
