@extends('layouts.app')
@section('title', 'Detail Rekap Praktikum')

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.rekap.index') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-arrow-left mr-1.5"></i> Kembali
                        </a>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Detail Rekap: {{ $kelas->nama_kelas }}</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('laboran.rekap.export-absensi', $kelas->id) }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm text-xs sm:text-sm font-medium transition-colors flex items-center gap-1.5">
                <i class="bi bi-file-earmark-excel"></i> Export Excel Presensi
            </a>
            <a href="{{ route('laboran.rekap.export-nilai', $kelas->id) }}" class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-sm text-xs sm:text-sm font-medium transition-colors flex items-center gap-1.5">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel Nilai
            </a>
            <a href="{{ route('laboran.rekap.cetak', $kelas->id) }}" target="_blank" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm text-xs sm:text-sm font-medium transition-colors flex items-center gap-1.5">
                <i class="bi bi-printer"></i> Cetak Dokumen
            </a>
            <a href="{{ route('laboran.rekap.export-pdf', $kelas->id) }}" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg shadow-sm text-xs sm:text-sm font-medium transition-colors flex items-center gap-1.5">
                <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
            </a>
        </div>
    </div>

    <!-- Info Kelas -->
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-200 dark:border-[#344150] p-6 print:shadow-none print:border-none print:p-0 print:mb-6">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-200 dark:border-[#344150] pb-2 print:border-black">Informasi Kelas</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Nama Kelas</p>
                <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $kelas->nama_kelas }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Jadwal</p>
                <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Dosen</p>
                <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $kelas->dosen?->name ?? 'Belum Ditentukan' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Laboran</p>
                <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $kelas->laboran?->name ?? 'Belum Ditentukan' }}</p>
            </div>
        </div>
    </div>

    <!-- Rekap Absensi -->
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-200 dark:border-[#344150] overflow-hidden print:shadow-none print:border-none print:mb-6">
        <div class="p-5 border-b border-slate-200 dark:border-[#344150] bg-slate-50/50 dark:bg-[#29323e] flex flex-col sm:flex-row sm:items-center justify-between gap-2 print:bg-transparent print:border-black">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white print:text-black">Rekap Absensi Mahasiswa</h2>
            <a href="{{ route('laboran.rekap.export-absensi', $kelas->id) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-1 print:hidden">
                <i class="bi bi-download"></i> Unduh Excel Presensi
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse print:text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#29323e] text-slate-500 dark:text-slate-300 text-xs uppercase tracking-wider print:bg-transparent print:text-black print:border-b-2 print:border-black">
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 border-r w-10">No</th>
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 border-r min-w-[200px]">Nama / NIM</th>
                        @foreach($kelas->jadwals as $jadwal)
                            <th class="px-2 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 text-center border-r" title="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}">
                                P{{ $jadwal->pertemuan_ke }}
                            </th>
                        @endforeach
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 text-center">% Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-[#344150]">
                    @foreach($kelas->approvedMahasiswas as $index => $mhs)
                        @php
                            $hadirCount = 0;
                            $totalPertemuan = $kelas->jadwals->count();
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#29323e] print:border-b print:border-slate-300">
                            <td class="px-4 py-2 border-r border-slate-200 dark:border-[#344150] text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-r border-slate-200 dark:border-[#344150]">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $mhs->name }}</div>
                                @if($mhs->mahasiswaProfile?->nim)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $mhs->mahasiswaProfile->nim }}</div>
                                @elseif($mhs->nip_nim)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $mhs->nip_nim }}</div>
                                @endif
                            </td>
                            @foreach($kelas->jadwals as $jadwal)
                                @php
                                    $absen = $jadwal->absensis->where('user_id', $mhs->id)->first();
                                    $status = $absen ? $absen->status_hadir : null;
                                @endphp
                                <td class="px-2 py-2 text-center border-r border-slate-200 dark:border-[#344150]">
                                    @if($status === 'hadir')
                                        @php $hadirCount++; @endphp
                                        <i class="bi bi-check-circle-fill text-green-500 print:hidden"></i>
                                        <span class="hidden print:inline text-green-600">H</span>
                                    @elseif($status === 'izin')
                                        <span class="text-blue-500 font-bold print:text-blue-600">I</span>
                                    @elseif($status === 'sakit')
                                        <span class="text-yellow-500 font-bold print:text-yellow-600">S</span>
                                    @elseif($status === 'alpa')
                                        <i class="bi bi-x-circle-fill text-red-500 print:hidden"></i>
                                        <span class="hidden print:inline text-red-600">A</span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 print:text-slate-400"></span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-4 py-2 text-center font-bold text-slate-800 dark:text-white">
                                {{ $totalPertemuan > 0 ? round(($hadirCount / $totalPertemuan) * 100) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rekap Nilai Tugas -->
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-200 dark:border-[#344150] overflow-hidden print:shadow-none print:border-none">
        <div class="p-5 border-b border-slate-200 dark:border-[#344150] bg-slate-50/50 dark:bg-[#29323e] flex flex-col sm:flex-row sm:items-center justify-between gap-2 print:bg-transparent print:border-black">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-white print:text-black">Rekap Nilai Tugas & Modul</h2>
            <a href="{{ route('laboran.rekap.export-nilai', $kelas->id) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 dark:text-teal-400 inline-flex items-center gap-1 print:hidden">
                <i class="bi bi-download"></i> Unduh Excel Nilai
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse print:text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#29323e] text-slate-500 dark:text-slate-300 text-xs uppercase tracking-wider print:bg-transparent print:text-black print:border-b-2 print:border-black">
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 border-r w-10">No</th>
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 border-r min-w-[200px]">Nama / NIM</th>
                        @foreach($kelas->tugasLaporans as $i => $tugas)
                            <th class="px-2 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 text-center border-r" title="{{ $tugas->judul_tugas }}">
                                T{{ $i + 1 }}
                            </th>
                        @endforeach
                        <th class="px-4 py-3 font-semibold border-b border-slate-200 dark:border-[#344150] print:border-b print:border-slate-300 text-center">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-[#344150]">
                    @foreach($kelas->approvedMahasiswas as $index => $mhs)
                        @php
                            $totalNilai = 0;
                            $tugasDikerjakan = 0;
                            $totalTugas = $kelas->tugasLaporans->count();
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#29323e] print:border-b print:border-slate-300">
                            <td class="px-4 py-2 border-r border-slate-200 dark:border-[#344150] text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-r border-slate-200 dark:border-[#344150]">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $mhs->name }}</div>
                                @if($mhs->mahasiswaProfile?->nim)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $mhs->mahasiswaProfile->nim }}</div>
                                @elseif($mhs->nip_nim)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $mhs->nip_nim }}</div>
                                @endif
                            </td>
                            @foreach($kelas->tugasLaporans as $tugas)
                                @php
                                    $nilai = $tugas->nilai->where('mahasiswa_id', $mhs->id)->first();
                                    $skor = $nilai ? $nilai->nilai : null;
                                @endphp
                                <td class="px-2 py-2 text-center border-r border-slate-200 dark:border-[#344150]">
                                    @if($skor !== null)
                                        @php
                                            $totalNilai += $skor;
                                            $tugasDikerjakan++;
                                        @endphp
                                        <span class="font-medium {{ $skor < 60 ? 'text-red-600' : 'text-slate-800 dark:text-white' }}">{{ $skor }}</span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 print:text-slate-400"></span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-4 py-2 text-center font-bold text-slate-800 dark:text-white">
                                {{ $tugasDikerjakan > 0 ? round($totalNilai / $tugasDikerjakan, 1) : 0 }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
