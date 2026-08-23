<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TugasLaporan;
use App\Models\SubmissionLaporan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MahasiswaTugasController extends Controller
{
    /**
     * Menampilkan daftar tugas mahasiswa (opsional/global)
     */
    public function index()
    {
        $user = auth()->user();
        $kelasIds = $user->approvedKelas()->pluck('kelas_praktikums.id');

        $tugasBelumDikerjakan = TugasLaporan::whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDoesntHave('submissionLaporans', function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->with(['kelasPraktikum', 'laboran'])
            ->orderBy('deadline', 'asc')
            ->get();

        return view('pages.mahasiswa.tugas.tugas', compact('tugasBelumDikerjakan'));
    }

    /**
     * Menampilkan halaman upload tugas/laporan
     */
    public function show($kelas_id, $tugas_id)
    {
        $tugas = TugasLaporan::where('kelas_praktikum_id', $kelas_id)
                    ->findOrFail($tugas_id);

        $submission = SubmissionLaporan::where('tugas_laporan_id', $tugas_id)
                        ->where('mahasiswa_id', auth()->id())
                        ->first();

        $nilai = \App\Models\Nilai::where('tugas_laporan_id', $tugas_id)
                    ->where('mahasiswa_id', auth()->id())
                    ->first();

        return view('pages.mahasiswa.tugas.submit-tugas', compact('tugas', 'submission', 'kelas_id', 'nilai'));
    }

    /**
     * Memproses upload file laporan
     */
    public function submit(Request $request, $kelas_id, $tugas_id)
    {
        $request->validate([
            'file_laporan' => 'required|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240' // Max 10MB
        ]);

        $tugas = TugasLaporan::where('kelas_praktikum_id', $kelas_id)
                    ->findOrFail($tugas_id);

        $mahasiswaId = auth()->id();
        $now = Carbon::now();
        $deadline = Carbon::parse($tugas->deadline);
        $status = $now->greaterThan($deadline) ? 'terlambat' : 'tepat_waktu';

        // Cek jika sudah pernah mengumpulkan
        $submission = SubmissionLaporan::where('tugas_laporan_id', $tugas_id)
                        ->where('mahasiswa_id', $mahasiswaId)
                        ->first();

        // Handle File Upload
        if ($request->hasFile('file_laporan')) {
            $file = $request->file('file_laporan');
            $fileName = time() . '_' . $mahasiswaId . '_' . $file->getClientOriginalName();
            
            // Simpan ke disk 'public' di dalam folder 'submissions'
            $path = $file->storeAs('submissions', $fileName, 'public');

            // Hapus file lama jika ada
            if ($submission && $submission->file_laporan) {
                Storage::disk('public')->delete($submission->file_laporan);
            }

            if ($submission) {
                // Update submission lama
                $submission->update([
                    'file_laporan' => $path,
                    'tanggal_submit' => $now,
                    'status' => $status
                ]);
            } else {
                // Buat submission baru
                SubmissionLaporan::create([
                    'tugas_laporan_id' => $tugas_id,
                    'mahasiswa_id' => $mahasiswaId,
                    'file_laporan' => $path,
                    'tanggal_submit' => $now,
                    'status' => $status
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Laporan berhasil diunggah!'
                ]);
            }

            return redirect()->route('mahasiswa.tugas.show', [$kelas_id, $tugas_id])
                ->with('success', 'Laporan berhasil diunggah!');
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengunggah file laporan.'
            ], 400);
        }

        return back()->with('error', 'Gagal mengunggah file laporan.');
    }
}
