<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\TugasLaporan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin.
     */
    public function adminDashboard()
    {
        return view('pages.admin.dashboard');
    }

    /**
     * Dashboard Dosen.
     */
    public function dosenDashboard()
    {
        return view('pages.dosen.dashboard');
    }

    /**
     * Dashboard Laboran.
     */
    public function laboranDashboard()
    {
        return view('pages.laboran.dashboard');
    }

    /**
     * Dashboard Mahasiswa.
     */
    public function mahasiswaDashboard()
    {
        $user = auth()->user();
        $kelasIds = $user->approvedKelas()->pluck('kelas_praktikums.id');

        // Jadwal Hari Ini
        $jadwalHariIni = Jadwal::with(['kelasPraktikum', 'ruangan'])
            ->whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Tugas Perlu Dikerjakan
        $tugasMendesak = TugasLaporan::with(['kelasPraktikum'])
            ->whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDoesntHave('submissionLaporans', function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->orderBy('deadline', 'asc')
            ->take(5) // Limit to top 5 most urgent
            ->get();

        return view('pages.mahasiswa.dashboard', compact('jadwalHariIni', 'tugasMendesak'));
    }
}
