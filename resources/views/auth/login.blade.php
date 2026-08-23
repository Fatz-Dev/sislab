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
                        primary: "#006EC4",
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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600&family=Lexend:wght@300;400;500;600&family=Manrope:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />
</head>

<body>
    <section class="login-screen" id="loginScreen" aria-label="Login">
        <div class="login-visual">
            <img src="{{ asset('assets/image/login-hero.png') }}" alt="Inventory warehouse" />
        </div>
        <div class="login-content">
            <div class="login-brand" aria-label="Inventory home">
                    {{-- <div class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></div> --}}
                <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="UIN Ar-Raniry" style="width: 80px; height: auto;" />
                {{-- <span class="">x</span> --}}
                {{-- <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="UIN Ar-Raniry" style="width: 80px; height: auto;" /> --}}
                <span class="brand-name">{{ env('APP_NAME') }}</span>
            </div>
            <form class="login-form" id="loginForm" method="POST" action="{{ route('login.process') }}">
                @csrf
                <div class="login-heading">
                    <h1>Welcome</h1>
                    <p>Please login here</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has('email') && !old('email'))
                    <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div class="login-fields">
                    <label class="login-input focused">
                        <span>Email Address</span>
                        <input
                            id="emailInput"
                            name="email"
                            type="email"
                            placeholder="Enter your email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            required
                        />
                        @error('email')
                            <p class="text-red-600 mt-2 text-sm">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="login-input">
                        <span>Password</span>
                        <input
                            id="passwordInput"
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        />
                        @error('password')
                            <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                        @enderror

                        <button
                            class="password-toggle"
                            type="button"
                            id="passwordToggle"
                            aria-label="Show password"
                            aria-pressed="false"
                        >
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </label>

                    <div class="login-options" style="justify-content: flex-end;">
                        <button type="button" class="forgot-button" id="forgotButton">Forgot Password?</button>
                    </div>

                    <button type="submit" class="login-button">Login</button>
                </div>
                <div class="mt-4 flex justify-center gap-2">
                    <span style="color: #a2a1a8; font-size: 14px;">Daftar sebagai mahasiswa</span>
                    <a href="{{ route('register') }}" class="forgot-button" style="text-decoration: none; font-size: 14px; margin-left: 5px;">Register di sini</a>
                </div>
            </form>
        </div>
        
    </section>

    <div class="toast" id="toast" role="status"></div>
    <script src="{{ asset('assets/app.js') }}"></script>
</body>

</html>
