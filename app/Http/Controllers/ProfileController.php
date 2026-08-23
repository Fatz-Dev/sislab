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
}
