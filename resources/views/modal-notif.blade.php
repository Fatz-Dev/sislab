{{-- ══════════════════════════════════════════════════════════════
     Notification Panel — slide-in from right
     • Tab 1: Semua Notifikasi (sistem / pengumuman)
     • Tab 2: Jadwal Shalat (real-time via Aladhan API)
     ══════════════════════════════════════════════════════════════ --}}

<style>
/* ── Notification Panel Internal CSS ── */
.notif-overlay { position: fixed; inset: 0; z-index: 998; background: rgba(0,0,0,.35); backdrop-filter: blur(4px); opacity: 0; visibility: hidden; transition: opacity .3s ease, visibility .3s ease; }
.notif-overlay.visible { opacity: 1; visibility: visible; }
.notif-panel { position: fixed; top: 0; right: -420px; z-index: 999; width: 400px; max-width: 100vw; height: 100vh; display: flex; flex-direction: column; background: #fff; box-shadow: -8px 0 40px rgba(0,0,0,.15); transition: right .35s cubic-bezier(.4,0,.2,1); }
.notif-panel.open { right: 0; }
.notif-header { padding: 20px 24px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e5e7eb; }
.notif-header h2 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.notif-header h2 i { color: #2563eb; }
.notif-close { width: 36px; height: 36px; display: grid; place-items: center; background: rgba(162,161,168,.1); border-radius: 8px; font-size: 22px; color: #111827; transition: background .2s; border: none; cursor: pointer; }
.notif-close:hover { background: rgba(162,161,168,.25); }
.notif-tabs { padding: 0 24px; display: flex; gap: 4px; border-bottom: 1px solid #e5e7eb; }
.notif-tab { flex: 1; padding: 12px 0; background: none; font-size: 13px; font-weight: 500; color: #6b7280; border: none; border-bottom: 2px solid transparent; transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; }
.notif-tab:hover { color: #111827; }
.notif-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
.notif-tab-content { display: none; flex: 1; overflow-y: auto; }
.notif-tab-content.active { display: flex; flex-direction: column; }
.notif-list { display: flex; flex-direction: column; padding: 12px; gap: 4px; }
.notif-item { display: flex; gap: 14px; padding: 16px; border-radius: 12px; transition: background .2s; }
.notif-item:hover { background: #f9fafb; }
.notif-item.unread { background: rgba(37,99,235,.04); }
.notif-item-icon { width: 40px; height: 40px; flex: 0 0 40px; display: grid; place-items: center; border-radius: 10px; font-size: 18px; }
.notif-item-icon.info { background: rgba(59,130,246,.1); color: #3b82f6; }
.notif-item-icon.success { background: rgba(16,185,129,.1); color: #10b981; }
.notif-item-icon.warning { background: rgba(245,158,11,.1); color: #f59e0b; }
.notif-item-icon.danger { background: rgba(239,68,68,.1); color: #ef4444; }
.notif-item-icon.shalat { background: rgba(99,102,241,.1); color: #6366f1; }
.notif-item-body { flex: 1; }
.notif-item-body strong { display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 4px; }
.notif-item-body p { margin: 0 0 8px; font-size: 13px; color: #4b5563; line-height: 1.5; }
.notif-item-body small { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #9ca3af; }
.notif-empty { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: #9ca3af; gap: 12px; }
.notif-empty i { font-size: 48px; opacity: .5; }
.notif-empty p { margin: 0; font-size: 14px; }
/* Badge */
.notif-badge { position: absolute; top: 6px; right: 6px; min-width: 18px; height: 18px; padding: 0 5px; display: flex; align-items: center; justify-content: center; background: #ef4444; color: #fff; font-size: 10px; font-weight: 700; border-radius: 9px; border: 2px solid #fff; line-height: 1; }
/* Shalat Styles */
.shalat-loading { padding: 40px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; color: #6b7280; font-size: 14px; }
.shalat-spinner { width: 32px; height: 32px; border: 3px solid rgba(0,110,196,.1); border-top-color: #2563eb; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.shalat-meta { padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; display: flex; flex-direction: column; gap: 6px; }
.shalat-location, .shalat-date { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4b5563; }
.shalat-location i, .shalat-date i { color: #2563eb; font-size: 15px; }
.shalat-list { flex: 1; padding: 12px 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 6px; }
.shalat-card { display: flex; align-items: center; gap: 14px; padding: 14px 16px; border-radius: 12px; background: #fff; border: 1px solid #e5e7eb; transition: all .25s; }
.shalat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
.shalat-card.past { opacity: .5; }
.shalat-card.next { border-color: #2563eb; background: rgba(37,99,235,.04); box-shadow: 0 4px 20px rgba(37,99,235,.1); }
.shalat-card-icon { width: 42px; height: 42px; flex: 0 0 42px; display: grid; place-items: center; border-radius: 10px; font-size: 20px; }
.shalat-card-info { flex: 1; }
.shalat-card-info strong { display: block; font-size: 14px; font-weight: 600; color: #111827; }
.shalat-card-info span { font-size: 13px; color: #6b7280; font-weight: 400; }
.shalat-card-status { display: flex; align-items: center; }
.shalat-done { color: #10b981; font-size: 18px; }
.shalat-upcoming { padding: 4px 10px; font-size: 11px; font-weight: 600; color: #2563eb; background: rgba(37,99,235,.1); border-radius: 20px; animation: pulseUpcoming 2s ease-in-out infinite; }
@keyframes pulseUpcoming { 0%, 100% { opacity: 1; } 50% { opacity: .6; } }
@keyframes bellPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.15); } }
.notif-pulse { animation: bellPulse .6s ease-in-out 3; }
</style>

<div class="notif-overlay" id="notifOverlay"></div>

<aside class="notif-panel" id="notifPanel" aria-label="Notifications">
    {{-- Header --}}
    <div class="notif-header">
        <h2><i class="bi bi-bell"></i> Notifikasi</h2>
        <button class="notif-close" id="notifClose" aria-label="Tutup notifikasi">&times;</button>
    </div>

    {{-- Tabs --}}
    <div class="notif-tabs">
        <button class="notif-tab active" data-tab="semua">
            <i class="bi bi-inbox"></i> Semua
        </button>
        <button class="notif-tab" data-tab="shalat">
            <i class="bi bi-moon"></i> Jadwal Shalat
        </button>
    </div>

    {{-- ── Tab: Semua Notifikasi ───────────────────────────────── --}}
    <div class="notif-tab-content active" id="tabSemua">
        <div class="notif-list" id="notifList">
            {{-- Diisi oleh JS / backend --}}
            <div class="notif-empty" id="notifEmpty">
                <i class="bi bi-check-circle"></i>
                <p>Belum ada notifikasi</p>
            </div>
        </div>
    </div>

    {{-- ── Tab: Jadwal Shalat ──────────────────────────────────── --}}
    <div class="notif-tab-content" id="tabShalat">
        {{-- Loading state --}}
        <div class="shalat-loading" id="shalatLoading">
            <div class="shalat-spinner"></div>
            <p>Memuat jadwal shalat…</p>
        </div>

        {{-- Lokasi & Tanggal --}}
        <div class="shalat-meta" id="shalatMeta" style="display:none;">
            <div class="shalat-location">
                <i class="bi bi-geo-alt"></i>
                <span id="shalatLocation">—</span>
            </div>
            <div class="shalat-date">
                <i class="bi bi-calendar3"></i>
                <span id="shalatDate">—</span>
            </div>
        </div>

        {{-- Waktu shalat cards --}}
        <div class="shalat-list" id="shalatList"></div>

    </div>
</aside>

{{-- ══════════════════════════════════════════════════════════════
     JavaScript — Notification Logic + Aladhan Prayer API
     ══════════════════════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Elements ────────────────────────────────────────────
    const notifBtns    = document.querySelectorAll('#notificationButton');
    const notifPanel   = document.getElementById('notifPanel');
    const notifOverlay = document.getElementById('notifOverlay');
    const notifClose   = document.getElementById('notifClose');
    const notifList    = document.getElementById('notifList');
    const notifEmpty   = document.getElementById('notifEmpty');
    const shalatList   = document.getElementById('shalatList');
    const shalatLoading = document.getElementById('shalatLoading');
    const shalatMeta   = document.getElementById('shalatMeta');
    const tabButtons   = document.querySelectorAll('.notif-tab');
    const tabContents  = document.querySelectorAll('.notif-tab-content');

    let prayerTimesData = null;
    let prayerCheckInterval = null;
    let lastAlertedPrayer = null;

    // ─── Panel Open / Close ──────────────────────────────────
    function openPanel() {
        notifPanel.classList.add('open');
        notifOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
        // Load prayer times on first open
        if (!prayerTimesData) loadPrayerTimes();
    }

    function closePanel() {
        notifPanel.classList.remove('open');
        notifOverlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    notifBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openPanel();
        });
    });
    if (notifClose) notifClose.addEventListener('click', closePanel);
    if (notifOverlay) notifOverlay.addEventListener('click', closePanel);

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && notifPanel.classList.contains('open')) closePanel();
    });

    // ─── Tab Switching ───────────────────────────────────────
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            const target = btn.dataset.tab === 'shalat' ? 'tabShalat' : 'tabSemua';
            document.getElementById(target).classList.add('active');
        });
    });

    // ─── System Notifications ────────────────────────────────
    // Render system notifications from server-injected data
    function renderSystemNotifications(notifications) {
        if (!notifications || notifications.length === 0) {
            notifEmpty.style.display = 'flex';
            return;
        }
        notifEmpty.style.display = 'none';

        notifications.forEach(n => {
            const item = document.createElement('div');
            item.className = 'notif-item' + (n.read ? '' : ' unread');
            item.innerHTML = `
                <div class="notif-item-icon ${n.type || 'info'}">
                    <i class="bi ${getNotifIcon(n.type)}"></i>
                </div>
                <div class="notif-item-body">
                    <strong>${escapeHtml(n.title)}</strong>
                    <p>${escapeHtml(n.message)}</p>
                    <small><i class="bi bi-clock"></i> ${n.time}</small>
                </div>
            `;
            notifList.insertBefore(item, notifEmpty);
        });
    }

    function getNotifIcon(type) {
        const icons = {
            info:    'bi-info-circle',
            success: 'bi-check-circle',
            warning: 'bi-exclamation-triangle',
            danger:  'bi-x-octagon',
            shalat:  'bi-moon',
        };
        return icons[type] || 'bi-bell';
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    // ─── Load server notifications from Database ─────────────
    @auth
        @php
            $userNotifications = auth()->user()->notifications()->take(10)->get()->map(function($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->data['title'] ?? 'Notifikasi',
                    'message' => $n->data['message'] ?? '',
                    'url' => $n->data['url'] ?? '#',
                    'time' => $n->created_at->diffForHumans(),
                    'type' => 'info',
                    'read' => !is_null($n->read_at)
                ];
            });
        @endphp
        const dbNotifications = @json($userNotifications);
        renderSystemNotifications(dbNotifications);
    @else
        notifEmpty.style.display = 'flex';
    @endauth

    // ─── Aladhan Prayer Time API ─────────────────────────────
    // Fallback coordinates: Banda Aceh (default for UIN Ar-Raniry)
    const DEFAULT_LAT = 5.5483;
    const DEFAULT_LNG = 95.3238;
    const DEFAULT_CITY = 'Banda Aceh';

    const PRAYER_NAMES = {
        subuh:   { id: 'Subuh',   icon: 'bi-sunrise',         color: '#3b82f6' },
        terbit:  { id: 'Syuruq',  icon: 'bi-sun',             color: '#f59e0b' },
        dzuhur:  { id: 'Dzuhur',  icon: 'bi-brightness-high', color: '#f97316' },
        ashar:   { id: 'Ashar',   icon: 'bi-cloud-sun',       color: '#ef4444' },
        maghrib: { id: 'Maghrib', icon: 'bi-sunset',          color: '#8b5cf6' },
        isya:    { id: 'Isya',    icon: 'bi-moon-stars',      color: '#1e3a5f' },
    };

    async function loadPrayerTimes() {
        shalatLoading.style.display = 'flex';
        shalatMeta.style.display = 'none';
        shalatList.innerHTML = '';

        try {
            // Default to Kota Banda Aceh (0119) since this is UIN Ar-Raniry
            let cityId = '0119';
            let cityName = 'Kota Banda Aceh';

            try {
                const pos = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, { timeout: 5000 });
                });
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                // Reverse geocode to get city name
                const geoRes = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=id`);
                const geoData = await geoRes.json();
                let city = geoData.address?.city || geoData.address?.town || geoData.address?.county || '';

                if (city) {
                    let searchQuery = city.replace(/Kota |Kabupaten /ig, '');
                    const searchRes = await fetch(`https://api.myquran.com/v2/sholat/kota/cari/${searchQuery}`);
                    const searchData = await searchRes.json();
                    
                    if (searchData.status && searchData.data && searchData.data.length > 0) {
                        cityId = searchData.data[0].id;
                        cityName = searchData.data[0].lokasi;
                    }
                }
            } catch { /* geolocation denied/failed, use default */ }

            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();

            const res = await fetch(`https://api.myquran.com/v2/sholat/jadwal/${cityId}/${yyyy}/${mm}/${dd}`);
            const data = await res.json();

            if (!data.status) throw new Error('API error');

            prayerTimesData = data.data.jadwal;
            renderPrayerTimes(prayerTimesData, cityName);
            startPrayerCheck();

        } catch (err) {
            console.error('Error fetching shalat schedule:', err);
            shalatLoading.innerHTML = `
                <i class="bi bi-exclamation-triangle" style="font-size:32px;color:#ef4444;"></i>
                <p>Gagal memuat jadwal shalat</p>
                <button class="shalat-retry" onclick="location.reload()">Coba lagi</button>
            `;
        }
    }

    function renderPrayerTimes(jadwal, city) {
        shalatLoading.style.display = 'none';
        shalatMeta.style.display = 'flex';

        // Set meta info
        document.getElementById('shalatLocation').textContent = city;
        document.getElementById('shalatDate').textContent = jadwal.tanggal;

        // Render prayer cards
        const now = new Date();
        let nextPrayer = null;

        Object.entries(PRAYER_NAMES).forEach(([key, info]) => {
            const time = jadwal[key];
            const [h, m] = time.split(':').map(Number);
            const prayerDate = new Date(now);
            prayerDate.setHours(h, m, 0, 0);

            const isPast = now > prayerDate;
            const isNext = !isPast && !nextPrayer;
            if (isNext) nextPrayer = { key, info, time };

            const card = document.createElement('div');
            card.className = `shalat-card ${isPast ? 'past' : ''} ${isNext ? 'next' : ''}`;
            card.innerHTML = `
                <div class="shalat-card-icon" style="background:${info.color}20;color:${info.color}">
                    <i class="bi ${info.icon}"></i>
                </div>
                <div class="shalat-card-info">
                    <strong>${info.id}</strong>
                    <span>${time}</span>
                </div>
                <div class="shalat-card-status">
                    ${isPast ? '<span class="shalat-done"><i class="bi bi-check-lg"></i></span>' : ''}
                    ${isNext ? '<span class="shalat-upcoming">Berikutnya</span>' : ''}
                </div>
            `;
            shalatList.appendChild(card);
        });
    }

    // ─── Real-time Prayer Alert Check ────────────────────────
    function startPrayerCheck() {
        if (prayerCheckInterval) clearInterval(prayerCheckInterval);

        prayerCheckInterval = setInterval(() => {
            if (!prayerTimesData) return;

            const now = new Date();
            const currentTime = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

            Object.entries(PRAYER_NAMES).forEach(([key, info]) => {
                const time = prayerTimesData[key];
                const cleanTime = time.trim();

                if (cleanTime === currentTime && lastAlertedPrayer !== key) {
                    lastAlertedPrayer = key;
                    showPrayerAlert(info, cleanTime);
                    addPrayerNotification(info, cleanTime);

                    // Show browser notification if permitted
                    if (Notification.permission === 'granted') {
                        new Notification(`Waktu ${info.id}`, {
                            body: `Sudah masuk waktu ${info.id} (${cleanTime})`,
                            icon: '{{ asset("assets/image/Lambang_UIN_Ar-Raniry.svg") }}',
                        });
                    }
                }
            });
        }, 10000); // Check every 10 seconds
    }

    function showPrayerAlert(info, time) {
        const city = document.getElementById('shalatLocation').textContent;
        
        // Show Global SweetAlert Modal
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: `Waktu ${info.id} Telah Tiba!`,
                html: `Sudah masuk waktu <strong>${info.id}</strong> pukul <strong>${time}</strong> untuk wilayah ${city} dan sekitarnya.`,
                icon: 'info',
                iconHtml: `<i class="bi ${info.icon}" style="color: ${info.color}; font-size: 32px;"></i>`,
                confirmButtonText: 'Tutup',
                confirmButtonColor: info.color,
                customClass: {
                    icon: 'border-0'
                }
            });
        }

        // Pulse the notification button
        notifBtns.forEach(btn => {
            btn.classList.add('notif-pulse');
            // Auto-remove pulse after 60 seconds
            setTimeout(() => {
                btn.classList.remove('notif-pulse');
            }, 60000);
        });
    }

    function addPrayerNotification(info, time) {
        notifEmpty.style.display = 'none';

        const item = document.createElement('div');
        item.className = 'notif-item unread';
        item.innerHTML = `
            <div class="notif-item-icon shalat">
                <i class="bi ${info.icon}"></i>
            </div>
            <div class="notif-item-body">
                <strong>Waktu ${info.id}</strong>
                <p>Sudah masuk waktu ${info.id} pukul ${time}</p>
                <small><i class="bi bi-clock"></i> Baru saja</small>
            </div>
        `;
        notifList.insertBefore(item, notifList.firstChild);

        // Update badge count
        updateBadge();
    }

    // ─── Badge Counter ───────────────────────────────────────
    function updateBadge() {
        const count = notifList.querySelectorAll('.notif-item.unread').length;
        notifBtns.forEach(btn => {
            let badge = btn.querySelector('.notif-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'notif-badge';
                    btn.appendChild(badge);
                }
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else if (badge) {
                badge.style.display = 'none';
            }
        });
    }

    // ─── Real-time Notification listener via Laravel Echo ────
    function addRealtimeNotification(n) {
        notifEmpty.style.display = 'none';

        const item = document.createElement('div');
        item.className = 'notif-item unread animate-fade-in';
        item.innerHTML = `
            <div class="notif-item-icon ${n.type || 'info'}">
                <i class="bi ${getNotifIcon(n.type)}"></i>
            </div>
            <div class="notif-item-body">
                <strong>${escapeHtml(n.title)}</strong>
                <p>${escapeHtml(n.message)}</p>
                <small><i class="bi bi-clock"></i> Baru saja (${n.time})</small>
            </div>
        `;
        notifList.insertBefore(item, notifList.firstChild);
        updateBadge();
    }

    @auth
    // Delay slightly to ensure Echo is initialized by Vite
    setTimeout(() => {
        if (window.Echo) {
            const userRole = '{{ auth()->user()->role }}';
            window.Echo.channel('notifikasi.' + userRole)
                .listen('PengumumanPublished', (e) => {
                    addRealtimeNotification(e);
                    
                    // Panggil fungsi toast kustom
                    if (typeof showToast === 'function') {
                        // Kalau sudah ada dari file lain (seperti app.js) yang menggunakan textContent
                        showToast(`📢 Pengumuman Baru: ${e.title} - ${e.message}`);
                    } else {
                        // Fallback fungsi toast custom 5 detik
                        const toast = document.createElement('div');
                        toast.className = `fixed top-4 right-4 z-[99999] px-4 py-3 rounded-lg shadow-lg border flex items-center gap-3 transition-all transform translate-x-0 bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400`;
                        toast.innerHTML = `<i class="bi bi-info-circle-fill text-lg"></i> <span class="font-medium text-sm">📢 <b>${escapeHtml(e.title)}</b><br>${escapeHtml(e.message)}</span>`;
                        document.body.appendChild(toast);
                        
                        setTimeout(() => {
                            toast.classList.add('opacity-0', 'translate-x-full');
                            setTimeout(() => toast.remove(), 300);
                        }, 5000);
                    }

                    notifBtns.forEach(btn => {
                        btn.classList.add('notif-pulse');
                        setTimeout(() => {
                            btn.classList.remove('notif-pulse');
                        }, 60000);
                    });
                    
                    // Show browser notification if permitted
                    if (Notification.permission === 'granted') {
                        new Notification(e.title, {
                            body: e.message,
                            icon: '{{ asset("assets/image/Lambang_UIN_Ar-Raniry.svg") }}',
                        });
                    }
                });
        }
    }, 500);
    @endauth

    // ─── Request browser notification permission ─────────────
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

});
</script>
