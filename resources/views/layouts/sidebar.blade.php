<aside class="sidebar" aria-label="Primary navigation">
    <div class="brand" aria-label="Inventory home">
        {{-- <div class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></div> --}}
        <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" alt="" style="width: 50px; margin-right: 10px;">
        <span class="brand-name">{{ env('APP_NAME') }}</span>
        <button class="sidebar-close" id="sidebarClose" aria-label="Close navigation">×</button>
    </div>

    <nav class="nav-list">
        <ul class="nav-menu">
            {{-- Dashboard — tampil untuk semua role --}}
            <li>
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="nav-item {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span><span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
                {{-- ═══ ADMIN: Semua menu ═══ --}}

                <li class="nav-group {{ request()->is('*/ruangan*') || request()->is('*/barang*') ? 'open' : '' }}">
                    <button class="nav-item nav-toggle" type="button" aria-expanded="false">
                        <span class="nav-icon"><i class="bi bi-database"></i></span>
                        <span>Data Lab</span>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </button>
                    <ul class="nav-submenu">
                        <li>
                            <a href="{{ route('admin.ruangan.index') }}" class="nav-subitem {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                                <i class="bi bi-building"></i><span>Ruang Lab</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.barang.index') }}" class="nav-subitem {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                                <i class="bi bi-tools"></i><span>Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-subitem">
                                <i class="bi bi-tag"></i><span>Kategori</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-group {{ request()->is('*/users/*') ? 'open' : '' }}">
                    <button class="nav-item nav-toggle" type="button" aria-expanded="false">
                        <span class="nav-icon"><i class="bi bi-database"></i></span>
                        <span>Users</span>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </button>
                    <ul class="nav-submenu">
                        <li>
                            <a href="{{ route('admin.users.admin') }}" class="nav-subitem">
                                <i class="bi bi-person-fill"></i><span>Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.laboran') }}" class="nav-subitem">
                                <i class="bi bi-person-video3"></i><span>Laboran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.dosen') }}" class="nav-subitem">
                                <i class="bi bi-person-workspace"></i><span>Dosen</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.mahasiswa') }}" class="nav-subitem">
                                <i class="bi bi-person-raised-hand"></i><span>Mahasiswa</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span><span>Kelas Praktikum</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.enrollments.index') }}" class="nav-item {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-person-check"></i></span><span>Approval Pendaftaran</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.jadwal.index') }}" class="nav-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-calendar"></i></span><span>Lihat Jadwal</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-item {{ request()->routeIs('*.laporan.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-briefcase"></i></span><span>Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-gear"></i></span><span>Settings</span>
                    </a>
                </li>

            @elseif(auth()->user()->role === 'laboran')
                {{-- ═══ LABORAN: Data Lab + Laporan ═══ --}}

                {{-- Data Lab (collapsible) --}}
                <li class="nav-group {{ request()->is('*/master-data/*') ? 'open' : '' }}">
                    <button class="nav-item nav-toggle" type="button" aria-expanded="false">
                        <span class="nav-icon"><i class="bi bi-database"></i></span>
                        <span>Data Lab</span>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </button>
                    <ul class="nav-submenu">
                        <li>
                            <a href="#" class="nav-subitem">
                                <i class="bi bi-building"></i><span>Ruang Lab</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-subitem">
                                <i class="bi bi-tools"></i><span>Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-subitem">
                                <i class="bi bi-tag"></i><span>Kategori</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('laboran.kelas.index') }}" class="nav-item {{ request()->routeIs('laboran.kelas.*', 'laboran.jadwal.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span><span>Kelas Praktikum</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-item {{ request()->routeIs('*.laporan.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-briefcase"></i></span><span>Laporan</span>
                    </a>
                </li>

            @elseif(auth()->user()->role === 'dosen')
                {{-- ═══ DOSEN: Kelas + Laporan ═══ --}}

                <li>
                    <a href="{{ route('dosen.kelas.index') }}" class="nav-item {{ request()->routeIs('dosen.kelas.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span><span>Kelas Praktikum</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-item {{ request()->routeIs('*.laporan.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-briefcase"></i></span><span>Laporan</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <div class="theme-switch" role="group" aria-label="Color theme">
        <button class="theme-option selected" data-theme="light"><span class="theme-icon">☼</span>Light</button>
        <button class="theme-option" data-theme="dark"><span class="theme-icon">◐</span>Dark</button>
    </div>
</aside>
