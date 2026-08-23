<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenKelasController extends Controller
{
    /**
     * Menampilkan daftar kelas di mana dosen login sebagai pengajar.
     */
    public function index()
    {
        $dosen = Auth::user();

        // Mengambil kelas praktikum di mana dosen_id = ID dosen yang login
        // Tampilkan hanya yang semesternya aktif (opsional, tapi lebih baik)
        $kelas = KelasPraktikum::with(['semester', 'laboran'])
            ->withCount(['approvedMahasiswas as terdaftar_count'])
            ->where('dosen_id', $dosen->id)
            ->latest()
            ->paginate(12);

        return view('pages.dosen.kelas.list-kelas', compact('kelas'));
    }

    /**
     * Menampilkan detail kelas beserta daftar mahasiswa (yang disetujui).
     */
    public function show($id)
    {
        $dosen = Auth::user();

        $kelas = KelasPraktikum::with([
            'semester', 
            'laboran', 
            'ruangan',
            'approvedMahasiswas',
            'modulPraktikums',
            'jadwals' => function($q) {
                $q->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
            }
        ])
        ->where('dosen_id', $dosen->id)
        ->findOrFail($id);

        return view('pages.dosen.kelas.detail-kelas', compact('kelas'));
    }
    /**
     * Menyimpan modul materi baru yang diupload oleh dosen.
     */
    public function storeModul(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file_pdf' => 'required|mimes:pdf|max:5120', // Maks 5MB
        ], [
            'file_pdf.required' => 'File PDF harus diunggah.',
            'file_pdf.mimes' => 'File harus berupa dokumen PDF.',
            'file_pdf.max' => 'Ukuran PDF tidak boleh melebihi 5MB.',
        ]);

        $dosen = Auth::user();

        // Pastikan kelas ini milik dosen yang login
        $kelas = KelasPraktikum::where('dosen_id', $dosen->id)->findOrFail($id);

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Simpan ke storage/app/public/modul-praktikum
            $path = $file->storeAs('modul-praktikum', $filename, 'public');

            $modul = $kelas->modulPraktikums()->create([
                'judul' => $request->judul,
                'file_pdf' => $path,
                'uploaded_by' => $dosen->id,
            ]);

            return response()->json([
                'message' => 'Modul praktikum berhasil diunggah.',
                'modul' => [
                    'id' => $modul->id,
                    'judul' => $modul->judul,
                    'file_pdf' => \Illuminate\Support\Facades\Storage::url($modul->file_pdf),
                    'tanggal_upload' => $modul->created_at->format('d M Y')
                ]
            ]);
        }

        return response()->json(['message' => 'Gagal mengunggah modul praktikum.'], 422);
    }

    /**
     * Menghapus modul materi.
     */
    public function destroyModul(Request $request, $id, $modul_id)
    {
        $dosen = Auth::user();
        
        // Pastikan kelas ini milik dosen yang login
        $kelas = KelasPraktikum::where('dosen_id', $dosen->id)->findOrFail($id);
        
        $modul = $kelas->modulPraktikums()->findOrFail($modul_id);

        // Hapus file fisik
        if ($modul->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($modul->file_pdf)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($modul->file_pdf);
        }

        $modul->delete();

        return response()->json(['message' => 'Modul berhasil dihapus.']);
    }
}
