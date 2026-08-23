@extends('layouts.app-mahasiswa')
@section('title', 'Kumpul Tugas: ' . $tugas->judul)

@section('content')
    <div class="space-y-6 mt-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Informasi Tugas -->
            <div class="lg:col-span-1">
                <div class="bg-white  rounded-xl shadow-sm border border-slate-100 p-5 space-y-4">
                    <h2 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-3">Informasi Tugas</h2>

                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1">Judul Tugas</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $tugas->judul }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1">Diberikan Oleh</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $tugas->laboran->name ?? 'Laboran' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1">Tenggat Waktu</p>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar-x text-black"></i>
                            <span class="text-sm font-bold text-black">
                                {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('l, d F Y H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengumpulan -->
            <div class="lg:col-span-2">
                <div class="bg-white  rounded-xl shadow-sm border border-slate-100 p-5">
                    <h2 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-3 mb-4">Area Pengumpulan</h2>

                    <div class="mb-4 mt-4">
                        <p class="text-xs text-slate-500 font-medium mb-1">Deskripsi & Instruksi</p>
                        <div class="text-sm text-slate-700 bg-slate-50  p-3 rounded-lg border border-slate-100">
                            {!! nl2br(e($tugas->deskripsi ?? 'Tidak ada instruksi khusus.')) !!}
                        </div>
                    </div>

                    @if($submission)
                        <div class="mb-6 p-4 bg-green-50  border border-green-200  rounded-lg flex items-start gap-3">
                            <div class="p-2 bg-green-100  rounded-full shrink-0">
                                <i class="bi bi-check-lg text-green-600  text-lg"></i>
                            </div>
                            <div class="w-full">
                                <h3 class="font-semibold text-green-800">Tugas Sudah Dikumpulkan!</h3>
                                <p class="text-sm text-green-700 mt-1">Anda mengumpulkan tugas pada {{ \Carbon\Carbon::parse($submission->tanggal_submit)->translatedFormat('l, d F Y H:i') }} WIB.</p>
                                <p class="text-sm font-medium text-green-700 mt-1">
                                    Status: 
                                    @if($submission->status == 'tepat_waktu')
                                        <span class="bg-green-200 text-green-800 text-xs px-2 py-0.5 rounded-full uppercase tracking-wider">Tepat Waktu</span>
                                    @else
                                        <span class="bg-red-200 text-red-800 text-xs px-2 py-0.5 rounded-full uppercase tracking-wider">Terlambat</span>
                                    @endif
                                </p>
                                <div class="mt-3">
                                    <a href="{{ Storage::url($submission->file_laporan) }}" target="_blank" class="text-sm font-medium text-green-700 hover:underline flex items-center gap-1">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat File Terunggah
                                    </a>
                                </div>
                                
                                @if(isset($nilai))
                                <div class="mt-4 p-3 bg-white rounded border border-green-200 shadow-sm">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Catatan Laboran</p>
                                    @if($nilai->keterangan)
                                        <p class="text-sm text-slate-800">{{ $nilai->keterangan }}</p>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada catatan dari laboran.</p>
                                    @endif
                                    
                                    <div class="mt-2 pt-2 border-t border-slate-100">
                                        <p class="text-sm text-slate-700"><strong>Nilai:</strong> <span class="{{ $nilai->nilai >= 70 ? 'text-green-600' : 'text-red-600' }} font-bold">{{ $nilai->nilai ?? 'Belum dinilai' }}</span></p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <form id="submitTugasForm" action="{{ route('mahasiswa.tugas.submit', [$kelas_id, $tugas->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-slate-900" for="file_laporan">
                                    Unggah File Laporan
                                </label>
                                <input class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none" 
                                       id="file_laporan" 
                                       name="file_laporan" 
                                       type="file" 
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                                       required>
                                <p class="mt-1 text-xs text-slate-500">Format yang didukung: PDF, DOCX, PPTX, ZIP, RAR. (Maks 10MB)</p>
                                <p class="mt-1 text-sm text-red-600 hidden" id="error-file_laporan"></p>
                                
                                <!-- Container Live Preview -->
                                <div id="filePreviewContainer" class="mt-4 hidden border border-slate-200 rounded-lg overflow-hidden bg-slate-50"></div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <a href="{{ route('mahasiswa.kelas.detail', $kelas_id) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 transition-colors">
                                    Kembali
                                </a>
                                <button type="submit" id="btnSubmit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors flex items-center gap-2">
                                    <i class="bi bi-cloud-arrow-up"></i> Kirim Laporan
                                </button>
                            </div>
                        </form>
                        <div class="mt-4">
                            <h2 class="font-semibold text-slate-800">Informasi</h2>
                            <p class="text-sm text-slate-500">Tugas nya hanya bisa dikumpulkan sekali, periksa kembali tugas nya sebelum dikirim.</p>
                        </div>
                    @endif


                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
        // --- Live Preview File Handler ---
        $('#file_laporan').on('change', function(e) {
            let container = $('#filePreviewContainer');
            let file = e.target.files[0];
            
            if (!file) {
                container.addClass('hidden').empty();
                return;
            }

            container.removeClass('hidden').empty();

            let fileType = file.type;
            let fileName = file.name;
            let fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            // Jika tipe file adalah PDF, render menggunakan iframe
            if (fileType === 'application/pdf') {
                let fileURL = URL.createObjectURL(file);
                container.html(`
                    <div class="bg-slate-100 p-2 border-b border-slate-200 flex justify-between items-center">
                        <span class="text-sm font-semibold text-slate-700 truncate px-2"><i class="bi bi-file-earmark-pdf text-red-500 mr-1"></i> ${fileName} <span class="text-xs text-slate-500 ml-2">(${fileSize})</span></span>
                        <button type="button" class="btn-clear-file text-slate-400 hover:text-red-500 transition-colors px-2" title="Batal Pilih File">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <iframe src="${fileURL}" class="w-full h-[400px]" frameborder="0"></iframe>
                `);
            } else {
                // Tipe Non-PDF (Word, PPT, ZIP, dll)
                let iconClass = 'bi-file-earmark-text text-blue-500'; // Default icon
                
                if (fileName.match(/\.(doc|docx)$/i)) iconClass = 'bi-file-earmark-word text-blue-600';
                else if (fileName.match(/\.(ppt|pptx)$/i)) iconClass = 'bi-file-earmark-ppt text-orange-500';
                else if (fileName.match(/\.(zip|rar)$/i)) iconClass = 'bi-file-earmark-zip text-yellow-600';

                container.html(`
                    <div class="relative p-8 flex flex-col items-center justify-center text-center">
                        <button type="button" class="btn-clear-file absolute top-4 right-4 text-slate-400 hover:text-red-500 transition-colors" title="Batal Pilih File">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                        <i class="bi ${iconClass} text-6xl mb-3"></i>
                        <h4 class="text-base font-bold text-slate-800 break-all w-full md:w-3/4">${fileName}</h4>
                        <p class="text-sm text-slate-500 mt-1">Ukuran: ${fileSize}</p>
                        <div class="mt-4 px-4 py-2 bg-blue-50 text-blue-700 text-xs rounded-lg border border-blue-100 inline-block">
                            <i class="bi bi-info-circle mr-1"></i> File siap untuk disubmit (Pratinjau isi tidak didukung untuk tipe ini)
                        </div>
                    </div>
                `);
            }
        });

        // Handler untuk tombol hapus (Batal pilih)
        $(document).on('click', '.btn-clear-file', function() {
            $('#file_laporan').val('');
            $('#filePreviewContainer').addClass('hidden').empty();
            $('#error-file_laporan').addClass('hidden').text('');
        });

        $('#submitTugasForm').on('submit', function(e) {
            e.preventDefault();
            
            let form = this;
            let formData = new FormData(form);
            let url = $(form).attr('action');
            let submitBtn = $('#btnSubmit');
            let originalText = submitBtn.html();
            
            // Validate file input
            let fileInput = $('#file_laporan')[0];
            if (!fileInput.files.length && !{{ $submission ? 'true' : 'false' }}) {
                $('#error-file_laporan').removeClass('hidden').text('Silakan pilih file untuk diunggah.');
                return;
            }

            // Clear errors
            $('[id^="error-"]').addClass('hidden').text('');
            
            submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split animate-spin"></i> Mengunggah...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (typeof window.showToast === 'function') {
                        window.showToast(response.message, 'success');
                    }
                    
                    // Reload page after a short delay to show the "Sudah Dikumpulkan" alert
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
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
                            window.showToast(xhr.responseJSON?.message || 'Gagal mengunggah file.', 'error');
                        }
                    }
                }
            });
        });
    });
</script>
@endpush
