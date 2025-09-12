{{-- resources/views/layouts/partials/header.blade.php --}}
<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand" height="20"/>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        </div>
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            {{-- ... Kode Navbar lainnya, pastikan path gambar benar ... --}}
            {{-- Contoh: <img src="{{ asset('kaiadmin/img/profile.jpg') }}" ... /> --}}
        </div>
    </nav>
    </div>