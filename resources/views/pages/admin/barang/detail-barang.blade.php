@extends('layouts.app')

@section('title', 'Detail Barang: ' . $barang->nama_barang . ' - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <button onclick="window.history.back()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <i class="bi bi-arrow-left text-xl"></i>
        </button>
        <div>
            <h1 class="text-2xl font-bold text-ink dark:text-white">{{ $barang->nama_barang }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kode: <span class="font-mono bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">{{ $barang->kode_barang }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card Detail -->
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 lg:col-span-1 h-fit">
            <h3 class="text-lg font-semibold text-ink dark:text-white mb-4 border-b border-slate-100 dark:border-[#344150] pb-2">Informasi Barang</h3>
            
            <div class="space-y-4">
                <!-- Foto Barang dengan Hover Edit -->
                <div class="relative group w-full aspect-square bg-slate-100 dark:bg-zinc-800 rounded-lg flex flex-col items-center justify-center overflow-hidden border border-slate-200 dark:border-[#344150]">
                    @if($barang->foto_barang)
                        <img id="preview-foto-main" src="{{ asset('storage/' . $barang->foto_barang) }}" alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover">
                        <i id="icon-placeholder-main" class="bi bi-box-seam text-4xl text-slate-400 mb-2 hidden"></i>
                        <span id="text-placeholder-main" class="text-sm text-slate-500 font-medium hidden">Belum ada foto</span>
                    @else
                        <img id="preview-foto-main" src="" class="w-full h-full object-cover hidden">
                        <i id="icon-placeholder-main" class="bi bi-box-seam text-4xl text-slate-400 mb-2"></i>
                        <span id="text-placeholder-main" class="text-sm text-slate-500 font-medium">Belum ada foto</span>
                    @endif
                    
                    <!-- Overlay Edit -->
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="toggleModal('modal-foto', true)">
                        <span class="text-white font-medium flex items-center gap-2">
                            <i class="bi bi-camera"></i> Ubah Foto
                        </span>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Kategori</p>
                    <p class="text-sm text-ink dark:text-white font-medium">{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Merk / Tipe</p>
                    <p class="text-sm text-ink dark:text-white font-medium">{{ $barang->merk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Lokasi Ruangan</p>
                    <p class="text-sm text-ink dark:text-white font-medium">
                        <a href="{{ route('admin.ruangan.show', $barang->ruangan_id) }}" class="text-primary hover:underline">
                            <i class="bi bi-door-open mr-1"></i>{{ $barang->ruangan->nama_ruangan ?? '-' }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Keterangan Khusus</p>
                    <p class="text-sm text-ink dark:text-slate-300">{{ $barang->keterangan ?: 'Tidak ada keterangan.' }}</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-ink dark:text-white mt-8 mb-4 border-b border-slate-100 dark:border-[#344150] pb-2">Status & Kondisi</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-2 rounded bg-slate-50 dark:bg-zinc-800">
                    <span class="text-sm text-slate-600 dark:text-slate-300">Total Stok</span>
                    <span class="font-bold text-ink dark:text-white">{{ $barang->total_stok }}</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded border border-green-100 bg-green-50 dark:bg-green-900/10 dark:border-green-800/30">
                    <span class="text-sm text-green-700 dark:text-green-400"><i class="bi bi-check-circle mr-1"></i> Stok Baik</span>
                    <span class="font-bold text-green-700 dark:text-green-400">{{ $barang->stok_baik }}</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded border border-yellow-100 bg-yellow-50 dark:bg-yellow-900/10 dark:border-yellow-800/30">
                    <span class="text-sm text-yellow-700 dark:text-yellow-400"><i class="bi bi-exclamation-circle mr-1"></i> Rusak Ringan</span>
                    <span class="font-bold text-yellow-700 dark:text-yellow-400">{{ $barang->stok_rusak_ringan }}</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded border border-red-100 bg-red-50 dark:bg-red-900/10 dark:border-red-800/30">
                    <span class="text-sm text-red-700 dark:text-red-400"><i class="bi bi-x-circle mr-1"></i> Rusak Berat</span>
                    <span class="font-bold text-red-700 dark:text-red-400">{{ $barang->stok_rusak_berat }}</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded border border-slate-200 bg-slate-100 dark:bg-zinc-700 dark:border-zinc-600">
                    <span class="text-sm text-slate-600 dark:text-slate-300"><i class="bi bi-question-circle mr-1"></i> Hilang</span>
                    <span class="font-bold text-slate-600 dark:text-slate-300">{{ $barang->stok_hilang }}</span>
                </div>
            </div>
        </div>

        <!-- Card Riwayat/Audit -->
        <div class="bg-white dark:bg-[#171d25] border border-slate-100 dark:border-[#344150] rounded-xl shadow-sm p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-ink dark:text-white mb-4">Riwayat Penggunaan & Audit Laboran</h3>
            
            @if($riwayatPenggunaan->isEmpty())
                <div class="text-center py-10 bg-slate-50 dark:bg-zinc-800 rounded-lg border border-dashed border-slate-300 dark:border-slate-600">
                    <i class="bi bi-clipboard-x text-3xl text-slate-400 mb-2"></i>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Belum ada riwayat penggunaan atau audit untuk barang ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-[#29323e] dark:text-slate-300 border-y border-slate-200 dark:border-[#344150]">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Laboran</th>
                                <th class="px-4 py-3">Kelas Praktikum</th>
                                <th class="px-4 py-3 text-center">Jml Digunakan</th>
                                <th class="px-4 py-3">Kondisi Setelah</th>
                                <th class="px-4 py-3">Catatan Audit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#344150]">
                            @foreach($riwayatPenggunaan as $riwayat)
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $riwayat->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 font-medium text-ink dark:text-white">{{ $riwayat->laboran->name ?? 'Sistem' }}</td>
                                    <td class="px-4 py-3">
                                        @if($riwayat->jadwal && $riwayat->jadwal->kelasPraktikum)
                                            <a href="{{ route('admin.kelas.show', $riwayat->jadwal->kelasPraktikum->id) }}" class="text-primary hover:underline">
                                                {{ $riwayat->jadwal->kelasPraktikum->nama_kelas }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Audit Manual</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-700 dark:text-slate-200">{{ $riwayat->jumlah_digunakan }}</td>
                                    <td class="px-4 py-3">
                                        @if($riwayat->kondisi_setelah === 'Baik')
                                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">{{ $riwayat->kondisi_setelah }}</span>
                                        @elseif($riwayat->kondisi_setelah === 'Rusak Ringan')
                                            <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ $riwayat->kondisi_setelah }}</span>
                                        @elseif($riwayat->kondisi_setelah === 'Rusak Berat')
                                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">{{ $riwayat->kondisi_setelah }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">{{ $riwayat->kondisi_setelah }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs">{{ $riwayat->catatan ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload Foto -->
<div id="modal-foto" class="fixed inset-0 z-[9999] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-foto', false)"></div>
    <!-- Modal Panel -->
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white dark:bg-[#171d25] w-full max-w-md rounded-2xl shadow-xl pointer-events-auto flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 dark:border-[#344150]">
                <h3 class="text-lg font-bold text-ink dark:text-white">Upload Foto Barang</h3>
                <button type="button" onclick="toggleModal('modal-foto', false)" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <!-- Body -->
            <div class="p-4 sm:p-6 overflow-y-auto">
                <form id="form-foto" enctype="multipart/form-data" class="space-y-4">
                    <div class="w-full aspect-video bg-slate-100 dark:bg-[#0d1117] border-2 border-dashed border-slate-300 dark:border-[#344150] rounded-xl flex flex-col items-center justify-center relative overflow-hidden group">
                        <input type="file" name="foto" id="inp-foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        
                        <div id="preview-container" class="absolute inset-0 hidden">
                            <img id="img-preview" src="" class="w-full h-full object-contain bg-black/5 dark:bg-black/20">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-sm font-medium"><i class="bi bi-pencil mr-1"></i> Ganti Foto</span>
                            </div>
                        </div>

                        <div id="placeholder-container" class="text-center p-6">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
                                <i class="bi bi-cloud-arrow-up text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-ink dark:text-white">Klik atau drag gambar ke sini</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Maksimal 2MB (JPG, PNG, WEBP)</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="toggleModal('modal-foto', false)" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:text-slate-300 dark:bg-[#29323e] dark:hover:bg-[#344150] rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btn-save-foto" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-brand-dark rounded-lg transition-colors flex items-center gap-2">
                            <span>Simpan Foto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const barangId = {{ $barang->id }};
    const uploadUrl = '{{ route('admin.barang.upload-foto', $barang->id) }}';

    function toggleModal(id, show) {
        document.getElementById(id).classList.toggle('hidden', !show);
        if(!show) {
            // Reset form when closed
            document.getElementById('form-foto').reset();
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('placeholder-container').classList.remove('hidden');
        }
    }

    // Preview File
    const inpFoto = document.getElementById('inp-foto');
    const previewContainer = document.getElementById('preview-container');
    const placeholderContainer = document.getElementById('placeholder-container');
    const imgPreview = document.getElementById('img-preview');

    inpFoto.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if(file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file maksimal 2MB', 'error');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                placeholderContainer.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Form Submit
    document.getElementById('form-foto').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const file = inpFoto.files[0];
        if (!file) {
            showToast('Pilih foto terlebih dahulu', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('foto', file);
        formData.append('_token', csrfToken);

        const btn = document.getElementById('btn-save-foto');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Menyimpan...';
        btn.disabled = true;

        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                toggleModal('modal-foto', false);
                
                // Update image di UI
                const mainImg = document.getElementById('preview-foto-main');
                const mainIcon = document.getElementById('icon-placeholder-main');
                const mainText = document.getElementById('text-placeholder-main');
                
                mainImg.src = data.image_url;
                mainImg.classList.remove('hidden');
                mainIcon.classList.add('hidden');
                mainText.classList.add('hidden');
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(err => {
            showToast('Gagal mengupload foto', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Toast logic as defined in instruction.md to reuse existing toaster if global function exists
    // Fallback if showToast isn't globally available
    if (typeof showToast !== 'function') {
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-[99999] px-4 py-3 rounded-lg shadow-lg border flex items-center gap-3 transition-all transform translate-x-0 ${
                type === 'success' 
                ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400' 
                : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400'
            }`;
            
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            toast.innerHTML = `<i class="bi ${icon} text-lg"></i> <span class="font-medium text-sm">${message}</span>`;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    }
</script>
@endsection
