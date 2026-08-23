@extends('layouts.app')
@section('title', 'Detail Jadwal & Absensi Mahasiswa')

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
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Detail Jadwal</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Pertemuan {{ $minggu_ke }} - {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
            </h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-slate-800 dark:text-red-400" role="alert">
            <span class="font-medium">Peringatan:</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if(typeof window.showToast === 'function') {
                    window.showToast("{{ session('success') }}", 'success');
                }
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Info Panel Kiri -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informasi Jadwal -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-slate-500 dark:text-white"></i>
                    <h2 class="font-semibold text-slate-800 dark:text-white">Informasi Jadwal</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-1">Topik</dt>
                        <dd class="text-sm text-slate-900 dark:text-white font-medium">{{ $jadwal->topik ?: 'Tidak ada topik' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-1">Waktu</dt>
                        <dd class="text-sm text-slate-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 dark:text-white uppercase tracking-wider mb-1">Status</dt>
                        <dd class="mt-1">
                            @if($jadwal->status === 'terjadwal')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5"></span> Terjadwal
                                </span>
                            @elseif($jadwal->status === 'berlangsung')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 animate-pulse">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Berlangsung
                                </span>
                            @elseif($jadwal->status === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                    <span class="w-2 h-2 rounded-full bg-slate-500 mr-1.5"></span> Selesai
                                </span>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Panel Kanan -->
        <div class="lg:col-span-2">
            <!-- Tab Navigation -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700 mb-6">
                <nav class="flex overflow-x-auto" aria-label="Tabs">
                    <a href="javascript:void(0)" onclick="switchTab('absensi')" id="tab-absensi"
                        class="tab-link border-b-2 border-green-500 text-green-600 font-bold py-4 px-6 text-sm transition-colors whitespace-nowrap">
                        <i class="bi bi-people mr-1"></i> Absensi Mahasiswa
                    </a>
                    <a href="javascript:void(0)" onclick="switchTab('tugas')" id="tab-tugas"
                        class="tab-link border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white dark:hover:border-slate-600 py-4 px-6 text-sm font-medium transition-colors whitespace-nowrap">
                        <i class="bi bi-journal-text mr-1"></i> Tugas & Laporan
                    </a>
                </nav>
            </div>

            <!-- Tab Absensi -->
            <div id="content-absensi" class="tab-content block">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h2 class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-ui-checks text-green-500"></i> Form Kehadiran
                    </h2>
                    @if($jadwal->status === 'selesai')
                        <div class="text-xs text-amber-600 bg-amber-50 px-3 py-1.5 rounded-md border border-amber-200">
                            <i class="bi bi-exclamation-triangle"></i> Jadwal telah selesai. Keterangan wajib diisi.
                        </div>
                    @endif
                </div>

                <form action="{{ route('laboran.jadwal.absenMahasiswa', [$kelas->id, $jadwal->id]) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Mahasiswa</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Kehadiran</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-white uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($mahasiswas as $mhs)
                                    @php
                                        // Cari data absensi dari relasi yang sudah di-eager load
                                        $absenMhs = $mhs->absensis->first();
                                        $currentStatus = $absenMhs ? $absenMhs->status_hadir : '';
                                        $currentKeterangan = $absenMhs ? $absenMhs->keterangan : '';
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $mhs->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $mhs->nim ?? 'NIM belum diatur' }}</div>
                                        </td>
                                        
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-3">
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" name="absensi[{{ $mhs->id }}][status_hadir]" value="hadir" class="w-4 h-4 text-emerald-600 bg-slate-100 border-slate-300 focus:ring-emerald-500" {{ $currentStatus == 'hadir' ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700 dark:text-slate-300">H</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" name="absensi[{{ $mhs->id }}][status_hadir]" value="izin" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 focus:ring-blue-500" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700 dark:text-slate-300">I</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" name="absensi[{{ $mhs->id }}][status_hadir]" value="sakit" class="w-4 h-4 text-amber-500 bg-slate-100 border-slate-300 focus:ring-amber-500" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700 dark:text-slate-300">S</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" name="absensi[{{ $mhs->id }}][status_hadir]" value="alpha" class="w-4 h-4 text-red-600 bg-slate-100 border-slate-300 focus:ring-red-500" {{ $currentStatus == 'alpha' ? 'checked' : '' }}>
                                                    <span class="text-sm text-slate-700 dark:text-slate-300">A</span>
                                                </label>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4">
                                            <input type="text" name="absensi[{{ $mhs->id }}][keterangan]" value="{{ old('absensi.'.$mhs->id.'.keterangan', $currentKeterangan) }}" placeholder="Alasan..." class="bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" {{ $jadwal->status === 'selesai' ? 'required' : '' }}>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-slate-500 dark:text-white">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="bi bi-people text-3xl mb-2 text-slate-300"></i>
                                                <p>Belum ada mahasiswa terdaftar di kelas ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($mahasiswas) > 0)
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 transition-colors flex items-center gap-2">
                            <i class="bi bi-save"></i> Simpan Semua Absensi
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            </div> <!-- End Tab Absensi -->

            <!-- Tab Tugas -->
            <div id="content-tugas" class="tab-content hidden bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 ">
            <div class="flex px-2 py-3 justify-end border-b-2 border-slate-100 dark:border-slate-700">
                <a href="{{ route('laboran.tugas.create', ['kelas_id' => $kelas->id, 'jadwal_id' => $jadwal->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2 text-sm font-medium transition-colors">
                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                </a>
            </div>
                @php
                    $tugasPertemuan = $kelas->tugasLaporans->where('jadwal_id', $jadwal->id);
                @endphp
                @if($tugasPertemuan->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400 text-3xl mx-auto mb-4">
                            <i class="bi bi-journal-x"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Belum Ada Tugas</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-0">Belum ada tugas atau laporan yang ditugaskan pada pertemuan ini.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($tugasPertemuan as $tugas)
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-5 hover:border-green-300 dark:hover:border-green-600 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow">
                                <div>
                                    <h4 class="text-base font-bold text-slate-800 dark:text-white mb-1">{{ $tugas->judul }}</h4>
                                    @if($tugas->deskripsi)
                                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-0">{{ $tugas->deskripsi }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800">
                                            <i class="bi bi-clock-history"></i>
                                            Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d F Y, H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="shrink-0 w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('laboran.tugas.edit', ['kelas_id' => $kelas->id, 'tugas_id' => $tugas->id, 'jadwal_id' => $jadwal->id]) }}" class="w-full sm:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <!-- Aksi laboran lihat daftar kumpul -->
                                    <a href="{{ route('laboran.tugas.submissions', [$kelas->id, $tugas->id]) }}" class="w-full sm:w-auto px-4 py-2 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-400 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                        Lihat Kumpulan <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div> <!-- End Tab Tugas -->

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabId) {
        // Reset all tabs
        $('.tab-link').removeClass('border-green-500 text-green-600 font-bold')
                     .addClass('border-transparent text-slate-500 font-medium');
        $('.tab-content').addClass('hidden').removeClass('block');
        
        // Set active tab
        $('#tab-' + tabId).removeClass('border-transparent text-slate-500 font-medium')
                          .addClass('border-green-500 text-green-600 font-bold');
        $('#content-' + tabId).removeClass('hidden').addClass('block');
    }
</script>
@endpush
