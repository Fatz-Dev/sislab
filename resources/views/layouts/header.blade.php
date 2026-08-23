<header class="topbar sticky top-0 z-20 bg-[var(--page)]" style="padding-bottom: 15px; margin-bottom: 20px;">
    <div class="topbar-intro">
        <button class="mobile-menu-button" id="sidebarToggle" aria-label="Open navigation" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <div class="greeting">
            <h1>Hello {{ Auth::user()->name }}</h1>
            <p>
                @php
                    $hour = now()->format('H');
                @endphp
                @if ($hour < 12) Selamat Pagi
                @elseif ($hour < 15) Selamat Siang
                @elseif ($hour < 18) Selamat Sore
                @else Selamat Malam
                @endif
            </p>
            <div class="live-clock" id="liveClock">
                <i class="bi bi-clock"></i>
                <span id="liveClockTime">--:--:--</span>
            </div>
            <script>
                (function(){
                    const el = document.getElementById('liveClockTime');
                    function tick(){
                        const n = new Date();
                        el.textContent = n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
                    }
                    tick();
                    setInterval(tick, 1000);
                })();
            </script>
        </div>
        <button class="icon-button notification-button" id="notificationButton" aria-label="Notifications">
            <span aria-hidden="true"><i class="bi bi-bell"></i></span>
        </button>
        
        <div class="relative flex items-center">
            <button class="profile-button" id="profileButton" aria-expanded="false">
                @if (Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}" />
                @else
                    <div style="width:32px;height:32px;border-radius:50%;background:#006EC4;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <span><strong>{{ Auth::user()->name }}</strong><small>{{ ucfirst(Auth::user()->role) }}</small></span>
                <b aria-hidden="true">⌄</b>
            </button>
            <div class="profile-menu" id="profileMenu" style="top: 100%; right: 0; margin-top: 8px;">
                <button onclick="window.location.href='{{ route('profile') }}'" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;cursor:pointer;font:inherit;color:inherit;">My profile</button>
                @if(Auth::user()->role === 'admin')
                <button onclick="window.location.href='{{ route('admin.settings.index') }}'" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;cursor:pointer;font:inherit;color:inherit;">Account settings</button>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;cursor:pointer;font:inherit;color:inherit;">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

