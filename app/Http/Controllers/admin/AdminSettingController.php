<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        return view('pages.admin.pengaturan.setting');
    }

    public function storePengumuman(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_role' => 'required|array',
            'target_role.*' => 'in:dosen,laboran,mahasiswa'
        ]);

        $pengumuman = new \App\Models\Pengumuman();
        $pengumuman->judul = $validated['judul'];
        $pengumuman->isi = $validated['isi'];
        $pengumuman->target_role = $validated['target_role'];
        $pengumuman->tanggal_publish = \Illuminate\Support\Carbon::now();
        $pengumuman->admin_id = \Illuminate\Support\Facades\Auth::id();
        $pengumuman->save();

        // Broadcast event untuk setiap target role
        foreach ($pengumuman->target_role as $role) {
            event(new \App\Events\PengumumanPublished($pengumuman, $role));
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dipublikasikan'
        ]);
    }
}
