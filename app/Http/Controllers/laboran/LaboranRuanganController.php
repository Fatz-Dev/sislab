<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class LaboranRuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('created_at', 'desc')->get();
        return view('pages.laboran.ruangan.index', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255|unique:ruangans,nama_ruangan',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ruang Lab berhasil ditambahkan.']);
        }
        return redirect()->back()->with('success', 'Ruang Lab berhasil ditambahkan.');
    }
}
