@extends('layouts.app')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink dark:text-white">Pengaturan Sistem</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola preferensi dan konfigurasi sistem laboratorium.</p>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Menu Pengaturan -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-100 dark:border-[#344150] overflow-hidden sticky top-24">
                <nav class="flex flex-col p-2 space-y-1">
                    <button onclick="switchSettingTab('umum')" id="tab-btn-umum" class="px-4 py-2.5 text-sm font-medium rounded-lg bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 flex items-center gap-3 w-full text-left transition-colors">
                        <i class="bi bi-gear text-lg"></i> Umum
                    </button>
                    <button onclick="switchSettingTab('pengumuman')" id="tab-btn-pengumuman" class="px-4 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-[#202832] flex items-center gap-3 w-full text-left transition-colors">
                        <i class="bi bi-shield-lock text-lg"></i> Pengumuman
                    </button>
                </nav>
            </div>
        </div>

        <!-- Konten Pengaturan -->
        <div class="flex-1 space-y-6">
            <!-- Form Umum -->
            <div id="tab-content-umum" class="bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-100 dark:border-[#344150] overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-[#344150]">
                    <h3 class="text-lg font-semibold text-ink dark:text-white">Pengaturan Umum</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ubah informasi dasar tentang aplikasi sistem lab ini.</p>
                </div>
                <div class="p-6">
                    <form class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Aplikasi</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-colors" value="SISLAB Fisika" placeholder="Masukkan nama aplikasi">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi Singkat</label>
                            <textarea rows="3" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-colors" placeholder="Masukkan deskripsi...">Sistem Informasi Laboratorium Fisika Terpadu untuk kemudahan praktikum.</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email Kontak Admin</label>
                            <input type="email" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-colors" value="admin@sislab.fisika.ac.id" placeholder="email@contoh.com">
                        </div>

                        <div class="flex items-center justify-between py-3 border-t border-slate-100 dark:border-[#344150] mt-4">
                            <div>
                                <p class="text-sm font-medium text-ink dark:text-white">Mode Maintenance</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Matikan akses publik ke sistem sementara waktu.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-[#202832] border-t border-slate-100 dark:border-[#344150] flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 dark:bg-[#171d25] dark:text-slate-300 dark:border-[#344150] dark:hover:bg-[#29323e] transition-colors">
                        Batal
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition-colors flex items-center gap-2">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Form Pengumuman -->
            <div id="tab-content-pengumuman" class="hidden bg-white dark:bg-[#171d25] rounded-xl shadow-sm border border-slate-100 dark:border-[#344150] overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-[#344150]">
                    <h3 class="text-lg font-semibold text-ink dark:text-white">Buat Pengumuman Baru</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kirimkan pengumuman yang akan muncul di notifikasi pengguna terpilih.</p>
                </div>
                <div class="p-6">
                    <form id="form-pengumuman" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Judul Pengumuman</label>
                            <input type="text" name="judul" id="inp-judul" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-colors" placeholder="Cth: Penutupan Lab Sementara">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Isi Pesan</label>
                            <textarea name="isi" id="inp-isi" required rows="4" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#344150] rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-colors" placeholder="Tuliskan isi pengumuman secara detail..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Target Penerima (Kirim ke Notifikasi)</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="target[]" value="dosen" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500 dark:border-[#344150] dark:bg-[#0d1117]">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Dosen</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="target[]" value="laboran" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500 dark:border-[#344150] dark:bg-[#0d1117]">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Laboran</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="target[]" value="mahasiswa" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500 dark:border-[#344150] dark:bg-[#0d1117]">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Mahasiswa</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="target_all" id="chk-semua" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500 dark:border-[#344150] dark:bg-[#0d1117]" onchange="toggleSemuaTarget(this)">
                                    <span class="text-sm font-medium text-slate-800 dark:text-white">Kirim ke Semua Role</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-[#202832] border-t border-slate-100 dark:border-[#344150] flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('form-pengumuman').reset()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 dark:bg-[#171d25] dark:text-slate-300 dark:border-[#344150] dark:hover:bg-[#29323e] transition-colors">
                        Reset
                    </button>
                    <button type="button" id="btn-kirim-pengumuman" onclick="submitPengumuman()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition-colors flex items-center gap-2">
                        <i class="bi bi-send"></i> Publish Pengumuman
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const storeUrl = '{{ route('admin.settings.pengumuman.store') }}';

    function switchSettingTab(tab) {
        // Toggle Active Button
        const btnUmum = document.getElementById('tab-btn-umum');
        const btnPengumuman = document.getElementById('tab-btn-pengumuman');
        
        const activeClass = 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'.split(' ');
        const inactiveClass = 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-[#202832]'.split(' ');

        if (tab === 'umum') {
            btnUmum.classList.remove(...inactiveClass);
            btnUmum.classList.add(...activeClass);
            btnPengumuman.classList.remove(...activeClass);
            btnPengumuman.classList.add(...inactiveClass);
            
            document.getElementById('tab-content-umum').classList.remove('hidden');
            document.getElementById('tab-content-pengumuman').classList.add('hidden');
        } else {
            btnPengumuman.classList.remove(...inactiveClass);
            btnPengumuman.classList.add(...activeClass);
            btnUmum.classList.remove(...activeClass);
            btnUmum.classList.add(...inactiveClass);
            
            document.getElementById('tab-content-pengumuman').classList.remove('hidden');
            document.getElementById('tab-content-umum').classList.add('hidden');
        }
    }

    function toggleSemuaTarget(el) {
        const checkboxes = document.querySelectorAll('input[name="target[]"]');
        checkboxes.forEach(cb => {
            cb.checked = el.checked;
        });
    }

    function submitPengumuman() {
        const judul = document.getElementById('inp-judul').value.trim();
        const isi = document.getElementById('inp-isi').value.trim();
        
        let target = [];
        document.querySelectorAll('input[name="target[]"]:checked').forEach(cb => {
            target.push(cb.value);
        });

        if (!judul || !isi) {
            showToast('Judul dan Isi pengumuman wajib diisi', 'error');
            return;
        }

        if (target.length === 0) {
            showToast('Pilih setidaknya satu target penerima', 'error');
            return;
        }

        const btn = document.getElementById('btn-kirim-pengumuman');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Memproses...';
        btn.disabled = true;

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                judul: judul,
                isi: isi,
                target_role: target
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById('form-pengumuman').reset();
            } else {
                showToast(data.message || 'Terjadi kesalahan saat mengirim pengumuman', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Gagal terhubung ke server', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    if (typeof showToast !== 'function') {
        window.showToast = function(message, type = 'success', duration = 3000) {
            const toast = document.createElement('div');
            
            // Tambahkan dukungan warna untuk tipe 'info'
            let colors = '';
            let icon = '';
            
            if (type === 'success') {
                colors = 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400';
                icon = 'bi-check-circle-fill';
            } else if (type === 'error') {
                colors = 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400';
                icon = 'bi-exclamation-triangle-fill';
            } else if (type === 'info') {
                colors = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400';
                icon = 'bi-info-circle-fill';
            }

            toast.className = `fixed top-4 right-4 z-[99999] px-4 py-3 rounded-lg shadow-lg border flex items-center gap-3 transition-all transform translate-x-0 ${colors}`;
            toast.innerHTML = `<i class="bi ${icon} text-lg"></i> <span class="font-medium text-sm">${message}</span>`;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    }
</script>
        </div>
    </div>
</div>
@endsection