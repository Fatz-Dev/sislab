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
                <p class="text-base text-slate-900 font-medium m-0">
                    @if(Auth::user()->mahasiswaProfile && Auth::user()->mahasiswaProfile->nim)
                        {{ Auth::user()->mahasiswaProfile->nim }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Alamat Email</p>
                <p class="text-base text-slate-900 font-medium m-0">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor Telepon</p>
                <p class="text-base text-slate-900 font-medium m-0">
                    @if(Auth::user()->phone)
                        {{ Auth::user()->phone }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jurusan</p>
                <p class="text-base text-slate-900 font-medium m-0">
                    @if(Auth::user()->mahasiswaProfile && Auth::user()->mahasiswaProfile->jurusan)
                        {{ Auth::user()->mahasiswaProfile->jurusan }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Angkatan</p>
                <p class="text-base text-slate-900 font-medium m-0">
                    @if(Auth::user()->mahasiswaProfile && Auth::user()->mahasiswaProfile->angkatan)
                        {{ Auth::user()->mahasiswaProfile->angkatan }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Profil -->
<div id="modalEditProfile" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden" style="animation: modalFadeIn 0.3s ease-out; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-900 m-0 flex items-center gap-2">
                <i class="bi bi-pencil-square text-blue-600"></i> Edit Profil
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-900 transition-colors bg-transparent border-none cursor-pointer text-xl">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="form-edit-profile" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="m-0">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ Auth::user()->phone }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all" placeholder="Contoh: 08123456789">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="photo" class="block text-sm font-semibold text-slate-700 mb-1">Foto Profil Baru (Opsional)</label>
                        <div class="p-4 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 text-center">
                            <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-xs text-slate-400 mt-2 mb-0">Format didukung: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="pt-4 mt-2 border-t border-slate-100">
                        <h4 class="text-sm font-semibold text-slate-900 mb-3">Ubah Password (Opsional)</h4>
                        <div class="flex flex-col gap-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                                <input type="password" name="password" id="password" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all" placeholder="Kosongkan jika tidak ingin diubah">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</button>
            <button type="button" id="btn-submit-profile" class="px-4 py-2 bg-blue-600 text-white border-none rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2">
                Simpan Perubahan
            </button>
        </div>
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

    $(document).ready(function() {
        $('#btn-submit-profile').on('click', function() {
            $('#form-edit-profile').submit();
        });

        $('#form-edit-profile').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const submitBtn = $('#btn-submit-profile');
            const originalText = submitBtn.html();
            
            // Clear previous errors
            form.find('.invalid-feedback').addClass('hidden').text('');
            form.find('input').removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');

            submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Menyimpan...');

            const formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (window.showToast) {
                        window.showToast(response.message);
                    }
                    setTimeout(() => window.location.reload(), 1000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            const input = form.find(`[name="${key}"]`);
                            input.addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
                            input.siblings('.invalid-feedback').removeClass('hidden').text(errors[key][0]);
                        }
                    } else {
                        let errorMsg = "Terjadi kesalahan saat menyimpan profil.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        if (window.showToast) {
                            window.showToast(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection