@extends('layouts.app-mahasiswa')
@section('title', 'Pilih Kelas Praktikum')

@section('content')
    <div class="max-w-7xl ">

        <div style="margin:20px 0 28px;">

            {{-- Back --}}
            <div style="margin-bottom:18px;">
                <a href="{{ route('mahasiswa.dashboard') }}"
                style="display:inline-flex; align-items:center; gap:7px; color:#64748b; font-size:13px; font-weight:500; text-decoration:none; transition:color .2s;"
                onmouseover="this.style.color='#0f172a'"
                onmouseout="this.style.color='#64748b'">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>

            {{-- Title --}}
            <div style="background-color: #ffffff; padding:12px 18px; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(15,23,42,.05);">
                <h1 style="margin:0; color:#0f172a; font-size:24px; line-height:1.3; font-weight:700;">
                    Pilih Kelas Praktikum
                </h1>

                <p style="margin:6px 0 0; color:#64748b; font-size:13px; line-height:1.6;">
                    Daftar ke kelas praktikum yang tersedia pada semester aktif.
                </p>
            </div>

        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast("{{ session('success') }}");
                    }
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast("{{ session('error') }}");
                    }
                });
            </script>
        @endif

        @php
            // Jika enrollment ditutup, hanya tampilkan yang statusnya pending
            if (!$isEnrollmentOpen) {
                $kelasDisplay = $kelasOpen->filter(function($kelas) use ($enrolledKelasIds, $enrollmentStatuses) {
                    $isEnrolled = in_array($kelas->id, $enrolledKelasIds);
                    return $isEnrolled && isset($enrollmentStatuses[$kelas->id]) && $enrollmentStatuses[$kelas->id] === 'pending';
                });
            } else {
                $kelasDisplay = $kelasOpen;
            }
        @endphp

        @if (!$isEnrollmentOpen)
            <div class="mt-6 bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
                <div class="flex items-center gap-2 p-4">
                    <i class="bi bi-exclamation-triangle-fill text-rose-500"></i>
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-800 mb-2 m-0">Pemilihan Kelas Sudah Ditutup</h3>
                        <p class="text-xs text-slate-500 m-0">Anda tidak dapat lagi mendaftar ke kelas praktikum baru. Jika ada pengajuan yang masih berstatus menunggu, Anda dapat melihatnya di bawah ini.</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($kelasDisplay->isEmpty())
            <div
                style="margin:24px 0; padding:48px 24px; text-align:center; background:#fff; border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 2px 8px rgba(15,23,42,.04);">

                <div
                    style="width:64px; height:64px; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; border-radius:16px; background:#f1f5f9; color:#94a3b8; font-size:28px;">
                    <i class="bi bi-inboxes"></i>
                </div>

                <h3 style="margin:0; color:#1e293b; font-size:17px; font-weight:700;">
                    Tidak Ada Kelas
                </h3>

                <p style="max-width:420px; margin:7px auto 0; color:#94a3b8; font-size:13px; line-height:1.6;">
                    @if (!$isEnrollmentOpen)
                        Tidak ada pengajuan pendaftaran kelas yang sedang menunggu persetujuan.
                    @else
                        Saat ini belum ada kelas praktikum yang membuka pendaftaran.
                    @endif
                </p>

            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($kelasDisplay as $kelas)
                    @php
        $isEnrolled = in_array($kelas->id, $enrolledKelasIds);
        $status = $isEnrolled ? $enrollmentStatuses[$kelas->id] : null;
        $isFull = $kelas->jumlah_approved >= $kelas->kapasitas;
                    @endphp

                    @php
        $capacityPercentage =
            $kelas->kapasitas > 0 ? min(100, ($kelas->jumlah_approved / $kelas->kapasitas) * 100) : 0;

        $remaining = max(0, $kelas->kapasitas - $kelas->jumlah_approved);
                    @endphp
                    <div
                        style="width:100%; background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(15,23,42,.05);">

                        {{-- Header --}}
                        <div style="padding:16px 18px; border-bottom:1px solid #f1f5f9;">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">

                                <div style="min-width:0; flex:1;">
                                    <h3
                                        style="margin:0; color:#0f172a; font-size:17px; line-height:1.4; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $kelas->nama_kelas }}
                                    </h3>

                                    <p
                                        style="margin:4px 0 0; color:#94a3b8; font-size:11px; font-weight:600; letter-spacing:.06em; text-transform:uppercase;">
                                        {{ $kelas->semester->nama_semester ?? 'Semester belum ditentukan' }}
                                    </p>
                                </div>
                            </div>
                        </div>


                        {{-- Information --}}
                        <div style="padding:0 18px;">

                            {{-- Dosen --}}
                            <div
                                style="display:flex; align-items:center; justify-content:space-between; gap:12px; min-height:54px; border-bottom:1px solid #f1f5f9;">

                                <div style="display:flex; align-items:center; gap:10px; min-width:95px;">
                                    <span style="color:#64748b; font-size:14px;">Dosen</span>
                                </div>

                                <span
                                    style="min-width:0; color:#334155; font-size:14px; font-weight:600; text-align:right; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                    title="{{ $kelas->dosen->name ?? 'Belum ditentukan' }}">
                                    {{ $kelas->dosen->name ?? 'Belum ditentukan' }}
                                </span>

                            </div>


                            {{-- Laboran --}}
                            <div
                                style="display:flex; align-items:center; justify-content:space-between; gap:12px; min-height:54px; border-bottom:1px solid #f1f5f9;">

                                <div style="display:flex; align-items:center; gap:10px; min-width:95px;">
                                    <span style="color:#64748b; font-size:14px;">Laboran</span>
                                </div>

                                <span
                                    style="min-width:0; color:#334155; font-size:14px; font-weight:600; text-align:right; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                    title="{{ $kelas->laboran->name ?? 'Belum ditentukan' }}">
                                    {{ $kelas->laboran->name ?? 'Belum ditentukan' }}
                                </span>

                            </div>


                            {{-- Kapasitas --}}
                            <div style="padding:13px 0 15px;">

                                <div
                                    style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:9px;">

                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <span style="color:#64748b; font-size:14px;">Kapasitas</span>
                                    </div>

                                    <span
                                        style="color:{{ $isFull ? '#dc2626' : '#059669' }}; font-size:14px; font-weight:700;">
                                        {{ $kelas->jumlah_approved }}
                                        <span style="color:#94a3b8; font-weight:500;">/ {{ $kelas->kapasitas }}</span>
                                    </span>

                                </div>

                                <div
                                    style="width:100%; height:5px; background:#f1f5f9; border-radius:999px; overflow:hidden;">
                                    <div
                                        style="width:{{ $capacityPercentage }}%; height:100%; background:{{ $isFull ? '#ef4444' : '#10b981' }}; border-radius:999px;">
                                    </div>
                                </div>

                            </div>

                        </div>


                        {{-- Footer --}}
                        <div style="padding:12px 18px; background:#f8fafc; border-top:1px solid #f1f5f9;">

                            @if ($isEnrolled)
                                {{-- Approved --}}
                                @if ($status === 'approved')
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">

                                        <span
                                            style="display:flex; align-items:center; gap:7px; color:#059669; font-size:13px; font-weight:600;">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Disetujui
                                        </span>

                                        <span
                                            style="padding:5px 9px; border:1px solid #e2e8f0; border-radius:7px; background:#fff; color:#64748b; font-size:11px; font-weight:600;">
                                            Tergabung
                                        </span>

                                    </div>


                                    {{-- Pending --}}
                                @elseif ($status === 'pending')
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">

                                        <span
                                            style="display:flex; align-items:center; gap:7px; color:#d97706; font-size:13px; font-weight:600;">
                                            <i class="bi bi-clock-fill"></i>
                                            Menunggu
                                        </span>

                                        @if ($isEnrollmentOpen)
                                            <button type="button"
                                                onclick="openModalMahasiswa('Batal Daftar', 'Apakah Anda yakin ingin membatalkan pendaftaran kelas ini?', '{{ route('mahasiswa.kelas.cancel', $kelas->id) }}', 'DELETE')"
                                                style="padding:4px; border:0; background:none; color:#ef4444; font-size:13px; font-weight:600; cursor:pointer;">
                                                Batal
                                            </button>
                                        @endif

                                    </div>


                                    {{-- Rejected --}}
                                @elseif ($status === 'rejected')
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">

                                        <span
                                            style="display:flex; align-items:center; gap:7px; color:#dc2626; font-size:13px; font-weight:600;">
                                            <i class="bi bi-x-circle-fill"></i>
                                            Ditolak
                                        </span>

                                        <span style="color:#94a3b8; font-size:11px;">
                                            Hubungi admin
                                        </span>

                                    </div>
                                @endif
                            @else
                                {{-- Full --}}
                                @if ($isFull)
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">

                                        <span
                                            style="display:flex; align-items:center; gap:7px; color:#64748b; font-size:13px; font-weight:600;">
                                            <i class="bi bi-slash-circle"></i>
                                            Kelas penuh
                                        </span>

                                        <span style="color:#94a3b8; font-size:11px;">
                                            {{ $kelas->jumlah_approved }}/{{ $kelas->kapasitas }}
                                        </span>

                                    </div>


                                    {{-- Available --}}
                                @else
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">

                                        <div>
                                            <div
                                                style="display:flex; align-items:center; gap:7px; color:#334155; font-size:13px; font-weight:600;">
                                                <span
                                                    style="width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                                                Tersedia
                                            </div>

                                            <div style="margin-top:2px; color:#94a3b8; font-size:11px;">
                                                {{ $remaining }} slot tersisa
                                            </div>
                                        </div>

                                        <button type="button"
                                            onclick="openModalMahasiswa('Daftar Kelas', 'Apakah Anda yakin ingin mendaftar ke kelas ini?', '{{ route('mahasiswa.kelas.apply', $kelas->id) }}', 'POST')"
                                            style="display:flex; align-items:center; justify-content:center; gap:6px; min-width:88px; height:36px; padding:0 14px; border:0; border-radius:8px; background:#10b981; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                                            <i class="bi bi-plus-lg"></i>
                                            Daftar
                                        </button>

                                    </div>
                                @endif
                            @endif

                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi -->
    <div id="modal-confirm-mahasiswa" class="fixed inset-0 z-[99] hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" 
        style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 99; display: none; align-items: center; justify-content: center; padding: 1rem; background-color: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px);">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm border border-slate-100 overflow-hidden" 
            style="background-color: #ffffff; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1); width: 100%; max-width: 24rem; border: 1px solid #f1f5f9; overflow: hidden;">
            <div class="flex justify-between items-center p-4 border-b border-slate-100" 
                style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                <h3 id="modal-title" class="text-lg font-semibold text-slate-800" style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin: 0;">Konfirmasi</h3>
                <button onclick="closeModalMahasiswa()" class="text-slate-400 hover:text-slate-600" style="color: #94a3b8; background: transparent; border: none; cursor: pointer; padding: 0.5rem;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-4" style="padding: 1rem;">
                <p id="modal-confirm-msg" class="text-slate-600 text-sm" style="color: #475569; font-size: 0.875rem; margin: 0;">Apakah Anda yakin?</p>
            </div>
            <div class="flex justify-end gap-2 p-4 pt-0" style="display: flex; justify-content: flex-end; gap: 0.5rem; padding: 1rem; padding-top: 0;">
                <button type="button" onclick="closeModalMahasiswa()"
                    class="px-4 py-2 text-sm border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50" 
                    style="padding: 0.5rem 1rem; font-size: 0.875rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; color: #475569; background-color: transparent; cursor: pointer;">Batal</button>
                <form id="modal-form" method="POST" action="" style="margin: 0;">
                    @csrf
                    <div id="modal-method"></div>
                    <button type="submit" id="btn-confirm-yes"
                        class="px-4 py-2 text-sm text-white rounded-lg font-medium" 
                        style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #ffffff; border-radius: 0.5rem; font-weight: 500; border: none; cursor: pointer;">
                        Ya, Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        let currentActionElement = null; // Menyimpan referensi elemen card kelas yang diklik

        function openModalMahasiswa(title, message, actionUrl, method, element) {
            const modal = document.getElementById('modal-confirm-mahasiswa');
            currentActionElement = element; // Simpan elemen untuk update UI nanti (opsional)
            
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-confirm-msg').textContent = message;
            document.getElementById('modal-form').action = actionUrl;
            
            // Simpan method di data attribute form
            document.getElementById('modal-form').dataset.method = method.toUpperCase();
            
            const btnYes = document.getElementById('btn-confirm-yes');

            if (method.toUpperCase() === 'DELETE') {
                btnYes.className = 'px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium';
                btnYes.style.backgroundColor = '#dc2626'; // fallback inline red
            } else {
                btnYes.className = 'px-4 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium';
                btnYes.style.backgroundColor = '#10b981'; // fallback inline emerald
            }
            
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }

        function closeModalMahasiswa() {
            const modal = document.getElementById('modal-confirm-mahasiswa');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        $(document).ready(function() {
            $('#modal-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const actionUrl = form.attr('action');
                const method = form.data('method') || 'POST';
                const btnYes = $('#btn-confirm-yes');
                
                // Disable button
                btnYes.prop('disabled', true).text('Memproses...');

                $.ajax({
                    url: actionUrl,
                    method: method,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        closeModalMahasiswa();
                        if (window.showToast) {
                            window.showToast(response.message || "Aksi berhasil.");
                        }
                        // Reload halaman setelah jeda agar user melihat toast, atau langsung update UI. 
                        // Karena perubahan status kelas mengubah layout progress bar, reload adalah yang paling aman dan konsisten.
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        btnYes.prop('disabled', false).text('Ya, Lanjutkan');
                        closeModalMahasiswa();
                        let errorMsg = "Terjadi kesalahan.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        if (window.showToast) {
                            window.showToast(errorMsg);
                        }
                    }
                });
            });
        });
    </script>
@endpush
