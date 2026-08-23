@extends('layouts.app')
@section('title', 'Kelas Praktikum Anda')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Kelas Praktikum Anda</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftar kelas di mana Anda ditugaskan sebagai dosen pengampu.</p>
        </div>
    </div>

    <!-- Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelas as $k)
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-50 dark:border-slate-700  relative overflow-hidden">
                    
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-slate-900 text-blue-700 dark:text-white border border-blue-100 ">
                            {{ $k->ruangan?->nama_ruangan ?? 'Belum ada Ruangan' }}
                        </span>
                        
                        @if($k->status === 'open')
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Buka
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tutup
                            </span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1 relative z-10">{{ $k->nama_kelas }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 relative z-10"><i class="bi bi-calendar3 mr-1"></i> {{ $k->semester->nama_semester ?? 'Semester Tidak Diketahui' }}</p>
                </div>
                
                <!-- Card Body -->
                <div class="p-5 flex-1 bg-slate-50/50 dark:bg-slate-900">
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-200 font-medium uppercase tracking-wider">Laboran</p>
                                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $k->laboran->name ?? 'Belum Ditentukan' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-300 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="w-full">
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Mahasiswa Terdaftar</p>
                                <div class="flex justify-between items-end mb-1">
                                    <p class="text-sm text-slate-700 font-medium">
                                        {{ $k->terdaftar_count }} <span class="text-slate-400 text-xs font-normal">dari {{ $k->kapasitas }}</span>
                                    </p>
                                    @php
                                        $percent = $k->kapasitas > 0 ? min(100, round(($k->terdaftar_count / $k->kapasitas) * 100)) : 0;
                                    @endphp
                                    <span class="text-xs font-medium {{ $percent >= 100 ? 'text-red-500' : 'text-emerald-500' }}">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-{{ $percent >= 100 ? 'red' : 'emerald' }}-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 mt-auto">
                    <a href="{{ route('dosen.kelas.show', $k->id) }}" class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        Lihat Detail Kelas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-dashed p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-4">
                        <i class="bi bi-journal-x text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Belum Ada Kelas</h3>
                    <p class="text-slate-500 max-w-md">Anda belum ditugaskan sebagai dosen pengampu untuk kelas praktikum apa pun saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($kelas->hasPages())
    <div class="mt-6 flex justify-end">
        {{ $kelas->links() }}
    </div>
    @endif

</div>
@endsection
