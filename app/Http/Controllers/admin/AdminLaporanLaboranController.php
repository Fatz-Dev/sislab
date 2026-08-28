<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanLaboran;
use Illuminate\Http\Request;

class AdminLaporanLaboranController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index()
    {
        $laporans = LaporanLaboran::with(['jadwal.kelasPraktikum.ruangan', 'laboran'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.laporan_laboran.index', compact('laporans'));
    }

    /**
     * Mark report as reviewed.
     */
    public function review(Request $request, $id)
    {
        $laporan = LaporanLaboran::findOrFail($id);
        $laporan->update([
            'status_admin' => 'reviewed'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui menjadi Reviewed.'
            ]);
        }

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
