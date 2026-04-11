<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>{{ $header ?? 'Dashboard' }} - Bapenda</title>
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
        <style>
            @page { margin: 2cm; }
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .middle_body {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <div class="middle_body">
        <i class="fas fa-solid fa-file fa-2x"></i>This is a sample PDF document generated from a Blade template.
    </div>
    <body>

    </body>
</html>