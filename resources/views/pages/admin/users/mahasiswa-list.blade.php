@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">Data Mahasiswa</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Daftar akun mahasiswa yang terdaftar di sistem.</p>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="mb-4 flex flex-wrap gap-4 items-center">
        <div>
            <label for="filter-angkatan" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Filter Angkatan</label>
            <select id="filter-angkatan" class="text-sm bg-white dark:bg-[#171d25] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                <option value="">Semua Angkatan</option>
                <!-- Opsi bisa ditambah secara statis atau dinamis. Misalnya kita pasang statis untuk contoh -->
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
        <div>
            <label for="filter-status" class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Filter Status</label>
            <select id="filter-status" class="text-sm bg-white dark:bg-[#171d25] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="table-mahasiswa" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">No</th>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">NIM</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Angkatan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Diisi oleh DataTables -->
                </tbody>
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
            var table = $('#table-mahasiswa').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.users.mahasiswa') }}",
                    data: function (d) {
                        d.angkatan = $('#filter-angkatan').val();
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'nim', name: 'nim', orderable: false, searchable: false },
                    { data: 'email', name: 'email' },
                    { data: 'angkatan', name: 'angkatan', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="flex gap-2">
                                    <button onclick="window.location.href='/admin/users/${row.id}/detail'" class="p-1.5 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400"><i class="bi bi-eye"></i></button>
                                    <button class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400"><i class="bi bi-pencil-square"></i></button>
                                    <button class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400"><i class="bi bi-trash"></i></button>
                                </div>
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

            // Reload table on filter change
            $('#filter-angkatan, #filter-status').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
