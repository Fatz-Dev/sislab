@extends('layouts.app')

@section('title', 'Detail User - SISLAB')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <button onclick="history.back()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <i class="bi bi-arrow-left text-xl"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">Detail Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Informasi lengkap akun pengguna.</p>
        </div>
    </div>

    <!-- Card Profil Umum -->
    <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-[#29323e] border border-slate-200 dark:border-[#344150] flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if(isset($user->avatar) && $user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <i class="bi bi-person text-4xl text-slate-400"></i>
                @endif
            </div>
            <div class="flex-1 space-y-4">
                <div>
                    <h2 class="text-xl font-bold text-ink dark:text-white">{{ $user->name }}</h2>
                    <p class="text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <span class="px-3 py-1 text-sm font-medium bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-full capitalize border border-blue-200 dark:border-blue-800">
                        Role: {{ $user->role }}
                    </span>
                    @if($user->is_active)
                        <span class="px-3 py-1 text-sm font-medium bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400 rounded-full border border-green-200 dark:border-green-800">
                            Status: Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-medium bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-full border border-red-200 dark:border-red-800">
                            Status: Nonaktif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Spesifik Role -->
    <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-ink dark:text-white mb-4 border-b border-slate-100 dark:border-[#344150] pb-2">Informasi Spesifik</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
            @if($user->role == 'admin')
                <div class="col-span-full">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada data spesifik tambahan untuk role Administrator pada saat ini.</p>
                </div>
            @endif

            @if($user->role == 'dosen')
                <div class="col-span-full">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada data spesifik tambahan untuk role Dosen pada saat ini.</p>
                </div>
            @endif

            @if($user->role == 'laboran')
                @if($user->laboranProfile)
                    <div>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">NIP / ID</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->laboranProfile->nip ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Jabatan</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->laboranProfile->jabatan ?? 'Belum diisi' }}</p>
                    </div>
                @else
                    <div class="col-span-full">
                        <p class="text-slate-500 dark:text-slate-400 text-sm italic">Profil laboran belum dilengkapi.</p>
                    </div>
                @endif
            @endif

            @if($user->role == 'mahasiswa')
                @if($user->mahasiswaProfile)
                    <div>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">NIM</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->mahasiswaProfile->nim ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Angkatan</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->mahasiswaProfile->angkatan ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Nomor Telepon</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->mahasiswaProfile->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="col-span-full">
                        <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Alamat</span>
                        <p class="text-sm font-medium text-ink dark:text-white">{{ $user->mahasiswaProfile->address ?? 'Belum diisi' }}</p>
                    </div>
                @else
                    <div class="col-span-full">
                        <p class="text-slate-500 dark:text-slate-400 text-sm italic">Profil mahasiswa belum dilengkapi.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection