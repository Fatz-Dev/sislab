<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#f7f9fc" />
    <link rel="icon" href="data:," />
    <title>@yield('title') - {{ config('app.name', 'SISLAB Fisika') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
                    },
                    colors: {
                        ink: "#1c1f26",
                        brand: "#16a34a",
                        "brand-dark": "#005fae",
                        sidebar: "#d7eaf8",
                        "line-soft": "#16a34a",
                    },
                    boxShadow: {
                        panel: "0 16px 38px rgba(24, 44, 72, 0.06)",
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
</head>

<body class="min-w-[320px] bg-[#f7f9fc] font-sans text-ink antialiased dark:bg-[#101419] dark:text-[#f6f8fb]">
    <script>
        if (localStorage.getItem("sislab-theme") === "dark") {
            document.body.classList.add("dark");
        }
    </script>
    <div id="app" class="flex min-h-screen bg-[#f7f9fc] dark:bg-[#101419]">
        <main class="flex min-w-0 flex-1 flex-col px-4 pb-4 pt-4 sm:px-6 md:px-7 md:py-7">
            <section
                class="mt-5 flex min-h-0 flex-1 flex-col rounded-xl border border-slate-100 bg-white shadow-panel dark:border-[#29323e] dark:bg-[#171d25]">
                <div
                    class="relative flex flex-1 items-center justify-center border-t border-slate-100 px-6 pb-14 dark:border-[#344150]">
                    <div id="empty-state" class="flex flex-col items-center text-center">
                        <img class="mb-6 h-auto w-full max-w-[280px] dark:invert dark:hue-rotate-90"
                            src="{{ asset('assets/image/Error-No-Background.png') }}" alt="Error illustration" />
                        <h2 class="text-3xl font-bold tracking-tight dark:text-[#f6f8fb] mb-2 text-brand">
                            @yield('code') - @yield('title')
                        </h2>
                        <p class="mt-1 max-w-md text-base text-slate-500 dark:text-[#b9c5d2]">
                            @yield('message')
                        </p>

                        <div class="mt-8">
                            <a href="{{ url('/') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-emerald-600 dark:text-white dark:border-white">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <div id="toast"
        class="fixed bottom-6 left-1/2 z-50 hidden -translate-x-1/2 rounded-lg bg-slate-900 px-4 py-2.5 text-[11px] font-medium text-white shadow-lg transition-opacity duration-200 dark:bg-white dark:text-slate-900">
    </div>
    <script>
        const systemTheme = window.matchMedia("(prefers-color-scheme: dark)");
        const themeMeta = document.querySelector('meta[name="theme-color"]');

        function syncSystemTheme(event) {
            if (!themeMeta) return;

            themeMeta.setAttribute("content", event.matches ? "#101419" : "#f7f9fc");
        }

        syncSystemTheme(systemTheme);

        if (typeof systemTheme.addEventListener === "function") {
            systemTheme.addEventListener("change", syncSystemTheme);
        } else {
            systemTheme.addListener(syncSystemTheme);
        }
    </script>
</body>

</html>
