<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-bold">Alamat Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="mt-2 text-warning small">
                Email Anda belum diverifikasi.
                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-warning fw-bold">Kirim
                    ulang email verifikasi.</button>
            </div>
            @if (session('status') === 'verification-link-sent')
                <div class="mt-2 text-success small fw-bold">
                    Tautan verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif
        @endif
    </div>

    <div class="mb-3">
        <label for="jabatan" class="form-label fw-bold">Jabatan</label>
        <input id="jabatan" name="jabatan" type="text" class="form-control bg-light"
            value="{{ old('jabatan', $user->jabatan) }}" readonly>
        <small class="text-muted">Hubungi admin untuk mengubah jabatan Anda.</small>
    </div>

    <div class="mb-3">
        <label for="unit_kerja" class="form-label fw-bold">Unit Kerja</label>
        <input id="unit_kerja" name="unit_kerja" type="text" class="form-control bg-light"
            value="{{ old('unit_kerja', $user->unit_kerja) }}" readonly>
        <small class="text-muted">Hubungi admin untuk mengubah lokasi unit kerja Anda.</small>
    </div>

    <div class="mb-4">
        <label for="profile_photo" class="form-label fw-bold">Foto Profil (Opsional)</label>
        <input id="profile_photo" name="profile_photo" type="file"
            class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
        @error('profile_photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan Profil</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success fw-bold small"><i class="fas fa-check-circle me-1"></i> Tersimpan.</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
    @csrf
</form>