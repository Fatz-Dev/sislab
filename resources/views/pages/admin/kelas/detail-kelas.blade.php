@extends('layouts.app')

@section('title', 'Detail Kelas Praktikum')

@section('content')
<div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 dark:bg-zinc-800 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white m-0" id="val_nama_kelas_title">Detail Kelas: {{ $kelas->nama_kelas }}</h3>
        <a href="{{ route('admin.kelas.index') }}" class="text-sm text-slate-600 dark:text-slate-300 hover:text-red-700">← Kembali</a>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Detail Kelas -->
            <div class="bg-slate-50/50 dark:bg-zinc-800 p-4 rounded-lg border border-slate-100">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-slate-800 dark:text-white text-sm mb-2 uppercase tracking-wider">Informasi Kelas</h4>
                    <a href="#" onclick="toggleModalEditKelas(true); return false;" class="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-800 rounded-full px-2 py-1 border border-red-600"><i class="bi bi-pencil" style="color: red;"></i> </a>
                </div>
                <div class="space-y-1">
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">ID Kelas</span>: <span
                            class="text-slate-800 dark:text-white font-mono">{{ $kelas->id }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Nama Kelas</span>: <span
                            class="text-slate-800 dark:text-white" id="val_nama_kelas">{{ $kelas->nama_kelas }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Semester</span>: <span
                            class="text-slate-800 dark:text-white" id="val_semester">{{ $kelas->semester->nama_semester ?? '' }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Ruangan Lab</span>: <span
                            class="text-slate-800 dark:text-white" id="val_ruangan_kelas">{{ $kelas->ruangan->nama_ruangan ?? '' }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Jadwal Rutin</span>: <span
                            class="text-slate-800 dark:text-white" id="val_jadwal_kelas">{{ $kelas->hari ? $kelas->hari . ', ' . \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : 'Belum diisi' }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Dibuat</span>: <span
                            class="text-slate-800 dark:text-white">{{ $kelas->created_at->format('d M Y H:i') }}</span></p>
                </div>
            </div>

            <!-- Dosen & Laboran -->
            <div class="bg-slate-50/50 dark:bg-zinc-800 p-4 rounded-lg border border-slate-100">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-slate-800 dark:text-white text-sm mb-2 uppercase tracking-wider">Tenaga Pendidik</h4>
                    <a href="#" onclick="toggleModalEditKelas(true); return false;" class="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-800 rounded-full px-2 py-1 border border-red-600"><i class="bi bi-pencil" style="color: red;"></i> </a>
                </div>
                <div class="space-y-1">
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Dosen Pengampu</span>: <span
                            class="text-slate-800 dark:text-white" id="val_dosen">{{ $kelas->dosen->name }}</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Laboran</span>: <span
                            class="text-slate-800 dark:text-white" id="val_laboran">{{ $kelas->laboran->name }}</span></p>
                </div>
            </div>

            <!-- Kapasitas & Status -->
            <div class="bg-slate-50/50 dark:bg-zinc-800 p-4 rounded-lg border border-slate-100">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-slate-800 dark:text-white text-sm mb-2 uppercase tracking-wider">Kapasitas & Status</h4>
                    <a href="#" onclick="toggleModalEditKelas(true); return false;" class="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-800 rounded-full px-2 py-1 border border-red-600"><i class="bi bi-pencil" style="color: red;"></i> </a>
                </div>
                <div class="space-y-1">
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Kapasitas Maks</span>: <span
                            class="text-slate-800 dark:text-white"><span id="val_kapasitas">{{ $kelas->kapasitas }}</span> mahasiswa</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Jumlah Pendaftar</span>: <span
                            class="text-slate-800 dark:text-white font-semibold text-blue-600">{{ $kelas->mahasiswas()->count() }}
                            mahasiswa</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Pendaftar Diterima</span>: <span
                            class="text-slate-800 dark:text-white font-semibold text-emerald-600">{{ $kelas->approvedCount() }}
                            mahasiswa</span></p>
                    <p><span class="font-medium text-slate-600 dark:text-slate-300 w-32 inline-block">Kuota Tersisa</span>: <span
                            class="text-slate-800 dark:text-white font-bold">{{ $kelas->availableSlots() }}
                            mahasiswa</span></p>
                </div>
            </div>

            <!-- Status Pendaftaran -->
            <div class="bg-slate-50/50 dark:bg-zinc-800 p-4 rounded-lg border border-slate-100">
                <h4 class="font-semibold text-slate-800 dark:text-white text-sm mb-2 uppercase tracking-wider">Status Pendaftaran</h4>
                @php $isOpen = $kelas->status === 'open'; @endphp
                <div
                    class="flex items-center gap-2 p-2 rounded-lg border {{ $isOpen ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }}">
                    <div
                        class="w-2 h-2 rounded-full {{ $isOpen ? 'bg-emerald-600' : 'bg-rose-600' }}">
                    </div>
                    <span
                        class="font-semibold {{ $isOpen ? 'text-emerald-800' : 'text-rose-800' }}">
                        {{ $isOpen ? 'BUKA' : 'TUTUP' }}
                    </span>
                    <span class="text-xs text-slate-500 ml-auto">
                        Terakhir diubah: {{ $kelas->updated_at->format('d M Y H:i') }}
                    </span>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200">
                    <form action="{{ route('admin.kelas.toggle-status', $kelas->id) }}" method="POST"
                        class="flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <button type="button"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                            {{ $isOpen
                                ? 'bg-rose-600 text-white hover:bg-rose-700' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}"
                            onclick="openConfirmModal(this.closest('form'), 'Konfirmasi Perubahan Status', '{{ $isOpen ? 'Apakah Anda yakin ingin MENUTUP pendaftaran kelas ini?' : 'Apakah Anda yakin ingin MEMBUKA pendaftaran kelas ini?' }}', '{{ $isOpen ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}', '{{ $isOpen ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }}')">
                            {{ $isOpen ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.kelas.delete', $kelas->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="openConfirmModal(this.form, 'Konfirmasi Hapus Kelas', 'Apakah Anda yakin ingin menghapus kelas ini?', 'Hapus Kelas', 'bg-rose-600 hover:bg-rose-700')" class="text-left bg-red-600 hover:bg-rose-700 px-8 py-2 rounded-lg transition-colors border border-red-600 text-sm font-medium text-white"><i class="bi bi-trash" style="color: white;"></i> Hapus kelas</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Pendaftar -->
        <div class="mt-8 border-t border-slate-200 pt-6">
            <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Daftar Pendaftar</h4>
            
            @if($kelas->mahasiswas->isEmpty())
                <div class="bg-slate-50 dark:bg-zinc-800 border border-slate-200 rounded-lg p-6 text-center text-slate-500">
                    Belum ada mahasiswa yang mendaftar di kelas ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-800 border-y border-slate-200 text-sm text-slate-600 uppercase tracking-wider">
                                <th class="py-3 px-4 font-semibold text-slate-800 dark:text-white">No</th>
                                <th class="py-3 px-4 font-semibold text-slate-800 dark:text-white">Nama Mahasiswa</th>
                                <th class="py-3 px-4 font-semibold text-slate-800 dark:text-white">NIM</th>
                                <th class="py-3 px-4 font-semibold text-slate-800 dark:text-white">Status</th>
                                <th class="py-3 px-4 font-semibold text-slate-800 dark:text-white">Tanggal Daftar</th>
                                <th class="py-3 px-4 font-semibold text-center text-slate-800 dark:text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($kelas->mahasiswas as $index => $mhs)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-300">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 text-slate-800 dark:text-white font-medium">{{ $mhs->name }}</td>
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-300">{{ $mhs->nip_nim ?? $mhs->mahasiswaProfile->nim ?? '' }}</td>
                                    <td class="py-3 px-4">
                                        @if($mhs->pivot->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Menunggu
                                            </span>
                                        @elseif($mhs->pivot->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Diterima
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 dark:text-slate-300 text-sm">
                                        {{ $mhs->pivot->created_at ? $mhs->pivot->created_at->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($mhs->pivot->status === 'pending')
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('admin.enrollments.approve', $mhs->pivot->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PATCH">
                                                    <button type="button" class="px-3 py-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-md text-xs font-semibold transition-colors" onclick="openConfirmModal(this.closest('form'), 'Persetujuan Pendaftar', 'Apakah Anda yakin ingin menyetujui mahasiswa ini untuk masuk ke kelas?', 'Setujui', 'bg-emerald-600 hover:bg-emerald-700')">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.enrollments.reject', $mhs->pivot->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PATCH">
                                                    <button type="button" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-md text-xs font-semibold transition-colors" onclick="openConfirmModal(this.closest('form'), 'Penolakan Pendaftar', 'Apakah Anda yakin ingin menolak mahasiswa ini?', 'Tolak', 'bg-rose-600 hover:bg-rose-700')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="customConfirmModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 overflow-hidden transform scale-95 transition-transform duration-200" id="confirmModalContent">
        <div class="p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-2" id="confirmModalTitle">Konfirmasi</h3>
            <p class="text-sm text-slate-600 mb-6" id="confirmModalText">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors" onclick="closeConfirmModal()">Batal</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors" id="confirmModalActionBtn">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modal-edit-kelas" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200" id="modalEditKelasContent">
        <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Edit Kelas Praktikum</h3>
            <button type="button" onclick="toggleModalEditKelas(false)" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="formEditKelas">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-1">Nama Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_kelas" name="nama_kelas" value="{{ $kelas->nama_kelas }}" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Contoh: Fisika Dasar A" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-slate-700 mb-1">Semester <span class="text-rose-500">*</span></label>
                        <select id="semester_id" name="semester_id" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                            <option value="" disabled>Pilih Semester</option>
                            @foreach($semesters as $smt)
                                <option value="{{ $smt->id }}" {{ $kelas->semester_id == $smt->id ? 'selected' : '' }}>{{ $smt->nama_semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-slate-700 mb-1">Kapasitas Maks <span class="text-rose-500">*</span></label>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ $kelas->kapasitas }}" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Maks mahasiswa" min="1" required>
                    </div>
                </div>
                <div>
                    <label for="dosen_id" class="block text-sm font-medium text-slate-700 mb-1">Dosen Pengampu <span class="text-rose-500">*</span></label>
                    <select id="dosen_id" name="dosen_id" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                        <option value="" disabled>Pilih Dosen</option>
                        @foreach($dosens as $dsn)
                            <option value="{{ $dsn->id }}" {{ $kelas->dosen_id == $dsn->id ? 'selected' : '' }}>{{ $dsn->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="laboran_id" class="block text-sm font-medium text-slate-700 mb-1">Laboran <span class="text-rose-500">*</span></label>
                    <select id="laboran_id" name="laboran_id" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                        <option value="" disabled>Pilih Laboran</option>
                        @foreach($laborans as $lab)
                            <option value="{{ $lab->id }}" {{ $kelas->laboran_id == $lab->id ? 'selected' : '' }}>{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Jadwal & Ruangan Rutin</h4>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="ruangan_id" class="block text-sm font-medium text-slate-700 mb-1">Ruangan Lab <span class="text-rose-500">*</span></label>
                            <select id="ruangan_id" name="ruangan_id" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                                <option value="" disabled>Pilih Ruangan</option>
                                @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}" {{ $kelas->ruangan_id == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="hari" class="block text-sm font-medium text-slate-700 mb-1">Hari <span class="text-rose-500">*</span></label>
                            <select id="hari" name="hari" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                                <option value="" disabled>Pilih Hari</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                    <option value="{{ $h }}" {{ $kelas->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-sm font-medium text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ $kelas->jam_mulai ? \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-sm font-medium text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" id="jam_selesai" name="jam_selesai" value="{{ $kelas->jam_selesai ? \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t border-slate-100 bg-slate-50/50">
                <button type="button" onclick="toggleModalEditKelas(false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" id="btnSubmitEditKelas" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors flex items-center justify-center">
                    <span id="btnSubmitEditText">Simpan Perubahan</span>
                    <div id="btnSubmitEditLoader" class="hidden ml-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let formToSubmit = null;

    function openConfirmModal(form, title, text, btnText, btnColorClasses) {
        formToSubmit = form;
        document.getElementById('confirmModalTitle').innerText = title;
        document.getElementById('confirmModalText').innerText = text;
        
        const actionBtn = document.getElementById('confirmModalActionBtn');
        actionBtn.innerText = btnText;
        actionBtn.className = `px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors ${btnColorClasses}`;
        
        const modal = document.getElementById('customConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Animasi masuk
        setTimeout(() => {
            document.getElementById('confirmModalContent').classList.remove('scale-95');
            document.getElementById('confirmModalContent').classList.add('scale-100');
        }, 10);
    }

    function closeConfirmModal() {
        formToSubmit = null;
        const modal = document.getElementById('customConfirmModal');
        const content = document.getElementById('confirmModalContent');
        
        // Animasi keluar
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    document.getElementById('confirmModalActionBtn').addEventListener('click', function() {
        if(formToSubmit) {
            formToSubmit.submit();
        }
    });

    // Menampilkan toaster otomatis jika ada session success atau error
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            if(typeof window.showToast === 'function') {
                window.showToast("{{ session('success') }}");
            }
        @endif
        @if(session('error'))
            if(typeof window.showToast === 'function') {
                window.showToast("{{ session('error') }}");
            }
        @endif
    });

    // Modal Edit Kelas
    window.toggleModalEditKelas = function(show) {
        const modal = document.getElementById('modal-edit-kelas');
        const content = document.getElementById('modalEditKelasContent');
        
        if (show) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        } else {
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    };

    // Handle Submit Form Edit
    $('#formEditKelas').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const btnSubmit = $('#btnSubmitEditKelas');
        const textSubmit = $('#btnSubmitEditText');
        const loaderSubmit = $('#btnSubmitEditLoader');
        
        btnSubmit.prop('disabled', true);
        textSubmit.text('Menyimpan...');
        loaderSubmit.removeClass('hidden');
        
        $.ajax({
            url: "{{ route('admin.kelas.update', $kelas->id) }}",
            type: 'POST', // akan diconvert ke PUT oleh @_method
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    window.toggleModalEditKelas(false);
                    window.showToast(response.message);
                    
                    // Update DOM secara manual agar tidak perlu reload
                    $('#val_nama_kelas_title').text('Detail Kelas: ' + response.data.nama_kelas);
                    $('#val_nama_kelas').text(response.data.nama_kelas);
                    $('#val_semester').text(response.data.semester_nama);
                    $('#val_dosen').text(response.data.dosen_nama);
                    $('#val_laboran').text(response.data.laboran_nama);
                    $('#val_kapasitas').text(response.data.kapasitas);
                    $('#val_ruangan_kelas').text(response.data.ruangan_nama);
                    $('#val_jadwal_kelas').text(response.data.hari + ', ' + response.data.jam_mulai + ' - ' + response.data.jam_selesai);

                    // Update nilai pada form modal agar tersinkronisasi
                    $('#nama_kelas').val(response.data.nama_kelas);
                    $('#semester_id').val(response.data.semester_id);
                    $('#kapasitas').val(response.data.kapasitas);
                    $('#dosen_id').val(response.data.dosen_id);
                    $('#laboran_id').val(response.data.laboran_id);
                    $('#ruangan_id').val(response.data.ruangan_id);
                    $('#hari').val(response.data.hari);
                    $('#jam_mulai').val(response.data.jam_mulai);
                    $('#jam_selesai').val(response.data.jam_selesai);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = errors[Object.keys(errors)[0]][0];
                }
                window.showToast(errorMsg);
            },
            complete: function() {
                btnSubmit.prop('disabled', false);
                textSubmit.text('Simpan Perubahan');
                loaderSubmit.addClass('hidden');
            }
        });
    });
</script>
@endpush
