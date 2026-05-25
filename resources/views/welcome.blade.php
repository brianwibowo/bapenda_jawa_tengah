<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Selamat Datang - Bapenda Jateng</title>
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
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .hero-section {
            background: linear-gradient(135deg, #f0f8ff 0%, #e0f2fe 50%, #bae6fd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-bg-shape {
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
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 1);
            border-radius: 20px;
            color: #333333;
            transition: transform 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .glass-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background: linear-gradient(to right, #0D8ABC 0%, #00509E 100%);
            border: none;
            border-radius: 30px;
            padding: 12px 35px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 114, 255, 0.6);
            color: white;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="hero-bg-shape"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7 text-dark text-center text-lg-start mb-5 mb-lg-0">
                    <div class="d-inline-flex bg-white rounded-pill px-4 py-2 mb-4 align-items-center shadow-sm border border-light">
                        <span class="badge bg-primary rounded-pill me-2">BARU</span>
                        <span class="small fw-semibold text-secondary">Sistem v2 Tertata Lengkap</span>
                    </div>
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">Sistem Monitoring <br><span style="color: #0D8ABC;">SIP-Hapus</span></h1>
                    <p class="lead mb-5 text-muted pe-lg-5" style="font-weight: 400;">Platform terintegrasi untuk manajemen data pengajuan kendaraan, surat keputusan, dan log aktivitas yang terpusat, cepat, dan transparan.</p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-custom btn-lg text-white">Buka Dashboard <i class="fas fa-arrow-right ms-2"></i></a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-custom btn-lg text-white">Login Sekarang <i class="fas fa-sign-in-alt ms-2"></i></a>
                        @endauth
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="card glass-card h-100 p-4">
                                <div class="mb-3">
                                    <i class="fas fa-shield-alt fa-2x" style="color: #0D8ABC;"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Aman Terkendali</h4>
                                <p class="small text-muted mb-0">Sistem RBAC mendalam mengatur siapapun yang bisa mencetak dan menambah pengajuan.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card glass-card h-100 p-4 mt-sm-4">
                                <div class="mb-3">
                                    <i class="fas fa-bolt fa-2x" style="color: #f6d365;"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Sangat Cepat</h4>
                                <p class="small text-muted mb-0">Manajemen kendaraan dan status surat diproses hanya dalam hitungan detik.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card glass-card h-100 p-4">
                                <div class="mb-3">
                                    <i class="fas fa-file-pdf fa-2x" style="color: #ff6b6b;"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Automasi Cetak</h4>
                                <p class="small text-muted mb-0">Pembuatan Surat Keputusan & Dokumen Lampiran Pengajuan Otomatis.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card glass-card h-100 p-4 mt-sm-4">
                                <div class="mb-3">
                                    <i class="fas fa-search fa-2x" style="color: #a18cd1;"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Lacak Detail</h4>
                                <p class="small text-muted mb-0">Semua perubahan terekam ke dalam Audit Logs untuk verifikasi penuh.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>