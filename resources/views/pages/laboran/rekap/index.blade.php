@extends('layouts.app')
@section('title', 'Rekap Praktikum')

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.dashboard') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-house-door mr-1.5"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Rekap Praktikum</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Rekapitulasi Kelas Praktikum</h1>
        </div>
        <div class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-[#344150] shadow-sm rounded-lg px-4 py-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400">
                <i class="bi bi-calendar-range text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Semester Aktif</p>
                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $semesterAktif->nama_semester }}</p>
            </div>
        </div>
    </div>

    <!-- Alert / Messages -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Kelas List -->
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-200 dark:border-[#344150] overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-[#344150] bg-slate-50/50 dark:bg-[#29323e]">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Kelas Semester Ini</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pilih kelas untuk melihat rekapitulasi nilai dan absensi secara lengkap.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#29323e] text-slate-500 dark:text-slate-300 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150]">Nama Kelas</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150]">Jadwal</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150] text-center">Mahasiswa</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150] text-center">Pertemuan</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150] text-center">Tugas/Modul</th>
                        <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-[#344150] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-[#344150]">
                    @forelse($kelas as $k)
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#29323e] transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $k->nama_kelas }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Dosen: {{ $k->dosen->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700 dark:text-slate-300">{{ $k->hari }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ substr($k->jam_mulai, 0, 5) }} - {{ substr($k->jam_selesai, 0, 5) }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $k->approved_mahasiswas_count ?? $k->approvedMahasiswas->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $k->jadwals_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $k->tugas_laporans_count ?? $k->tugasLaporans->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('laboran.rekap.show', $k->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-lg text-sm font-medium transition-colors border border-green-200 dark:border-green-800">
                                    <i class="bi bi-eye"></i> Lihat Rekap
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-inbox text-4xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                    <p>Belum ada kelas praktikum di semester ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
