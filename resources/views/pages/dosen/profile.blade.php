@extends('layouts.app')
@section('title', 'Profil Dosen')

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
                <button onclick="openModal('modal-edit-profile')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-pencil"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="bg-white dark:bg-[#171d25] rounded-2xl shadow-sm border border-slate-100 dark:border-[#344150] p-6">
        <h3 class="text-lg font-semibold text-ink dark:text-white mb-4 border-b border-slate-100 dark:border-[#344150] pb-2">Informasi Pribadi & Akademik</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                <p class="text-base text-ink dark:text-white font-medium">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">NIP</p>
                <p class="text-base text-ink dark:text-white font-medium">
                    @if(Auth::user()->dosenProfile && Auth::user()->dosenProfile->nip)
                        {{ Auth::user()->dosenProfile->nip }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Alamat Email</p>
                <p class="text-base text-ink dark:text-white font-medium">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nomor Telepon</p>
                <p class="text-base text-ink dark:text-white font-medium">
                    @if(Auth::user()->phone)
                        {{ Auth::user()->phone }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Jabatan Akademik</p>
                <p class="text-base text-ink dark:text-white font-medium">
                    @if(Auth::user()->dosenProfile && Auth::user()->dosenProfile->jabatan_akademik)
                        {{ Auth::user()->dosenProfile->jabatan_akademik }}
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="modal-edit-profile" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform border border-slate-100 dark:border-[#344150] max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-[#344150] flex justify-between items-center bg-slate-50 dark:bg-[#29323e]">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="bi bi-pencil-square text-green-600 dark:text-green-400"></i> Edit Profil
            </h3>
            <button onclick="closeModal('modal-edit-profile')" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="form-edit-profile" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required class="w-full rounded-lg border-slate-300 dark:border-[#344150] bg-white dark:bg-[#0d1117] text-slate-900 dark:text-white text-sm focus:ring-green-500 focus:border-green-500">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required class="w-full rounded-lg border-slate-300 dark:border-[#344150] bg-white dark:bg-[#0d1117] text-slate-900 dark:text-white text-sm focus:ring-green-500 focus:border-green-500">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ Auth::user()->phone }}" class="w-full rounded-lg border-slate-300 dark:border-[#344150] bg-white dark:bg-[#0d1117] text-slate-900 dark:text-white text-sm focus:ring-green-500 focus:border-green-500">
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Foto Profil Baru (Opsional)</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Format JPG, PNG maksimal 2MB.</p>
                        <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 dark:border-[#344150]">
                        <h4 class="text-sm font-semibold text-slate-800 dark:text-white mb-3">Ubah Password (Opsional)</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Baru</label>
                                <input type="password" name="password" id="password" class="w-full rounded-lg border-slate-300 dark:border-[#344150] bg-white dark:bg-[#0d1117] text-slate-900 dark:text-white text-sm focus:ring-green-500 focus:border-green-500" placeholder="Kosongkan jika tidak ingin diubah">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border-slate-300 dark:border-[#344150] bg-white dark:bg-[#0d1117] text-slate-900 dark:text-white text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-[#344150] flex justify-end gap-3 bg-slate-50 dark:bg-[#29323e]">
            <button type="button" onclick="closeModal('modal-edit-profile')" class="px-4 py-2 border border-slate-300 dark:border-[#344150] rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#344150] font-medium transition-colors">Batal</button>
            <button type="button" id="btn-submit-profile" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

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
                method: 'POST', // Menggunakan POST dengan _method=PUT dari blade
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