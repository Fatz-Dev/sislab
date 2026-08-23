<?php

namespace App\Http\Controllers;

use App\Events\EnrollmentStatusUpdated;
use App\Models\KelasPraktikum;
use App\Models\KelasPraktikumMahasiswa;
use Illuminate\Http\Request;

class AdminEnrollmentController extends Controller
{
    /**
     * Tampilkan daftar semua pendaftaran mahasiswa ke kelas praktikum.
     *
     * Bisa difilter berdasarkan status (pending/approved/rejected)
     * dan kelas praktikum tertentu.
     */
    public function index(Request $request)
    {
        $query = KelasPraktikumMahasiswa::with([
            'kelasPraktikum:id,nama_kelas,kapasitas,status,semester_id',
            'kelasPraktikum.semester:id,nama_semester',
            'mahasiswa:id,name,email,nip_nim,phone',
        ]);

        // Filter berdasarkan status enrollment
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_praktikum_id', $request->input('kelas_id'));
        }

        $enrollments = $query->latest()->paginate(20);

        // Untuk dropdown filter
        $kelasList = KelasPraktikum::with('semester:id,nama_semester')
            ->select('id', 'nama_kelas', 'semester_id')
            ->get();

        // Hitung pending untuk badge
        $pendingCount = KelasPraktikumMahasiswa::where('status', 'pending')->count();

        return view('admin.enrollments.index', compact(
            'enrollments',
            'kelasList',
            'pendingCount',
        ));
    }

    /**
     * Approve pendaftaran mahasiswa ke kelas.
     *
     * Validasi: kapasitas kelas masih tersedia.
     */
    public function approve(Request $request, KelasPraktikumMahasiswa $enrollment)
    {
        if ($enrollment->status !== 'pending') {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Pendaftaran ini sudah diproses sebelumnya.'], 400);
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        // Cek kapasitas
        $kelas = $enrollment->kelasPraktikum;
        if ($kelas->isFull()) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Kapasitas kelas sudah penuh. Tidak dapat menyetujui pendaftaran.'], 400);
            return back()->with('error', 'Kapasitas kelas sudah penuh. Tidak dapat menyetujui pendaftaran.');
        }

        $enrollment->update([
            'status' => 'approved',
            'catatan_admin' => null,
        ]);

        broadcast(new EnrollmentStatusUpdated($enrollment));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran mahasiswa berhasil disetujui.',
                'new_status' => 'approved'
            ]);
        }
        return back()->with('success', 'Pendaftaran mahasiswa berhasil disetujui.');
    }

    /**
     * Reject pendaftaran mahasiswa ke kelas.
     *
     * Admin bisa menyertakan alasan penolakan via catatan_admin.
     */
    public function reject(Request $request, KelasPraktikumMahasiswa $enrollment)
    {
        if ($enrollment->status !== 'pending') {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Pendaftaran ini sudah diproses sebelumnya.'], 400);
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $enrollment->update([
            'status' => 'rejected',
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        broadcast(new EnrollmentStatusUpdated($enrollment));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran mahasiswa ditolak.',
                'new_status' => 'rejected'
            ]);
        }
        return back()->with('success', 'Pendaftaran mahasiswa ditolak.');
    }
}
