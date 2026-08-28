@extends('layouts.app')
@section('title', 'Laporan Kerusakan & Kondisi Barang')

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-house-door mr-1.5"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Laporan Laboran</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Laporan Kerusakan & Kondisi Barang</h1>
        </div>
    </div>

    <!-- Alert / Toast -->
    <div id="alert-container"></div>

    <!-- Laporan List -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-slate-800 dark:text-white">Daftar Laporan Laboran</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Kelas / Ruangan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Laboran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Status SOP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Kondisi Barang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Catatan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700" id="laporan-table-body">
                    @forelse($laporans as $laporan)
                        <tr id="row-{{ $laporan->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                {{ $laporan->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                <div class="font-medium">{{ optional(optional($laporan->jadwal)->kelasPraktikum)->nama_kelas }}</div>
                                <div class="text-xs text-slate-500">{{ optional(optional(optional($laporan->jadwal)->kelasPraktikum)->ruangan)->nama_ruangan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                {{ optional($laporan->laboran)->name }}
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
                                    <button onclick="reviewLaporan({{ $laporan->id }})" class="btn-review text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-xs px-3 py-1.5 focus:outline-none">
                                        <i class="bi bi-check-lg mr-1"></i> Tandai Reviewed
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class="bi bi-check-circle mr-1"></i> Reviewed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                <i class="bi bi-inbox text-3xl mb-2 block"></i>
                                Belum ada laporan kerusakan dari Laboran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg w-full max-w-sm p-6 transform transition-all scale-95 opacity-0 duration-200" id="confirmModalContent">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-4 mx-auto">
            <i class="bi bi-check-circle text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 text-center">Konfirmasi Review</h3>
        <p class="text-slate-600 dark:text-slate-300 text-sm mb-6 text-center">Tandai laporan ini sudah diperiksa dan ditindaklanjuti?</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Batal</button>
            <button type="button" id="confirmBtn" class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm shadow-green-600/20">Ya, Tandai</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentReviewId = null;

    function reviewLaporan(id) {
        currentReviewId = id;
        const modal = $('#confirmModal');
        const content = $('#confirmModalContent');
        modal.removeClass('hidden').addClass('flex');
        setTimeout(() => {
            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }
    
    function closeConfirmModal() {
        const modal = $('#confirmModal');
        const content = $('#confirmModalContent');
        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            modal.addClass('hidden').removeClass('flex');
            currentReviewId = null;
        }, 200);
    }

    $('#confirmBtn').on('click', function() {
        if(!currentReviewId) return;
        let id = currentReviewId;
        closeConfirmModal();

        $.ajax({
            url: `/admin/laporan-laboran/${id}/review`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    if(typeof window.showToast === 'function') {
                        window.showToast(response.message, 'success');
                    }
                    
                    // Ganti tombol menjadi label reviewed
                    const row = $(`#row-${id}`);
                    const actionTd = row.find('td:last-child');
                    actionTd.html(`
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                            <i class="bi bi-check-circle mr-1"></i> Reviewed
                        </span>
                    `);
                }
            },
            error: function(err) {
                console.error(err);
                if(typeof window.showToast === 'function') {
                    window.showToast('Gagal mengupdate status laporan.', 'error');
                } else {
                    alert('Gagal mengupdate status laporan.');
                }
            }
        });
    }
</script>
@endpush
