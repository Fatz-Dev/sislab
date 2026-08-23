<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Register') - {{ env('APP_NAME') }}</title>
    <meta name="description" content="Register an account for SISLAB Fisika." />
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
    <section class="login-screen" id="loginScreen" aria-label="Register">
        <div class="login-visual">
            <img src="{{ asset('assets/image/login-hero.png') }}" alt="Inventory warehouse" />
        </div>
        <div class="login-content">
            <div class="login-brand" aria-label="Inventory home">
                <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="UIN Ar-Raniry" style="width: 80px; height: auto;" />
                <span class="brand-name">{{ env('APP_NAME') }}</span>
            </div>
            <form class="login-form" id="registerForm" method="POST" action="{{ route('register.process') }}">
                @csrf
                <div class="login-heading">
                    <h1>Register</h1>
                    <p>Buat akun Mahasiswa baru</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="login-fields">
                    <!-- Nama Lengkap -->
                    <label class="login-input focused">
                        <span>Nama Lengkap</span>
                        <input
                            id="nameInput"
                            name="name"
                            type="text"
                            placeholder="Masukkan nama lengkap Anda"
                            value="{{ old('name') }}"
                            required
                        />
                        @error('name')
                            <p class="text-red-600 mt-2 text-sm">{{ $message }}</p>
                        @enderror
                    </label>

                    <!-- Email -->
                    <label class="login-input">
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

                    <!-- Password -->
                    <label class="login-input">
                        <span>Password</span>
                        <input
                            id="passwordInput"
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            autocomplete="new-password"
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

                    <!-- Konfirmasi Password -->
                    <label class="login-input">
                        <span>Konfirmasi Password</span>
                        <input
                            id="passwordConfirmationInput"
                            name="password_confirmation"
                            type="password"
                            placeholder="Ulangi password Anda"
                            autocomplete="new-password"
                            required
                        />
                        <button
                            class="password-toggle"
                            type="button"
                            id="passwordConfirmToggle"
                            aria-label="Show password"
                            aria-pressed="false"
                            style="bottom: 14px; position: absolute; right: 14px;"
                        >
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </label>

                    <button type="submit" class="login-button">Register</button>
                    
                    <div style="text-align: center; margin-top: 10px;">
                        <span style="color: #a2a1a8; font-size: 14px;">Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="forgot-button" style="text-decoration: none; font-size: 14px; margin-left: 5px;">Login di sini</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="toast" id="toast" role="status"></div>
    <script src="{{ asset('assets/app.js') }}"></script>
    <script>
        // Custom logic for the second password toggle
        document.addEventListener('DOMContentLoaded', function() {
            const confirmToggle = document.getElementById('passwordConfirmToggle');
            const confirmInput = document.getElementById('passwordConfirmationInput');
            
            if (confirmToggle && confirmInput) {
                confirmToggle.addEventListener('click', function() {
                    const isPassword = confirmInput.type === 'password';
                    confirmInput.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
                });
            }
        });
    </script>
</body>

</html>
