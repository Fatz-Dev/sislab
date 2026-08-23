@extends('layouts.app')

@section('title', 'Manajemen Ruang Lab - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-ink dark:text-white">Ruang Lab</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Daftar seluruh ruangan laboratorium yang
                    tersedia.</p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="table-ruangan" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">No</th>
                            <th class="px-4 py-3">Nama Ruangan</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Total Barang</th>
                            <th class="px-4 py-3 rounded-tr-lg">Aksi</th>
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
        $(document).ready(function () {
            $('#table-ruangan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.ruangan.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_ruangan', name: 'nama_ruangan' },
                    { data: 'deskripsi', name: 'deskripsi', orderable: false },
                    { data: 'total_barang', name: 'total_barang', orderable: false, searchable: false },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                    <button onclick="window.location.href='/admin/ruangan/${row.id}'" class="p-1.5 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                `;
                        }
                    }
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
        });
    </script>
@endpush