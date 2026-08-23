@extends('layouts.app-mahasiswa')
@section('title', 'Kelas Saya')

@section('content')
<div class="max-w-7xl mx-auto pb-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900 m-0">Kelas Saya</h1>
    </div>

    @if($kelas->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 text-3xl mx-auto mb-4">
                <i class="bi bi-inboxes"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 m-0">Belum Ada Kelas</h3>
            <p class="text-slate-500 mt-2 mb-0">Anda belum tergabung dalam kelas praktikum apapun, atau pendaftaran Anda masih menunggu persetujuan admin.</p>
            <a href="{{ route('mahasiswa.kelas.index') }}" class="inline-block mt-6 px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm text-sm">
                Cari Kelas Praktikum
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($kelas as $item)
            <a href="{{ route('mahasiswa.kelas.detail', $item->id) }}" class="block group">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden group-hover:shadow-md group-hover:border-blue-300 transition-all flex flex-col h-full">
                    <!-- Header Card -->
                    <div class="p-4 border-b border-slate-100 bg-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 m-0 leading-tight line-clamp-2">
                                    {{ $item->nama_kelas }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-2 mb-0">
                                    {{ $item->semester->nama_semester }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-4 flex-grow flex flex-col">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                            <span class="text-sm font-semibold text-slate-500">Dosen</span>
                            <span class="text-sm font-bold text-slate-800 text-right">{{ $item->dosen->name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                            <span class="text-sm font-semibold text-slate-500">Jadwal</span>
                            <span class="text-sm font-bold text-slate-800 text-right">
                                {{ ucfirst($item->hari) }}, 
                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-500">Ruangan</span>
                            <span class="text-sm font-bold text-slate-800 text-right">{{ $item->ruangan->nama_ruang ?? 'Belum Ditetapkan' }}</span>
                        </div>
                    </div>

                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $kelas->links() }}
        </div>
    @endif
</div>
@endsection