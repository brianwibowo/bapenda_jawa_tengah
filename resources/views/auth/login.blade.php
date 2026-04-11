<x-guest-layout>
    <div class="container container-login" style="width: 100%; max-width: 450px; padding: 0 15px;">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-2">Masuk ke Akun</h2>
            <p class="text-muted small">Silakan isi kredensial Anda untuk melanjutkan</p>
        </div>
        
        <x-auth-session-status class="mb-4 text-success fw-bold p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-25" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label fw-bold small text-dark">ALAMAT EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@bapenda.go.id">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label fw-bold small text-dark mb-0">KATA SANDI</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">
                            Lupa Password?
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                @error('password')
                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted small" for="remember">
                        Ingat sesi login
                    </label>
                </div>
            </div>
            
            <div>
                <button type="submit" class="btn btn-primary d-block w-100 shadow-sm py-2 fw-bold text-white fs-6">
                    Masuk Sekarang <i class="fas fa-sign-in-alt ms-1"></i>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>