<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Login') - {{ env('APP_NAME') }}</title>
    <meta name="description" content="Inventory management dashboard for items, assets, stores, and quantities." />
    <link rel="icon" href="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" type="image/svg+xml" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#008A01",
                        ink: "#16151C",
                        muted: "#A2A1A8",
                    },
                    fontFamily: {
                        lexend: ["Lexend", "sans-serif"],
                        inter: ["Inter", "sans-serif"],
                        manrope: ["Manrope", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@300;400;500;600&family=Manrope:wght@400;500;700&display=swap"
        rel="stylesheet" />
</head>

<body class="bg-white text-slate-800 font-sans antialiased min-h-screen">
    <section class="min-h-screen p-4 sm:p-6 lg:p-8 flex items-center justify-center lg:justify-start gap-8 xl:gap-14 bg-white" id="loginScreen" aria-label="Login">
        
        <!-- Kolom Visual (Hero Image) -->
        <div class="hidden lg:block lg:flex-1 h-[calc(100vh-4rem)] max-w-2xl xl:max-w-3xl rounded-3xl overflow-hidden bg-sky-100 shadow-sm shrink-0">
            <img src="{{ asset('assets/image/login-hero.png') }}" alt="Inventory warehouse" class="w-full h-full object-cover" />
        </div>

        <!-- Kolom Konten Login -->
        <div class="w-full max-w-[440px] flex flex-col justify-center py-6 px-2 sm:px-4 mx-auto shrink-0">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-4 mb-8" aria-label="Inventory home">
                <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="UIN Ar-Raniry" class="w-16 h-auto object-contain shrink-0" />
                <div>
                    <span class="block text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ env('APP_NAME') }}</span>
                    <p class="text-sm text-slate-500 font-medium leading-tight mt-1">Pendidikan Fisika</p>
                </div>
            </div>

            <!-- Form Login -->
            <form class="w-full" id="loginForm" method="POST" action="{{ route('login.process') }}">
                @csrf
                
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome</h1>
                    <p class="text-sm sm:text-base text-slate-500 mt-1">Please login here</p>
                </div>

                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-600 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->has('email') && !old('email'))
                    <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill text-red-600 shrink-0"></i>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <div class="space-y-5">
                    <!-- Input Email -->
                    <div class="space-y-1.5">
                        <label for="emailInput" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Email Address
                        </label>
                        <div class="relative">
                            <input
                                id="emailInput"
                                name="email"
                                type="email"
                                placeholder="Enter your email"
                                autocomplete="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full h-12 px-4 rounded-xl border border-slate-300 bg-white text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#008A01] focus:border-transparent transition-all shadow-sm"
                            />
                        </div>
                        @error('email')
                            <p class="text-red-600 mt-1.5 text-xs font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="space-y-1.5">
                        <label for="passwordInput" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="passwordInput"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                                class="w-full h-12 pl-4 pr-12 rounded-xl border border-slate-300 bg-white text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#008A01] focus:border-transparent transition-all shadow-sm"
                            />
                            <button
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1"
                                type="button"
                                id="passwordToggle"
                                aria-label="Show password"
                                aria-pressed="false"
                            >
                                <i class="bi bi-eye-slash text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 mt-1.5 text-xs font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="flex justify-end pt-1">
                        <button
                            type="button"
                            class="text-xs font-semibold text-[#008A01] hover:text-[#008A01] hover:underline transition-colors cursor-pointer"
                            id="forgotButton"
                        >
                            Forgot Password?
                        </button>
                    </div>

                    <!-- Tombol Submit -->
                    <button
                        type="submit"
                        class="w-full h-12 rounded-xl bg-[#008A01] hover:bg-[#047404] active:scale-[0.99] text-white font-semibold text-sm transition-all duration-200 shadow-md shadow-[#008A01]/25 hover:shadow-lg hover:shadow-[#008A01]/35 cursor-pointer flex items-center justify-center gap-2"
                    >
                        <span>Login</span>
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </button>
                </div>

                <!-- Link Registrasi Mahasiswa -->
                <div class="mt-6 pt-5 border-t border-slate-200 text-center text-xs sm:text-sm text-slate-500 flex items-center justify-center gap-1.5">
                    <span>Daftar sebagai mahasiswa?</span>
                    <a href="{{ route('register') }}" class="font-semibold text-[#008A01] hover:text-[#00589c] hover:underline transition-colors">
                        Register di sini
                    </a>
                </div>
            </form>
        </div>
        
    </section>

    <div class="toast" id="toast" role="status"></div>
    <script src="{{ asset('assets/app.js') }}"></script>
    <script>
        // Bersihkan session storage quotes saat pengguna berada di halaman login
        try {
            sessionStorage.removeItem('sislab_islamic_quote_shown');
            Object.keys(sessionStorage).forEach(function(k) {
                if (k.startsWith('sislab_quote_')) {
                    sessionStorage.removeItem(k);
                }
            });
        } catch (e) {}
    </script>
</body>

</html>
