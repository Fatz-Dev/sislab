@extends('layouts.app')
@section('title', 'Detail Kelas: ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.kelas.index') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-journal-bookmark mr-1.5"></i> Kelas Praktikum
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Detail Kelas</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $kelas->nama_kelas }}</h1>
        </div>
        
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $kelas->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                <span class="w-2 h-2 rounded-full mr-2 {{ $kelas->status === 'open' ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                {{ $kelas->status === 'open' ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}
            </span>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-slate-200 dark:border-slate-700 mb-6">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="kelasTabs" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg text-green-600 border-green-600 dark:text-green-500 dark:border-green-500 hover:text-green-600 dark:hover:text-green-500 transition-colors" id="info-tab" type="button" role="tab" aria-controls="info" aria-selected="true" onclick="switchTab('info')">
                    <i class="bi bi-info-circle mr-2"></i> Informasi & Mahasiswa
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg text-slate-500 hover:text-slate-600 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 transition-colors" id="pertemuan-tab" type="button" role="tab" aria-controls="pertemuan" aria-selected="false" onclick="switchTab('pertemuan')">
                    <i class="bi bi-calendar-check mr-2"></i> Daftar Pertemuan
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Contents -->
    <div id="kelasTabContent">
        <!-- Tab 1: Informasi -->
        <div class="transition-opacity duration-300 ease-in-out opacity-100 block" id="info" role="tabpanel" aria-labelledby="info-tab">
        
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
                <!-- Info Panel Kiri -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Informasi Kelas -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-400 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
                            <i class="bi bi-info-circle text-slate-500 dark:text-white"></i>
                            <h2 class="font-semibold text-slate-800 dark:text-white">Informasi Kelas</h2>
                        </div>
                        <div class="p-5">

                            <!-- Modul Praktikum -->
                            <div class="mb-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Modul Praktikum</h3>
                                </div>
                                
                                @if(isset($kelas->modulPraktikums) && $kelas->modulPraktikums->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($kelas->modulPraktikums as $modul)
                                        <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:border-blue-300 transition-colors group">
                                            <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="mt-0.5 relative flex-shrink-0 block">
                                                <canvas data-pdf-url="{{ Storage::url($modul->file_pdf) }}" class="pdf-canvas w-12 h-16 rounded border border-slate-200 shadow-sm object-cover bg-white dark:border-slate-600 dark:bg-slate-700" title="Cover PDF"></canvas>
                                                <div class="pdf-loading-skeleton absolute inset-0 bg-slate-200 dark:bg-slate-600 animate-pulse rounded border border-slate-200 dark:border-slate-600 flex items-center justify-center">
                                                    <i class="bi bi-file-earmark-pdf text-blue-400 text-xl"></i>
                                                </div>
                                            </a>
                                            <div class="flex-grow min-w-0">
                                                <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="text-sm font-semibold text-slate-800 dark:text-white truncate hover:text-blue-600 dark:hover:text-blue-400 transition-colors block" title="{{ $modul->judul }}">{{ $modul->judul }}</a>
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $modul->tanggal_upload ? $modul->tanggal_upload->format('d M Y') : '' }}</p>
                                            </div>
                                            <div class="flex flex-col gap-1 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="w-6 h-6 flex items-center justify-center rounded-md bg-white border border-slate-200 text-blue-600 hover:bg-blue-50 transition-colors shadow-sm" title="Lihat">
                                                    <i class="bi bi-eye text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-5 text-center bg-slate-50 dark:bg-slate-800/50">
                                        <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                                            <i class="bi bi-journal-x text-xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Belum Ada Modul</p>
                                        <p class="text-xs text-slate-500 mt-1">Dosen belum mengunggah modul/materi untuk kelas ini.</p>
                                    </div>
                                @endif
                            </div>

                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-1">Semester</dt>
                                    <dd class="text-sm text-slate-900 dark:text-white">{{ $kelas->semester->nama_semester}}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-1">Ruangan</dt>
                                    <dd class="text-sm text-slate-900 dark:text-white">{{ $kelas->ruangan?->nama_ruangan ?? 'Belum Ditentukan' }}</dd>
                                </div>
                                <div class="pt-3 border-t border-slate-100 dark:border-slate-400 mt-3">
                                    <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-2">Tim Pengajar & Asisten</dt>
                                    
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-person-workspace"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-white">Dosen Pengampu</p>
                                            <p class="text-sm font-medium text-slate-800 dark:text-white">{{ $kelas->dosen->name ?? 'Belum Ditentukan' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 dark:text-white">Laboran / Asisten</p>
                                            <p class="text-sm font-medium text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Statistik Kapasitas -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-400 overflow-hidden">
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-white">Mahasiswa Terdaftar</p>
                                @php
                                    $approvedCount = $kelas->approvedMahasiswas->count();
                                    $kapasitas = $kelas->kapasitas;
                                    $percent = $kapasitas > 0 ? min(100, round(($approvedCount / $kapasitas) * 100)) : 0;
                                @endphp
                                <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $approvedCount }} <span class="text-sm text-slate-400 dark:text-white font-normal">/ {{ $kapasitas }}</span></p>
                            </div>
                            <div class="w-12 h-12 rounded-full {{ $percent >= 100 ? 'bg-red-50 dark:bg-red-900 text-red-600' : 'bg-emerald-50 dark:bg-emerald-900 text-emerald-600' }} flex items-center justify-center text-xl">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="px-5 pb-5">
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                                <div class="{{ $percent >= 100 ? 'bg-red-500' : 'bg-emerald-500' }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-white mt-2 text-right">{{ $percent }}% Terisi</p>
                        </div>
                    </div>
                </div>

                <!-- Tabel Mahasiswa Kanan -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person-lines-fill text-black dark:text-white"></i>
                                <h2 class="font-semibold text-slate-800 dark:text-white">Daftar Mahasiswa</h2>
                            </div>
                            
                            <!-- Search input (Client Side) -->
                            <div class="relative max-w-xs ">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="w-full pl-9 pr-3 py-1.5 bg-white border-slate-300 text-slate-900 placeholder-slate-400 dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:placeholder-slate-400 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Cari nama atau NIM...">
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700" id="mhsTable">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Mahasiswa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Kontak</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                    @forelse($kelas->approvedMahasiswas as $index => $mhs)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors mhs-row">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-white">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center flex-shrink-0">
                                                        {{ substr($mhs->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-slate-900 dark:text-white mhs-name">{{ $mhs->name }}</div>
                                                        <div class="text-xs text-slate-500 dark:text-white mhs-nim">{{ $mhs->nip_nim ?? 'NIM belum diatur' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-700 dark:text-white flex items-center gap-2">
                                                    <i class="bi bi-envelope text-slate-400 dark:text-white"></i> {{ $mhs->email }}
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-white mt-1 flex items-center gap-2">
                                                    <i class="bi bi-telephone text-slate-400 dark:text-white"></i> {{ $mhs->phone ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    Aktif
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 dark:text-white">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-white mb-3">
                                                        <i class="bi bi-people text-2xl"></i>
                                                    </div>
                                                    <p class="font-medium">Belum ada mahasiswa</p>
                                                    <p class="text-sm text-slate-400 dark:text-white mt-1">Belum ada mahasiswa yang disetujui untuk kelas ini.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Pertemuan -->
        <div class="hidden transition-opacity duration-300 ease-in-out opacity-0" id="pertemuan" role="tabpanel" aria-labelledby="pertemuan-tab">
            <!-- Section Daftar Pertemuan -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden mt-2">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-calendar-event text-blue-500"></i>
                <h2 class="font-semibold text-slate-800 dark:text-white">Daftar Pertemuan (Jadwal)</h2>
            </div>
            <button type="button" onclick="openModal('tambahPertemuanModal')" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-slate-900 transition-colors">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Pertemuan
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Pertemuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Tanggal & Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Topik</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($kelas->jadwals as $index => $jadwal)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                Pertemuan {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-calendar text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="bi bi-clock text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-white">
                                {{ $jadwal->topik ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($jadwal->status === 'terjadwal')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Terjadwal
                                    </span>
                                @elseif($jadwal->status === 'berlangsung')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Berlangsung
                                    </span>
                                @elseif($jadwal->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('laboran.jadwal.show', [$kelas->id, $jadwal->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-white">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-white mb-3">
                                        <i class="bi bi-calendar-x text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Belum ada pertemuan</p>
                                    <p class="text-sm text-slate-400 dark:text-white mt-1">Jadwal pertemuan untuk kelas ini belum ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        </div>

    </div> 

</div> <!-- End Tab Content Container -->

<!-- Modal Tambah Pertemuan -->
<div id="tambahPertemuanModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto overflow-x-hidden w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center transition-opacity opacity-0">
    <div class="relative p-4 w-full max-w-md max-h-full transition-transform transform scale-95 duration-300">
        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-slate-100 dark:border-slate-700 rounded-t">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Tambah Pertemuan Baru
                </h3>
                <button type="button" onclick="closeModal('tambahPertemuanModal')" class="text-slate-400 bg-transparent hover:bg-slate-200 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-slate-600 dark:hover:text-white transition-colors">
                    <i class="bi bi-x-lg"></i>
                    <span class="sr-only">Tutup</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('laboran.jadwal.store', $kelas->id) }}" method="POST" class="p-4 md:p-5 space-y-4">
                @csrf
                
                <div>
                    <label for="topik" class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Topik Pertemuan <span class="text-red-500">*</span></label>
                    <input type="text" name="topik" id="topik" class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Modul 1 - Hukum Newton" required>
                </div>
                
                @php
                    $hariMap = [
                        'Senin' => 'Monday',
                        'Selasa' => 'Tuesday',
                        'Rabu' => 'Wednesday',
                        'Kamis' => 'Thursday',
                        'Jumat' => 'Friday',
                        'Sabtu' => 'Saturday',
                        'Minggu' => 'Sunday',
                    ];
                    
                    $opsiTanggal = [];
                    $defaultDate = '';
                    
                    if ($kelas->hari && isset($hariMap[$kelas->hari])) {
                        $englishDay = $hariMap[$kelas->hari];
                        
                        // Cari tanggal 4 minggu ke belakang pada hari tersebut
                        // Dan set bahasa Carbon agar nama bulan Bahasa Indonesia
                        $startDate = \Carbon\Carbon::now()->subWeeks(4)->modify('next ' . $englishDay);
                        $defaultDate = \Carbon\Carbon::parse('next ' . $englishDay)->format('Y-m-d');
                        
                        for ($i = 0; $i < 16; $i++) {
                            $dateObj = $startDate->copy()->addWeeks($i);
                            $dateString = $dateObj->format('Y-m-d');
                            $dateLabel = $kelas->hari . ', ' . $dateObj->translatedFormat('d F Y');
                            
                            $opsiTanggal[] = [
                                'value' => $dateString,
                                'label' => $dateLabel,
                                'is_default' => ($dateString === $defaultDate)
                            ];
                        }
                    }
                    
                    $defaultJamMulai = $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '';
                    $defaultJamSelesai = $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '';
                @endphp
                
                <div>
                    <label for="tanggal" class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Tanggal Pertemuan <span class="text-red-500">*</span></label>
                    <select name="tanggal" id="tanggal" class="bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="" disabled>Pilih Tanggal Pertemuan</option>
                        @foreach($opsiTanggal as $opsi)
                            <option value="{{ $opsi['value'] }}" {{ $opsi['is_default'] ? 'selected' : '' }}>
                                {{ $opsi['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="jam_mulai" class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ $defaultJamMulai }}" class="bg-slate-100 cursor-not-allowed dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400 text-sm rounded-lg block w-full p-2.5" required readonly tabindex="-1">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block mb-2 text-sm font-medium text-slate-900 dark:text-white">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ $defaultJamSelesai }}" class="bg-slate-100 cursor-not-allowed dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400 text-sm rounded-lg block w-full p-2.5" required readonly tabindex="-1">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-green-800 transition-colors">
                        Simpan Pertemuan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            const innerDiv = modal.querySelector('.transform');
            if (innerDiv) {
                innerDiv.classList.remove('scale-95');
                innerDiv.classList.add('scale-100');
            }
        }, 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('opacity-0');
        const innerDiv = modal.querySelector('.transform');
        if (innerDiv) {
            innerDiv.classList.remove('scale-100');
            innerDiv.classList.add('scale-95');
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }


    // Simple Client-Side Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.mhs-row');
        
        rows.forEach(row => {
            let name = row.querySelector('.mhs-name').textContent.toLowerCase();
            let nim = row.querySelector('.mhs-nim').textContent.toLowerCase();
            
            if (name.indexOf(filter) > -1 || nim.indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
    // Fungsi Tab Navigation
    function switchTab(tabId) {
        // Daftar semua tab button dan konten
        const infoBtn = document.getElementById('info-tab');
        const pertemuanBtn = document.getElementById('pertemuan-tab');
        const infoContent = document.getElementById('info');
        const pertemuanContent = document.getElementById('pertemuan');

        // Reset semua class aktif dari button
        const activeClassBtn = ['text-green-600', 'border-green-600', 'dark:text-green-500', 'dark:border-green-500'];
        const inactiveClassBtn = ['border-transparent', 'text-slate-500', 'hover:text-slate-600', 'hover:border-slate-300', 'dark:text-slate-400', 'dark:hover:text-slate-300'];

        infoBtn.classList.remove(...activeClassBtn);
        infoBtn.classList.add(...inactiveClassBtn);
        pertemuanBtn.classList.remove(...activeClassBtn);
        pertemuanBtn.classList.add(...inactiveClassBtn);

        // Hide all contents with fade out
        infoContent.classList.remove('opacity-100');
        infoContent.classList.add('opacity-0');
        pertemuanContent.classList.remove('opacity-100');
        pertemuanContent.classList.add('opacity-0');

        setTimeout(() => {
            infoContent.classList.add('hidden');
            infoContent.classList.remove('block');
            pertemuanContent.classList.add('hidden');
            pertemuanContent.classList.remove('block');

            // Aktifkan tab yang dipilih
            if (tabId === 'info') {
                infoBtn.classList.remove(...inactiveClassBtn);
                infoBtn.classList.add(...activeClassBtn);
                infoContent.classList.remove('hidden');
                infoContent.classList.add('block');
                
                // Trigger reflow & fade in
                void infoContent.offsetWidth;
                infoContent.classList.remove('opacity-0');
                infoContent.classList.add('opacity-100');
            } else if (tabId === 'pertemuan') {
                pertemuanBtn.classList.remove(...inactiveClassBtn);
                pertemuanBtn.classList.add(...activeClassBtn);
                pertemuanContent.classList.remove('hidden');
                pertemuanContent.classList.add('block');
                
                // Trigger reflow & fade in
                void pertemuanContent.offsetWidth;
                pertemuanContent.classList.remove('opacity-0');
                pertemuanContent.classList.add('opacity-100');
            }
        }, 150); // Timeout setengah dari durasi transisi Tailwind (300ms) agar lebih responsif
    }
</script>

<!-- Script PDF.js untuk Cover Render -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.pdf-canvas').forEach(function(canvas) {
            var url = canvas.getAttribute('data-pdf-url');
            var skeleton = canvas.nextElementSibling;
            
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                return pdf.getPage(1);
            }).then(function(page) {
                var viewport = page.getViewport({ scale: 0.5 });
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                return page.render(renderContext).promise;
            }).then(function() {
                if (skeleton) skeleton.classList.add('hidden');
            }).catch(function(error) {
                console.error('Error rendering PDF:', error);
                if (skeleton) skeleton.classList.remove('animate-pulse');
            });
        });
    });
</script>
@endpush
