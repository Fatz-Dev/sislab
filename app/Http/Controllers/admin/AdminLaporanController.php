<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangInventaris;
use App\Models\KelasPraktikum;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\LaporanLaboran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\InventarisExport;
use App\Exports\LaboranExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLaporanController extends Controller
{
    /**
     * Halaman utama laporan — 2 tab: Rekap Nilai & Inventaris.
     * Juga menangani request AJAX dari DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tab === 'inventaris') {
                return $this->datatableInventaris($request);
            } elseif ($request->tab === 'laboran') {
                return $this->datatableLaboran($request);
            } else {
                return $this->datatableNilai($request);
            }
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

    private function datatableLaboran(Request $request)
    {
        $query = LaporanLaboran::with(['jadwal.kelasPraktikum.ruangan', 'laboran'])
            ->when($request->filled('status_admin'), fn($q) => $q->where('status_admin', $request->status_admin))
            ->when($request->filled('ruangan_id'), function($q) use ($request) {
                $q->whereHas('jadwal.kelasPraktikum', function($q2) use ($request) {
                    $q2->where('ruangan_id', $request->ruangan_id);
                });
            })
            ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($row) => $row->created_at->format('d/m/Y H:i'))
            ->addColumn('ruangan', fn($row) => $row->jadwal->kelasPraktikum->ruangan->nama_ruangan ?? '-')
            ->addColumn('kelas', fn($row) => $row->jadwal->kelasPraktikum->nama_kelas ?? '-')
            ->addColumn('laboran', fn($row) => $row->laboran->name ?? '-')
            ->make(true);
    }

    public function cetakInventarisPdf(Request $request)
    {
        $ruangan_id = $request->query('ruangan_id');
        $ruangan = $ruangan_id ? Ruangan::find($ruangan_id) : null;
        $barangs = BarangInventaris::with(['ruangan', 'kategoriBarang'])
            ->when($ruangan_id, fn($q) => $q->where('ruangan_id', $ruangan_id))
            ->orderBy('ruangan_id')->orderBy('nama_barang')->get();

        $pdf = Pdf::loadView('pages.admin.laporan.cetak-inventaris-pdf', compact('barangs', 'ruangan'));
        return $pdf->download('laporan-inventaris-'.date('YmdHis').'.pdf');
    }

    public function cetakInventarisExcel(Request $request)
    {
        return Excel::download(new InventarisExport($request->query('ruangan_id')), 'laporan-inventaris-'.date('YmdHis').'.xlsx');
    }

    public function cetakLaboran(Request $request)
    {
        $query = LaporanLaboran::with(['jadwal.kelasPraktikum.ruangan', 'laboran'])
            ->when($request->filled('status_admin'), fn($q) => $q->where('status_admin', $request->status_admin))
            ->when($request->filled('ruangan_id'), function($q) use ($request) {
                $q->whereHas('jadwal.kelasPraktikum', function($q2) use ($request) {
                    $q2->where('ruangan_id', $request->ruangan_id);
                });
            })
            ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date));

        $laporans = $query->orderBy('created_at', 'desc')->get();
        return view('pages.admin.laporan.cetak-laboran', compact('laporans'));
    }

    public function cetakLaboranPdf(Request $request)
    {
        $query = LaporanLaboran::with(['jadwal.kelasPraktikum.ruangan', 'laboran'])
            ->when($request->filled('status_admin'), fn($q) => $q->where('status_admin', $request->status_admin))
            ->when($request->filled('ruangan_id'), function($q) use ($request) {
                $q->whereHas('jadwal.kelasPraktikum', function($q2) use ($request) {
                    $q2->where('ruangan_id', $request->ruangan_id);
                });
            })
            ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) => $q->whereDate('created_at', '<=', $request->end_date));

        $laporans = $query->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('pages.admin.laporan.cetak-laboran-pdf', compact('laporans'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan-laboran-'.date('YmdHis').'.pdf');
    }

    public function cetakLaboranExcel(Request $request)
    {
        return Excel::download(new LaboranExport($request->query('status_admin'), $request->query('ruangan_id'), $request->query('start_date'), $request->query('end_date')), 'laporan-laboran-'.date('YmdHis').'.xlsx');
    }
}
