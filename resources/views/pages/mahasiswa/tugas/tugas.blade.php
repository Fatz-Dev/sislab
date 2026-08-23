@extends('layouts.app-mahasiswa')
@section('title', 'Daftar Tugas')

@section('content')
<div class="space-y-6 mt-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Tugas Belum Dikerjakan</h1>
        <p class="text-slate-500 text-sm mt-1">Daftar tugas dari kelas praktikum Anda yang menunggu untuk diunggah laporannya.</p>
    </div>

    @if($tugasBelumDikerjakan->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 text-center">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center text-green-500 text-3xl mx-auto mb-4">
                <i class="bi bi-emoji-smile"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Bagus Sekali!</h3>
            <p class="text-slate-500 mt-2">Tidak ada tugas yang tertunda. Anda sudah menyelesaikan semuanya.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tugasBelumDikerjakan as $tugas)
           <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 hover:shadow-md transition-shadow flex flex-col h-full">
               <a href="{{ route('mahasiswa.tugas.show', [$tugas->kelas_praktikum_id, $tugas->id]) }}" class="cursor-pointer ">
                   <div >
                       <div class="mb-3">
                           <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                               {{ $tugas->kelasPraktikum->nama_kelas }}
                           </span>
                           <h2 class="text-lg font-bold text-slate-800 line-clamp-1">{{ $tugas->judul }}</h2>
                       </div>
                       
                       <div class="mt-auto pt-4 border-t border-slate-100">
                           <div class="flex items-center justify-between mb-4">
                               <div>
                                   <p class="text-xs text-slate-500 font-medium">Tenggat Waktu</p>
                                   <p class="text-sm font-semibold text-red-600 flex items-center gap-1">
                                       <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                                   </p>
                               </div>
                           </div>
                           <a href="{{ route('mahasiswa.tugas.show', [$tugas->kelas_praktikum_id, $tugas->id]) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                               Kerjakan Tugas <i class="bi bi-arrow-right"></i>
                           </a>
                       </div>
                   </div>
               </a>
            </div> 
            @endforeach
        </div>
    @endif
</div>
@endsection