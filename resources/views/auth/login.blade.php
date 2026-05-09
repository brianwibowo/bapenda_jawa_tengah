<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-1">Masuk ke Akun</h3>
        <p class="text-muted small">Silakan isi kredensial Anda</p>
    </div>

    <x-auth-session-status
        class="mb-4 fw-bold p-3 rounded border border-success border-opacity-25"
        style="background-color: #d1e7dd; color: #0f5132;"
        :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-bold small text-dark">ALAMAT EMAIL</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                <input id="email" type="email"
                    class="form-control bg-white border-start-0 ps-0 @error('email') is-invalid @enderror" name="email"
                    :value="old('email')" required autofocus autocomplete="username" placeholder="nama@bapenda.go.id">
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-bold small text-dark mb-0">KATA SANDI</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-decoration-none small text-primary fw-semibold hover-primary">
                        Lupa Password?
                    </a>
                @endif
            </div>
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                <input id="password" type="password"
                    class="form-control bg-white border-start-0 ps-0 @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
            @enderror
        </div>

        @if (Route::has('register'))
            <div class="text-end mb-3">
                <a href="{{ route('register') }}" class="text-decoration-none small text-primary fw-semibold hover-primary">
                    Daftar Akun Wajib Pajak
                </a>
            </div>
        @endif

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted small" for="remember">
                    Ingat sesi login
                </label>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary shadow-sm d-block w-100 py-2 fw-bold text-white"
                style="background: linear-gradient(to right, #0D8ABC 0%, #00509E 100%); border: none; border-radius: 8px;">
                Masuk Sekarang <i class="fas fa-sign-in-alt ms-1"></i>
            </button>
        </div>
    </form>
</x-guest-layout>