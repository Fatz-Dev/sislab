<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return view('pages.admin.profile', compact('user'));
            case 'dosen':
                return view('pages.dosen.profile', compact('user'));
            case 'laboran':
                return view('pages.laboran.profile', compact('user'));
            case 'mahasiswa':
                return view('pages.mahasiswa.profile', compact('user'));
            default:
                abort(403, 'Role tidak dikenali.');
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
