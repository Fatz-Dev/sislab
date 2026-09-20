@extends('layouts.app')
@section('title', 'Dashboard Dosen')

@section('content')
<div class="space-y-6">

    <!-- Top Greeting Banner -->
    <div class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span>Dashboard Pengampu Praktikum</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Selamat datang kembali, <strong>{{ $dosen->name }}</strong>! Pantau kelas praktikum yang Anda ampu, data mahasiswa, dan agenda jadwal mengajar.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Dosen Pengampu
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium bg-slate-50 dark:bg-slate-800/60 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700">
                <i class="bi bi-calendar3 mr-1 text-slate-400"></i>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Summary Cards Grid (Desain Asli dengan Tailwind CSS) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1: Kelas Summary -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Kelas Summary</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#ffeedb] dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalKelasDosen }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelas Diampu</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#eceaff] dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <strong class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $kelasList->sum('kapasitas') }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Kapasitas</span>
                </div>
            </div>
        </article>

        <!-- Card 2: Mahasiswa Summary -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Mahasiswa Summary</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-people"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalMahasiswaBimbingan }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mahasiswa Aktif</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e7e5ff] dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <strong class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $totalKelasDosen > 0 ? round($totalMahasiswaBimbingan / $totalKelasDosen) : 0 }}
                    </strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rata-rata / Kelas</span>
                </div>
            </div>
        </article>

        <!-- Card 3: Modul Summary -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Materi & Modul</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalModul }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Modul Diunggah</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#ffeedb] dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-folder-check"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">
                        {{ $kelasList->where('modul_praktikums_count', '>', 0)->count() }}
                    </strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelas Ber-Modul</span>
                </div>
            </div>
        </article>

        <!-- Card 4: Sesi Praktikum -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Sesi Praktikum</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalJadwal }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Pertemuan</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#eceaff] dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <strong class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $jadwalDosen->count() }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Jadwal Mendatang</span>
                </div>
            </div>
        </article>
    </div>

    <!-- Chart.js Visual Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart 1: Kapasitas vs Mahasiswa Terdaftar (Bar Chart) -->
        <div class="lg:col-span-7 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-bar-chart-fill text-primary"></i> Kapasitas & Mahasiswa per Kelas
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbandingan jumlah mahasiswa terdaftar vs kapasitas maksimal kelas</p>
                </div>
                <a href="{{ route('dosen.kelas.index') }}" class="text-xs text-primary hover:underline font-medium flex items-center gap-1">
                    Kelola Kelas <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-72 w-full">
                <canvas id="chartKelasDosen"></canvas>
            </div>
        </div>

        <!-- Chart 2: Status Penilaian Laporan Mahasiswa (Doughnut Chart) -->
        <div class="lg:col-span-5 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-pie-chart text-primary"></i> Evaluasi Tugas Laporan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Progres penilaian submisi laporan mahasiswa</p>
                </div>
                <a href="{{ route('dosen.rekap.index') }}" class="text-xs text-primary hover:underline font-medium">
                    Rekap Nilai
                </a>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="chartPenilaianDosen"></canvas>
            </div>

            <!-- Status Metrics Bar -->
            <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100 dark:border-slate-800 mt-3 text-center">
                <div class="p-2.5 rounded-lg bg-emerald-50/60 dark:bg-emerald-950/20">
                    <span class="block text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ $sudahDinilai }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Sudah Dinilai</span>
                </div>
                <div class="p-2.5 rounded-lg bg-amber-50/60 dark:bg-amber-950/20">
                    <span class="block text-sm font-bold text-amber-700 dark:text-amber-400">{{ $belumDinilai }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Menunggu Penilaian</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Row: Tabel Kelas Diampu & Jadwal Praktikum Terdekat -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Tabel: Daftar Kelas Praktikum yang Diampu -->
        <div class="lg:col-span-7 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-collection text-primary"></i> Kelas Praktikum Saya
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar kelas praktikum yang Anda ampu pada semester aktif</p>
                </div>
                <a href="{{ route('dosen.kelas.index') }}" class="text-xs text-primary hover:underline font-medium">
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                    <thead class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-2.5 px-3 rounded-l-lg">Nama Kelas</th>
                            <th class="py-2.5 px-3">Laboran</th>
                            <th class="py-2.5 px-3">Ruangan</th>
                            <th class="py-2.5 px-3 text-center">Modul</th>
                            <th class="py-2.5 px-3 text-right rounded-r-lg">Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($kelasList as $kelas)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-3">
                                    <a href="{{ route('dosen.kelas.show', $kelas->id) }}" class="font-semibold text-slate-800 dark:text-white hover:text-primary dark:hover:text-primary transition-colors">
                                        {{ $kelas->nama_kelas }}
                                    </a>
                                    @if($kelas->semester?->nama_semester)
                                        <div class="text-[11px] text-slate-400">{{ $kelas->semester->nama_semester }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if($kelas->laboran?->name)
                                        <span class="text-slate-700 dark:text-slate-300 font-medium">
                                            {{ $kelas->laboran->name }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if($kelas->ruangan?->nama_ruangan)
                                        <span class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300">
                                            <i class="bi bi-geo-alt text-slate-400 text-[10px]"></i>
                                            {{ $kelas->ruangan->nama_ruangan }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-400 border border-sky-200 dark:border-sky-800">
                                        {{ $kelas->modul_praktikums_count }} Modul
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $kelas->approved_mahasiswas_count }}</span>
                                    <span class="text-slate-400 text-[11px]">/ {{ $kelas->kapasitas }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada kelas praktikum yang diampu pada semester ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel: Agenda Jadwal Praktikum Terdekat -->
        <div class="lg:col-span-5 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary"></i> Jadwal Praktikum Terdekat
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sesi pertemuan praktikum yang akan berlangsung</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                    <thead class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-2.5 px-3 rounded-l-lg">Tanggal & Jam</th>
                            <th class="py-2.5 px-3">Kelas</th>
                            <th class="py-2.5 px-3 text-right rounded-r-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($jadwalDosen as $jadwal)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-800 dark:text-white">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-medium text-slate-800 dark:text-white">
                                        {{ $jadwal->kelasPraktikum?->nama_kelas }}
                                    </div>
                                    @if($jadwal->ruangan?->nama_ruangan)
                                        <div class="text-[11px] text-slate-400">
                                            {{ $jadwal->ruangan->nama_ruangan }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('dosen.jadwal.show', [$jadwal->kelas_praktikum_id, $jadwal->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-medium bg-primary text-white hover:bg-opacity-90 transition-colors">
                                        Buka <i class="bi bi-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    <i class="bi bi-calendar-check text-2xl block mb-1 text-slate-300"></i>
                                    Tidak ada jadwal praktikum mendatang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js tidak termuat.');
        return;
    }

    Chart.defaults.font.family = "'Inter', 'Lexend', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Chart Kapasitas vs Mahasiswa Terdaftar per Kelas
    const ctxKelas = document.getElementById('chartKelasDosen');
    if (ctxKelas) {
        new Chart(ctxKelas, {
            type: 'bar',
            data: {
                labels: @json($chartKelas['labels']),
                datasets: [
                    {
                        label: 'Mahasiswa Terdaftar',
                        data: @json($chartKelas['dataTerdaftar']),
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                        maxBarThickness: 32
                    },
                    {
                        label: 'Kapasitas Kelas',
                        data: @json($chartKelas['dataKapasitas']),
                        backgroundColor: '#6366F1',
                        borderRadius: 6,
                        maxBarThickness: 32
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 5, font: { size: 11 }, color: '#64748b' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 8,
                            boxHeight: 8,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 11 }
                    }
                }
            }
        });
    }

    // 2. Chart Evaluasi / Penilaian Laporan Mahasiswa
    const ctxPenilaian = document.getElementById('chartPenilaianDosen');
    if (ctxPenilaian) {
        const dataValues = @json($chartPenilaian['data']);
        const total = dataValues.reduce((a, b) => a + b, 0);

        new Chart(ctxPenilaian, {
            type: 'doughnut',
            data: {
                labels: @json($chartPenilaian['labels']),
                datasets: [{
                    data: total > 0 ? dataValues : [0, 0],
                    backgroundColor: [
                        '#10B981', // Sudah Dinilai (Emerald)
                        '#F59E0B'  // Menunggu Penilaian (Amber)
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 11 },
                        callbacks: {
                            label: function(context) {
                                const val = context.raw || 0;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} laporan (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
