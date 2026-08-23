    <!-- ── Bottom Navigation ─────────────────────────────── -->
    <nav class="bottom-nav" id="bottomNav">
        <div class="nav-brand">
            <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="Logo" class="nav-brand-logo" />
            <span class="nav-brand-title">SISLAB FISIKA</span>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('mahasiswa.tugas.index') }}" class="bottom-nav-item {{ request()->routeIs('mahasiswa.tugas.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Tugas</span>
        </a>
        <a href="{{ route('mahasiswa.myclass') }}" class="bottom-nav-item {{ request()->routeIs('mahasiswa.myclass', 'mahasiswa.kelas.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i>
            <span>Kelas</span>
        </a>
        <a href="{{ route('profile') }}" class="bottom-nav-item mr-2 {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
            <button class="notification-btn" id="notificationButton" aria-label="Notifications">
                <i class="bi bi-bell text-white"></i>
                <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
            </button>
        <a href="{{ route('logout') }}" class="nav-signout" id="desktopExit" title="Sign out" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </nav>