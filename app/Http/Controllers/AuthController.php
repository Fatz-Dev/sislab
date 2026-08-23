<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     *
     * Keamanan:
     * - Rate limiting (5 percobaan/menit per IP+email)
     * - Cek akun aktif sebelum login
     * - Regenerasi session setelah login (anti session fixation)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // --- Rate Limiting ---
        $throttleKey = Str::transliterate(
            Str::lower($request->input('email')) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // --- Cek kredensial ---
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        // --- Cek apakah akun aktif ---
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        // --- Login berhasil ---
        RateLimiter::clear($throttleKey);

        // Regenerasi session ID untuk mencegah session fixation
        $request->session()->regenerate();

        // Jika mahasiswa belum lengkapi profil, redirect ke halaman profil
        if ($user->isMahasiswa() && ! $user->is_profile_completed) {
            return redirect()->route('mahasiswa.profile.complete');
        }

        return redirect()->intended(route($user->dashboardRoute()));
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    // ─── Registrasi Mahasiswa ────────────────────────────────

    /**
     * Tampilkan halaman registrasi (hanya untuk mahasiswa).
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi akun mahasiswa baru.
     *
     * Setelah register, user langsung auto-login dan diarahkan
     * ke halaman lengkapi profil (NIM, phone, foto).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        // Buat user mahasiswa baru (profil belum lengkap)
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->role = 'mahasiswa';
        $user->is_active = true;
        $user->is_profile_completed = false;
        $user->save();

        // Auto-login
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('mahasiswa.profile.complete')
            ->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil Anda.');
    }
}
