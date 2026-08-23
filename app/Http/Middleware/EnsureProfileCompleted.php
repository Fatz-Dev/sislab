<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Memaksa mahasiswa menyelesaikan profil sebelum bisa mengakses halaman lain.
     *
     * Middleware ini hanya berlaku untuk user dengan role 'mahasiswa'.
     * Jika profil belum lengkap, user akan di-redirect ke halaman lengkapi profil.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user && $user->isMahasiswa() && ! $user->is_profile_completed) {
            // Jika sudah di halaman complete-profile, lanjutkan saja
            if ($request->routeIs('mahasiswa.profile.complete*')) {
                return $next($request);
            }

            // Jika logout, izinkan
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('mahasiswa.profile.complete')
                ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
