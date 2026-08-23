<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\KelasPraktikum;
use App\Models\TugasLaporan;
use App\Models\SubmissionLaporan;
use App\Models\Nilai;
use App\Notifications\TugasBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LaboranTugasController extends Controller
{
    /**
     * Tampilkan form untuk membuat tugas baru.
     */
    public function create(Request $request, $kelas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        if ($kelas->laboran_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses kelas ini.');
        }

        $jadwal_id = $request->query('jadwal_id');
        $tugas = null;

        return view('pages.laboran.tugas.add-tugas', compact('kelas', 'jadwal_id', 'tugas'));
    }

    /**
     * Simpan tugas baru ke database via AJAX.
     */
    public function store(Request $request, $kelas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        if ($kelas->laboran_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline'  => 'required|date',
            'jadwal_id' => 'nullable|exists:jadwals,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $tugas = new TugasLaporan();
        $tugas->kelas_praktikum_id = $kelas->id;
        $tugas->laboran_id = Auth::id();
        $tugas->jadwal_id = $request->jadwal_id;
        $tugas->judul = $request->judul;
        $tugas->deskripsi = $request->deskripsi;
        $tugas->deadline = $request->deadline;
        $tugas->save();

        try {
            // Kirim notifikasi ke semua mahasiswa yang enrolled (approved) di kelas ini
            $mahasiswas = $kelas->approvedMahasiswas;
            
            if ($mahasiswas->count() > 0) {
                // Batch insert notifications (faster than looping)
                \Illuminate\Support\Facades\Notification::send($mahasiswas, new TugasBaruNotification($tugas, $kelas));
                
                // Broadcast ONE event to ALL students' channels (prevent HTTP timeouts)
                // Pusher allows max 100 channels per broadcast request. Chunk it if there are more.
                $mahasiswaIds = $mahasiswas->pluck('id')->toArray();
                foreach (array_chunk($mahasiswaIds, 100) as $chunk) {
                    broadcast(new \App\Events\TugasBaruEvent($tugas, $kelas, $chunk));
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending TugasBaruNotification: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengirim notifikasi: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Tugas berhasil ditambahkan'
        ]);
    }

    /**
     * Tampilkan form edit tugas.
     */
    public function edit(Request $request, $kelas_id, $tugas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        if ($kelas->laboran_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses kelas ini.');
        }

        $tugas = TugasLaporan::where('kelas_praktikum_id', $kelas->id)->findOrFail($tugas_id);
        $jadwal_id = $request->query('jadwal_id');

        return view('pages.laboran.tugas.add-tugas', compact('kelas', 'jadwal_id', 'tugas'));
    }

    /**
     * Perbarui data tugas via AJAX.
     */
    public function update(Request $request, $kelas_id, $tugas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        if ($kelas->laboran_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tugas = TugasLaporan::where('kelas_praktikum_id', $kelas->id)->findOrFail($tugas_id);

        $validator = Validator::make($request->all(), [
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline'  => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $tugas->judul = $request->judul;
        $tugas->deskripsi = $request->deskripsi;
        $tugas->deadline = $request->deadline;
        $tugas->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Tugas berhasil diperbarui'
        ]);
    }

    /**
     * Menampilkan daftar mahasiswa yang mengumpulkan tugas beserta penilainnya.
     */
    public function submissions($kelas_id, $tugas_id)
    {
        $kelas = KelasPraktikum::with('mahasiswas')->findOrFail($kelas_id);
        $tugas = TugasLaporan::where('kelas_praktikum_id', $kelas_id)->findOrFail($tugas_id);

        // Ambil semua submission untuk tugas ini
        $submissions = SubmissionLaporan::where('tugas_laporan_id', $tugas_id)->get()->keyBy('mahasiswa_id');
        
        // Ambil semua nilai untuk tugas ini
        $nilais = Nilai::where('tugas_laporan_id', $tugas_id)->get()->keyBy('mahasiswa_id');

        return view('pages.laboran.tugas.submissions', compact('kelas', 'tugas', 'submissions', 'nilais'));
    }

    /**
     * Menyimpan atau mengupdate nilai mahasiswa.
     */
    public function grade(Request $request, $kelas_id, $tugas_id, $mahasiswa_id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        $nilai = Nilai::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa_id,
                'tugas_laporan_id' => $tugas_id,
            ],
            [
                'kelas_praktikum_id' => $kelas_id,
                'laboran_id' => auth()->id(),
                'nilai' => $request->nilai,
                'keterangan' => $request->keterangan
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil disimpan!'
        ]);
    }
}
