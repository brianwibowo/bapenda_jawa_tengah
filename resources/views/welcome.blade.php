<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Selamat Datang - Bapenda</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="{{ asset('kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
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
        body {
            background-color: #f8f9fa;
        }
        .welcome-container {
            max-width: 800px;
            margin-top: 10vh;
        }
    </style>
</head>
<body>
    <div class="container welcome-container">
        <div class="card shadow-lg">
            <div class="card-body p-5 text-center">
                <img src="{{ asset('kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="logo" height="40" class="mb-4">
                <h1 class="card-title fw-bold">Sistem Monitoring Bapenda</h1>
                <p class="card-text text-muted">Sebuah sistem terintegrasi untuk manajemen dan monitoring pengajuan berkas.</p>
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Admin / Superadmin</h5>
                        <p>Akses penuh untuk mengelola pengguna, memvalidasi, dan memonitor seluruh proses pengajuan.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">Penulis</h5>
                        <p>Akses untuk membuat dan mengirimkan berkas pengajuan baru serta melihat status pengajuan pribadi.</p>
                    </div>
                </div>

                <div class="mt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Masuk ke Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login untuk Melanjutkan</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>
</html>