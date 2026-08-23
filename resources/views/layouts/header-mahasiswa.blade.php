    <!-- ── Mobile Header ─────────────────────────────────── -->
    <header class="m-header fixed top-0 left-0 w-full z-50 shadow-sm" id="mobileHeader">
        <div class="m-header-left">
            <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" style="max-width: 3rem; max-height: 3rem;" />
            <span class="m-header-title">SISLAB FISIKA</span>
        </div>
        <div class="m-header-right">
            <button class="m-header-btn" id="menuToggle" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button class="m-header-btn" id="exitBtn" aria-label="Keluar" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </div>
    </header>

    <!-- ── Dropdown Menu (slide from top) ─────────────── -->
    <div class="menu-overlay" id="menuOverlay"></div>
    <nav class="dropdown-menu" id="dropdownMenu">
        <a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('mahasiswa.tugas.index') }}" class="dropdown-item {{ request()->routeIs('mahasiswa.tugas.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i><span>Tugas</span>
        </a>
        <a href="{{ route('mahasiswa.myclass') }}" class="dropdown-item {{ request()->routeIs('mahasiswa.myclass', 'mahasiswa.kelas.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i><span>Kelas</span>
        </a>
        <a href="#" id="notificationButton" class="dropdown-item {{ request()->routeIs('mahasiswa.notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i><span>Notifikasi</span>
        </a>
        <a href="{{ route('profile') }}" class="dropdown-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i><span>Profile</span>
        </a>
    </nav>
