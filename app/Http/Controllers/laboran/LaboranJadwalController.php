<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPraktikum;
use App\Models\Absensi;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\LaporanLaboran;
use App\Notifications\JadwalBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboranJadwalController extends Controller
{
    /**
     * Menyimpan jadwal pertemuan baru.
     */
    public function store(Request $request, $kelas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        // Pastikan laboran pengampu yang sedang login
        if ($kelas->laboran_id !== Auth::id()) {
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

        // Kirim notifikasi ke dosen
        if ($kelas->dosen_id) {
            $dosen = User::find($kelas->dosen_id);
            if ($dosen) {
                $dosen->notify(new JadwalBaruNotification($jadwal, Auth::user()->name));
                broadcast(new \App\Events\JadwalBaruEvent($jadwal, $dosen->id, Auth::user()->name));
            }
        }

        return redirect()->route('laboran.kelas.show', $kelas->id)->with('success', 'Pertemuan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail jadwal dan daftar mahasiswa untuk diabsen Laboran.
     */
    public function show($kelas_id, $jadwal_id)
    {
        $kelas = KelasPraktikum::with('tugasLaporans')->findOrFail($kelas_id);
        
        if ($kelas->laboran_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        $jadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)->findOrFail($jadwal_id);
        
        // Update status jadwal secara otomatis sebelum dirender
        $jadwal->updateStatusOtomatis();

        // Ambil mahasiswa yang enrolled (approved), beserta absensinya untuk jadwal ini
        $mahasiswas = $kelas->approvedMahasiswas()
            ->with(['absensis' => function($query) use ($jadwal_id) {
                $query->where('jadwal_id', $jadwal_id)
                      ->where('tipe', 'mahasiswa');
            }])
            ->get()
            ->sortBy('name');

        // Hitung urutan pertemuan (minggu ke-) berdasarkan urutan tanggal
        $semuaJadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)
                             ->orderBy('tanggal', 'asc')
                             ->orderBy('jam_mulai', 'asc')
                             ->pluck('id');
        $minggu_ke = $semuaJadwal->search($jadwal->id) + 1;

        $laporan = LaporanLaboran::where('jadwal_id', $jadwal->id)->first();

        return view('pages.laboran.jadwal.detail-jadwal', compact('kelas', 'jadwal', 'mahasiswas', 'minggu_ke', 'laporan'));
    }

    /**
     * Menyimpan absensi mahasiswa secara massal (Bulk)
     */
    public function absenMahasiswaBulk(Request $request, $kelas_id, $jadwal_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        if ($kelas->laboran_id !== Auth::id()) {
            return redirect()->back()->withErrors('Unauthorized access.');
        }

        $jadwal = Jadwal::where('kelas_praktikum_id', $kelas->id)->findOrFail($jadwal_id);
        $jadwal->updateStatusOtomatis();

        $request->validate([
            'absensi' => 'required|array',
            'absensi.*.status_hadir' => 'nullable|in:hadir,izin,sakit,alpha',
            'absensi.*.keterangan' => 'nullable|string|max:500'
        ]);

        $absensiData = $request->input('absensi');
        
        // Validasi keterangan jika jadwal sudah selesai
        if ($jadwal->status === 'selesai') {
            foreach ($absensiData as $userId => $data) {
                if (isset($data['status_hadir']) && empty($data['keterangan'])) {
                    return redirect()->back()->withErrors("Keterangan wajib diisi untuk mahasiswa yang diubah absensinya karena jam praktikum sudah selesai.")->withInput();
                }
            }
        }

        foreach ($absensiData as $userId => $data) {
            $statusHadir = $data['status_hadir'] ?? null;
            
            if ($statusHadir) {
                Absensi::updateOrCreate(
                    [
                        'kelas_praktikum_id' => $kelas->id,
                        'jadwal_id' => $jadwal->id,
                        'user_id' => $userId,
                        'tipe' => 'mahasiswa',
                    ],
                    [
                        'tanggal' => $jadwal->tanggal,
                        'diabsen_oleh' => Auth::id(),
                        'status_hadir' => $statusHadir,
                        'keterangan' => $data['keterangan'] ?? null
                    ]
                );
            } else {
                // Jika tidak dipilih, atau di-reset ke kosong, biarkan saja (jangan dihapus otomatis, kecuali jika kita buat 'reset')
                // Menghapus data absensi
                Absensi::where([
                    'kelas_praktikum_id' => $kelas->id,
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $userId,
                    'tipe' => 'mahasiswa'
                ])->delete();
            }
        }

        return redirect()->back()->with('success', 'Absensi mahasiswa berhasil disimpan.');
    }
}
