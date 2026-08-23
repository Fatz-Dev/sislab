<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPraktikum;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenJadwalController extends Controller
{
    /**
     * Menyimpan jadwal pertemuan baru.
     */
    public function store(Request $request, $kelas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        // Pastikan dosen pengampu yang sedang login
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menambah jadwal pada kelas ini.');
        }

        $request->validate([
            'topik' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Validasi tambahan: Pastikan hari sesuai dengan jadwal kelas
        $hariMap = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0
        ];
        
        if ($kelas->hari && isset($hariMap[$kelas->hari])) {
            $expectedDay = $hariMap[$kelas->hari];
            $selectedDay = \Carbon\Carbon::parse($request->tanggal)->dayOfWeek;
            
            if ($selectedDay !== $expectedDay) {
                return back()->withErrors(['tanggal' => "Pertemuan harus diselenggarakan pada hari {$kelas->hari}."])->withInput();
            }
        }

        $jadwal = Jadwal::create([
            'kelas_praktikum_id' => $kelas->id,
            'ruangan_id' => $kelas->ruangan_id, // Default mengikuti ruangan kelas
            'topik' => $request->topik,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'terjadwal',
            'created_by' => Auth::id(),
        ]);

        // Kirim notifikasi ke laboran
        if ($kelas->laboran_id) {
            $laboran = \App\Models\User::find($kelas->laboran_id);
            if ($laboran) {
                $laboran->notify(new \App\Notifications\JadwalBaruNotification($jadwal, Auth::user()->name));
                broadcast(new \App\Events\JadwalBaruEvent($jadwal, $laboran->id, Auth::user()->name));
            }
        }

        return redirect()->route('dosen.kelas.show', $kelas->id)->with('success', 'Pertemuan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail jadwal (pertemuan).
     */
    public function show($kelas_id, $jadwal_id)
    {
        $kelas = KelasPraktikum::with('laboran')->findOrFail($kelas_id);
        
        if ($kelas->dosen_id !== Auth::id()) {
            abort(403);
        }

        $jadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)->findOrFail($jadwal_id);
        
        // Update status jadwal secara otomatis sebelum dirender
        $jadwal->updateStatusOtomatis();
        
        // Ambil absensi laboran jika sudah ada
        $absensiLaboran = null;
        if ($kelas->laboran_id) {
            $absensiLaboran = Absensi::where('jadwal_id', $jadwal->id)
                ->where('user_id', $kelas->laboran_id)
                ->where('tipe', 'laboran')
                ->first();
        }

        // Hitung urutan pertemuan (minggu ke-) berdasarkan urutan tanggal
        $semuaJadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)
                             ->orderBy('tanggal', 'asc')
                             ->orderBy('jam_mulai', 'asc')
                             ->pluck('id');
        $minggu_ke = $semuaJadwal->search($jadwal->id) + 1;

        return view('pages.dosen.jadwal.detail-jadwal', compact('kelas', 'jadwal', 'absensiLaboran', 'minggu_ke'));
    }

    /**
     * Mengabsen Laboran (AJAX Endpoint).
     */
    public function absenLaboran(Request $request, $kelas_id, $jadwal_id)
    {
        $request->validate([
            'status_hadir' => 'required|in:hadir,izin,sakit,alpha,belum_absen'
        ]);

        $kelas = KelasPraktikum::findOrFail($kelas_id);
        if ($kelas->dosen_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        if (!$kelas->laboran_id) {
            return response()->json(['success' => false, 'message' => 'Tidak ada laboran yang di-assign pada kelas ini.'], 400);
        }

        $jadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)->findOrFail($jadwal_id);
        $jadwal->updateStatusOtomatis(); // Pastikan status terupdate sebelum validasi

        $statusHadir = $request->status_hadir;
        if ($statusHadir === 'belum_absen') {
            $statusHadir = null; // Menghapus absensi (null)
        }

        // Validasi keterangan jika jadwal sudah selesai
        if ($jadwal->status === 'selesai' && $statusHadir !== null) {
            $request->validate([
                'keterangan' => 'required|string|max:500'
            ], [
                'keterangan.required' => 'Keterangan wajib diisi karena jam praktikum sudah selesai.'
            ]);
        }

        // Update or create absensi
        $absensi = Absensi::updateOrCreate(
            [
                'kelas_praktikum_id' => $kelas->id,
                'jadwal_id' => $jadwal->id,
                'user_id' => $kelas->laboran_id,
                'tipe' => 'laboran',
            ],
            [
                'tanggal' => $jadwal->tanggal,
                'diabsen_oleh' => Auth::id(),
                'status_hadir' => $statusHadir,
                'keterangan' => $request->keterangan ?? null
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absensi laboran berhasil disimpan.',
            'data' => $absensi
        ]);
    }
}
