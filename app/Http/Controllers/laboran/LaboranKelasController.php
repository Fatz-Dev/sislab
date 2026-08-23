<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\KelasPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboranKelasController extends Controller
{
    /**
     * Menampilkan daftar kelas yang ditugaskan kepada Laboran
     */
    public function index()
    {
        // Ambil kelas dimana laboran_id adalah ID user yang sedang login
        $kelas = KelasPraktikum::with(['dosen', 'semester', 'ruangan'])
            ->withCount(['approvedMahasiswas as terdaftar_count'])
            ->where('laboran_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.laboran.kelas.list-kelas', compact('kelas'));
    }

    /**
     * Menampilkan detail kelas untuk Laboran
     */
    public function show($id)
    {
        $kelas = KelasPraktikum::with(['dosen', 'semester', 'ruangan', 'jadwals', 'modulPraktikums'])
            ->withCount(['approvedMahasiswas as terdaftar_count'])
            ->findOrFail($id);

        // Pastikan kelas tersebut memang ditugaskan pada Laboran ini
        if ($kelas->laboran_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas praktikum ini.');
        }

        // Ambil mahasiswa yang enrolled (approved)
        $mahasiswa = $kelas->approvedMahasiswas;

        return view('pages.laboran.kelas.detail-kelas', compact('kelas', 'mahasiswa'));
    }
}
