@extends('layouts.app')
@section('title', 'Ruang Lab')

@section('content')
<div class="space-y-6">
    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.dashboard') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-house-door mr-1.5"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Ruang Lab</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Kelola Ruang Lab</h1>
        </div>
    </div>

    <!-- Alert / Toast -->
    <div id="alert-container"></div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-slate-800 dark:text-white">Daftar Ruang Lab</h2>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm shadow-green-600/20 transition-all focus:outline-none focus:ring-2 focus:ring-green-500/50">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Ruangan
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Nama Ruangan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($ruangans as $ruangan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                {{ $ruangan->nama_ruangan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $ruangan->deskripsi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                <i class="bi bi-inbox text-3xl mb-2 block"></i>
                                Belum ada data ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg w-full max-w-md p-0 transform transition-all scale-95 opacity-0 duration-200" id="createModalContent">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tambah Ruangan Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="createForm" class="p-5 space-y-4">
            @csrf
            <div>
                <label for="nama_ruangan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_ruangan" id="nama_ruangan" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" required placeholder="Contoh: Lab Fisika Dasar">
            </div>
            
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" placeholder="Keterangan opsional ruangan..."></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm shadow-green-600/20 transition-all flex items-center">
                    <i class="bi bi-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCreateModal() {
        const modal = $('#createModal');
        const content = $('#createModalContent');
        modal.removeClass('hidden').addClass('flex');
        setTimeout(() => {
            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }
    
    function closeCreateModal() {
        const modal = $('#createModal');
        const content = $('#createModalContent');
        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.addClass('hidden').removeClass('flex');
        }, 200);
    }

    $('#createForm').on('submit', function(e) {
        e.preventDefault();

        let submitBtn = $('#submitBtn');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="bi bi-hourglass-split animate-spin mr-2"></i> Menyimpan...').prop('disabled', true);
        
        $.ajax({
            url: `{{ route('laboran.ruangan.store') }}`,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    if(typeof window.showToast === 'function') {
                        window.showToast(response.message, 'success');
                    }
                    closeCreateModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                submitBtn.html(originalText).prop('disabled', false);
                let msg = 'Gagal menyimpan ruangan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if(typeof window.showToast === 'function') {
                    window.showToast(msg, 'error');
                } else {
                    alert(msg);
                }
            }
        });
    });
</script>
@endpush
