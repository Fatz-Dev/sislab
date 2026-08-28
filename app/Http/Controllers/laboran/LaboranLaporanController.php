<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasPraktikum;
use App\Models\LaporanLaboran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboranLaporanController extends Controller
{
    /**
     * Display a listing of the reports by Laboran.
     */
    public function index()
    {
        $laporans = LaporanLaboran::with(['jadwal.kelasPraktikum.ruangan'])
            ->where('laboran_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $existingJadwalIds = $laporans->pluck('jadwal_id')->toArray();
        $jadwals = Jadwal::with('kelasPraktikum.ruangan')
            ->where('status', 'selesai')
            ->whereHas('kelasPraktikum', function ($query) {
                $query->where('laboran_id', Auth::id());
            })
            ->whereNotIn('id', $existingJadwalIds)
            ->get();

        return view('pages.laboran.laporan.index', compact('laporans', 'jadwals'));
    }

    /**
     * Store a newly created Laporan Laboran in storage.
     */
    public function store(Request $request, $jadwal_id)
    {
        $jadwal = Jadwal::with('kelasPraktikum')->findOrFail($jadwal_id);
        
        // Pastikan laboran pengampu yang sedang login
        if ($jadwal->kelasPraktikum->laboran_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak membuat laporan pada kelas ini.');
        }

        // Pastikan jadwal sudah selesai
        if ($jadwal->status !== 'selesai') {
            if($request->ajax()) return response()->json(['success' => false, 'message' => 'Laporan kerusakan hanya dapat dibuat setelah jadwal praktikum selesai.']);
            return back()->withErrors('Laporan kerusakan hanya dapat dibuat setelah jadwal praktikum selesai.')->withInput();
        }

        // Cek apakah laporan sudah pernah dibuat
        $existing = LaporanLaboran::where('jadwal_id', $jadwal->id)->first();
        if ($existing) {
            if($request->ajax()) return response()->json(['success' => false, 'message' => 'Laporan untuk pertemuan ini sudah dibuat sebelumnya.']);
            return back()->withErrors('Laporan untuk pertemuan ini sudah dibuat sebelumnya.');
        }

        $request->validate([
            'status_sop' => 'required|in:dijalankan,dijalankan_sebagian,tidak_dijalankan',
            'kelayakan_barang' => 'required|in:semua_layak,ada_yang_rusak',
            'catatan_temuan' => 'required_if:kelayakan_barang,ada_yang_rusak|nullable|string|max:1000',
        ]);

        LaporanLaboran::create([
            'jadwal_id' => $jadwal->id,
            'laboran_id' => Auth::id(),
            'status_sop' => $request->status_sop,
            'kelayakan_barang' => $request->kelayakan_barang,
            'catatan_temuan' => $request->kelayakan_barang == 'ada_yang_rusak' ? $request->catatan_temuan : null,
            'status_admin' => 'pending',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Laporan kerusakan dan kondisi barang berhasil dikirim ke Admin.']);
        }
        return redirect()->back()->with('success', 'Laporan kerusakan dan kondisi barang berhasil dikirim ke Admin.');
    }
}
