{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<div class="sidebar" data-background-color="dark">

    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand" height="20" />
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
                    <h4 class="text-section">Manajemen</h4>
                </li>

                {{-- == MENU UNTUK ROLE "PENULIS" (DIPERBARUI) == --}}
                @role('penulis')
                    {{-- Hanya satu menu untuk 'pengajuan.index' (Riwayat/Daftar Bundel) --}}
                    <li class="nav-item {{ request()->routeIs('pengajuan.*') || request()->routeIs('kendaraan.*') ? 'active' : '' }}">
                        <a href="{{ route('pengajuan.index') }}">
                            <i class="fas fa-folder-open"></i>
                            <p>Berkas Pengajuan Saya</p>
                        </a>
                    </li>
                    
                    {{-- Link "Buat Pengajuan Berkas" (route 'pengajuan.create') DIHAPUS --}}
                @endrole

                {{-- == MENU UNTUK ROLE "ADMIN" & "SUPERADMIN" (Tetap sama) == --}}
                @role('admin|superadmin')
                    <li class="nav-item {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pengajuan.index') }}">
                            <i class="fas fa-tasks"></i>
                            <p>Manajemen Pengajuan</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users-cog"></i>
                            <p>Manajemen Pengguna</p>
                        </a>
                    </li>
                @endrole

            </ul>
        </div>
    </div>
</div>