@extends('layouts.app')

@section('title', 'Detail Ruangan: ' . $ruangan->nama_ruangan . ' - Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <button onclick="window.location.href='{{ route('admin.ruangan.index') }}'" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <i class="bi bi-arrow-left text-xl"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">{{ $ruangan->nama_ruangan }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">{{ $ruangan->deskripsi ?? 'Detail ruangan laboratorium.' }}</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-2 mb-6 border-b border-slate-200 dark:border-[#344150]">
        <button onclick="switchTab('tab-kelas')" id="btn-tab-kelas" class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-ink dark:hover:text-white transition-colors -mb-px">
            <i class="bi bi-mortarboard mr-1"></i> Kelas Praktikum
        </button>
        <button onclick="switchTab('tab-barang')" id="btn-tab-barang" class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-ink dark:hover:text-white transition-colors -mb-px">
            <i class="bi bi-tools mr-1"></i> Barang Ruangan
        </button>
    </div>

    <!-- ═══ Card 1: Kelas Praktikum (Read-Only) ═══ -->
    <div id="tab-kelas" class="tab-content hidden">
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
            <h3 class="text-lg font-semibold text-ink dark:text-white mb-4">Kelas Praktikum di Ruangan Ini</h3>
            <div class="overflow-x-auto">
                <table id="table-kelas" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">No</th>
                            <th class="px-4 py-3">Nama Kelas</th>
                            <th class="px-4 py-3">Dosen</th>
                            <th class="px-4 py-3">Laboran</th>
                            <th class="px-4 py-3">Semester</th>
                            <th class="px-4 py-3 rounded-tr-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══ Card 2: Barang Ruangan (CRUD + Import) ═══ -->
    <div id="tab-barang" class="tab-content hidden">
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-ink dark:text-white">Barang di Ruangan Ini</h3>
                <div class="flex gap-2">
                    <button onclick="toggleModal('modal-import', true)" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                        <i class="bi bi-file-earmark-excel mr-1"></i> Import Excel
                    </button>
                    <button onclick="openAddBarangModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                        + Tambah Barang
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="table-barang" class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">No</th>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Merk</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3 rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal Tambah/Edit Barang ═══ -->
<div id="modal-barang" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-lg border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-slate-100 dark:border-[#344150]">
            <h3 id="modal-barang-title" class="text-lg font-semibold text-ink dark:text-white">Tambah Barang</h3>
            <button onclick="toggleModal('modal-barang', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-barang" class="p-4 space-y-4">
            <input type="hidden" id="barang-id" value="">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Kode Barang</label>
                    <input type="text" id="inp-kode" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Nama Barang</label>
                    <input type="text" id="inp-nama" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Merk</label>
                    <input type="text" id="inp-merk" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Kategori</label>
                    <select id="inp-kategori" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">-- Pilih --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-3">
                <div id="wrapper-baik">
                    <label id="label-baik" class="block text-xs font-medium text-ink dark:text-slate-300 mb-1">Stok Baik</label>
                    <input type="number" id="inp-baik" value="0" min="0" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary" required>
                </div>
                <div id="wrapper-ringan">
                    <label class="block text-xs font-medium text-ink dark:text-slate-300 mb-1">R. Ringan</label>
                    <input type="number" id="inp-ringan" value="0" min="0" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                </div>
                <div id="wrapper-berat">
                    <label class="block text-xs font-medium text-ink dark:text-slate-300 mb-1">R. Berat</label>
                    <input type="number" id="inp-berat" value="0" min="0" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                </div>
                <div id="wrapper-hilang">
                    <label class="block text-xs font-medium text-ink dark:text-slate-300 mb-1">Hilang</label>
                    <input type="number" id="inp-hilang" value="0" min="0" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Keterangan</label>
                <textarea id="inp-keterangan" rows="2" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="toggleModal('modal-barang', false)" class="px-4 py-2 text-sm border border-slate-200 dark:border-[#344150] rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#29323e]">Batal</button>
                <button type="submit" id="btn-submit-barang" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══ Modal Import Excel ═══ -->
<div id="modal-import" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-md border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-slate-100 dark:border-[#344150]">
            <h3 class="text-lg font-semibold text-ink dark:text-white">Import Barang dari Excel</h3>
            <button onclick="toggleModal('modal-import', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-import" class="p-4 space-y-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Upload file <code>.xlsx</code> dengan kolom header: <strong>kode_barang, nama_barang, merk, kategori_id, stok_baik, stok_rusak_ringan, stok_rusak_berat, stok_hilang, keterangan</strong><a href="" class="text-primary dark:text-blue-400 underline"> Download Template Excel</a></p>
                <input type="file" id="inp-file" accept=".xlsx,.xls" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-import', false)" class="px-4 py-2 text-sm border border-slate-200 dark:border-[#344150] rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#29323e]">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">Import</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.tailwindcss.js"></script>

    <script>
        const ruanganId = {{ $ruangan->id }};
        const csrfToken = '{{ csrf_token() }}';

        // ─── Tab Switching ──────────────────────────────────────
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-green-600', 'text-green-600', 'dark:text-green-500');
                el.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400');
            });
            document.getElementById(tabId).classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabId);
            btn.classList.add('border-green-600', 'text-green-600', 'dark:text-green-500');
            btn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400');
        }
        // Default: open tab kelas
        switchTab('tab-kelas');

        // ─── Modal Toggle ───────────────────────────────────────
        function toggleModal(id, show) {
            document.getElementById(id).classList.toggle('hidden', !show);
        }

        // ─── DataTables: Kelas ──────────────────────────────────
        $(document).ready(function() {
            $('#table-kelas').DataTable({
                processing: true,
                serverSide: true,
                ajax: `/admin/ruangan/${ruanganId}/kelas-data`,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_kelas', name: 'nama_kelas' },
                    { data: 'dosen_name', name: 'dosen_name', orderable: false, searchable: false },
                    { data: 'laboran_name', name: 'laboran_name', orderable: false, searchable: false },
                    { data: 'semester_name', name: 'semester_name', orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                ],
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
            });

            // ─── DataTables: Barang ─────────────────────────────
            var tableBarang = $('#table-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: `/admin/ruangan/${ruanganId}/barang-data`,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kode_barang', name: 'kode_barang' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'merk', name: 'merk' },
                    { data: 'kategori', name: 'kategori', orderable: false, searchable: false },
                    { data: 'total_stok', name: 'total_stok', orderable: false, searchable: false },
                    { data: 'kondisi', name: 'kondisi', orderable: false, searchable: false },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="flex gap-2">
                                    <button onclick='openEditBarangModal(${JSON.stringify(row)})' class="p-1.5 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400"><i class="bi bi-pencil-square"></i></button>
                                </div>
                            `;
                        }
                    }
                ],
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
            });

            // ─── Form Submit: Tambah / Edit Barang ──────────────
            $('#form-barang').on('submit', function(e) {
                e.preventDefault();
                const barangId = $('#barang-id').val();
                const isEdit = barangId !== '';
                const url = isEdit
                    ? `/admin/ruangan/${ruanganId}/barang/${barangId}`
                    : `/admin/ruangan/${ruanganId}/barang`;

                $.ajax({
                    url: url,
                    type: isEdit ? 'PUT' : 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: {
                        kode_barang: $('#inp-kode').val(),
                        nama_barang: $('#inp-nama').val(),
                        merk: $('#inp-merk').val(),
                        kategori_id: $('#inp-kategori').val() || null,
                        stok_baik: $('#inp-baik').val(),
                        stok_rusak_ringan: $('#inp-ringan').val(),
                        stok_rusak_berat: $('#inp-berat').val(),
                        stok_hilang: $('#inp-hilang').val(),
                        keterangan: $('#inp-keterangan').val(),
                    },
                    success: function(res) {
                        toggleModal('modal-barang', false);
                        tableBarang.ajax.reload();
                        window.showToast(res.message);
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            window.showToast(Object.values(errors).flat().join('\n'));
                        } else {
                            window.showToast('Terjadi kesalahan.');
                        }
                    }
                });
            });

            // ─── Form Submit: Import Excel ──────────────────────
            $('#form-import').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData();
                formData.append('file', $('#inp-file')[0].files[0]);

                $.ajax({
                    url: `/admin/ruangan/${ruanganId}/import`,
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toggleModal('modal-import', false);
                        tableBarang.ajax.reload();
                        window.showToast(res.message);
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            window.showToast(Object.values(errors).flat().join('\n'));
                        } else {
                            window.showToast('Gagal mengimpor file. Pastikan format kolom benar.');
                        }
                    }
                });
            });
        });

        // ─── Modal Helpers ──────────────────────────────────────
        function openAddBarangModal() {
            $('#modal-barang-title').text('Tambah Barang Baru');
            $('#barang-id').val('');
            $('#inp-kode').val('');
            $('#inp-nama').val('');
            $('#inp-merk').val('');
            $('#inp-kategori').val('');
            $('#inp-baik').val(0);
            $('#inp-ringan').val(0);
            $('#inp-berat').val(0);
            $('#inp-hilang').val(0);
            $('#inp-keterangan').val('');
            
            // Sembunyikan field kondisi saat tambah barang
            $('#wrapper-ringan, #wrapper-berat, #wrapper-hilang').addClass('hidden');
            $('#wrapper-baik').addClass('col-span-4');
            $('#label-baik').text('Jumlah Barang Masuk (Kondisi Baik)');

            toggleModal('modal-barang', true);
        }

        function openEditBarangModal(row) {
            $('#modal-barang-title').text('Edit Kondisi Barang');
            $('#barang-id').val(row.id);
            $('#inp-kode').val(row.kode_barang);
            $('#inp-nama').val(row.nama_barang);
            $('#inp-merk').val(row.merk);
            $('#inp-kategori').val(row.kategori_id);
            $('#inp-baik').val(row.stok_baik);
            $('#inp-ringan').val(row.stok_rusak_ringan);
            $('#inp-berat').val(row.stok_rusak_berat);
            $('#inp-hilang').val(row.stok_hilang);
            $('#inp-keterangan').val(row.keterangan);

            // Tampilkan kembali semua field kondisi saat edit barang
            $('#wrapper-ringan, #wrapper-berat, #wrapper-hilang').removeClass('hidden');
            $('#wrapper-baik').removeClass('col-span-4');
            $('#label-baik').text('Stok Baik');

            toggleModal('modal-barang', true);
        }
    </script>
@endpush
