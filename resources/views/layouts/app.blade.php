<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title ?? (isset($header) ? trim(strip_tags($header)) : 'Aplikasi Bapenda') }}</title>
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
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/kaiadmin.min.css') }}" />
    {{-- Custom overrides: sidebar colors per-role & notification UI --}}
    <link rel="stylesheet" href="{{ asset('kaiadmin/css/bapenda.css') }}" />
</head>

<body>
    <div class="wrapper">
        @include('layouts.partials.sidebar')

        <div class="main-panel">
            @include('layouts.partials.header')

            <div class="container">
                <div class="page-inner">
                    @if (isset($header))
                        <div class="page-header" style="display:block!important;">
                            {!! $header !!}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/plugin/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/js/kaiadmin.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && typeof jQuery.fn.select2 !== 'undefined') {
                jQuery('.searchable-select').select2({
                    theme: 'bootstrap',
                    width: '100%',
                    placeholder: 'Pilih...',
                    allowClear: true,
                });
            }
        });
    </script>
</body>

</html>