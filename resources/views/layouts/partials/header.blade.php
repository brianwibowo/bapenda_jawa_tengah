{{-- resources/views/layouts/partials/header.blade.php --}}
<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
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
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}"
                                alt="..." class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username d-flex flex-column align-items-start ms-2">
                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                            <span class="badge bg-secondary" style="font-size: 0.65rem;"><i
                                    class="fas fa-id-badge me-1"></i>{{ Auth::user()->hasRole('wajib_pajak') ? 'Wajib Pajak' : (Auth::user()->jabatan ?? 'Pegawai') }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}"
                                            alt="image profile" class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                                        <div class="text-muted small mb-1"><i class="fas fa-id-badge me-1"></i>
                                            {{ Auth::user()->hasRole('wajib_pajak') ? 'Wajib Pajak' : (Auth::user()->jabatan ?? 'Pegawai') }}</div>
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
        </div>
    </nav>
</div>