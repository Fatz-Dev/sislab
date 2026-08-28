@extends('layouts.app')
@section('title', 'Riwayat Laporan Kerusakan')

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
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Laporan Saya</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Riwayat Laporan Kerusakan & Kondisi Barang</h1>
        </div>
    </div>

    <!-- Alert / Toast -->
    <div id="alert-container"></div>

    <!-- Laporan List -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-slate-800 dark:text-white">Daftar Laporan yang Telah Dibuat</h2>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm shadow-green-600/20 transition-all focus:outline-none focus:ring-2 focus:ring-green-500/50">
                <i class="bi bi-plus-lg mr-2"></i> Buat Laporan Baru
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Kelas / Ruangan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Status SOP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Kondisi Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Catatan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Status Admin</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($laporans as $laporan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                {{ $laporan->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                <div class="font-medium">{{ optional(optional($laporan->jadwal)->kelasPraktikum)->nama_kelas }}</div>
                                <div class="text-xs text-slate-500">{{ optional(optional(optional($laporan->jadwal)->kelasPraktikum)->ruangan)->nama_ruangan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white capitalize">
                                {{ str_replace('_', ' ', $laporan->status_sop) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                @if($laporan->kelayakan_barang == 'semua_layak')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Semua Layak</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ada Rusak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-900 dark:text-white min-w-[200px]">
                                {{ $laporan->catatan_temuan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if($laporan->status_admin == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="bi bi-hourglass-split mr-1"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="bi bi-check-circle mr-1"></i> Reviewed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                <i class="bi bi-inbox text-3xl mb-2 block"></i>
                                Anda belum pernah membuat laporan kerusakan.
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
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg w-full max-w-lg p-0 transform transition-all scale-95 opacity-0 duration-200" id="createModalContent">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Buat Laporan Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="createLaporanForm" class="p-5 space-y-4">
            @csrf
            <div>
                <label for="jadwal_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pilih Jadwal / Pertemuan <span class="text-red-500">*</span></label>
                <select name="jadwal_id" id="jadwal_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" required>
                    <option value="">-- Pilih Jadwal Praktikum yang Telah Selesai --</option>
                    @foreach($jadwals as $jadwal)
                        <option value="{{ $jadwal->id }}">
                            {{ optional($jadwal->kelasPraktikum)->nama_kelas }} 
                            ({{ optional(optional($jadwal->kelasPraktikum)->ruangan)->nama_ruangan }}) 
                            - {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status_sop" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Pelaksanaan SOP <span class="text-red-500">*</span></label>
                <select name="status_sop" id="status_sop" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" required>
                    <option value="dijalankan">Dijalankan</option>
                    <option value="dijalankan_sebagian">Dijalankan Sebagian</option>
                    <option value="tidak_dijalankan">Tidak Dijalankan</option>
                </select>
            </div>

            <div>
                <label for="kelayakan_barang" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kondisi Kelayakan Barang <span class="text-red-500">*</span></label>
                <select name="kelayakan_barang" id="kelayakan_barang" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" onchange="toggleCatatan()" required>
                    <option value="semua_layak">Semua Layak</option>
                    <option value="ada_yang_rusak">Ada yang Rusak / Hilang</option>
                </select>
            </div>

            <div id="catatanContainer" class="hidden">
                <label for="catatan_temuan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Temuan / Kerusakan <span class="text-red-500">*</span></label>
                <textarea name="catatan_temuan" id="catatan_temuan" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm py-2" placeholder="Sebutkan barang yang rusak atau masalah lain..."></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Batal</button>
                <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm shadow-green-600/20 transition-all flex items-center">
                    <i class="bi bi-send mr-2"></i> Kirim Laporan
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

    function toggleCatatan() {
        if ($('#kelayakan_barang').val() === 'ada_yang_rusak') {
            $('#catatanContainer').removeClass('hidden');
            $('#catatan_temuan').attr('required', true);
        } else {
            $('#catatanContainer').addClass('hidden');
            $('#catatan_temuan').removeAttr('required').val('');
        }
    }

    $('#createLaporanForm').on('submit', function(e) {
        e.preventDefault();
        
        let jadwalId = $('#jadwal_id').val();
        if (!jadwalId) {
            if(typeof window.showToast === 'function') {
                window.showToast('Silakan pilih jadwal terlebih dahulu.', 'error');
            } else {
                alert('Silakan pilih jadwal terlebih dahulu.');
            }
            return;
        }

        let submitBtn = $('#submitBtn');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="bi bi-hourglass-split mr-2"></i> Mengirim...').prop('disabled', true);
        
        $.ajax({
            url: `/laboran/jadwal/${jadwalId}/laporan`,
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
                let msg = 'Gagal mengirim laporan.';
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
