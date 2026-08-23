@extends('layouts.app')
@section('title', 'Persetujuan Pendaftaran Kelas')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Persetujuan Pendaftaran</h1>
            <p class="text-sm text-slate-500 mt-1">Tinjau dan setujui pendaftaran mahasiswa ke kelas praktikum.</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($pendingCount > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-sm font-medium border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $pendingCount }} Menunggu
                </span>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-end">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="flex-1 flex flex-col sm:flex-row gap-4 w-full" id="filter-form">
            <div class="w-full sm:w-1/3">
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" onchange="document.getElementById('filter-form').submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-1/3">
                <label for="kelas_id" class="block text-sm font-medium text-slate-700 mb-1">Kelas Praktikum</label>
                <select name="kelas_id" id="kelas_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" onchange="document.getElementById('filter-form').submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->semester->nama_semester ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <a href="{{ route('admin.enrollments.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Kapasitas</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($enrollments as $enrollment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                        {{ substr($enrollment->mahasiswa->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $enrollment->mahasiswa->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-slate-500">{{ $enrollment->mahasiswa->nip_nim ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 font-medium">{{ $enrollment->kelasPraktikum->nama_kelas ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $enrollment->kelasPraktikum->semester->nama_semester ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($enrollment->kelasPraktikum)
                                    @php
                                        $approved = $enrollment->kelasPraktikum->approvedMahasiswas()->count();
                                        $kapasitas = $enrollment->kelasPraktikum->kapasitas;
                                        $isFull = $approved >= $kapasitas;
                                    @endphp
                                    <span class="{{ $isFull ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                                        {{ $approved }}/{{ $kapasitas }}
                                    </span>
                                @else
                                    <span class="text-slate-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($enrollment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @elseif($enrollment->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Disetujui
                                    </span>
                                @elseif($enrollment->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                    @if($enrollment->catatan_admin)
                                        <div class="mt-1 text-xs text-slate-500 truncate max-w-[120px]" title="{{ $enrollment->catatan_admin }}">
                                            {{ $enrollment->catatan_admin }}
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($enrollment->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="confirmApprove('{{ route('admin.enrollments.approve', $enrollment->id) }}', '{{ $enrollment->mahasiswa->name }}')" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </button>
                                        <button onclick="confirmReject('{{ route('admin.enrollments.reject', $enrollment->id) }}', '{{ $enrollment->mahasiswa->name }}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs">- Selesai -</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p>Tidak ada data pendaftaran yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($enrollments->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $enrollments->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Approve -->
<div id="modal-approve" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-emerald-50">
            <h3 class="text-lg font-bold text-emerald-800 flex items-center gap-2"><i class="bi bi-check-circle-fill"></i> Setujui Pendaftaran</h3>
            <button onclick="closeModal('modal-approve')" class="text-emerald-600 hover:text-emerald-900"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="p-6">
            <p class="text-slate-600 mb-4">Apakah Anda yakin ingin menyetujui pendaftaran dari <strong id="approve-mhs-name" class="text-slate-800"></strong>?</p>
            <form id="form-approve" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('modal-approve')" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium">Batal</button>
                    <button type="submit" id="btn-submit-approve" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Ya, Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="modal-reject" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-red-50">
            <h3 class="text-lg font-bold text-red-800 flex items-center gap-2"><i class="bi bi-x-circle-fill"></i> Tolak Pendaftaran</h3>
            <button onclick="closeModal('modal-reject')" class="text-red-600 hover:text-red-900"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="p-6">
            <p class="text-slate-600 mb-4">Apakah Anda yakin ingin menolak pendaftaran dari <strong id="reject-mhs-name" class="text-slate-800"></strong>?</p>
            <form id="form-reject" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="catatan_admin" class="block text-sm font-medium text-slate-700 mb-1">Catatan/Alasan (Opsional)</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="3" class="w-full rounded-lg border-slate-300 text-sm focus:ring-red-500 focus:border-red-500" placeholder="Kapasitas sudah penuh, atau alasan lainnya..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('modal-reject')" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium">Batal</button>
                    <button type="submit" id="btn-submit-reject" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Ya, Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmApprove(actionUrl, studentName) {
        document.getElementById('approve-mhs-name').textContent = studentName;
        document.getElementById('form-approve').action = actionUrl;
        
        const modal = document.getElementById('modal-approve');
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    }

    function confirmReject(actionUrl, studentName) {
        document.getElementById('reject-mhs-name').textContent = studentName;
        document.getElementById('form-reject').action = actionUrl;
        document.getElementById('catatan_admin').value = '';
        
        const modal = document.getElementById('modal-reject');
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    // AJAX Form Submission
    $(document).ready(function() {
        $('#form-approve, #form-reject').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const data = form.serialize();
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.text();
            
            submitBtn.prop('disabled', true).text('Memproses...');

            $.ajax({
                url: url,
                method: 'POST', // Method override happens via _method=PATCH
                data: data,
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (window.showToast) window.showToast(response.message);
                    setTimeout(() => window.location.reload(), 1000);
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(originalText);
                    let errorMsg = "Terjadi kesalahan.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    if (window.showToast) window.showToast(errorMsg);
                    closeModal(form.closest('div[id^="modal-"]').attr('id'));
                }
            });
        });
    });
</script>
@endpush
