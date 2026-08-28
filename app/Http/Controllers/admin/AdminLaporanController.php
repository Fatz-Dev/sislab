<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangInventaris;
use App\Models\KelasPraktikum;
use App\Models\Ruangan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminLaporanController extends Controller
{
    /**
     * Halaman utama laporan — 2 tab: Rekap Nilai & Inventaris.
     * Juga menangani request AJAX dari DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $request->tab === 'inventaris'
                ? $this->datatableInventaris($request)
                : $this->datatableNilai($request);
        }

        $semesters = Semester::orderByDesc('id')->get();
        $ruangans  = Ruangan::orderBy('nama_ruangan')->get();

        return view('pages.admin.laporan.laporan', compact('semesters', 'ruangans'));
    }

    private function datatableNilai(Request $request)
    {
        $query = KelasPraktikum::with(['semester', 'dosen', 'laboran', 'tugasLaporans'])
            ->withCount(['approvedMahasiswas as jml_mhs', 'tugasLaporans as jml_tugas'])
            ->when($request->filled('semester_id'), fn($q) => $q->where('semester_id', $request->semester_id));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('semester', fn($row) => $row->semester?->nama_semester)
            ->addColumn('dosen',    fn($row) => $row->dosen?->name)
            ->addColumn('laboran',  fn($row) => $row->laboran?->name)
            ->make(true);
    }

    private function datatableInventaris(Request $request)
    {
        $query = BarangInventaris::with(['ruangan', 'kategoriBarang'])
            ->when($request->filled('ruangan_id'), fn($q) => $q->where('ruangan_id', $request->ruangan_id));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori',   fn($row) => $row->kategoriBarang?->nama_kategori)
            ->addColumn('ruangan',    fn($row) => $row->ruangan?->nama_ruangan)
            ->addColumn('total_stok', fn($row) => $row->total_stok)
            ->make(true);
    }

    /**
     * Halaman cetak rekap nilai per semester.
     */
    public function cetakNilai(Request $request)
    {
        $semester_id = $request->query('semester_id');
        $kelas_id    = $request->query('kelas_id');

        $semester = $semester_id ? Semester::find($semester_id) : null;

        $query = KelasPraktikum::with([
            'semester',
            'dosen',
            'laboran',
            'tugasLaporans',
            'approvedMahasiswas.nilais' => fn($q) => $q->when($kelas_id, fn($q2) => $q2->where('kelas_praktikum_id', $kelas_id)),
        ])
        ->when($semester_id, fn($q) => $q->where('semester_id', $semester_id))
        ->when($kelas_id,    fn($q) => $q->where('id', $kelas_id));

        $kelasList = $query->get();

        // Lampirkan nilai per mahasiswa per tugas agar mudah dirender di view
        foreach ($kelasList as $kelas) {
            $tugasIds = $kelas->tugasLaporans->pluck('id');
            foreach ($kelas->approvedMahasiswas as $mhs) {
                // key: tugas_id => nilai
                $nilaiMap = $mhs->nilais
                    ->whereIn('tugas_laporan_id', $tugasIds)
                    ->where('kelas_praktikum_id', $kelas->id)
                    ->keyBy('tugas_laporan_id');
                $mhs->nilaiMap = $nilaiMap;
            }
        }

        return view('pages.admin.laporan.cetak-nilai', compact('kelasList', 'semester'));
    }

    /**
     * Halaman cetak rekap inventaris laboratorium.
     */
    public function cetakInventaris(Request $request)
    {
        $ruangan_id = $request->query('ruangan_id');

        $ruangan = $ruangan_id ? Ruangan::find($ruangan_id) : null;

        $barangs = BarangInventaris::with(['ruangan', 'kategoriBarang'])
            ->when($ruangan_id, fn($q) => $q->where('ruangan_id', $ruangan_id))
            ->orderBy('ruangan_id')
            ->orderBy('nama_barang')
            ->get();

        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('pages.admin.laporan.cetak-inventaris', compact('barangs', 'ruangan', 'ruangans'));
    }
}
