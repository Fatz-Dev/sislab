@extends('layouts.app-mahasiswa')
@section('title', 'Detail Kelas - ' . $kelas->nama_kelas)

@section('content')
    <div class="max-w-7xl mx-auto pb-10">
        <!-- Header Kelas -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-bold">Aktif</span>
                            <span class="text-sm font-semibold text-slate-500">{{ $kelas->semester->nama_semester }}</span>
                        </div>
                        <h1 class="text-2xl font-extrabold text-slate-900 m-0 leading-tight">{{ $kelas->nama_kelas }}</h1>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-6 pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-black shrink-0">
                            <i class="bi bi-person-badge text-xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-semibold text-slate-500 mb-0 uppercase tracking-wider">Dosen</p>
                            <p class="text-sm font-bold text-slate-800 mb-0 truncate">{{ $kelas->dosen->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-black shrink-0">
                            <i class="bi bi-person-workspace text-xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-semibold text-slate-500 mb-0 uppercase tracking-wider">Laboran</p>
                            <p class="text-sm font-bold text-slate-800 mb-0 truncate">{{ $kelas->laboran->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-black shrink-0">
                            <i class="bi bi-door-open text-xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-semibold text-slate-500 mb-0 uppercase tracking-wider">Ruangan</p>
                            <p class="text-sm font-bold text-slate-800 mb-0 truncate">{{ $kelas->ruangan->nama_ruang }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-black shrink-0">
                            <i class="bi bi-clock text-xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-semibold text-slate-500 mb-0 uppercase tracking-wider">Waktu</p>
                            <p class="text-sm font-bold text-slate-800 mb-0 truncate">
                                {{ ucfirst($kelas->hari) }},
                                {{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Tab -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 overflow-x-auto">
                <nav class="flex -mb-px px-6 gap-6 min-w-max" aria-label="Tabs">
                    <a href="javascript:void(0)" onclick="switchTab('materi')" id="tab-materi" class="tab-link border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-sm font-bold transition-colors">
                        Modul Materi
                    </a>
                    <a href="javascript:void(0)" onclick="switchTab('tugas')" id="tab-tugas"
                        class="tab-link border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 py-4 px-1 text-sm font-medium transition-colors">
                        Tugas & Laporan
                    </a>
                </nav>
            </div>

            <!-- Tab Materi -->
            <div id="content-materi" class="tab-content p-6 space-y-8">
                <!-- Modul Praktikum -->
                <div>
                    <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Modul Utama</h3>
                    @if(isset($kelas->modulPraktikums) && $kelas->modulPraktikums->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($kelas->modulPraktikums as $modul)
                            <div class="flex w-full bg-white items-center gap-3 p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300">
                                <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="relative shrink-0 block mt-1">
                                    <canvas data-pdf-url="{{ Storage::url($modul->file_pdf) }}" class="pdf-canvas w-12 h-16 rounded border border-slate-200 shadow-sm object-cover bg-white" title="Cover PDF"></canvas>
                                    <div class="pdf-loading-skeleton absolute inset-0 bg-slate-200 animate-pulse rounded border border-slate-200 flex items-center justify-center">
                                        <i class="bi bi-file-earmark-pdf text-blue-400 text-xl"></i>
                                    </div>
                                </a>
                                <div class="flex-grow min-w-0">
                                    <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="text-sm font-semibold text-slate-800 truncate hover:text-blue-600 transition-colors block" title="{{ $modul->judul }}">{{ $modul->judul }}</a>
                                    <p class="text-xs text-slate-500 mt-1 mb-2">{{ $modul->tanggal_upload ? $modul->tanggal_upload->format('d M Y') : '' }}</p>
                                    <a href="{{ Storage::url($modul->file_pdf) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-100 transition-colors border border-blue-100">
                                        <i class="bi bi-eye"></i> Lihat Modul
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-8 text-center bg-slate-50 dark:bg-slate-800/50">
                            <div class="w-16 h-16 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-400">
                                <i class="bi bi-journal-x text-2xl"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-700 dark:text-slate-300">Belum Ada Modul Utama</h4>
                            <p class="text-sm text-slate-500 mt-1">Dosen belum mengunggah modul praktikum untuk kelas ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Jadwal Pertemuan -->
                <div>
                    <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Jadwal Pertemuan</h3>
                    @if(isset($kelas->jadwals) && $kelas->jadwals->count() > 0)
                        <div class="space-y-4">
                            @foreach($kelas->jadwals as $jadwal)
                            <div class="border border-slate-200 rounded-xl p-5 bg-white  shadow-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded border border-indigo-100">Pertemuan {{ $loop->iteration }}</span>
                                            <h4 class="text-base font-bold text-slate-800 m-0">{{ $jadwal->topik }}</h4>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-start sm:items-end gap-1">
                                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                            <i class="bi bi-calendar-event text-blue-500"></i>
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                            <i class="bi bi-clock"></i>
                                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border border-slate-200 rounded-xl p-8 text-center bg-slate-50 ">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-400">
                                <i class="bi bi-calendar-x text-2xl"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-700">Belum Ada Pertemuan</h4>
                            <p class="text-sm text-slate-500 mt-1">Jadwal pertemuan praktikum belum ditambahkan oleh dosen.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab Tugas -->
            <div id="content-tugas" class="tab-content hidden p-6">
                @if($kelas->tugasLaporans->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-400 text-3xl mx-auto mb-4">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 m-0">Belum Ada Tugas</h3>
                        <p class="text-slate-500 mt-2 mb-0">Belum ada tugas atau laporan yang ditugaskan pada kelas ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($kelas->tugasLaporans as $tugas)
                            <div class="border border-slate-200 rounded-xl p-5 hover:border-blue-300 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50 hover:bg-white shadow-sm hover:shadow">
                                <div>
                                    <h4 class="text-base font-bold text-slate-800 mb-1">{{ $tugas->judul }}</h4>
                                    @if($tugas->deskripsi)
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-0">{{ $tugas->deskripsi }}</p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-3 mt-3">
                                        <div class="flex items-center gap-1.5 text-xs font-semibold text-orange-700 bg-orange-100 px-2.5 py-1 rounded-md border border-orange-200">
                                            <i class="bi bi-clock-history"></i>
                                            Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d F Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0 w-full md:w-auto mt-2 md:mt-0">
                                    <a href="{{ route('mahasiswa.tugas.show', [$kelas->id, $tugas->id]) }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm w-full md:w-auto flex items-center justify-center gap-2">
                                        Lihat Detail <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function switchTab(tabId) {
        // Reset all tabs to default state
        $('.tab-link').removeClass('border-blue-500 text-blue-600 font-bold').addClass('border-transparent text-slate-500 font-medium');
        $('.tab-content').addClass('hidden');
        
        // Activate selected tab
        $('#tab-' + tabId).removeClass('border-transparent text-slate-500 font-medium').addClass('border-blue-500 text-blue-600 font-bold');
        $('#content-' + tabId).removeClass('hidden');
    }
</script>

<!-- Script PDF.js untuk Cover Render -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    document.addEventListener("DOMContentLoaded", function() {
        // Hanya inisialisasi jika tab materi ditampilkan
        const renderPDFs = function() {
            document.querySelectorAll('.pdf-canvas').forEach(function(canvas) {
                if (canvas.dataset.rendered) return; // Jangan render ulang

                var url = canvas.getAttribute('data-pdf-url');
                var skeleton = canvas.nextElementSibling;
                
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    return pdf.getPage(1);
                }).then(function(page) {
                    var viewport = page.getViewport({ scale: 0.5 });
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    return page.render(renderContext).promise;
                }).then(function() {
                    canvas.dataset.rendered = true;
                    if (skeleton) skeleton.classList.add('hidden');
                }).catch(function(error) {
                    console.error('Error rendering PDF:', error);
                    if (skeleton) skeleton.classList.remove('animate-pulse');
                });
            });
        };

        // Panggil render saat tab materi dibuka, atau saat halaman di muat jika materi aktif (walau by default tab pertama yang aktif)
        $('#tab-materi').on('click', function() {
            // Beri sedikit jeda agar DOM terlihat dulu sebelum canvas merender (mencegah bug ukuran canvas 0)
            setTimeout(renderPDFs, 50);
        });
        
        // Panggil juga pertama kali jika tab materi default (meski saat ini tab info yang default)
        if (!$('#content-materi').hasClass('hidden')) {
            renderPDFs();
        }
    });
</script>
@endpush