<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class LaboranKategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::orderBy('created_at', 'desc')->get();
        return view('pages.laboran.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_barangs,nama_kategori',
        ]);

        KategoriBarang::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.']);
        }
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }
}
