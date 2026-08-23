<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar Mahasiswa
     */
    public function mahasiswa(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('mahasiswaProfile')->where('role', 'mahasiswa')
                ->when($request->filled('angkatan'), function ($q) use ($request) {
                    $q->whereHas('mahasiswaProfile', function ($qProfile) use ($request) {
                        $qProfile->where('angkatan', $request->angkatan);
                    });
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $isActive = $request->status === 'aktif' ? 1 : 0;
                    $q->where('is_active', $isActive);
                });
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nim', function($row){
                    return $row->mahasiswaProfile ? $row->mahasiswaProfile->nim : '';
                })
                ->addColumn('angkatan', function($row){
                    return $row->mahasiswaProfile ? $row->mahasiswaProfile->angkatan : '';
                })
                ->addColumn('status', function($row){
                    return $row->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Aktif</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Nonaktif</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('pages.admin.users.mahasiswa-list');
    }

    /**
     * Menampilkan daftar Laboran
     */
    public function laboran(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('laboranProfile')->where('role', 'laboran');
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nip', function($row){
                    return $row->laboranProfile ? $row->laboranProfile->nip : '';
                })
                ->addColumn('jabatan', function($row){
                    return $row->laboranProfile ? $row->laboranProfile->jabatan : '';
                })
                ->addColumn('status', function($row){
                    return $row->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Aktif</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Nonaktif</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('pages.admin.users.laboran-list');
    }

    /**
     * Menampilkan daftar Dosen
     */
    public function dosen(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', 'dosen'); // Asumsi dosen belum punya relasi dosenProfile, jika ada tambahkan with()
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return $row->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Aktif</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Nonaktif</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('pages.admin.users.dosen-list');
    }

    /**
     * Menampilkan daftar Admin
     */
    public function admin(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', 'admin');
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return $row->is_active 
                        ? '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Aktif</span>'
                        : '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Nonaktif</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('pages.admin.users.admin-list');
    }

    /**
     * Menampilkan detail spesifik dari seorang User
     */
    public function show($id)
    {
        $user = User::with(['mahasiswaProfile', 'laboranProfile'])->findOrFail($id);
        return view('pages.admin.users.detail-user', compact('user'));
    }
}
