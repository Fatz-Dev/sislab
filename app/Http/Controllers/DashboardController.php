<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangInventaris;
use App\Models\Ruangan;
use App\Models\KategoriBarang;
use App\Models\User;
use App\Models\KelasPraktikum;
use App\Models\KelasPraktikumMahasiswa;
use App\Models\Jadwal;
use App\Models\TugasLaporan;
use App\Models\ModulPraktikum;
use App\Models\SubmissionLaporan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin.
     */
    public function adminDashboard()
    {
        $totalJenisBarang = BarangInventaris::count();
        $totalUnitBarang = (int) (BarangInventaris::selectRaw('SUM(stok_baik + stok_rusak_ringan + stok_rusak_berat + stok_hilang) as total')->value('total') ?? 0);
        $stokBaik = (int) BarangInventaris::sum('stok_baik');
        $stokRusakRingan = (int) BarangInventaris::sum('stok_rusak_ringan');
        $stokRusakBerat = (int) BarangInventaris::sum('stok_rusak_berat');
        $stokHilang = (int) BarangInventaris::sum('stok_hilang');
        $stokPerluPerhatian = $stokRusakRingan + $stokRusakBerat + $stokHilang;
        $persentaseBaik = $totalUnitBarang > 0 ? round(($stokBaik / $totalUnitBarang) * 100, 1) : 0;

        $totalRuangan = Ruangan::count();
        $totalKategori = KategoriBarang::count();
        $totalKelas = KelasPraktikum::count();
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $pendingEnrollments = KelasPraktikumMahasiswa::where('status', 'pending')->count();

        // Data Chart 1: Kondisi Kelayakan Peralatan (Doughnut Chart)
        $chartKondisi = [
            'labels' => ['Kondisi Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'],
            'data' => [$stokBaik, $stokRusakRingan, $stokRusakBerat, $stokHilang],
        ];

        // Data Chart 2: Sebaran & Komposisi Inventaris per Ruang Lab (Grouped Bar Chart)
        $ruangans = Ruangan::with('barangInventaris')->orderBy('nama_ruangan')->get();
        $chartRuangan = [
            'labels' => $ruangans->pluck('nama_ruangan')->toArray(),
            'dataBaik' => $ruangans->map(fn($r) => (int) $r->barangInventaris->sum('stok_baik'))->toArray(),
            'dataRusak' => $ruangans->map(fn($r) => (int) $r->barangInventaris->sum(fn($b) => $b->stok_rusak_ringan + $b->stok_rusak_berat + $b->stok_hilang))->toArray(),
        ];

        // Data Chart 3: Distribusi Alat Berdasarkan Kategori (Horizontal Bar Chart)
        $kategoris = KategoriBarang::withCount('barangInventaris')->orderBy('nama_kategori')->get();
        $chartKategori = [
            'labels' => $kategoris->pluck('nama_kategori')->toArray(),
            'data' => $kategoris->pluck('barang_inventaris_count')->toArray(),
        ];

        // Tabel Operasional Cepat:
        // 1. Barang Inventaris Terbaru
        $recentItems = BarangInventaris::with(['ruangan', 'kategoriBarang'])->latest()->take(5)->get();

        // 2. Peralatan Perlu Perhatian / Pemeliharaan (Rusak Ringan / Berat / Hilang)
        $itemsAttention = BarangInventaris::with(['ruangan', 'kategoriBarang'])
            ->where(function ($q) {
                $q->where('stok_rusak_ringan', '>', 0)
                  ->orWhere('stok_rusak_berat', '>', 0)
                  ->orWhere('stok_hilang', '>', 0);
            })
            ->orderByRaw('(stok_rusak_ringan + stok_rusak_berat + stok_hilang) DESC')
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'totalJenisBarang',
            'totalUnitBarang',
            'stokBaik',
            'stokRusakRingan',
            'stokRusakBerat',
            'stokHilang',
            'stokPerluPerhatian',
            'persentaseBaik',
            'totalRuangan',
            'totalKategori',
            'totalKelas',
            'totalMahasiswa',
            'pendingEnrollments',
            'chartKondisi',
            'chartRuangan',
            'chartKategori',
            'recentItems',
            'itemsAttention'
        ));
    }

    /**
     * Dashboard Dosen.
     */
    public function dosenDashboard()
    {
        $dosen = auth()->user();

        // 1. Kelas Praktikum Dosen
        $kelasIds = KelasPraktikum::where('dosen_id', $dosen->id)->pluck('id');
        $kelasList = KelasPraktikum::with(['ruangan', 'laboran', 'semester'])
            ->withCount(['approvedMahasiswas', 'modulPraktikums', 'jadwals'])
            ->where('dosen_id', $dosen->id)
            ->get();

        $totalKelasDosen = $kelasList->count();
        $totalMahasiswaBimbingan = KelasPraktikumMahasiswa::whereIn('kelas_praktikum_id', $kelasIds)
            ->where('status', 'approved')
            ->count();
        $totalModul = ModulPraktikum::whereIn('kelas_praktikum_id', $kelasIds)->count();
        $totalJadwal = Jadwal::whereIn('kelas_praktikum_id', $kelasIds)->count();

        // 2. Data Chart 1: Kapasitas vs Mahasiswa Terdaftar per Kelas (Bar Chart)
        $chartKelas = [
            'labels' => $kelasList->pluck('nama_kelas')->toArray(),
            'dataTerdaftar' => $kelasList->pluck('approved_mahasiswas_count')->toArray(),
            'dataKapasitas' => $kelasList->pluck('kapasitas')->toArray(),
        ];

        // 3. Data Chart 2: Status Evaluasi / Penilaian Laporan Mahasiswa (Doughnut Chart)
        $tugasIds = TugasLaporan::whereIn('kelas_praktikum_id', $kelasIds)->pluck('id');
        $sudahDinilai = SubmissionLaporan::whereIn('tugas_laporan_id', $tugasIds)
            ->whereNotNull('nilai')
            ->count();
        $belumDinilai = SubmissionLaporan::whereIn('tugas_laporan_id', $tugasIds)
            ->whereNull('nilai')
            ->count();

        $chartPenilaian = [
            'labels' => ['Sudah Dinilai', 'Menunggu Penilaian'],
            'data' => [$sudahDinilai, $belumDinilai],
        ];

        // 4. Jadwal Praktikum Terdekat Dosen
        $jadwalDosen = Jadwal::with(['kelasPraktikum', 'ruangan'])
            ->whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->take(5)
            ->get();

        return view('pages.dosen.dashboard', compact(
            'dosen',
            'kelasList',
            'totalKelasDosen',
            'totalMahasiswaBimbingan',
            'totalModul',
            'totalJadwal',
            'chartKelas',
            'chartPenilaian',
            'jadwalDosen',
            'sudahDinilai',
            'belumDinilai'
        ));
    }

    /**
     * Dashboard Laboran.
     */
    public function laboranDashboard()
    {
        $laboran = auth()->user();

        // 1. Kelas Praktikum Didampingi Laboran
        $kelasLaboranIds = KelasPraktikum::where('laboran_id', $laboran->id)->pluck('id');
        $kelasList = KelasPraktikum::with(['dosen', 'ruangan'])
            ->withCount(['approvedMahasiswas', 'jadwals'])
            ->where('laboran_id', $laboran->id)
            ->get();

        $totalKelasDidampingi = $kelasList->count();

        // 2. Inventaris Laboratorium
        $totalJenisBarang = BarangInventaris::count();
        $totalUnitBarang = (int) (BarangInventaris::selectRaw('SUM(stok_baik + stok_rusak_ringan + stok_rusak_berat + stok_hilang) as total')->value('total') ?? 0);
        $stokBaik = (int) BarangInventaris::sum('stok_baik');
        $stokRusakRingan = (int) BarangInventaris::sum('stok_rusak_ringan');
        $stokRusakBerat = (int) BarangInventaris::sum('stok_rusak_berat');
        $stokHilang = (int) BarangInventaris::sum('stok_hilang');
        $stokPerluPerhatian = $stokRusakRingan + $stokRusakBerat + $stokHilang;
        $persentaseBaik = $totalUnitBarang > 0 ? round(($stokBaik / $totalUnitBarang) * 100, 1) : 0;
        $totalRuangan = Ruangan::count();

        // 3. Antrean Laporan Praktikum yang Perlu Dinilai
        $tugasIds = TugasLaporan::whereIn('kelas_praktikum_id', $kelasLaboranIds)->pluck('id');
        $pendingSubmissions = SubmissionLaporan::with(['tugasLaporan.kelasPraktikum', 'mahasiswa'])
            ->whereIn('tugas_laporan_id', $tugasIds)
            ->whereNull('nilai')
            ->latest()
            ->take(5)
            ->get();

        $totalPendingSubmissions = SubmissionLaporan::whereIn('tugas_laporan_id', $tugasIds)
            ->whereNull('nilai')
            ->count();
        $totalGradedSubmissions = SubmissionLaporan::whereIn('tugas_laporan_id', $tugasIds)
            ->whereNotNull('nilai')
            ->count();

        // 4. Data Chart 1: Kondisi Kelayakan Alat Lab (Doughnut Chart)
        $chartKondisi = [
            'labels' => ['Kondisi Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'],
            'data' => [$stokBaik, $stokRusakRingan, $stokRusakBerat, $stokHilang],
        ];

        // 5. Data Chart 2: Sebaran Alat per Ruang Lab (Grouped Bar Chart)
        $ruangans = Ruangan::with('barangInventaris')->orderBy('nama_ruangan')->get();
        $chartRuangan = [
            'labels' => $ruangans->pluck('nama_ruangan')->toArray(),
            'dataBaik' => $ruangans->map(fn($r) => (int) $r->barangInventaris->sum('stok_baik'))->toArray(),
            'dataRusak' => $ruangans->map(fn($r) => (int) $r->barangInventaris->sum(fn($b) => $b->stok_rusak_ringan + $b->stok_rusak_berat + $b->stok_hilang))->toArray(),
        ];

        // 6. Data Chart 3: Status Koreksi Laporan (Doughnut Chart)
        $chartKoreksi = [
            'labels' => ['Sudah Dinilai', 'Menunggu Koreksi'],
            'data' => [$totalGradedSubmissions, $totalPendingSubmissions],
        ];

        // 7. Peralatan Perlu Pemeliharaan
        $itemsAttention = BarangInventaris::with(['ruangan', 'kategoriBarang'])
            ->where(function ($q) {
                $q->where('stok_rusak_ringan', '>', 0)
                  ->orWhere('stok_rusak_berat', '>', 0)
                  ->orWhere('stok_hilang', '>', 0);
            })
            ->orderByRaw('(stok_rusak_ringan + stok_rusak_berat + stok_hilang) DESC')
            ->take(5)
            ->get();

        // 8. Jadwal Praktikum yang Didampingi Laboran
        $jadwalLaboran = Jadwal::with(['kelasPraktikum', 'ruangan'])
            ->whereIn('kelas_praktikum_id', $kelasLaboranIds)
            ->whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->take(5)
            ->get();

        return view('pages.laboran.dashboard', compact(
            'laboran',
            'kelasList',
            'totalKelasDidampingi',
            'totalJenisBarang',
            'totalUnitBarang',
            'stokBaik',
            'stokRusakRingan',
            'stokRusakBerat',
            'stokHilang',
            'stokPerluPerhatian',
            'persentaseBaik',
            'totalRuangan',
            'pendingSubmissions',
            'totalPendingSubmissions',
            'totalGradedSubmissions',
            'chartKondisi',
            'chartRuangan',
            'chartKoreksi',
            'itemsAttention',
            'jadwalLaboran'
        ));
    }

    /**
     * Dashboard Mahasiswa.
     */
    public function mahasiswaDashboard()
    {
        $user = auth()->user();
        $kelasIds = $user->approvedKelas()->pluck('kelas_praktikums.id');

        // Jadwal Hari Ini
        $jadwalHariIni = Jadwal::with(['kelasPraktikum', 'ruangan'])
            ->whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Tugas Perlu Dikerjakan
        $tugasMendesak = TugasLaporan::with(['kelasPraktikum'])
            ->whereIn('kelas_praktikum_id', $kelasIds)
            ->whereDoesntHave('submissionLaporans', function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->orderBy('deadline', 'asc')
            ->take(5) // Limit to top 5 most urgent
            ->get();

        return view('pages.mahasiswa.dashboard', compact('jadwalHariIni', 'tugasMendesak'));
    }
}
