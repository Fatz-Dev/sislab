<?php

namespace App\Http\Controllers;

use App\Models\KelasPraktikum;
use App\Models\KelasPraktikumMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Tampilkan daftar kelas praktikum yang bisa di-apply (open + semester aktif).
     *
     * Menampilkan info kapasitas, dosen, laboran, jadwal,
     * dan status enrollment mahasiswa untuk tiap kelas.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Ambil info semester aktif
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $isEnrollmentOpen = $activeSemester ? $activeSemester->is_enrollment_open : false;

        // Ambil kelas yang open di semester aktif
        $kelasOpen = KelasPraktikum::open()
            ->semesterAktif()
            ->with(['dosen:id,name', 'laboran:id,name', 'semester:id,nama_semester'])
            ->withCount(['approvedMahasiswas as jumlah_approved'])
            ->get();

        // ID kelas yang sudah di-apply oleh mahasiswa ini (semua status)
        $enrolledKelasIds = $user->kelasDiikuti()
            ->pluck('kelas_praktikums.id')
            ->toArray();

        // Status enrollment per kelas
        $enrollmentStatuses = $user->kelasDiikuti()
            ->get()
            ->keyBy('id')
            ->map(fn ($kelas) => $kelas->pivot->status);

        return view('pages.mahasiswa.kelas.list-kelas', compact(
            'kelasOpen',
            'enrolledKelasIds',
            'enrollmentStatuses',
            'isEnrollmentOpen'
        ));
    }

    /**
     * Apply (mendaftar) ke sebuah kelas praktikum.
     *
     * Validasi:
     * - Enrollment global sedang dibuka
     * - Kelas harus berstatus 'open'
     * - Kelas harus di semester aktif
     * - Belum pernah apply ke kelas ini
     * - Kapasitas masih tersedia
     */
    public function apply(Request $request, KelasPraktikum $kelasPraktikum)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validasi: Enrollment global
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        if (!$activeSemester || !$activeSemester->is_enrollment_open) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Pemilihan kelas saat ini sedang ditutup.'], 400);
            return back()->with('error', 'Pemilihan kelas saat ini sedang ditutup.');
        }

        // Validasi: kelas harus open
        if ($kelasPraktikum->status !== 'open') {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Kelas ini tidak sedang menerima pendaftaran.'], 400);
            return back()->with('error', 'Kelas ini tidak sedang menerima pendaftaran.');
        }

        // Validasi: semester aktif
        if (! $kelasPraktikum->semester || ! $kelasPraktikum->semester->is_active) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Kelas ini bukan di semester aktif.'], 400);
            return back()->with('error', 'Kelas ini bukan di semester aktif.');
        }

        // Validasi: belum pernah apply
        $existingEnrollment = $user->kelasDiikuti()
            ->where('kelas_praktikums.id', $kelasPraktikum->id)
            ->first();

        if ($existingEnrollment) {
            $status = $existingEnrollment->pivot->status;
            $messages = [
                'pending'  => 'Anda sudah mendaftar ke kelas ini dan masih menunggu persetujuan.',
                'approved' => 'Anda sudah terdaftar di kelas ini.',
                'rejected' => 'Pendaftaran Anda ke kelas ini sebelumnya ditolak. Hubungi admin untuk informasi lebih lanjut.',
            ];

            $msg = $messages[$status] ?? 'Anda sudah terdaftar di kelas ini.';
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $msg], 400);
            return back()->with('error', $msg);
        }

        // Validasi: kapasitas belum penuh
        if ($kelasPraktikum->isFull()) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Kapasitas kelas ini sudah penuh.'], 400);
            return back()->with('error', 'Kapasitas kelas ini sudah penuh.');
        }

        // Insert enrollment dengan status pending
        $user->kelasDiikuti()->attach($kelasPraktikum->id, [
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim! Menunggu persetujuan admin.',
                'new_status' => 'pending'
            ]);
        }
        return back()->with('success', 'Pendaftaran berhasil dikirim! Menunggu persetujuan admin.');
    }

    /**
     * Batalkan pendaftaran (hanya jika masih pending).
     */
    public function cancel(Request $request, KelasPraktikum $kelasPraktikum)
    {
        /** @var User $user */
        $user = Auth::user();

        $enrollment = $user->kelasDiikuti()
            ->where('kelas_praktikums.id', $kelasPraktikum->id)
            ->first();

        if (! $enrollment) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas ini.'], 400);
            return back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        if ($enrollment->pivot->status !== 'pending') {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Pendaftaran yang sudah diproses tidak dapat dibatalkan.'], 400);
            return back()->with('error', 'Pendaftaran yang sudah diproses tidak dapat dibatalkan.');
        }

        $user->kelasDiikuti()->detach($kelasPraktikum->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dibatalkan.',
                'new_status' => null
            ]);
        }
        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}
