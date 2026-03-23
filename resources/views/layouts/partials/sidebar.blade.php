{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<div class="sidebar" data-background-color="dark">

    <div class="sidebar-logo">
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

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                {{-- Menu Dashboard (Untuk Semua Role) --}}
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengajuan</h4>
                </li>

                {{-- == MENU BERBASIS PERMISSION == --}}
                {{-- == MENU UNTUK MASYARAKAT (Yang Punya Akses Pengajuan) == --}}
                @can('view_menu_buat_pengajuan')
                <li class="nav-item {{ request()->routeIs('pengajuan.create') ? 'active' : '' }}">
                    <a href="{{ route('pengajuan.create') }}">
                        <i class="fas fa-plus-circle"></i>
                        <p>Buat Pengajuan</p>
                    </a>
                </li>
                @endcan

                @can('view_own_sk')
                <li class="nav-item {{ request()->routeIs('sk.index') || request()->routeIs('sk.show') ? 'active' : '' }}">
                    <a href="{{ route('sk.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <p>Daftar SKP</p>
                    </a>
                </li>
                @endcan

                @can('create_sk')
                <li class="nav-item {{ request()->routeIs('sk.create') ? 'active' : '' }}">
                    <a href="{{ route('sk.create') }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Buat SKP</p>
                    </a>
                </li>
                @endcan

                @can('view_menu_daftar_pengajuan')
                <li class="nav-item {{ request()->routeIs('pengajuan.index') ? 'active' : '' }}">
                    <a href="{{ route('pengajuan.index') }}">
                        <i class="fas fa-list-alt"></i>
                        <p>Daftar Pengajuan</p>
                    </a>
                </li>
                @endcan

                {{-- == MENU UNTUK PENGELOLA PENGAJUAN (Semua Instansi) == --}}
                @can('view_menu_manajemen_pengajuan')
                    <li class="nav-item {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengajuan.index') }}">
                            <i class="fas fa-tasks"></i>
                            <p>Manajemen Pengajuan</p>
                        </a>
                    </li>
                @endcan

                {{-- == MENU UNTUK SUPERADMIN / ADMIN UTAMA (RBAC) == --}}
                @canany(['view_menu_hak_akses', 'view_menu_akses_group', 'view_menu_pengguna'])
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">USER MANAGEMENT</h4>
                    </li>

                    @can('view_menu_hak_akses')
                    <li class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fas fa-user-lock"></i>
                            <p>Hak Akses</p>
                        </a>
                    </li>
                    @endcan

                    @can('view_menu_akses_group')
                    <li class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fas fa-user-shield"></i>
                            <p>Akses Group</p>
                        </a>
                    </li>
                    @endcan

                    @can('view_menu_pengguna')
                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users-cog"></i>
                            <p>Pengguna</p>
                        </a>
                    </li>
                    @endcan
                @endcanany

            </ul>
        </div>
    </div>
</div>