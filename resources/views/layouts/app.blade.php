{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Bapenda Jateng') - KaiAdmin</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="{{ asset('kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons",
                ],
                urls: ["{{ asset('kaiadmin/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <link rel="stylesheet" href="{{ asset('kaiadmin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/demo.css') }}" />
</head>
<body>
    <div class="wrapper">
        
        {{-- Memanggil Sidebar --}}
        @include('layouts.partials.sidebar')

        <div class="main-panel">

            {{-- Memanggil Header --}}
            @include('layouts.partials.header')

            {{-- Ini adalah "slot" di mana konten spesifik halaman akan dimasukkan --}}
            @yield('content')

            {{-- Memanggil Footer --}}
            @include('layouts.partials.footer')

        </div>

        {{-- ... (kode untuk custom template switcher bisa ditaruh di sini atau dihapus) ... --}}
    </div>
    <script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>

    <script src="{{ asset('kaiadmin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <script src="{{ asset('kaiadmin/js/plugin/chart.js/chart.min.js') }}"></script>

    <script src="{{ asset('kaiadmin/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <script src="{{ asset('kaiadmin/js/plugin/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('kaiadmin/js/kaiadmin.min.js') }}"></script>
    
    <script src="{{ asset('kaiadmin/js/setting-demo.js') }}"></script>
    {{-- <script src="{{ asset('kaiadmin/js/demo.js') }}"></script> --}}

    {{-- Ini adalah "slot" untuk script tambahan dari halaman spesifik --}}
    @stack('scripts')
</body>
</html>