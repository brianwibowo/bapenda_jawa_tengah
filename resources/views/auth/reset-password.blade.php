<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-1">Reset Password</h3>
        <p class="text-muted small mb-0">Masukkan password baru Anda dua kali</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-bold small text-dark">ALAMAT EMAIL</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold small text-dark">PASSWORD BARU</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                type="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-bold small text-dark">KONFIRMASI PASSWORD BARU</label>
            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                type="password" name="password_confirmation" required autocomplete="new-password">
            <small id="password-match-hint" class="text-muted d-block mt-1"></small>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary fw-bold px-4">
                Simpan Password Baru
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const hint = document.getElementById('password-match-hint');

            function updatePasswordHint() {
                if (!confirmation.value) {
                    hint.textContent = '';
                    return;
                }

                if (password.value === confirmation.value) {
                    hint.textContent = 'Konfirmasi password cocok.';
                    hint.className = 'text-success d-block mt-1';
                } else {
                    hint.textContent = 'Konfirmasi password belum sama.';
                    hint.className = 'text-danger d-block mt-1';
                }
            }

            password.addEventListener('input', updatePasswordHint);
            confirmation.addEventListener('input', updatePasswordHint);
        });
    </script>
</x-guest-layout>
