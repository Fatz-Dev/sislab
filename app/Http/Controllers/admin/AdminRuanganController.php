<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangInventaris;
use App\Models\KategoriBarang;
use App\Models\KelasPraktikum;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class AdminRuanganController extends Controller
{
    /**
     * List semua ruangan.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ruangan::withCount('barangInventaris');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('total_barang', fn($row) => $row->barang_inventaris_count)
                ->make(true);
        }

        return view('pages.admin.ruangan.list-ruang');
    }

    /**
     * Detail ruangan: Card Kelas Praktikum + Card Barang.
     */
    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();
        return view('pages.admin.ruangan.detail-ruang', compact('ruangan', 'kategoris'));
    }

    /**
     * AJAX: DataTables barang per ruangan.
     */
    public function barangData(Request $request, $id)
    {
        $query = BarangInventaris::with('kategoriBarang')->where('ruangan_id', $id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', fn($row) => $row->kategoriBarang?->nama_kategori)
            ->addColumn('total_stok', fn($row) => $row->total_stok)
            ->addColumn('kondisi', function ($row) {
                $parts = [];
                if ($row->stok_baik > 0) $parts[] = '<span class="text-green-600 dark:text-green-400">' . $row->stok_baik . ' Baik</span>';
                if ($row->stok_rusak_ringan > 0) $parts[] = '<span class="text-yellow-600 dark:text-yellow-400">' . $row->stok_rusak_ringan . ' R.Ringan</span>';
                if ($row->stok_rusak_berat > 0) $parts[] = '<span class="text-red-600 dark:text-red-400">' . $row->stok_rusak_berat . ' R.Berat</span>';
                if ($row->stok_hilang > 0) $parts[] = '<span class="text-slate-500">' . $row->stok_hilang . ' Hilang</span>';
                return implode(' · ', $parts) ?: '<span class="text-slate-400">0</span>';
            })
            ->addColumn('nama_barang', fn($row) => '<a href="'.route('admin.barang.show', $row->id).'" class="text-green-600 dark:text-green-400 hover:underline font-medium">'.$row->nama_barang.'</a>')
            ->rawColumns(['kondisi', 'nama_barang'])
            ->make(true);
    }

    /**
     * AJAX: DataTables kelas praktikum per ruangan.
     */
    public function kelasData(Request $request, $id)
    {
        $query = KelasPraktikum::with(['dosen', 'laboran', 'semester'])
            ->where('ruangan_id', $id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_kelas', fn($row) => '<a href="'.route('admin.kelas.show', $row->id).'" class="text-green-600 dark:text-green-400 hover:underline font-medium">'.$row->nama_kelas.'</a>')
            ->addColumn('dosen_name', fn($row) => $row->dosen?->name)
            ->addColumn('laboran_name', fn($row) => $row->laboran?->name)
            ->addColumn('semester_name', fn($row) => $row->semester?->nama_semester ?? '')
            ->rawColumns(['nama_kelas'])
            ->make(true);
    }

    /**
     * Simpan barang baru ke ruangan.
     */
    public function storeBarang(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_barangs,id',
            'stok_baik' => 'required|integer|min:0',
            'stok_rusak_ringan' => 'nullable|integer|min:0',
            'stok_rusak_berat' => 'nullable|integer|min:0',
            'stok_hilang' => 'nullable|integer|min:0',
            'merk' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        BarangInventaris::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'ruangan_id' => $id,
            'stok_baik' => $request->stok_baik ?? 0,
            'stok_rusak_ringan' => $request->stok_rusak_ringan ?? 0,
            'stok_rusak_berat' => $request->stok_rusak_berat ?? 0,
            'stok_hilang' => $request->stok_hilang ?? 0,
            'merk' => $request->merk,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan.']);
    }

    /**
     * Update barang.
     */
    public function updateBarang(Request $request, $ruanganId, $barangId)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_barangs,id',
            'stok_baik' => 'required|integer|min:0',
            'stok_rusak_ringan' => 'nullable|integer|min:0',
            'stok_rusak_berat' => 'nullable|integer|min:0',
            'stok_hilang' => 'nullable|integer|min:0',
            'merk' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $barang = BarangInventaris::where('ruangan_id', $ruanganId)->findOrFail($barangId);
        $barang->update($request->only([
            'kode_barang', 'nama_barang', 'kategori_id',
            'stok_baik', 'stok_rusak_ringan', 'stok_rusak_berat', 'stok_hilang',
            'merk', 'keterangan',
        ]));

        return response()->json(['success' => true, 'message' => 'Barang berhasil diperbarui.']);
    }

    /**
     * Import Excel barang ke ruangan.
     */
    public function importBarang(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ]);

        Excel::import(new \App\Imports\BarangImport($id), $request->file('file'));

        return response()->json(['success' => true, 'message' => 'Data berhasil diimpor.']);
    }
}
