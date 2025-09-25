{{-- resources/views/layouts/partials/header.blade.php --}}
<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
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

            {{-- ... Kode Navbar lainnya bisa ditambahkan di sini jika ada ... --}}

            <!-- INI BAGIAN BARU: MENU PENGGUNA DI POJOK KANAN -->
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            {{-- Ganti dengan gambar profil jika ada, jika tidak pakai inisial --}}
                            <img src="https://placehold.co/50x50/F5F5DC/000000?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" alt="..." class="avatar-img rounded-circle"/>
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="https://placehold.co/100x100/F5F5DC/000000?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" alt="image profile" class="avatar-img rounded"/>
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a>
                                <div class="dropdown-divider"></div>
                                
                                {{-- Form untuk Logout --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>

                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
            <!-- AKHIR BAGIAN BARU -->

        </div>
    </nav>
</div>