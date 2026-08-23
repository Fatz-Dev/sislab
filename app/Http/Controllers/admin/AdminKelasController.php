<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPraktikum;
use App\Models\Semester;
use App\Models\User;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminKelasController extends Controller
{
    /**
     * Tampilkan halaman manajemen kelas.
     */
    public function index()
    {
        $semesters = Semester::orderBy('id', 'desc')->get();
        $dosens = User::where('role', 'dosen')->where('is_active', true)->get();
        $laborans = User::where('role', 'laboran')->where('is_active', true)->get();
        $ruangans = Ruangan::orderBy('nama_ruangan', 'asc')->get();
        $activeSemester = Semester::where('is_active', true)->first();

        return view('pages.admin.kelas.list-kelas', compact('semesters', 'dosens', 'laborans', 'ruangans', 'activeSemester'));
    }

    /**
     * Menyimpan kelas praktikum baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'  => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
            'dosen_id'    => 'required|exists:users,id',
            'laboran_id'  => 'required|exists:users,id|unique:kelas_praktikums,laboran_id',
            'kapasitas'   => 'required|integer|min:1',
            'ruangan_id'  => 'required|exists:ruangans,id',
            'hari'        => 'required|string',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek Bentrok
        $isClash = KelasPraktikum::where('semester_id', $request->semester_id)
            ->where('ruangan_id', $request->ruangan_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })->exists();

        if ($isClash) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sudah dipakai oleh kelas lain pada hari dan jam tersebut!'
            ], 422);
        }

        KelasPraktikum::create([
            'nama_kelas'  => $request->nama_kelas,
            'semester_id' => $request->semester_id,
            'dosen_id'    => $request->dosen_id,
            'laboran_id'  => $request->laboran_id,
            'kapasitas'   => $request->kapasitas,
            'ruangan_id'  => $request->ruangan_id,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => 'closed', // Default selalu tertutup
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas praktikum baru berhasil ditambahkan.'
        ]);
    }

    /**
     * DataTables untuk kelas praktikum.
     */
    public function data()
    {
        $kelas = KelasPraktikum::with(['semester', 'dosen', 'laboran', 'ruangan'])->select('kelas_praktikums.*');

        return DataTables::of($kelas)
            ->editColumn('nama_kelas', function ($k) {
                $url = route('admin.kelas.show', $k->id);
                return '<a href="' . $url . '" class="text-black dark:text-white font-semibold hover:underline">' . htmlspecialchars($k->nama_kelas) . '</a>';
            })
            ->addColumn('semester_nama', function ($k) {
                return $k->semester ? $k->semester->nama_semester : '-';
            })
            ->addColumn('dosen_nama', function ($k) {
                return $k->dosen ? $k->dosen->name : '-';
            })
            ->addColumn('laboran_nama', function ($k) {
                return $k->laboran ? $k->laboran->name : '-';
            })
            ->addColumn('kapasitas_info', function ($k) {
                return $k->approvedCount() . ' / ' . $k->kapasitas;
            })
            ->addColumn('action', function ($k) {
                $isChecked = $k->status === 'open' ? 'checked' : '';
                return '
                    <div class="flex items-center justify-center">
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" class="sr-only peer toggle-status" data-id="' . $k->id . '" ' . $isChecked . '>
                            <div class="w-11 h-6 shrink-0 bg-slate-300 rounded-full peer-checked:bg-emerald-600 transition-colors shadow-inner"></div>
                            <div class="absolute left-[2px] top-[2px] w-5 h-5 shrink-0 bg-white border border-slate-200 rounded-full transition-transform peer-checked:translate-x-2.5 shadow-sm"></div>
                        </label>
                    </div>
                ';
            })
            ->rawColumns(['action', 'nama_kelas'])
            ->make(true);
    }

    /**
     * Menampilkan detail kelas.
     */
    public function show($id)
    {
        $kelas = KelasPraktikum::with(['semester', 'dosen', 'laboran', 'mahasiswas', 'ruangan'])->findOrFail($id);
        $semesters = Semester::orderBy('id', 'desc')->get();
        $dosens = User::where('role', 'dosen')->where('is_active', true)->get();
        $laborans = User::where('role', 'laboran')->where('is_active', true)->get();
        $ruangans = Ruangan::orderBy('nama_ruangan', 'asc')->get();

        return view('pages.admin.kelas.detail-kelas', compact('kelas', 'semesters', 'dosens', 'laborans', 'ruangans'));
    }

    /**
     * Memperbarui kelas praktikum.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas'  => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
            'dosen_id'    => 'required|exists:users,id',
            'laboran_id'  => 'required|exists:users,id|unique:kelas_praktikums,laboran_id,' . $id,
            'kapasitas'   => 'required|integer|min:1',
            'ruangan_id'  => 'required|exists:ruangans,id',
            'hari'        => 'required|string',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cek Bentrok
        $isClash = KelasPraktikum::where('semester_id', $request->semester_id)
            ->where('ruangan_id', $request->ruangan_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id) // Abaikan id ini sendiri
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })->exists();

        if ($isClash) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sudah dipakai oleh kelas lain pada hari dan jam tersebut!'
            ], 422);
        }

        $kelas = KelasPraktikum::findOrFail($id);
        $kelas->update([
            'nama_kelas'  => $request->nama_kelas,
            'semester_id' => $request->semester_id,
            'dosen_id'    => $request->dosen_id,
            'laboran_id'  => $request->laboran_id,
            'kapasitas'   => $request->kapasitas,
            'ruangan_id'  => $request->ruangan_id,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        // Eager load data terkait untuk dikembalikan ke frontend
        $kelas->load(['semester', 'dosen', 'laboran', 'ruangan']);

        return response()->json([
            'success' => true,
            'message' => 'Detail kelas berhasil diperbarui.',
            'data' => [
                'nama_kelas' => $kelas->nama_kelas,
                'semester_id' => $kelas->semester_id,
                'semester_nama' => $kelas->semester->nama_semester,
                'dosen_id' => $kelas->dosen_id,
                'dosen_nama' => $kelas->dosen->name,
                'laboran_id' => $kelas->laboran_id,
                'laboran_nama' => $kelas->laboran->name,
                'kapasitas' => $kelas->kapasitas,
                'ruangan_id' => $kelas->ruangan_id,
                'ruangan_nama' => $kelas->ruangan->nama_ruangan,
                'hari' => $kelas->hari,
                'jam_mulai' => \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i'),
                'jam_selesai' => \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i'),
            ]
        ]);
    }

    /**
     * Toggle status open/closed untuk pendaftaran kelas.
     */
    public function toggleStatus(Request $request, $id)
    {
        $kelas = KelasPraktikum::findOrFail($id);
        
        // Toggle status
        $kelas->status = $kelas->status === 'open' ? 'closed' : 'open';
        $kelas->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran kelas berhasil diubah.',
            'new_status' => $kelas->status
        ]);
    }

    /**
     * Mengubah status semua kelas sekaligus.
     */
    public function toggleAllStatus(Request $request)
    {
        $status = $request->input('status'); // 'open' or 'closed'
        
        if (!in_array($status, ['open', 'closed'])) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }

        KelasPraktikum::query()->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Status semua kelas berhasil diubah menjadi ' . $status . '.'
        ]);
    }
    /**
     * Toggle status pemilihan kelas secara global untuk semester aktif.
     */
    public function toggleEnrollment(Request $request)
    {
        $status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            return response()->json(['success' => false, 'message' => 'Tidak ada semester aktif.'], 404);
        }

        $activeSemester->is_enrollment_open = $status;
        $activeSemester->save();

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran kelas berhasil ' . ($status ? 'dibuka' : 'ditutup') . '.',
            'new_status' => $status
        ]);
    }
    public function destroy($id)
    {
        $kelas = KelasPraktikum::findOrFail($id);

        // Mencegah penghapusan jika ada mahasiswa terdaftar (entah pending atau approved)
        if ($kelas->mahasiswas()->exists()) {
            return redirect()->back()->with('error', 'Kelas tidak dapat dihapus karena sudah ada mahasiswa yang terdaftar.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas praktikum berhasil dihapus.');
    }
}
