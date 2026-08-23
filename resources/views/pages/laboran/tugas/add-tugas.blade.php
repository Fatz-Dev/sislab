@extends('layouts.app')
@section('title', $tugas ? 'Edit Tugas & Laporan' : 'Tambah Tugas & Laporan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Penyesuaian agar flatpickr menyatu dengan style tailwind */
        .flatpickr-calendar { font-family: inherit; }
    </style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.kelas.index') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-journal-bookmark mr-1.5"></i> Kelas Praktikum
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <a href="{{ route('laboran.kelas.show', $kelas->id) }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors ml-1">
                                {{ $kelas->nama_kelas }}
                            </a>
                        </div>
                    </li>
                    @if($jadwal_id)
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <a href="{{ route('laboran.jadwal.show', [$kelas->id, $jadwal_id]) }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors ml-1">
                                Detail Jadwal
                            </a>
                        </div>
                    </li>
                    @endif
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">{{ $tugas ? 'Edit Tugas' : 'Tambah Tugas' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                {{ $tugas ? 'Edit Tugas & Laporan' : 'Tambah Tugas & Laporan' }}
            </h1>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
            <i class="bi {{ $tugas ? 'bi-pencil-square' : 'bi-plus-circle' }} text-green-500"></i>
            <h2 class="font-semibold text-slate-800 dark:text-white">Formulir {{ $tugas ? 'Edit' : 'Tambah' }} Tugas</h2>
        </div>

        <div class="p-5">
            <form id="tugasForm" action="{{ $tugas ? route('laboran.tugas.update', [$kelas->id, $tugas->id]) : route('laboran.tugas.store', $kelas->id) }}" method="POST">
                @csrf
                @if($tugas)
                    @method('PUT')
                @endif
                @if($jadwal_id)
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal_id }}">
                @endif
                <div class="space-y-4">
                    <!-- Judul Tugas -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Tugas <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ $tugas ? $tugas->judul : '' }}" 
                            class="bg-gray-50 dark:bg-slate-800 border dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                            placeholder="Contoh: Laporan Pendahuluan Modul 1" required>
                        <p class="mt-1 text-sm text-red-600 hidden" id="error-judul"></p>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tenggat Waktu (Deadline) <span class="text-red-500">*</span></label>
                        <input type="text" id="deadline" name="deadline" 
                            value="{{ $tugas && $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('Y-m-d H:i') : '' }}" 
                            class="bg-gray-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                            placeholder="Pilih Tanggal dan Waktu..." required>
                        <p class="mt-1.5 text-sm font-medium text-green-600 dark:text-green-400 hidden flex items-center gap-1" id="preview-deadline"></p>
                        <p class="mt-1 text-sm text-red-600 hidden" id="error-deadline"></p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi & Instruksi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" 
                            class="bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                            placeholder="Tuliskan instruksi atau format laporan di sini...">{{ $tugas ? $tugas->deskripsi : '' }}</textarea>
                        <p class="mt-1 text-sm text-red-600 hidden" id="error-deskripsi"></p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    @if($jadwal_id)
                        <a href="{{ route('laboran.jadwal.show', [$kelas->id, $jadwal_id]) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-700 transition-colors">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('laboran.kelas.show', $kelas->id) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-700 transition-colors">
                            Batal
                        </a>
                    @endif
                    <button type="submit" id="btnSubmit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 transition-colors flex items-center gap-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    $(document).ready(function() {
        
        // Initialize Flatpickr
        $('#deadline').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id"
        });

        $('#tugasForm').on('submit', function(e) {
            e.preventDefault();
            
            let form = $(this);
            let url = form.attr('action');
            let method = form.attr('method');
            let submitBtn = $('#btnSubmit');
            let originalText = submitBtn.html();
            
            // Clear previous errors
            $('[id^="error-"]').addClass('hidden').text('');
            
            // Disable button
            submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split animate-spin"></i> Menyimpan...');

            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),
                success: function(response) {
                    if (typeof window.showToast === 'function') {
                        window.showToast(response.message, 'success');
                    }
                    
                    // Redirect back
                    setTimeout(function() {
                        let jadwalId = "{{ $jadwal_id }}";
                        if(jadwalId) {
                            window.location.href = "{{ route('laboran.jadwal.show', [$kelas->id, ':id']) }}".replace(':id', jadwalId);
                        } else {
                            window.location.href = "{{ route('laboran.kelas.show', $kelas->id) }}";
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            $('#error-' + key).removeClass('hidden').text(errors[key][0]);
                        }
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('Terjadi kesalahan pada server.', 'error');
                        }
                    }
                }
            });
        });

        // Live Preview Deadline
        const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        function updateDeadlinePreview() {
            let val = $('#deadline').val();
            let preview = $('#preview-deadline');
            if (val) {
                let d = new Date(val);
                let text = '<i class="bi bi-info-circle text-green-500"></i> Tugas akan ditutup pada: <strong>' + 
                           namaHari[d.getDay()] + ', ' + 
                           d.getDate() + ' ' + 
                           namaBulan[d.getMonth()] + ' ' + 
                           d.getFullYear() + ' pukul ' + 
                           String(d.getHours()).padStart(2, '0') + ':' + 
                           String(d.getMinutes()).padStart(2, '0') + ' WIB</strong>';
                preview.html(text).removeClass('hidden');
            } else {
                preview.addClass('hidden').html('');
            }
        }

        $('#deadline').on('change input', updateDeadlinePreview);
        
        // Trigger on initial load (terutama saat Mode Edit)
        updateDeadlinePreview();
    });
</script>
@endpush
