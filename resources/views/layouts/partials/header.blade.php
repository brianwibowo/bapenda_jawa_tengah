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
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="notification" id="notifBadge">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <style>
                        .notif-box {
                            width: 320px !important;
                            max-width: 100vw;
                        }
                        .notif-center a {
                            display: flex !important;
                            align-items: flex-start;
                            padding: 10px 15px !important;
                        }
                        .notif-content {
                            flex: 1;
                            min-width: 0;
                            padding-left: 12px;
                        }
                        .notif-content .block {
                            display: block;
                            white-space: normal !important;
                            word-wrap: break-word;
                            line-height: 1.4;
                            margin-bottom: 4px;
                        }
                    </style>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">
                                Anda memiliki <span id="notifCountText">{{ Auth::user()->unreadNotifications->count() }}</span> notifikasi baru
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <a href="#" id="markAsReadBtn" class="float-end small text-primary">Tandai Semua Dibaca</a>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                        <a href="{{ $notification->data['url'] ?? '#' }}" class="{{ $notification->read_at ? '' : 'bg-light' }}">
                                            <div class="notif-icon notif-primary"> <i class="fa fa-info-circle"></i> </div>
                                            <div class="notif-content">
                                                <span class="block">
                                                    {{ $notification->data['message'] ?? 'Aktivitas Baru' }}
                                                </span>
                                                <span class="time">{{ $notification->created_at->diffForHumans() }}</span> 
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-3 text-center text-muted">Belum ada notifikasi.</div>
                                    @endforelse
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const markAsReadBtn = document.getElementById('markAsReadBtn');
        if (markAsReadBtn) {
            markAsReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('{{ route("notifications.markAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('notifBadge');
                        if (badge) badge.remove();
                        const countText = document.getElementById('notifCountText');
                        if (countText) countText.innerText = '0';
                        markAsReadBtn.remove();
                        
                        // remove bg-light class from all unread items
                        document.querySelectorAll('.notif-center a.bg-light').forEach(el => {
                            el.classList.remove('bg-light');
                        });
                    }
                });
            });
        }
    });
</script>