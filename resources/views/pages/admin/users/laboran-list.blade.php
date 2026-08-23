@extends('layouts.app')

@section('title', 'Manajemen Laboran - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">Data Laboran</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Daftar akun pengelola laboratorium (Laboran).</p>
        </div>
        <button onclick="toggleModal('modal-add', true)" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
            + Tambah Laboran
        </button>
    </div>

    <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="table-laboran" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">No</th>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">NIP / ID</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Laboran -->
<div id="modal-add" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-md border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-slate-100 dark:border-[#344150]">
            <h3 class="text-lg font-semibold text-ink dark:text-white">Tambah Laboran</h3>
            <button onclick="toggleModal('modal-add', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form action="#" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">NIP / ID <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="nip" class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jabatan <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="jabatan" class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-add', false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 dark:text-slate-300 dark:bg-[#29323e] dark:hover:bg-[#344150]">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Laboran -->
<div id="modal-edit" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-md border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-slate-100 dark:border-[#344150]">
            <h3 class="text-lg font-semibold text-ink dark:text-white">Edit Laboran</h3>
            <button onclick="toggleModal('modal-edit', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="form-edit" action="#" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">NIP / ID</label>
                <input type="text" name="nip" id="edit-nip" class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jabatan</label>
                <input type="text" name="jabatan" id="edit-jabatan" class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" id="edit-email" required class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Baru <span class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-slate-200 dark:border-[#344150] rounded-lg bg-transparent text-ink dark:text-white focus:outline-none focus:border-primary">
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-edit', false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 dark:text-slate-300 dark:bg-[#29323e] dark:hover:bg-[#344150]">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Laboran -->
<div id="modal-delete" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-sm border border-slate-100 dark:border-[#344150] overflow-hidden text-center p-6">
        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h3 class="text-lg font-bold text-ink dark:text-white mb-2">Hapus Laboran?</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Tindakan ini tidak dapat dibatalkan. Laboran yang dihapus akan kehilangan akses ke sistem.</p>
        <form id="form-delete" action="#" method="POST" class="flex justify-center gap-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="toggleModal('modal-delete', false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 dark:text-slate-300 dark:bg-[#29323e] dark:hover:bg-[#344150]">Batal</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Ya, Hapus</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.tailwindcss.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#table-laboran').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.laboran') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'nip', name: 'nip', orderable: false, searchable: false },
                    { data: 'email', name: 'email' },
                    { data: 'jabatan', name: 'jabatan', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Hindari kutip pada nama
                            const safeName = row.name ? row.name.replace(/'/g, "&#39;").replace(/"/g, "&quot;") : '';
                            const safeNip = row.nip ? row.nip.replace(/'/g, "&#39;").replace(/"/g, "&quot;") : '';
                            const safeJabatan = row.jabatan ? row.jabatan.replace(/'/g, "&#39;").replace(/"/g, "&quot;") : '';
                            
                            return `
                                <div class="flex gap-2">
                                    <button onclick="window.location.href='/admin/users/${row.id}/detail'" class="p-1.5 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400"><i class="bi bi-eye"></i></button>
                                    <button onclick="openEditModal(${row.id}, '${safeName}', '${safeNip}', '${safeJabatan}', '${row.email}')" class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400"><i class="bi bi-pencil-square"></i></button>
                                    <button onclick="openDeleteModal(${row.id})" class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400"><i class="bi bi-trash"></i></button>
                                </div>
                            `;
                        }
                    }
                ],
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
            });
        });

        // Toggle Modal Visibility
        function toggleModal(modalID, show) {
            const modal = document.getElementById(modalID);
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        // Open Edit Modal with Data
        function openEditModal(id, name, nip, jabatan, email) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-nip').value = nip;
            document.getElementById('edit-jabatan').value = jabatan;
            document.getElementById('edit-email').value = email;
            // document.getElementById('form-edit').action = '/admin/users/laboran/' + id;
            toggleModal('modal-edit', true);
        }

        // Open Delete Modal with Data
        function openDeleteModal(id) {
            // document.getElementById('form-delete').action = '/admin/users/laboran/' + id;
            toggleModal('modal-delete', true);
        }
    </script>
@endpush
