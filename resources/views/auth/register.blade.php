<x-guest-layout>

    <style>
        :root {
            --clr-navy: #0f2342;
            --clr-blue: #1a5fd4;
            --clr-blue-lt: #eef4ff;
            --clr-border: #d6dde8;
            --clr-muted: #6b7a90;
            --clr-error: #c0392b;
            --clr-success: #1a7a4e;
            --radius: 6px;
            --font: 'Segoe UI', system-ui, sans-serif;
        }

        .reg-wrap {
            font-family: var(--font);
            color: var(--clr-navy);
        }

        /* ── Header ── */
        .reg-header {
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1.5px solid var(--clr-border);
        }

        .reg-header__badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--clr-blue);
            background: var(--clr-blue-lt);
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: .6rem;
        }

        .reg-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--clr-navy);
            margin: 0 0 .25rem;
            letter-spacing: -.01em;
        }

        .reg-header p {
            font-size: .8rem;
            color: var(--clr-muted);
            margin: 0;
        }

        /* ── Form fields ── */
        .reg-field {
            margin-bottom: 1rem;
        }

        .reg-field label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: var(--clr-muted);
            margin-bottom: .35rem;
        }

        .reg-field .form-control,
        .reg-field .form-select {
            border: 1.5px solid var(--clr-border);
            border-radius: var(--radius);
            padding: .55rem .75rem;
            font-size: .875rem;
            color: var(--clr-navy);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
            width: 100%;
        }

        .reg-field .form-control:focus,
        .reg-field .form-select:focus {
            border-color: var(--clr-blue);
            box-shadow: 0 0 0 3px rgba(26, 95, 212, .12);
            outline: none;
        }

        .reg-field .form-control::placeholder {
            color: #b0bac9;
        }

        .reg-field .form-control.is-invalid,
        .reg-field .form-select.is-invalid {
            border-color: var(--clr-error);
        }

        .reg-field .invalid-feedback {
            font-size: .75rem;
            color: var(--clr-error);
            margin-top: .3rem;
        }

        .pw-hint {
            font-size: .75rem;
            margin-top: .3rem;
            min-height: 1rem;
        }

        .pw-hint.match {
            color: var(--clr-success);
        }

        .pw-hint.nomatch {
            color: var(--clr-error);
        }

        /* ── Checkboxes ── */
        .reg-check {
            display: flex;
            align-items: flex-start;
            gap: .55rem;
            margin-bottom: .6rem;
        }

        .reg-check input[type="checkbox"] {
            width: 15px;
            height: 15px;
            margin-top: 2px;
            flex-shrink: 0;
            accent-color: var(--clr-blue);
            border-radius: 3px;
        }

        .reg-check label {
            font-size: .8rem;
            color: var(--clr-navy);
            line-height: 1.45;
            cursor: pointer;
        }

        .reg-check .invalid-feedback {
            font-size: .75rem;
            color: var(--clr-error);
        }

        /* ── Divider before footer ── */
        .reg-sep {
            border: none;
            border-top: 1px solid var(--clr-border);
            margin: 1.25rem 0;
        }

        /* ── Footer row ── */
        .reg-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .reg-footer__login {
            font-size: .8rem;
            color: var(--clr-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .reg-footer__login:hover {
            text-decoration: underline;
        }

        .btn-register {
            background: var(--clr-blue);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            padding: .55rem 1.5rem;
            font-size: .875rem;
            font-weight: 700;
            letter-spacing: .01em;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }

        .btn-register:hover {
            background: #1450b8;
        }

        .btn-register:active {
            transform: scale(.98);
        }
    </style>

    <div class="reg-wrap">

        {{-- Header --}}
        <div class="reg-header text-center">
            <span class="reg-header__badge">Pendaftaran</span>
            <h3>Daftar Akun Wajib Pajak</h3>
            <p>Lengkapi data untuk membuat akun baru</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="reg-field">
                <label for="name">Nama Lengkap</label>
                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                    value="{{ old('name') }}" placeholder="Nama sesuai identitas" required autofocus
                    autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="reg-field">
                <label for="email">Alamat Email</label>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                    value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nomor HP (WhatsApp) --}}
            <div class="reg-field">
                <label for="no_hp">Nomor HP (WhatsApp)</label>
                <input id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" type="text" name="no_hp"
                    value="{{ old('no_hp') }}" placeholder="081234567890" required>
                @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Domisili --}}
            <div class="reg-field">
                <label for="domisili_regency_id">Domisili (Kab/Kota Jawa Tengah)</label>
                <select id="domisili_regency_id" name="domisili_regency_id"
                    class="form-select @error('domisili_regency_id') is-invalid @enderror" required>
                    <option value="">— Pilih Domisili —</option>
                    @foreach($regencies as $regency)
                        <option value="{{ $regency->id }}" @selected(old('domisili_regency_id') === $regency->id)>
                            {{ $regency->name }}
                        </option>
                    @endforeach
                </select>
                @error('domisili_regency_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="reg-field">
                <label for="password">Password</label>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                    name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="reg-field" style="margin-bottom: 1.25rem;">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                    name="password_confirmation" placeholder="Ketik ulang password" required
                    autocomplete="new-password">
                <span id="pw-hint" class="pw-hint"></span>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            <div class="reg-check" style="margin-bottom: 1.25rem;">
                <input class="@error('terms_law') is-invalid @enderror" type="checkbox" value="1" id="terms_law"
                    name="terms_law" @checked(old('terms_law'))>
                <div>
                    <label for="terms_law">
                        Saya bersedia mengikuti ketentuan perundang-undangan yang berlaku.
                    </label>
                    @error('terms_law')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="reg-sep">

            {{-- Footer --}}
            <div class="reg-footer">
                <a href="{{ route('login') }}" class="reg-footer__login">
                    Sudah punya akun? Masuk
                </a>
                <button type="submit" class="btn-register">
                    Daftar Sekarang
                </button>
            </div>

        </form>
    </div>

    <script>
        (function () {
            const pw = document.getElementById('password');
            const conf = document.getElementById('password_confirmation');
            const hint = document.getElementById('pw-hint');

            function check() {
                if (!conf.value) { hint.textContent = ''; hint.className = 'pw-hint'; return; }
                const match = pw.value === conf.value;
                hint.textContent = match ? '✓ Password sesuai.' : '✗ Password belum sesuai.';
                hint.className = 'pw-hint ' + (match ? 'match' : 'nomatch');
            }

            pw.addEventListener('input', check);
            conf.addEventListener('input', check);
        })();
    </script>

</x-guest-layout>