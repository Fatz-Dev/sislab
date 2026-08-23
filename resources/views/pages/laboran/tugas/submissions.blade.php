@extends('layouts.app')
@section('title', 'Kumpulan Tugas: ' . $tugas->judul)

@section('content')
<div class="space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 dark:text-white mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('laboran.kelas.index') }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors">
                            <i class="bi bi-journal-bookmark mr-1.5"></i> Kelas Praktikum
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <a href="{{ route('laboran.kelas.show', $kelas->id) }}" class="inline-flex items-center dark:text-white dark:hover:text-green-200 transition-colors ml-1">
                                {{ $kelas->nama_kelas }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-slate-400 mx-1 text-xs"></i>
                            <span class="text-slate-700 dark:text-white font-medium ml-1">Kumpulan Tugas</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                Kumpulan Tugas & Penilaian
            </h1>
        </div>
    </div>

    <!-- Informasi Tugas -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-1">Judul Tugas</p>
                <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $tugas->judul }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mb-1">Tenggat Waktu</p>
                <p class="text-sm font-semibold text-red-600 dark:text-red-400 flex items-center gap-2">
                    <i class="bi bi-calendar-x"></i> {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('l, d F Y H:i') }} WIB
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th scope="col" class="px-6 py-4">Mahasiswa</th>
                        <th scope="col" class="px-6 py-4">Status Pengumpulan</th>
                        <th scope="col" class="px-6 py-4">File Laporan</th>
                        <th scope="col" class="px-6 py-4 w-64">Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach($kelas->mahasiswas as $mahasiswa)
                        @php
                            $submission = $submissions->get($mahasiswa->id);
                            $nilai = $nilais->get($mahasiswa->id);
                        @endphp
                        <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <!-- Profil Mahasiswa -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($mahasiswa->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $mahasiswa->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $mahasiswa->nim ?? 'NIM tidak tersedia' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($submission)
                                    @if($submission->status == 'tepat_waktu')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Tepat Waktu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Terlambat
                                        </span>
                                    @endif
                                    <div class="text-[11px] text-slate-400 mt-1">
                                        {{ \Carbon\Carbon::parse($submission->tanggal_submit)->translatedFormat('d M Y, H:i') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Belum Kumpul
                                    </span>
                                @endif
                            </td>

                            <!-- File Download -->
                            <td class="px-6 py-4">
                                @if($submission)
                                    <a href="{{ Storage::url($submission->file_laporan) }}" target="_blank" class="cursor-pointer inline-flex items-center justify-center w-24 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors" title="Download Laporan">
                                        <i class="bi bi-download mr-2"></i> Lihat file
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Penilaian (Form Inline) -->
                            <td class="px-6 py-4">
                                @if($submission)
                                    <form class="form-penilaian flex flex-col gap-2" action="{{ route('laboran.tugas.grade', [$kelas->id, $tugas->id, $mahasiswa->id]) }}" method="POST">
                                        @csrf
                                        <div class="flex gap-2 items-center">
                                            <input type="number" name="nilai" min="0" max="100" step="0.01" value="{{ $nilai ? $nilai->nilai : '' }}" placeholder="Nilai (0-100)" class="w-24 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg p-2 focus:ring-green-500 focus:border-green-500 dark:text-white" required>
                                            <button type="submit" class="btn-save shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors" title="Simpan Nilai">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <input type="text" name="keterangan" value="{{ $nilai ? $nilai->keterangan : '' }}" placeholder="Catatan/Keterangan..." class="w-full text-xs bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg p-2 focus:ring-green-500 focus:border-green-500 dark:text-white">
                                        </div>
                                    </form>
                                @else
                                    <div class="text-xs text-slate-400 italic">Menunggu pengumpulan...</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($kelas->mahasiswas->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                Tidak ada mahasiswa di kelas ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.form-penilaian').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('.btn-save');
            let icon = btn.find('i');
            
            // Animasi loading
            icon.removeClass('bi-check-lg').addClass('bi-hourglass-split animate-spin');
            btn.prop('disabled', true);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (typeof window.showToast === 'function') {
                        window.showToast(response.message, 'success');
                    }
                    
                    // Kembalikan tombol
                    setTimeout(() => {
                        icon.removeClass('bi-hourglass-split animate-spin').addClass('bi-check-lg');
                        btn.prop('disabled', false);
                    }, 500);
                },
                error: function(xhr) {
                    // Kembalikan tombol
                    icon.removeClass('bi-hourglass-split animate-spin').addClass('bi-check-lg');
                    btn.prop('disabled', false);
                    
                    if (typeof window.showToast === 'function') {
                        let msg = xhr.responseJSON?.message || 'Gagal menyimpan nilai.';
                        window.showToast(msg, 'error');
                    }
                }
            });
        });
    });
</script>
@endpush
