<x-guest-layout>
    <div class="container container-login" style="width: 400px;">
        <h3 class="text-center">Silakan Login</h3>
        
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" :value="old('email')" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="float-end">
                            <small>Lupa Password?</small>
                        </a>
                    @endif
                    <div class="position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                     @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group form-action-d-flex mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label m-0" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>
                
                <div class="form-action mb-3">
                    <button type="submit" class="btn btn-primary col-md-12 fw-bold">Login</button>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>