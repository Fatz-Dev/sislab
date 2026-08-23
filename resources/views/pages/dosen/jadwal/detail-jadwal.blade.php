@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('header')
<h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
    Pertemuan: {{ $jadwal->topik ?: 'Tanpa Topik' }}
</h2>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Header Info -->
        <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700">
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-1">
                        <a href="{{ route('dosen.kelas.show', $kelas->id) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            {{ $kelas->nama_kelas }}
                        </a>
                        <span class="text-black font-medium">Minggu {{ $minggu_ke }}</span>
                        <i class="bi bi-chevron-right text-[10px]"></i>
                        <span>Detail Pertemuan</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $jadwal->topik ?: 'Tanpa Topik' }}</h3>
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="flex items-center gap-1.5">
                            <i class="bi bi-calendar text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="bi bi-clock text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="bi bi-geo-alt text-blue-500"></i>
                            {{ $kelas->ruangan->nama_ruangan ?? 'Ruang Belum Diset' }}
                        </div>
                    </div>
                </div>
                
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($jadwal->status === 'terjadwal') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($jadwal->status === 'berlangsung') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                        @elseif($jadwal->status === 'selesai') bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300
                        @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif
                    ">
                        Status: {{ ucfirst($jadwal->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Panel Absensi Laboran -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                    <h2 class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-person-badge text-blue-500"></i> Kehadiran Laboran
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($kelas->laboran)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold">
                                    {{ substr($kelas->laboran->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $kelas->laboran->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $kelas->laboran->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Kehadiran Form -->
                            <form id="form-absen-laboran" onsubmit="submitAbsenLaboran(event)" class="mt-3 sm:mt-0">
                                @php
                                    $currentStatus = $absensiLaboran ? $absensiLaboran->status_hadir : '';
                                @endphp
                                
                                <div class="flex bg-slate-100 dark:bg-slate-800 rounded-lg p-1 w-max">
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status_hadir" value="hadir" class="peer sr-only" {{ $currentStatus == 'hadir' ? 'checked' : '' }} required>
                                        <div class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow">
                                            Hadir
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status_hadir" value="izin" class="peer sr-only" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white peer-checked:bg-blue-500 peer-checked:text-white peer-checked:shadow">
                                            Izin
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status_hadir" value="sakit" class="peer sr-only" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow">
                                            Sakit
                                        </div>
                                    </label>
                                    
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status_hadir" value="alpha" class="peer sr-only" {{ $currentStatus == 'alpha' ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow">
                                            Alpha
                                        </div>
                                    </label>
                                    
                                    <!-- Tombol hapus opsi radio jika batal / batalkan absen -->
                                    @if($currentStatus)
                                    <button type="button" onclick="submitResetAbsen()" title="Batalkan Absensi"
                                        class="ml-1 px-2 py-1.5 text-sm font-medium rounded-md text-slate-400 hover:text-red-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                @if($jadwal->status === 'selesai')
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan <span class="text-red-500">*</span></label>
                                    <textarea name="keterangan" rows="4" required placeholder="Karena jadwal sudah selesai, mohon berikan keterangan..." class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white">{{ $absensiLaboran->keterangan ?? '' }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Anda terlambat mengabsen laboran. Harap berikan alasan.</p>
                                </div>
                                @endif
                                
                                <div class="flex mt-4 justify-end items-center">
                                    <button type="submit" id="btn-submit-absen" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-slate-900 transition-colors">
                                        <span id="btn-submit-text">Update Kehadiran</span>
                                        <i class="bi bi-arrow-repeat animate-spin hidden ml-2" id="btn-submit-spinner"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div id="absen-feedback" class="mt-4 hidden text-sm font-medium rounded-md p-3"></div>
                    @else
                        <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                            <i class="bi bi-exclamation-circle text-2xl mb-2 block"></i>
                            <p>Tidak ada Laboran yang ditugaskan untuk kelas ini.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Panel Info Tambahan (Placeholder) -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-100 dark:border-slate-700">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                    <h2 class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-500"></i> Log & Status
                    </h2>
                </div>
                <div class="p-6 text-slate-500 dark:text-slate-400 text-sm">
                    <p>Status pertemuan saat ini: <strong>{{ ucfirst($jadwal->status) }}</strong></p>
                    <p class="mt-2 text-xs">Aktivitas Mahasiswa dan Laporan Praktikum untuk sesi ini akan ditampilkan di sini pada tahap selanjutnya.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function sendAbsenAjax(status) {
        const btnSubmit = document.getElementById('btn-submit-absen');
        const btnText = document.getElementById('btn-submit-text');
        const btnSpinner = document.getElementById('btn-submit-spinner');
        
        if(btnSubmit) {
            btnSubmit.disabled = true;
            btnSpinner.classList.remove('hidden');
        }

        fetch('{{ route('dosen.jadwal.absenLaboran', [$kelas->id, $jadwal->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status_hadir: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (status === 'belum_absen') {
                    window.location.reload();
                } else {
                    // Tampilkan notifikasi (kustom UI sesuai instruksi)
                    const feedback = document.getElementById('absen-feedback');
                    feedback.className = 'mt-4 text-sm font-medium rounded-md p-3 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400';
                    feedback.innerHTML = '<i class="bi bi-check-circle mr-1"></i> Kehadiran laboran berhasil disimpan.';
                    feedback.classList.remove('hidden');
                    
                    if(btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSpinner.classList.add('hidden');
                    }
                    
                    // Reload setelah 1.5 detik agar tombol reset (X) muncul jika belum ada
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                window.showToast('Gagal menyimpan absensi: ' + data.message);
                if(btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSpinner.classList.add('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showToast('Terjadi kesalahan saat menghubungi server.');
            if(btnSubmit) {
                btnSubmit.disabled = false;
                btnSpinner.classList.add('hidden');
            }
        });
    }

    function submitAbsenLaboran(e) {
        e.preventDefault(); // cegah reload halaman
        const formData = new FormData(e.target);
        const status = formData.get('status_hadir');
        
        if(!status) {
            window.showToast('Pilih salah satu status kehadiran terlebih dahulu!');
            return;
        }
        
        sendAbsenAjax(status);
    }
    
    function submitResetAbsen() {
        const modal = document.getElementById('modal-reset-absen');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
    
    function closeResetAbsenModal() {
        const modal = document.getElementById('modal-reset-absen');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    function confirmResetAbsen() {
        closeResetAbsenModal();
        sendAbsenAjax('belum_absen');
    }
</script>

<!-- Modal Konfirmasi Reset Absen -->
<div id="modal-reset-absen" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 w-full max-w-md mx-4 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800 dark:text-white">Batalkan Absensi</h3>
            <button type="button" onclick="closeResetAbsenModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-5">
            <p class="text-slate-600 dark:text-slate-300 text-sm">
                Apakah Anda yakin ingin membatalkan (mereset) absensi laboran ini? Data kehadiran yang sudah tersimpan akan dihapus.
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeResetAbsenModal()" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600">
                    Batal
                </button>
                <button type="button" onclick="confirmResetAbsen()" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
