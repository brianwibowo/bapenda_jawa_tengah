<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - Bapenda</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="{{ asset('kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700", "Inter:300,400,500,600,700"] },
            custom: {
                families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('kaiadmin/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
    <style>
        body.login {
            font-family: 'Inter', sans-serif;
        }
        .login-aside-bapenda {
            background: linear-gradient(135deg, #f0f8ff 0%, #e0f2fe 50%, #bae6fd 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }
        .login-aside-bapenda::after {
            content: "";
            position: absolute;
            top: -10%;
            right: -5%;
            width: 50%;
            height: 120%;
            background: rgba(13, 138, 188, 0.05);
            transform: rotate(15deg);
            z-index: 1;
            border-radius: 50px;
        }
        .login-aside-bapenda > div {
            position: relative;
            z-index: 2;
        }
        .bapenda-form-container {
            width: 100%;
            max-width: 450px;
            padding: 0 15px;
        }
    </style>
</head>
<body class="login">
    <div class="wrapper wrapper-login wrapper-login-full p-0">
        <div class="login-aside w-50 login-aside-bapenda d-flex align-items-center">
            <div class="text-start px-4 px-md-5 w-100">
                <div class="mb-3">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm border border-light fw-bold" style="letter-spacing: 1px;"><i class="fas fa-fingerprint me-2"></i> PORTAL RESMI</span>
                </div>
                <h1 class="display-5 fw-bold text-dark mb-4" style="line-height: 1.25;">Sistem Monitoring<br><span style="color: #0D8ABC;">SIP-Hapus</span></h1>
                <p class="lead text-secondary opacity-75 pe-md-4 mb-5" style="font-size: 1.1rem; font-weight: 400; line-height: 1.6;">
                    Platform terintegrasi untuk manajemen data pengajuan kendaraan, surat keputusan, dan log aktivitas yang terpusat dan transparan.
                </p>
                
                <div class="d-flex gap-4 text-start">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 bg-white shadow-sm" style="width: 45px; height: 45px; border: 1px solid #e0f2fe;">
                            <i class="fas fa-bolt" style="color: #0D8ABC;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Layanan Cepat</h6>
                            <small class="text-muted">Proses Otomatis</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 bg-white shadow-sm" style="width: 45px; height: 45px; border: 1px solid #e0f2fe;">
                            <i class="fas fa-shield-alt" style="color: #0D8ABC;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Data Aman</h6>
                            <small class="text-muted">Validasi Berlapis</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center bg-white">
            
            <div class="bapenda-form-container">
                {{ $slot }}
            </div>

            <div class="text-center mt-5">
                <p class="small text-muted mb-0">&copy; {{ date('Y') }} Sistem Monitoring SIP-Hapus</p>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/kaiadmin.min.js') }}"></script>
</body>
</html>