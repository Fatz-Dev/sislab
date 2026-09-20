@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Greeting Banner -->
    <div class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span>Dashboard Laboratorium Fisika</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>! Pantau kondisi inventaris, ruangan lab, dan aktivitas praktikum secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Aktif
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium bg-slate-50 dark:bg-slate-800/60 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700">
                <i class="bi bi-calendar3 mr-1 text-slate-400"></i>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Summary Cards Grid (Desain Asli dengan Tailwind CSS) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1: Item Summary -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Item Summary</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#ffeedb] dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ number_format($totalUnitBarang) }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Quantity in Hand</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#eceaff] dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <strong class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stokBaik) }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kondisi Baik ({{ $persentaseBaik }}%)</span>
                </div>
            </div>
        </article>

        <!-- Card 2: Product Summary -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Product Summary</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalRuangan }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ruang Laboratorium</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e7e5ff] dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-tags"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalKategori }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kategori Alat</span>
                </div>
            </div>
        </article>

        <!-- Card 3: Total items -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Total items</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalJenisBarang }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Jenis Alat Lab</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#ffeedb] dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-tools"></i>
                    </div>
                    <strong class="text-lg font-bold {{ $stokPerluPerhatian > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-white' }}">{{ $stokPerluPerhatian }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perlu Perhatian</span>
                </div>
            </div>
        </article>

        <!-- Card 4: Total assets / Praktikum -->
        <article class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Aktivitas Praktikum</h2>
            <div class="grid grid-cols-2 divide-x divide-slate-100 dark:divide-slate-800">
                <div class="flex flex-col items-center text-center pr-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5f7fd] dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <strong class="text-lg font-bold text-slate-800 dark:text-white">{{ $totalKelas }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelas Praktikum</span>
                </div>
                <div class="flex flex-col items-center text-center pl-3">
                    <div class="w-8 h-8 rounded-lg bg-[#eceaff] dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 flex items-center justify-center text-sm font-semibold mb-2">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <strong class="text-lg font-bold {{ $pendingEnrollments > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-white' }}">{{ $pendingEnrollments }}</strong>
                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Enrollment Pending</span>
                </div>
            </div>
        </article>
    </div>

    <!-- Chart.js Visual Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart 1: Kondisi Kelayakan Peralatan (Doughnut Chart) -->
        <div class="lg:col-span-5 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-pie-chart text-primary"></i> Kondisi Kelayakan Alat
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Proporsi kondisi fisik seluruh unit inventaris</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                    {{ $persentaseBaik }}% Prima
                </span>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="chartKondisi"></canvas>
            </div>

            <!-- Kondisi Summary Metrics Bar -->
            <div class="grid grid-cols-4 gap-2 pt-4 border-t border-slate-100 dark:border-slate-800 mt-3 text-center">
                <div class="p-2 rounded-lg bg-emerald-50/60 dark:bg-emerald-950/20">
                    <span class="block text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ $stokBaik }}</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Baik</span>
                </div>
                <div class="p-2 rounded-lg bg-amber-50/60 dark:bg-amber-950/20">
                    <span class="block text-xs font-semibold text-amber-700 dark:text-amber-400">{{ $stokRusakRingan }}</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">R. Ringan</span>
                </div>
                <div class="p-2 rounded-lg bg-rose-50/60 dark:bg-rose-950/20">
                    <span class="block text-xs font-semibold text-rose-700 dark:text-rose-400">{{ $stokRusakBerat }}</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">R. Berat</span>
                </div>
                <div class="p-2 rounded-lg bg-slate-100/60 dark:bg-slate-800/50">
                    <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $stokHilang }}</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Hilang</span>
                </div>
            </div>
        </div>

        <!-- Chart 2: Sebaran & Komposisi Inventaris per Ruang Lab (Bar Chart) -->
        <div class="lg:col-span-7 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-bar-chart-fill text-primary"></i> Sebaran per Ruang Laboratorium
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbandingan jumlah alat kondisi baik vs perlu perbaikan</p>
                </div>
                <a href="{{ route('admin.ruangan.index') }}" class="text-xs text-primary hover:underline font-medium flex items-center gap-1">
                    Kelola Ruangan <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-72 w-full">
                <canvas id="chartRuangan"></canvas>
            </div>
        </div>
    </div>

    <!-- Secondary Row: Chart Kategori & Two Tables (Item List & Asset List) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart 3: Distribusi Alat Berdasarkan Kategori -->
        <div class="lg:col-span-5 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-collection-fill text-primary"></i> Kategori Alat Praktikum
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Jumlah varian alat terdaftar di setiap kategori</p>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>

        <!-- Tabel: Asset List (Peralatan Perlu Pemeliharaan / Perhatian) -->
        <div class="lg:col-span-7 bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-amber-500"></i> Asset List: Perlu Pemeliharaan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Alat dengan riwayat rusak ringan, rusak berat, atau hilang</p>
                </div>
                <a href="{{ route('admin.barang.index') }}" class="text-xs text-primary hover:underline font-medium">
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                    <thead class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-2.5 px-3 rounded-l-lg">Asset Name</th>
                            <th class="py-2.5 px-3 text-center">Image</th>
                            <th class="py-2.5 px-3">Store / Ruangan</th>
                            <th class="py-2.5 px-3 text-right rounded-r-lg">Status Rusak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($itemsAttention as $index => $item)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-3">
                                    <a href="{{ route('admin.barang.show', $item->id) }}" class="font-semibold text-slate-800 dark:text-white hover:text-primary dark:hover:text-primary transition-colors">
                                        {{ $item->nama_barang }}
                                    </a>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $item->kode_barang }}@if($item->merk) • {{ $item->merk }}@endif
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($item->foto_barang)
                                        <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="{{ $item->nama_barang }}" class="w-9 h-9 object-cover rounded-lg mx-auto shadow-sm border border-slate-200 dark:border-slate-700" />
                                    @else
                                        @php
                                            $gradClasses = [
                                                'bg-gradient-to-br from-amber-500 to-orange-600',
                                                'bg-gradient-to-br from-rose-500 to-red-600',
                                                'bg-gradient-to-br from-amber-400 to-yellow-500',
                                                'bg-gradient-to-br from-purple-500 to-indigo-600',
                                                'bg-gradient-to-br from-sky-500 to-blue-600'
                                            ];
                                            $grad = $gradClasses[$index % count($gradClasses)];
                                        @endphp
                                        <div class="w-9 h-9 rounded-lg {{ $grad }} flex items-center justify-center text-white mx-auto shadow-sm">
                                            <i class="bi bi-tools text-sm"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300 font-medium">
                                        <i class="bi bi-geo-alt text-slate-400 text-[10px]"></i>
                                        {{ $item->ruangan?->nama_ruangan ?? 'Belum dialokasikan' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="space-y-0.5">
                                        @if($item->stok_rusak_ringan > 0)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                                {{ $item->stok_rusak_ringan }} R. Ringan
                                            </span>
                                        @endif
                                        @if($item->stok_rusak_berat > 0)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                                {{ $item->stok_rusak_berat }} R. Berat
                                            </span>
                                        @endif
                                        @if($item->stok_hilang > 0)
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                {{ $item->stok_hilang }} Hilang
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    <i class="bi bi-check-circle-fill text-emerald-500 text-2xl block mb-1"></i>
                                    Seluruh peralatan laboratorium dalam kondisi prima (0 rusak).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Item List (Inventaris Terbaru) -->
    <div class="bg-white dark:bg-[#171d25] border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
            <div>
                <h2 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="bi bi-box text-primary"></i> Item List: Inventaris Laboratorium
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar item alat terbaru yang terdaftar dalam sistem inventaris</p>
            </div>
            <a href="{{ route('admin.barang.index') }}" class="text-xs text-primary hover:underline font-medium">
                View All Items <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                <thead class="text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="py-2.5 px-4 rounded-l-lg">Item Name</th>
                        <th class="py-2.5 px-4 text-center">Image</th>
                        <th class="py-2.5 px-4">Kategori</th>
                        <th class="py-2.5 px-4">Store / Ruangan</th>
                        <th class="py-2.5 px-4 text-center">Kondisi Baik</th>
                        <th class="py-2.5 px-4 text-right rounded-r-lg">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($recentItems as $index => $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.barang.show', $item->id) }}" class="font-semibold text-slate-800 dark:text-white hover:text-primary dark:hover:text-primary transition-colors">
                                    {{ $item->nama_barang }}
                                </a>
                                <div class="text-[11px] text-slate-400">
                                    {{ $item->kode_barang }}@if($item->merk) • {{ $item->merk }}@endif
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->foto_barang)
                                    <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="{{ $item->nama_barang }}" class="w-9 h-9 object-cover rounded-lg mx-auto shadow-sm border border-slate-200 dark:border-slate-700" />
                                @else
                                    @php
                                        $thumbClasses = [
                                            'bg-gradient-to-br from-blue-500 to-cyan-500',
                                            'bg-gradient-to-br from-emerald-500 to-teal-500',
                                            'bg-gradient-to-br from-purple-500 to-indigo-500',
                                            'bg-gradient-to-br from-amber-500 to-orange-500',
                                            'bg-gradient-to-br from-rose-500 to-pink-500'
                                        ];
                                        $tbColor = $thumbClasses[$index % count($thumbClasses)];
                                    @endphp
                                    <div class="w-9 h-9 rounded-lg {{ $tbColor }} flex items-center justify-center text-white mx-auto shadow-sm">
                                        <i class="bi bi-box-seam text-sm"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->kategoriBarang?->nama_kategori)
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $item->kategoriBarang->nama_kategori }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Belum dikategorikan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->ruangan?->nama_ruangan)
                                    <span class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300 font-medium">
                                        <i class="bi bi-geo-alt text-slate-400 text-[10px]"></i>
                                        {{ $item->ruangan->nama_ruangan }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Belum dialokasikan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $item->stok_baik }} unit</span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->total_stok }} unit</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada data barang inventaris terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js gagal dimuat dari layout CDN.');
        return;
    }

    // Chart.js default styling
    Chart.defaults.font.family = "'Inter', 'Lexend', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Chart Kondisi (Doughnut Chart)
    const ctxKondisi = document.getElementById('chartKondisi');
    if (ctxKondisi) {
        new Chart(ctxKondisi, {
            type: 'doughnut',
            data: {
                labels: @json($chartKondisi['labels']),
                datasets: [{
                    data: @json($chartKondisi['data']),
                    backgroundColor: [
                        '#10B981', // Baik (Emerald)
                        '#F59E0B', // Rusak Ringan (Amber)
                        '#EF4444', // Rusak Berat (Rose)
                        '#94A3B8'  // Hilang (Slate)
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
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.raw || 0;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} unit (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Chart Ruangan (Grouped Bar Chart)
    const ctxRuangan = document.getElementById('chartRuangan');
    if (ctxRuangan) {
        new Chart(ctxRuangan, {
            type: 'bar',
            data: {
                labels: @json($chartRuangan['labels']),
                datasets: [
                    {
                        label: 'Kondisi Baik',
                        data: @json($chartRuangan['dataBaik']),
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                        maxBarThickness: 28
                    },
                    {
                        label: 'Perlu Perhatian / Rusak',
                        data: @json($chartRuangan['dataRusak']),
                        backgroundColor: '#F59E0B',
                        borderRadius: 6,
                        maxBarThickness: 28
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
                        ticks: { stepSize: 10, font: { size: 11 }, color: '#64748b' }
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

    // 3. Chart Kategori (Horizontal Bar Chart)
    const ctxKategori = document.getElementById('chartKategori');
    if (ctxKategori) {
        new Chart(ctxKategori, {
            type: 'bar',
            data: {
                labels: @json($chartKategori['labels']),
                datasets: [{
                    label: 'Jumlah Jenis Alat',
                    data: @json($chartKategori['data']),
                    backgroundColor: [
                        '#0284C7',
                        '#0D9488',
                        '#6366F1',
                        '#8B5CF6',
                        '#EC4899',
                        '#F59E0B'
                    ],
                    borderRadius: 6,
                    maxBarThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 1, font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 11 },
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} jenis alat`;
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
