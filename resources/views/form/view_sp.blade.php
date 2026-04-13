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
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                min-height: 100vh;
                margin: 0;
                background: #f7f9fc;
            }
            .middle_body {
                margin: 64px auto;
                max-width: 760px;
                text-align: center;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 48px 36px;
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            }
            .middle_body i {
                display: block;
                font-size: 64px;
                color: #2563eb;
                margin-bottom: 20px;
            }
            .middle_body p {
                margin: 0;
                font-size: 20px;
                line-height: 1.5;
                color: #334155;
            }
        </style>
    </head>
    <div class="middle_body">
        <i class="fas fa-solid fa-file"></i>
        <p>This is a sample PDF document generated from a Blade template.</p>
    </div>
    <body>

    </body>
</html>