<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class MahasiswaProfileController extends Controller
{
    /**
     * Tampilkan form lengkapi profil mahasiswa.
     */
    public function showCompleteForm()
    {
        /** @var User $user */
        $user = Auth::user();

        // Jika profil sudah lengkap, redirect ke dashboard
        if ($user->is_profile_completed) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return view('pages.mahasiswa.profile-completion', compact('user'));
    }

    /**
     * Proses lengkapi profil mahasiswa.
     *
     * Field wajib: nip_nim (NIM), phone
     * Field opsional: photo
     */
    public function completeProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nim'      => ['required', 'string', 'max:30', 'unique:mahasiswas,nim'],
            'angkatan' => ['required', 'string', 'max:4'],
            'phone'    => ['required', 'string', 'max:20'],
            'photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nim.required'      => 'NIM wajib diisi.',
            'nim.unique'        => 'NIM sudah terdaftar di sistem.',
            'angkatan.required' => 'Tahun angkatan wajib diisi.',
            'phone.required'    => 'Nomor HP wajib diisi.',
            'photo.image'       => 'File harus berupa gambar.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ]);

        // Simpan foto jika ada
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos/mahasiswa', 'public');
            $user->photo = $path;
        }

        $user->phone = $validated['phone'];
        $user->is_profile_completed = true;
        $user->save();

        // Simpan data profil spesifik mahasiswa
        $user->mahasiswaProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nim'      => $validated['nim'],
                'angkatan' => $validated['angkatan'],
                'jurusan'  => 'Pendidikan Fisika',
            ]
        );

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Profil berhasil dilengkapi! Selamat datang di Sislab Fisika.');
    }
}
