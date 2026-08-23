@extends('layouts.app')
@section('title', 'Profil Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Profil -->
    <div class="bg-white dark:bg-[#171d25] rounded-2xl shadow-sm border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-green-500 to-emerald-600"></div>
        <div class="px-6 pb-6 relative">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 -mt-12 mb-4">
                <div class="w-24 h-24 rounded-full border-4 border-white dark:border-[#171d25] bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400 font-bold text-3xl shadow-md overflow-hidden">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-ink dark:text-white">{{ Auth::user()->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="flex-grow"></div>
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-pencil"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="bg-white dark:bg-[#171d25] rounded-2xl shadow-sm border border-slate-100 dark:border-[#344150] p-6">
        <h3 class="text-lg font-semibold text-ink dark:text-white mb-4 border-b border-slate-100 dark:border-[#344150] pb-2">Informasi Pribadi</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                <p class="text-base text-ink dark:text-white font-medium">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Alamat Email</p>
                <p class="text-base text-ink dark:text-white font-medium">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nomor Telepon</p>
                <p class="text-base text-ink dark:text-white font-medium">{{ Auth::user()->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status Akun</p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                    Aktif
                </span>
            </div>
        </div>
    </div>
</div>
@endsection