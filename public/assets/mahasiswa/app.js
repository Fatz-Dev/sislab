document.addEventListener('DOMContentLoaded', function () {

  // ── Elements ──────────────────────────────────────
  const menuToggle   = document.getElementById('menuToggle');
  const menuOverlay  = document.getElementById('menuOverlay');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const exitBtn      = document.getElementById('exitBtn');
  const toast        = document.getElementById('toast');
  let toastTimer;

  // ── Dropdown Menu ─────────────────────────────────
  function toggleMenu() {
    const isOpen = dropdownMenu.classList.toggle('open');
    menuOverlay.classList.toggle('visible', isOpen);
  }

  function closeMenu() {
    dropdownMenu.classList.remove('open');
    menuOverlay.classList.remove('visible');
  }

  if (menuToggle)  menuToggle.addEventListener('click', toggleMenu);
  if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);

  // Close on dropdown item click
  document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      closeMenu();
      if(window.showToast) window.showToast(item.querySelector('span').textContent + ' dipilih');
    });
  });

  // ── Bottom Nav ────────────────────────────────────
  document.querySelectorAll('.bottom-nav-item').forEach(item => {
    item.addEventListener('click', (e) => {
      const href = item.getAttribute('href');
      // Jika href adalah '#' atau kosong, cegah navigasi dan tampilkan toast
      if (!href || href === '#' || href === '') {
        e.preventDefault();
        document.querySelectorAll('.bottom-nav-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        if(window.showToast) window.showToast(item.querySelector('span').textContent + ' (Segera Hadir)');
      }
      // Jika valid, biarkan browser berpindah halaman secara alami (dikelola route Laravel)
    });
  });

  // ── Exit Button ───────────────────────────────────
  if (exitBtn) {
    exitBtn.addEventListener('click', () => {
      if(window.showToast) window.showToast('Signed out');
    });
  }

  // ── Toast ─────────────────────────────────────────
  window.showToast = function(message) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 5000); // Update durasi jadi 5 detik
  };

  // ── Prayer Time Alarm ─────────────────────────────
  function checkPrayerTime() {
    // Gunakan jQuery AJAX untuk mengambil waktu shalat Banda Aceh (sesuai instruksi)
    if (typeof $ !== 'undefined') {
        $.ajax({
            url: "https://api.aladhan.com/v1/timingsByCity",
            method: "GET",
            data: {
                city: "Banda Aceh",
                country: "Indonesia",
                method: 11
            },
            success: function(response) {
                if(response && response.data && response.data.timings) {
                    const timings = response.data.timings;
                    const now = new Date();
                    const currentHour = String(now.getHours()).padStart(2, '0');
                    const currentMinute = String(now.getMinutes()).padStart(2, '0');
                    const currentTime = `${currentHour}:${currentMinute}`;
                    
                    const prayerNames = {
                        Fajr: "Subuh",
                        Dhuhr: "Dzuhur",
                        Asr: "Ashar",
                        Maghrib: "Maghrib",
                        Isha: "Isya"
                    };

                    for (const [key, name] of Object.entries(prayerNames)) {
                        if (timings[key] === currentTime) {
                            if (window.showToast) {
                                window.showToast(`Waktu shalat ${name} telah tiba untuk wilayah Banda Aceh dan sekitarnya.`);
                            }
                        }
                    }
                }
            },
            error: function(err) {
                console.error("Gagal mengambil data waktu shalat", err);
            }
        });
    }
  }

  // Cek setiap menit (60000 ms)
  setInterval(checkPrayerTime, 60000);
  
  // Panggil sekali di awal untuk cek jika waktu saat ini pas waktu shalat
  checkPrayerTime();

  // ── Realtime Announcement Listener ─────────────────
  function initEchoListener() {
    if (typeof window.Echo !== 'undefined') {
        // Mendengarkan pengumuman global untuk mahasiswa
        window.Echo.channel('notifikasi.mahasiswa')
            .listen('PengumumanPublished', (e) => {
                if (window.showToast) {
                    window.showToast(`📢 Pengumuman Baru: ${e.title} - ${e.message}`);
                }
            });

        // Mendengarkan notifikasi spesifik (seperti status pendaftaran kelas)
        if (window.authUser && window.authUser.id) {
            window.Echo.channel('notifikasi.mahasiswa.' + window.authUser.id)
                .listen('EnrollmentStatusUpdated', (e) => {
                    if (window.showToast) {
                        window.showToast(`🔔 Update: ${e.message}`);
                    }
                    
                    // Reload halaman jika sedang di halaman list-kelas agar status terupdate
                    if (window.location.href.includes('/kelas')) {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                });
        }
    } else {
        setTimeout(initEchoListener, 500); // Retry jika Echo belum siap (karena diload asinkron oleh Vite)
    }
  }
  initEchoListener();

});