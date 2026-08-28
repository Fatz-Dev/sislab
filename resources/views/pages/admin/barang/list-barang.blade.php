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
        <div class="flex gap-2">
            <button onclick="exportExcel()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <i class="bi bi-download mr-1"></i> Export Excel
            </button>
            <button onclick="toggleModal('modal-import-global', true)" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                <i class="bi bi-file-earmark-excel mr-1"></i> Import Excel
            </button>
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

<!-- ═══ Modal Import Excel Global ═══ -->
<div id="modal-import-global" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-lg w-full max-w-md border border-slate-100 dark:border-[#344150] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-slate-100 dark:border-[#344150]">
            <h3 class="text-lg font-semibold text-ink dark:text-white">Import Barang dari Excel</h3>
            <button onclick="toggleModal('modal-import-global', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-import-global" class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-ink dark:text-slate-300 mb-1">Pilih Ruangan</label>
                <select id="inp-ruangan-import" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:border-primary" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($ruangans as $ruangan)
                        <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Upload file <code>.xlsx</code> dengan kolom header: <strong>kode_barang, nama_barang, merk, kategori_id, stok_baik, stok_rusak_ringan, stok_rusak_berat, stok_hilang, keterangan</strong><a href="{{ route('admin.barang.template-import') }}" class="text-primary dark:text-blue-400 underline ml-1">Download Template Excel</a></p>
                <input type="file" id="inp-file-import" accept=".xlsx,.xls" class="w-full text-sm bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] text-ink dark:text-white rounded-lg px-3 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-import-global', false)" class="px-4 py-2 text-sm border border-slate-200 dark:border-[#344150] rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#29323e]">Batal</button>
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

            // ─── Form Submit: Import Excel Global ───────────────
            $('#form-import-global').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData();
                formData.append('ruangan_id', $('#inp-ruangan-import').val());
                formData.append('file', $('#inp-file-import')[0].files[0]);

                $.ajax({
                    url: "{{ route('admin.barang.import') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toggleModal('modal-import-global', false);
                        $('#form-import-global')[0].reset();
                        table.ajax.reload();
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
        function toggleModal(id, show) {
            document.getElementById(id).classList.toggle('hidden', !show);
        }

        // ─── Export Excel Helper ─────────────────────────────────
        function exportExcel() {
            const ruanganId = $('#filter-ruangan').val();
            const kategoriId = $('#filter-kategori').val();
            const url = `{{ route('admin.barang.export') }}?ruangan_id=${ruanganId}&kategori_id=${kategoriId}`;
            window.location.href = url;
        }
    </script>
@endpush
