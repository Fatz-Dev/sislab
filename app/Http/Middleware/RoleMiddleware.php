<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: middleware('role:admin,dosen')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Satu atau lebih role yang diizinkan
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah akun masih aktif
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
        }

        // Cek apakah role user termasuk dalam daftar role yang diizinkan
        if (! in_array($user->role, $roles, true)) {
            return redirect()->route($user->dashboardRoute())
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
