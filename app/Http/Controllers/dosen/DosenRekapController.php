<?php

namespace App\Http\Controllers\Dosen;

use App\Exports\RekapAbsensiExport;
use App\Exports\RekapNilaiExport;
use App\Http\Controllers\Controller;
use App\Models\KelasPraktikum;
use App\Models\Semester;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DosenRekapController extends Controller
{
    /**
     * Menampilkan daftar kelas untuk direkap.
     */
    public function index()
    {
        $semesterAktif = Semester::where('is_active', true)->first();
        if (!$semesterAktif) {
            return back()->with('error', 'Tidak ada semester aktif.');
        }

        $kelas = KelasPraktikum::withCount('jadwals')
            ->with(['approvedMahasiswas', 'tugasLaporans'])
            ->where('dosen_id', Auth::id())
            ->where('semester_id', $semesterAktif->id)
            ->get();

        return view('pages.dosen.rekap.index', compact('kelas', 'semesterAktif'));
    }

    /**
     * Menampilkan detail rekapitulasi (jadwal, absen, nilai) per kelas.
     */
    public function show($id)
    {
        $kelas = KelasPraktikum::with([
                'semester',
                'dosen',
                'laboran',
                'ruangan',
                'jadwals' => function ($q) {
                    $q->orderBy('pertemuan_ke', 'asc');
                },
                'jadwals.absensis',
                'approvedMahasiswas.mahasiswaProfile',
                'tugasLaporans' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'tugasLaporans.nilai'
            ])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);

        return view('pages.dosen.rekap.show', compact('kelas'));
    }

    /**
     * Ekspor Rekap Presensi Mahasiswa ke Berkas Excel (.xlsx).
     */
    public function exportAbsensi($id)
    {
        $kelas = KelasPraktikum::with([
                'semester',
                'dosen',
                'laboran',
                'ruangan',
                'jadwals' => function ($q) {
                    $q->orderBy('pertemuan_ke', 'asc');
                },
                'jadwals.absensis',
                'approvedMahasiswas.mahasiswaProfile',
            ])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);

        $sanitizedName = Str::slug($kelas->nama_kelas, '_');
        $fileName = 'Rekap_Presensi_' . $sanitizedName . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new RekapAbsensiExport($kelas), $fileName);
    }

    /**
     * Ekspor Rekap Nilai Tugas Mahasiswa ke Berkas Excel (.xlsx).
     */
    public function exportNilai($id)
    {
        $kelas = KelasPraktikum::with([
                'semester',
                'dosen',
                'laboran',
                'ruangan',
                'approvedMahasiswas.mahasiswaProfile',
                'tugasLaporans' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'tugasLaporans.nilai'
            ])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);

        $sanitizedName = Str::slug($kelas->nama_kelas, '_');
        $fileName = 'Rekap_Nilai_' . $sanitizedName . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new RekapNilaiExport($kelas), $fileName);
    }

    /**
     * Tampilan halaman cetak rekapitulasi mandiri (print view).
     */
    public function cetak(Request $request, $id)
    {
        $type = $request->query('type', 'semua');

        $kelas = KelasPraktikum::with([
                'semester',
                'dosen',
                'laboran',
                'ruangan',
                'jadwals' => function ($q) {
                    $q->orderBy('pertemuan_ke', 'asc');
                },
                'jadwals.absensis',
                'approvedMahasiswas.mahasiswaProfile',
                'tugasLaporans' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'tugasLaporans.nilai'
            ])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);

        return view('pages.rekap.cetak', compact('kelas', 'type'));
    }

    /**
     * Unduh berkas rekapitulasi format PDF (.pdf).
     */
    public function exportPdf(Request $request, $id)
    {
        $type = $request->query('type', 'semua');

        $kelas = KelasPraktikum::with([
                'semester',
                'dosen',
                'laboran',
                'ruangan',
                'jadwals' => function ($q) {
                    $q->orderBy('pertemuan_ke', 'asc');
                },
                'jadwals.absensis',
                'approvedMahasiswas.mahasiswaProfile',
                'tugasLaporans' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'tugasLaporans.nilai'
            ])
            ->where('dosen_id', Auth::id())
            ->findOrFail($id);

        $sanitizedName = Str::slug($kelas->nama_kelas, '_');
        $fileName = 'Rekap_' . ucfirst($type) . '_' . $sanitizedName . '_' . date('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('pages.rekap.cetak-pdf', compact('kelas', 'type'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}
