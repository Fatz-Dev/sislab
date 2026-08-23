@extends('layouts.app-mahasiswa')
@section('title', 'Dashboard')

@section('content')
    <section class="dashboard-grid" aria-label="Inventory dashboard">
        <section class="summary-card">
 
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800 ">Informasi Akademik</h2>
                    <p class="text-sm text-slate-500 mb-4">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>

            <div class="bg-white p-4 sm:p-6 mb-2 rounded-xl border border-slate-200 shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    <a href="{{ route('mahasiswa.myclass') }}"
                        class="group flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm hover:shadow">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-black bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="bi bi-journal-bookmark text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 text-center">Kelas Praktikum</span>
                    </a>

                    <a href="{{ route('mahasiswa.kelas.index') }}"
                        class="group flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm hover:shadow">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-black bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="bi bi-book text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 text-center">Pilih Kelas</span>
                    </a>

                    <a href="{{ route('mahasiswa.tugas.index') }}"
                        class="group flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm hover:shadow">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-black bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-earmark-text text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 text-center">Tugas & Laporan</span>
                    </a>

                    <a href="{{ route('mahasiswa.nilai.index') }}"
                        class="group flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm hover:shadow">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-black bg-white rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                            <i class="bi bi-award text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 text-center">Nilai</span>
                    </a>
                </div>
            </div>

        </section>

        <section class="my-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Card hari Ini -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="flex items-center justify-between border-b border-slate-100 p-4 bg-slate-50">
                        <h2 class="text-lg font-semibold text-slate-900 m-0 flex items-center gap-2">
                            <i class="bi bi-calendar-event text-black"></i> Jadwal Hari Ini
                        </h2>
                    </div>
                    <div class="p-5 flex flex-col gap-4 flex-grow">
                        @if($jadwalHariIni->isEmpty())
                            <div class="flex-grow flex flex-col items-center justify-center text-center p-12 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                <h3 class="text-sm font-bold text-slate-700">Tidak ada jadwal praktikum</h3>
                                <p class="text-xs text-slate-500 mt-1">Hari ini kosong. Silakan istirahat atau kerjakan laporan Anda.</p>
                            </div>
                        @else
                            @foreach($jadwalHariIni as $jadwal)
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-slate-100 bg-white hover:bg-slate-50 transition-colors">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 shrink-0">
                                    <div class="text-center leading-tight">
                                        <span class="block text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('D') }}</span>
                                        <span class="block text-lg font-extrabold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-sm font-semibold text-slate-700 m-0 mb-1 line-clamp-1">{{ $jadwal->kelasPraktikum->nama_kelas }}</h3>
                                    <p class="text-xs text-slate-500 m-0 flex items-center gap-1">
                                        <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-semibold">{{ $jadwal->ruangan->nama_ruangan }}</span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Card Tugas Perlu Dikerjakan -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="flex items-center justify-between border-b border-slate-100 p-4 bg-slate-50">
                        <h2 class="text-lg font-semibold text-slate-900 m-0 flex items-center gap-2">
                            <i class="bi bi-list-task text-black"></i> Belum Dikerjakan
                        </h2>
                        <a href="{{ route('mahasiswa.tugas.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="p-5 flex flex-col gap-0.5 flex-grow">
                        @if($tugasMendesak->isEmpty())
                            <div class="flex-grow flex flex-col items-center justify-center text-center p-6 bg-white rounded-lg border border-dashed border-blue-200">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-black text-3xl mb-3 shadow-sm">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <p class="text-xs text-black mt-1">Anda tidak memiliki laporan praktikum yang harus dikerjakan.</p>
                            </div>
                        @else
                            @foreach($tugasMendesak as $tugas)
                            <a class="cursor-pointer" {{ route('mahasiswa.tugas.show', [$tugas->kelas_praktikum_id, $tugas->id]) }}>
                                <div class="flex items-center justify-between p-3 rounded-lg border border-red-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-red-500 shrink-0 shadow-sm">
                                            <i class="bi bi-file-earmark-pdf text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-red-900 m-0 mb-0.5 line-clamp-1" title="{{ $tugas->judul }}">{{ Str::limit($tugas->judul, 25) }}</h3>
                                            <p class="text-xs text-red-700 m-0 flex items-center gap-1 font-medium">
                                                <i class="bi bi-exclamation-circle-fill"></i> Pada: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }} 
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('mahasiswa.tugas.show', [$tugas->kelas_praktikum_id, $tugas->id]) }}" class="bg-red-600 text-white px-3.5 py-1.5 rounded-md text-xs font-semibold hover:bg-red-700 transition-colors shadow-sm whitespace-nowrap">
                                        Kumpul
                                    </a>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </section>

    </section>
@endsection
