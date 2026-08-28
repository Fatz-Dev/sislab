<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\BarangInventaris;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LaboranBarangController extends Controller
{
    /**
     * Menampilkan list seluruh barang inventaris (global) untuk Laboran (Read-only).
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BarangInventaris::with(['ruangan', 'kategoriBarang'])
                ->when($request->filled('ruangan_id'), fn($q) => $q->where('ruangan_id', $request->ruangan_id))
                ->when($request->filled('kategori_id'), fn($q) => $q->where('kategori_id', $request->kategori_id));

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('kategori', fn($row) => $row->kategoriBarang?->nama_kategori)
                ->addColumn('ruangan', fn($row) => $row->ruangan?->nama_ruangan)
                ->addColumn('total_stok', fn($row) => $row->total_stok)
                ->addColumn('kondisi', function ($row) {
                    $parts = [];
                    if ($row->stok_baik > 0) $parts[] = '<span class="text-green-600 dark:text-green-400">' . $row->stok_baik . ' Baik</span>';
                    if ($row->stok_rusak_ringan > 0) $parts[] = '<span class="text-yellow-600 dark:text-yellow-400">' . $row->stok_rusak_ringan . ' R.Ringan</span>';
                    if ($row->stok_rusak_berat > 0) $parts[] = '<span class="text-red-600 dark:text-red-400">' . $row->stok_rusak_berat . ' R.Berat</span>';
                    if ($row->stok_hilang > 0) $parts[] = '<span class="text-slate-500">' . $row->stok_hilang . ' Hilang</span>';
                    return implode(' · ', $parts) ?: '<span class="text-slate-400">0</span>';
                })
                ->rawColumns(['kondisi'])
                ->make(true);
        }

        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan')->get();
        $kategoris = \App\Models\KategoriBarang::orderBy('nama_kategori')->get();
        return view('pages.laboran.barang.list-barang', compact('ruangans', 'kategoris'));
    }
}
