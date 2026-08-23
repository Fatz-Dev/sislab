<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\KelasPraktikum;

class AdminJadwalController extends Controller
{
    /**
     * Tampilkan halaman daftar jadwal kelas praktikum.
     */
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan', 'asc')->get();
        return view('pages.admin.jadwal.list-jadwal', compact('ruangans'));
    }

    /**
     * API Endpoint untuk mendapatkan jadwal format FullCalendar.
     */
    public function data(Request $request)
    {
        $query = KelasPraktikum::with(['dosen', 'semester', 'ruangan']);

        if ($request->has('ruangan_id') && $request->ruangan_id != '') {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Ambil kelas yang memiliki jadwal
        $kelas = $query->whereNotNull('hari')
                       ->whereNotNull('jam_mulai')
                       ->whereNotNull('jam_selesai')
                       ->get();

        $mapHari = [
            'Minggu' => 0,
            'Senin'  => 1,
            'Selasa' => 2,
            'Rabu'   => 3,
            'Kamis'  => 4,
            'Jumat'  => 5,
            'Sabtu'  => 6,
        ];

        $events = [];

        $colors = [
            '#3b82f6', // Blue
            '#a855f7', // Purple
            '#f59e0b', // Amber
            '#ec4899', // Pink
            '#10b981', // Emerald
            '#6366f1', // Indigo
            '#f97316', // Orange
            '#06b6d4', // Cyan
            '#14b8a6', // Teal
            '#f43f5e', // Rose
        ];

        foreach ($kelas as $k) {
            $dayIndex = $mapHari[$k->hari] ?? null;
            if ($dayIndex !== null) {
                $colorIndex = ($k->ruangan_id ?? 0) % count($colors);
                $eventColor = $colors[$colorIndex];
                
                $ruanganName = $k->ruangan ? $k->ruangan->nama_ruangan : 'Tanpa Ruangan';

                $events[] = [
                    'id' => $k->id,
                    'title' => '[' . $ruanganName . '] ' . $k->nama_kelas . ($k->dosen . $k->dosen->name),
                    'roomName' => $ruanganName,
                    'daysOfWeek' => [$dayIndex],
                    'startTime' => $k->jam_mulai,
                    'endTime' => $k->jam_selesai,
                    'url' => route('admin.kelas.show', $k->id),
                    'color' => $eventColor,
                ];
            }
        }

        return response()->json($events);
    }
}
