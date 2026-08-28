@extends('layouts.app')

@section('title', 'Cetak Laporan Keseluruhan - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
<div class="p-6">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink dark:text-white">Cetak Laporan Keseluruhan</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
            Rekap nilai praktikum per semester & laporan akhir inventaris laboratorium.
        </p>
    </div>

    {{-- ─── Tab Toggle ─────────────────────────────────────── --}}
    <div class="flex gap-1 mb-6 bg-slate-100 dark:bg-[#1e2730] p-1 rounded-xl w-fit">
        <button id="tab-nilai-btn" onclick="switchTab('nilai')"
            class="tab-btn px-5 py-2 rounded-lg text-sm font-medium transition-all">
            <i class="bi bi-mortarboard mr-1"></i> Rekap Nilai
        </button>
        <button id="tab-inventaris-btn" onclick="switchTab('inventaris')"
            class="tab-btn px-5 py-2 rounded-lg text-sm font-medium transition-all">
            <i class="bi bi-boxes mr-1"></i> Inventaris Lab
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 1: Rekap Nilai
    ══════════════════════════════════════════════════════════ --}}
    <div id="tab-nilai" class="tab-content">
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6">
            <div class="flex flex-wrap gap-4 items-end justify-between mb-5">
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="filter-semester" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                            Semester
                        </label>
                        <select id="filter-semester"
                            class="text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary min-w-[200px]">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_semester }}{{ $s->is_active ? ' (Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button id="btn-cetak-nilai"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="bi bi-printer"></i> Cetak Laporan Nilai
                </button>
            </div>

            <div class="overflow-x-auto">
                <table id="table-nilai" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">No</th>
                            <th class="px-4 py-3">Kelas Praktikum</th>
                            <th class="px-4 py-3">Semester</th>
                            <th class="px-4 py-3">Dosen</th>
                            <th class="px-4 py-3">Laboran</th>
                            <th class="px-4 py-3">Jumlah Mahasiswa</th>
                            <th class="px-4 py-3 rounded-tr-lg">Jumlah Tugas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         TAB 2: Inventaris
    ══════════════════════════════════════════════════════════ --}}
    <div id="tab-inventaris" class="tab-content hidden">
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6">
            <div class="flex flex-wrap gap-4 items-end justify-between mb-5">
                <div>
                    <label for="filter-ruangan" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                        Filter Ruangan
                    </label>
                    <select id="filter-ruangan"
                        class="text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary min-w-[200px]">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangans as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="btn-cetak-inventaris"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="bi bi-printer"></i> Cetak Laporan Inventaris
                </button>
            </div>

            <div class="overflow-x-auto">
                <table id="table-inventaris" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">No</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Ruangan</th>
                            <th class="px-4 py-3">Stok Baik</th>
                            <th class="px-4 py-3">R.Ringan</th>
                            <th class="px-4 py-3">R.Berat</th>
                            <th class="px-4 py-3">Hilang</th>
                            <th class="px-4 py-3 rounded-tr-lg">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.tailwindcss.js"></script>

    <script>
        // ─── Tab Logic ────────────────────────────────────────────
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'dark:bg-[#29323e]', 'shadow', 'text-ink', 'dark:text-white');
                btn.classList.add('text-slate-500', 'dark:text-slate-400');
            });
            const active = document.getElementById('tab-' + tab + '-btn');
            active.classList.add('bg-white', 'dark:bg-[#29323e]', 'shadow', 'text-ink', 'dark:text-white');
            active.classList.remove('text-slate-500', 'dark:text-slate-400');
        }

        // ─── Init DataTable Nilai ─────────────────────────────────
        var tableNilai = $('#table-nilai').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.laporan.index") }}',
                data: function(d) {
                    d.tab        = 'nilai';
                    d.semester_id = $('#filter-semester').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_kelas',  name: 'nama_kelas' },
                { data: 'semester',    name: 'semester', orderable: false, searchable: false },
                { data: 'dosen',       name: 'dosen', orderable: false, searchable: false },
                { data: 'laboran',     name: 'laboran', orderable: false, searchable: false },
                { data: 'jml_mhs',     name: 'jml_mhs', orderable: false, searchable: false },
                { data: 'jml_tugas',   name: 'jml_tugas', orderable: false, searchable: false },
            ],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
        });

        $('#filter-semester').on('change', function() { tableNilai.ajax.reload(); });

        // ─── Init DataTable Inventaris ────────────────────────────
        var tableInv = $('#table-inventaris').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.laporan.index") }}',
                data: function(d) {
                    d.tab        = 'inventaris';
                    d.ruangan_id = $('#filter-ruangan').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex',       orderable: false, searchable: false },
                { data: 'kode_barang',        name: 'kode_barang' },
                { data: 'nama_barang',        name: 'nama_barang' },
                { data: 'kategori',           name: 'kategori', orderable: false, searchable: false },
                { data: 'ruangan',            name: 'ruangan',  orderable: false, searchable: false },
                { data: 'stok_baik',          name: 'stok_baik' },
                { data: 'stok_rusak_ringan',  name: 'stok_rusak_ringan' },
                { data: 'stok_rusak_berat',   name: 'stok_rusak_berat' },
                { data: 'stok_hilang',        name: 'stok_hilang' },
                { data: 'total_stok',         name: 'total_stok', orderable: false, searchable: false },
            ],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
        });

        $('#filter-ruangan').on('change', function() { tableInv.ajax.reload(); });

        // ─── Tombol Cetak ─────────────────────────────────────────
        $('#btn-cetak-nilai').on('click', function() {
            const semesterId = $('#filter-semester').val();
            const url = '{{ route("admin.laporan.cetak-nilai") }}' +
                        (semesterId ? '?semester_id=' + semesterId : '');
            window.open(url, '_blank');
        });

        $('#btn-cetak-inventaris').on('click', function() {
            const ruanganId = $('#filter-ruangan').val();
            const url = '{{ route("admin.laporan.cetak-inventaris") }}' +
                        (ruanganId ? '?ruangan_id=' + ruanganId : '');
            window.open(url, '_blank');
        });

        // ─── Init default tab ─────────────────────────────────────
        switchTab('nilai');
    </script>
@endpush
