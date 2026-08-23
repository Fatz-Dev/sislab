@extends('layouts.app')

@section('title', 'Semua Barang Inventaris - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">Semua Barang Inventaris</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Daftar seluruh barang yang telah dialokasikan ke berbagai ruang lab.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-4 flex flex-wrap gap-4 items-center">
        <div>
            <label for="filter-ruangan" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Filter Ruangan</label>
            <select id="filter-ruangan" class="text-sm bg-white dark:bg-[#171d25] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                <option value="">Semua Ruangan</option>
                @foreach($ruangans as $ruangan)
                    <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter-kategori" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Filter Kategori</label>
            <select id="filter-kategori" class="text-sm bg-white dark:bg-[#171d25] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="table-barang" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">No</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Merk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Ruangan</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3 rounded-tr-lg">Kondisi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.tailwindcss.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#table-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.barang.index') }}",
                    data: function (d) {
                        d.ruangan_id = $('#filter-ruangan').val();
                        d.kategori_id = $('#filter-kategori').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kode_barang', name: 'kode_barang' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'merk', name: 'merk' },
                    { data: 'kategori', name: 'kategori', orderable: false, searchable: false },
                    { data: 'ruangan', name: 'ruangan', orderable: false, searchable: false },
                    { data: 'total_stok', name: 'total_stok', orderable: false, searchable: false },
                    { data: 'kondisi', name: 'kondisi', orderable: false, searchable: false },
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                }
            });

            $('#filter-ruangan, #filter-kategori').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
