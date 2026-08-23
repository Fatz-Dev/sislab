@extends('layouts.app-mahasiswa')
@section('title', 'Profil Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-6 mb-8">
    <!-- Header Profil -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="h-32 ">
            <img class="rounded-xl brightness-50 opacity-90" src="{{ asset('assets/image/login-hero.png') }}" alt="" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
        </div>
        <div class="px-6 pb-6 relative">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 -mt-12 mb-4">
                <div class="w-24 h-24 rounded-full border-4 border-white bg-sky-100 flex items-center justify-center text-sky-600 font-bold mt-4 text-4xl shadow-sm overflow-hidden">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-slate-900 m-0">{{ Auth::user()->name }}</h1>
                    <p class="text-sm text-slate-500 capitalize m-0">{{ Auth::user()->role }}</p>
                </div>
                <div class="flex-grow"></div>
                <button onclick="openEditModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <i class="bi bi-pencil"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-100">Informasi Pribadi & Akademik</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">NIM</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->mahasiswaProfile->nim ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Alamat Email</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor Telepon</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jurusan</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->mahasiswaProfile->jurusan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Angkatan</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->mahasiswaProfile->angkatan ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Profil -->
<div id="modalEditProfile" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden" style="animation: modalFadeIn 0.3s ease-out;">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900 m-0">Edit Profil</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-900 transition-colors bg-transparent border-none cursor-pointer text-xl">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form action="#" method="POST" enctype="multipart/form-data" class="m-0" onsubmit="event.preventDefault(); closeEditModal(); window.showToast('Fitur simpan profil sedang dalam pengembangan');">
            @csrf
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all" placeholder="Contoh: 08123456789">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                    <div class="p-4 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 text-center">
                        <input type="file" name="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" accept="image/*">
                        <p class="text-xs text-slate-400 mt-2 mb-0">Format didukung: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white border-none rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
<script>
    function openEditModal() {
        const modal = document.getElementById('modalEditProfile');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeEditModal() {
        const modal = document.getElementById('modalEditProfile');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Tutup modal jika mengklik area luar modal (backdrop)
    document.getElementById('modalEditProfile').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endpush
@endsection