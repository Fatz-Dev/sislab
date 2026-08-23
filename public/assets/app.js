document.addEventListener("DOMContentLoaded", function () {

    // ─── Cached Elements (may be null depending on page) ──────
    const searchInput    = document.querySelector("#globalSearch");
    const toast          = document.querySelector("#toast");
    const sidebar        = document.querySelector(".sidebar");
    const sidebarToggle  = document.querySelector("#sidebarToggle");
    const sidebarClose   = document.querySelector("#sidebarClose");
    const sidebarOverlay = document.querySelector("#sidebarOverlay");
    let toastTimer;

    // ─── Toast ────────────────────────────────────────────────
    window.showToast = function(message) {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add("show");
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove("show"), 2300);
    };

    // ─── Password Toggle (Login Page) ─────────────────────────
    const passwordInput  = document.getElementById("passwordInput");
    const passwordToggle = document.getElementById("passwordToggle");

    if (passwordInput && passwordToggle) {
        passwordToggle.addEventListener("click", function () {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";

            const icon = passwordToggle.querySelector("i");
            if (icon) {
                icon.classList.toggle("bi-eye", isHidden);
                icon.classList.toggle("bi-eye-slash", !isHidden);
            }

            passwordToggle.setAttribute(
                "aria-label",
                isHidden ? "Hide password" : "Show password",
            );
            passwordToggle.setAttribute("aria-pressed", String(isHidden));
        });
    }

    // ─── Sidebar Toggle (Dashboard) ───────────────────────────
    function setSidebarOpen(isOpen) {
        if (!sidebar) return;
        sidebar.classList.toggle("open", isOpen);
        if (sidebarOverlay) sidebarOverlay.classList.toggle("visible", isOpen);
        if (sidebarToggle) sidebarToggle.setAttribute("aria-expanded", String(isOpen));
        document.body.classList.toggle("sidebar-is-open", isOpen);
    }

    if (sidebarToggle) sidebarToggle.addEventListener("click", () => setSidebarOpen(true));
    if (sidebarClose) sidebarClose.addEventListener("click", () => setSidebarOpen(false));
    if (sidebarOverlay) sidebarOverlay.addEventListener("click", () => setSidebarOpen(false));

    // ─── Master Data Toggle (Collapsible Submenu) ───────────────
    document.querySelectorAll(".nav-toggle").forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const group = toggle.closest(".nav-group");
            if (!group) return;

            // Close other open groups (accordion behavior)
            document.querySelectorAll(".nav-group.open").forEach((g) => {
                if (g !== group) g.classList.remove("open");
            });

            const isOpen = group.classList.toggle("open");
            toggle.setAttribute("aria-expanded", String(isOpen));
        });
    });

    // Auto-open group if a submenu item is active
    document.querySelectorAll(".nav-subitem.active").forEach((item) => {
        const group = item.closest(".nav-group");
        if (group) {
            group.classList.add("open");
            const toggle = group.querySelector(".nav-toggle");
            if (toggle) toggle.setAttribute("aria-expanded", "true");
        }
    });

    // ─── Dark Mode / Theme Switch ─────────────────────────────
    // Restore saved theme on page load
    const savedTheme = localStorage.getItem("sislab-theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        document.querySelectorAll(".theme-option").forEach((btn) => {
            btn.classList.toggle("selected", btn.dataset.theme === "dark");
        });
    }

    document.querySelectorAll(".theme-option").forEach((option) => {
        option.addEventListener("click", () => {
            const isDark = option.dataset.theme === "dark";
            document.body.classList.toggle("dark", isDark);
            document
                .querySelectorAll(".theme-option")
                .forEach((theme) =>
                    theme.classList.toggle("selected", theme === option),
                );
            // Persist preference
            localStorage.setItem("sislab-theme", isDark ? "dark" : "light");
            showToast(`${isDark ? "Dark" : "Light"} theme enabled`);
        });
    });

    // ─── Search Filter (Dashboard) ────────────────────────────
    function filterTable(tableId, emptyId, query) {
        const table = document.querySelector(tableId);
        if (!table) return;
        const rows = [...table.querySelectorAll("tbody tr")];
        let visibleRows = 0;
        rows.forEach((row) => {
            const matches = row.textContent
                .toLowerCase()
                .includes(query.toLowerCase());
            row.style.display = matches ? "" : "none";
            if (matches) visibleRows += 1;
        });
        const emptyEl = document.querySelector(emptyId);
        if (emptyEl) emptyEl.style.display = visibleRows ? "none" : "block";
    }

    if (searchInput) {
        searchInput.addEventListener("input", (event) => {
            const query = event.target.value.trim();
            filterTable("#itemsTable", "#itemsEmpty", query);
            filterTable("#assetsTable", "#assetsEmpty", query);
        });
    }

    // ─── Profile Menu (Dashboard) ─────────────────────────────
    const profileButton = document.querySelector("#profileButton");
    const profileMenu   = document.querySelector("#profileMenu");

    if (profileButton && profileMenu) {
        profileButton.addEventListener("click", () => {
            const open = profileMenu.classList.toggle("open");
            profileButton.setAttribute("aria-expanded", String(open));
        });

        document.addEventListener("click", (event) => {
            if (!event.target.closest("#profileButton") && !event.target.closest("#profileMenu")) {
                profileMenu.classList.remove("open");
                profileButton.setAttribute("aria-expanded", "false");
            }
        });
    }

    // ─── Notification Button ──────────────────────────────────
    // Handled by modal-notif.blade.php

    // ─── View All Buttons ─────────────────────────────────────
    document.querySelectorAll(".view-all").forEach((button) => {
        button.addEventListener("click", () => {
            const title = button.dataset.view === "items" ? "Items" : "Assets";
            showToast(`${title} list opened`);
        });
    });

    // ─── Realtime Prayer Notification (Client-Side AJAX) ───────
    let prayerTimings = null;
    let lastNotifiedPrayer = null;

    function fetchPrayerTimes() {
        // Menggunakan API Aladhan, method 20 (Kemenag RI), wilayah Banda Aceh
        if (typeof $ === 'undefined') return; // Pastikan jQuery tersedia
        
        $.ajax({
            url: 'https://api.aladhan.com/v1/timingsByCity?city=Banda%20Aceh&country=Indonesia&method=20',
            method: 'GET',
            success: function(response) {
                if (response && response.code === 200) {
                    const allTimings = response.data.timings;
                    // Ambil hanya shalat wajib
                    prayerTimings = {
                        "Subuh": allTimings.Fajr,
                        "Dzuhur": allTimings.Dhuhr,
                        "Ashar": allTimings.Asr,
                        "Maghrib": allTimings.Maghrib,
                        "Isya": allTimings.Isha
                    };
                    console.log("Jadwal Shalat Banda Aceh hari ini:", prayerTimings);
                }
            },
            error: function() {
                console.warn("Gagal memuat jadwal shalat dari API.");
            }
        });
    }

    function checkPrayerTime() {
        if (!prayerTimings) return;

        const now = new Date();
        const currentTime = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        for (const [namaShalat, time] of Object.entries(prayerTimings)) {
            if (currentTime === time && lastNotifiedPrayer !== namaShalat) {
                lastNotifiedPrayer = namaShalat; // Tandai agar tidak spam di menit yang sama
                
                if (typeof window.showToast === 'function') {
                    // Notifikasi toast akan langsung muncul di pojok atas
                    window.showToast(`📢 Waktu shalat ${namaShalat} untuk wilayah Banda Aceh telah tiba!`);
                }
            }
        }
    }

    if (typeof $ !== 'undefined') {
        fetchPrayerTimes(); // Tarik jadwal saat halaman dimuat
        setInterval(checkPrayerTime, 30000); // Cek kecocokan waktu setiap 30 detik
        
        // Tarik ulang jadwal setiap jam 1 pagi (jika user membiarkan tab terbuka seharian)
        setInterval(function() {
            const now = new Date();
            if (now.getHours() === 1 && now.getMinutes() === 0) {
                fetchPrayerTimes();
                lastNotifiedPrayer = null; 
            }
        }, 60000);
    }

});
