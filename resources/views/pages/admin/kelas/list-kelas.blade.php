@extends('layouts.app')

@section('title', 'Manajemen Kelas Praktikum')

@section('content')
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 bg-slate-50/50 dark:bg-zinc-900">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white m-0">Manajemen Kelas Praktikum</h2>
            <p class="text-sm text-slate-500 mt-1">Atur pendaftaran kelas (buka/tutup) untuk mahasiswa.</p>
        </div>
        {{-- Toggle tutup pilih kelas mahasiswa --}}
        <div class="px-6 py-4 bg-white dark:bg-zinc-900 border-t border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 m-0">Pemilihan Kelas Mahasiswa</h4>
                <p class="text-xs text-slate-500 m-0">Buka atau tutup akses pendaftaran kelas untuk mahasiswa pada semester aktif ini.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                <input type="checkbox" id="toggleGlobalEnrollment" class="sr-only peer" {{ ($activeSemester && $activeSemester->is_enrollment_open) ? 'checked' : '' }}>
                <div class="w-11 h-6 shrink-0 bg-slate-300 rounded-full peer-checked:bg-emerald-600 transition-colors shadow-inner"></div>
                <div class="absolute left-[2px] top-[2px] w-5 h-5 shrink-0 bg-white border border-slate-200 rounded-full transition-transform peer-checked:translate-x-2.5 shadow-sm"></div>
            </label>
        </div>

    </div>

    <section class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 dark:bg-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white m-0">Daftar Kelas Praktikum</h3>
            <div class="flex items-center">
                {{-- Toggle Master (akan dipindah jika aktif kembali) --}}
                <div class="flex items-center gap-2">
                    <button onclick="toggleModalAddKelas(true)" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-emerald-700 transition-colors">
                        <i class="bi bi-plus-lg mr-1"></i> Tambah Kelas
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Data Table -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-slate-600 dark:text-slate-300 whitespace-nowrap" id="kelasTable">
                    <thead class="text-xs text-slate-700 dark:text-slate-200 uppercase bg-slate-50/80 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Nama Kelas</th>
                            <th class="px-4 py-3 font-semibold">Semester</th>
                            <th class="px-4 py-3 font-semibold">Dosen</th>
                            <th class="px-4 py-3 font-semibold">Laboran</th>
                            <th class="px-4 py-3 font-semibold">Kapasitas</th>
                            <th class="px-4 py-3 font-semibold text-center">Status Pendaftaran</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Toggle Semua -->
    <div id="modal-confirm"
        class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm border border-slate-100 overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Konfirmasi</h3>
                <button onclick="toggleModalConfirm(false)" class="text-slate-400 hover:text-slate-600"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-4">
                <p id="modal-confirm-msg" class="text-slate-600 text-sm">Apakah Anda yakin ingin membuka SEMUA kelas
                    praktikum sekaligus?</p>
            </div>
            <div class="flex justify-end gap-2 p-4 pt-0">
                <button type="button" onclick="cancelToggleAll()"
                    class="px-4 py-2 text-sm border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="button" id="btn-confirm-yes"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Ya,
                    Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div id="modal-add-kelas" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200" id="modalAddKelasContent">
            <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Tambah Kelas Praktikum</h3>
                <button type="button" onclick="toggleModalAddKelas(false)" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="formAddKelas">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-1">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama_kelas" name="nama_kelas" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: Fisika Dasar A" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="semester_id" class="block text-sm font-medium text-slate-700 mb-1">Semester <span class="text-rose-500">*</span></label>
                            <select id="semester_id" name="semester_id" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                <option value="" disabled selected>Pilih Semester</option>
                                @foreach($semesters as $smt)
                                    <option value="{{ $smt->id }}">{{ $smt->nama_semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="kapasitas" class="block text-sm font-medium text-slate-700 mb-1">Kapasitas Maks <span class="text-rose-500">*</span></label>
                            <input type="number" id="kapasitas" name="kapasitas" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Maks mahasiswa" min="1" required>
                        </div>
                    </div>
                    <div>
                        <label for="dosen_id" class="block text-sm font-medium text-slate-700 mb-1">Dosen Pengampu <span class="text-rose-500">*</span></label>
                        <select id="dosen_id" name="dosen_id" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                            <option value="" disabled selected>Pilih Dosen</option>
                            @foreach($dosens as $dsn)
                                <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="laboran_id" class="block text-sm font-medium text-slate-700 mb-1">Laboran <span class="text-rose-500">*</span></label>
                        <select id="laboran_id" name="laboran_id" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                            <option value="" disabled selected>Pilih Laboran</option>
                            @foreach($laborans as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="border-t border-slate-100 pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-slate-800 mb-3">Jadwal & Ruangan Rutin</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="ruangan_id" class="block text-sm font-medium text-slate-700 mb-1">Ruangan Lab <span class="text-rose-500">*</span></label>
                                <select id="ruangan_id" name="ruangan_id" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                    <option value="" disabled selected>Pilih Ruangan</option>
                                    @foreach($ruangans as $ruangan)
                                        <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hari" class="block text-sm font-medium text-slate-700 mb-1">Hari <span class="text-rose-500">*</span></label>
                                <select id="hari" name="hari" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                    <option value="" disabled selected>Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                                <input type="time" id="jam_mulai" name="jam_mulai" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                                <input type="time" id="jam_selesai" name="jam_selesai" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-5 border-t border-slate-100 bg-slate-50/50">
                    <button type="button" onclick="toggleModalAddKelas(false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" id="btnSubmitAddKelas" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors flex items-center justify-center">
                        <span id="btnSubmitText">Simpan Kelas</span>
                        <div id="btnSubmitLoader" class="hidden ml-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.tailwindcss.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.tailwindcss.js"></script>
    <script>
        $(document).ready(function() {
            // Setup DataTables
            const table = $('#kelasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.kelas.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '5%'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas'
                    },
                    {
                        data: 'semester_nama',
                        name: 'semester_nama'
                    },
                    {
                        data: 'dosen_nama',
                        name: 'dosen_nama'
                    },
                    {
                        data: 'laboran_nama',
                        name: 'laboran_nama'
                    },
                    {
                        data: 'kapasitas_info',
                        name: 'kapasitas_info',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%',
                        className: 'text-center'
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                }
            });

            // Toggle Status via AJAX
            $('#kelasTable').on('change', '.toggle-status', function() {
                const checkbox = $(this);
                const kelasId = checkbox.data('id');
                const isChecked = checkbox.is(':checked');

                // Nonaktifkan sementara agar user tidak double klik
                checkbox.prop('disabled', true);

                $.ajax({
                    url: `/admin/kelas/${kelasId}/toggle-status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.showToast(response.message);
                        }
                    },
                    error: function(xhr) {
                        window.showToast('Terjadi kesalahan saat mengubah status.');
                        // Kembalikan ke posisi awal jika gagal
                        checkbox.prop('checked', !isChecked);
                    },
                    complete: function() {
                        // Aktifkan kembali checkbox
                        checkbox.prop('disabled', false);
                    }
                });
            });

            // Helper Modal
            window.toggleModalConfirm = function(show) {
                document.getElementById('modal-confirm').classList.toggle('hidden', !show);
            };

            let currentToggleIsChecked = false;

            window.cancelToggleAll = function() {
                toggleModalConfirm(false);
                // Revert the switch if user cancels
                $('#toggleAllSwitch').prop('checked', !currentToggleIsChecked);
            };

            // Toggle Semua Kelas (Master Switch)
            $('#toggleAllSwitch').on('change', function(e) {
                currentToggleIsChecked = $(this).is(':checked');
                const status = currentToggleIsChecked ? 'open' : 'closed';
                const label = currentToggleIsChecked ? 'membuka' : 'menutup';

                $('#modal-confirm-msg').text(
                    `Apakah Anda yakin ingin ${label} SEMUA kelas praktikum sekaligus?`);
                toggleModalConfirm(true);

                // Unbind previous click events to avoid multiple executions
                $('#btn-confirm-yes').off('click').on('click', function() {
                    toggleModalConfirm(false);
                    executeToggleAll(status, currentToggleIsChecked);
                });
            });

            function executeToggleAll(status, isChecked) {
                const switchEl = $('#toggleAllSwitch');
                switchEl.prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.kelas.toggle-all') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            window.showToast(response.message);
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        window.showToast('Terjadi kesalahan saat mengubah status semua kelas.');
                        switchEl.prop('checked', !isChecked);
                    },
                    complete: function() {
                        switchEl.prop('disabled', false);
                    }
                });
            }

            // Modal Tambah Kelas
            window.toggleModalAddKelas = function(show) {
                const modal = document.getElementById('modal-add-kelas');
                const content = document.getElementById('modalAddKelasContent');
                
                if (show) {
                    modal.classList.remove('hidden');
                    // Reset form
                    $('#formAddKelas')[0].reset();
                    // Animasi masuk
                    setTimeout(() => {
                        content.classList.remove('scale-95');
                        content.classList.add('scale-100');
                    }, 10);
                } else {
                    // Animasi keluar
                    content.classList.remove('scale-100');
                    content.classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 200);
                }
            };

            // Handle Submit Tambah Kelas via AJAX
            $('#formAddKelas').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const btnSubmit = $('#btnSubmitAddKelas');
                const textSubmit = $('#btnSubmitText');
                const loaderSubmit = $('#btnSubmitLoader');
                
                // Set Loading State
                btnSubmit.prop('disabled', true);
                textSubmit.text('Menyimpan...');
                loaderSubmit.removeClass('hidden');
                
                $.ajax({
                    url: "{{ route('admin.kelas.store') }}",
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.toggleModalAddKelas(false);
                            window.showToast(response.message);
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Extract first error message
                            const errors = xhr.responseJSON.errors;
                            errorMsg = errors[Object.keys(errors)[0]][0];
                        }
                        window.showToast(errorMsg);
                    },
                    complete: function() {
                        // Reset Loading State
                        btnSubmit.prop('disabled', false);
                        textSubmit.text('Simpan Kelas');
                        loaderSubmit.addClass('hidden');
                    }
                });
            });

            // Global Enrollment Toggle
            $('#toggleGlobalEnrollment').on('change', function() {
                const isChecked = $(this).is(':checked');
                
                $.ajax({
                    url: "{{ route('admin.kelas.toggle-enrollment') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: isChecked
                    },
                    success: function(res) {
                        if(res.success) {
                            showToast(res.message, 'success');
                        } else {
                            showToast(res.message, 'error');
                            $('#toggleGlobalEnrollment').prop('checked', !isChecked);
                        }
                    },
                    error: function(err) {
                        showToast('Terjadi kesalahan server.', 'error');
                        $('#toggleGlobalEnrollment').prop('checked', !isChecked);
                    }
                });
            });
        });
    </script>
@endpush
