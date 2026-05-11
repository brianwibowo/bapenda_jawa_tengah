<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-1">Lupa Password</h3>
        <p class="text-muted small mb-0">
            Masukkan email akun Anda. Jika terdaftar, kami akan mengirim tautan reset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4 fw-bold p-3 rounded border border-success border-opacity-25" style="background-color: #d1e7dd; color: #0f5132;" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-bold small text-dark">ALAMAT EMAIL</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror"
                type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="text-decoration-none small text-primary fw-semibold">
                Kembali ke login
            </a>
            <button type="submit" class="btn btn-primary fw-bold px-4">
                Kirim Link Reset
            </button>
        </div>
    </form>
</x-guest-layout>
