<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaKelasController extends Controller
{
    /**
     * Menampilkan daftar kelas yang sudah diikuti (approved) oleh mahasiswa.
     */
    public function myClass()
    {
        $mahasiswa = Auth::user();
        
        // Ambil kelas yang sudah di-approve
        $kelas = $mahasiswa->approvedKelas()
            ->with(['dosen', 'semester', 'ruangan'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.mahasiswa.kelas.myclass', compact('kelas'));
    }

    /**
     * Menampilkan detail kelas praktikum yang dipilih.
     */
    public function detailClass($id)
    {
        $mahasiswa = Auth::user();
        
        // Ambil data kelas beserta relasi yang dibutuhkan
        // Cek apakah mahasiswa ini sudah approved untuk kelas ini
        $kelas = $mahasiswa->approvedKelas()
            ->with(['dosen', 'laboran', 'semester', 'ruangan', 'tugasLaporans', 'modulPraktikums', 'jadwals'])
            ->where('kelas_praktikums.id', $id)
            ->firstOrFail();

        return view('pages.mahasiswa.kelas.detail-kelas', compact('kelas'));
    }
}
