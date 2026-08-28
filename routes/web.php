<?php

use App\Http\Controllers\AdminEnrollmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\MahasiswaProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SISLAB FISIKA
|--------------------------------------------------------------------------
|
| Route dikelompokkan berdasarkan peran (role):
| - Guest    : halaman login & register (hanya untuk user yang belum login)
| - Auth     : route yang memerlukan autentikasi
|   ├─ Admin     : /admin/*
|   ├─ Dosen     : /dosen/*
|   ├─ Laboran   : /laboran/*
|   └─ Mahasiswa : /mahasiswa/*
|
*/

// ─── Guest Routes (belum login) ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // Registrasi khusus mahasiswa
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// ─── Authenticated Routes ────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout (semua role)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile global
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

    // ── Admin ────────────────────────────────────────────────
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

            // Manajemen Enrollment Mahasiswa
            Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
            Route::patch('/enrollments/{enrollment}/approve', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
            Route::patch('/enrollments/{enrollment}/reject', [AdminEnrollmentController::class, 'reject'])->name('enrollments.reject');

            // Manajemen Users
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/mahasiswa', [\App\Http\Controllers\Admin\AdminUserController::class, 'mahasiswa'])->name('mahasiswa');
                Route::get('/laboran', [\App\Http\Controllers\Admin\AdminUserController::class, 'laboran'])->name('laboran');
                Route::get('/admin', [\App\Http\Controllers\Admin\AdminUserController::class, 'admin'])->name('admin');
                Route::get('/dosen', [\App\Http\Controllers\Admin\AdminUserController::class, 'dosen'])->name('dosen'); 
                
                // Detail user route
                Route::get('/{id}/detail', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('show');
            });

            // Manajemen Kelas Praktikum
            Route::prefix('kelas')->name('kelas.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminKelasController::class, 'index'])->name('index');
                Route::post('/', [\App\Http\Controllers\Admin\AdminKelasController::class, 'store'])->name('store');
                Route::get('/data', [\App\Http\Controllers\Admin\AdminKelasController::class, 'data'])->name('data');
                Route::get('/{id}/detail', [\App\Http\Controllers\Admin\AdminKelasController::class, 'show'])->name('show');
                Route::put('/{id}', [\App\Http\Controllers\Admin\AdminKelasController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminKelasController::class, 'destroy'])->name('delete');
                Route::post('/toggle-all', [\App\Http\Controllers\Admin\AdminKelasController::class, 'toggleAllStatus'])->name('toggle-all');
                Route::post('/toggle-enrollment', [\App\Http\Controllers\Admin\AdminKelasController::class, 'toggleEnrollment'])->name('toggle-enrollment');
                Route::patch('/{id}/toggle-status', [\App\Http\Controllers\Admin\AdminKelasController::class, 'toggleStatus'])->name('toggle-status');
            });

            // Manajemen Barang (Global Inventory)
            Route::prefix('barang')->name('barang.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminBarangController::class, 'index'])->name('index');
                Route::get('/export', [\App\Http\Controllers\Admin\AdminBarangController::class, 'exportExcel'])->name('export');
                Route::get('/template-import', [\App\Http\Controllers\Admin\AdminBarangController::class, 'downloadTemplate'])->name('template-import');
                Route::post('/import', [\App\Http\Controllers\Admin\AdminBarangController::class, 'importGlobal'])->name('import');
                Route::get('/{id}', [\App\Http\Controllers\Admin\AdminBarangController::class, 'show'])->name('show');
                Route::post('/{id}/upload-foto', [\App\Http\Controllers\Admin\AdminBarangController::class, 'uploadFoto'])->name('upload-foto');
            });

            // Manajemen Ruangan
            Route::prefix('ruangan')->name('ruangan.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'show'])->name('show');
                Route::get('/{id}/barang-data', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'barangData'])->name('barang.data');
                Route::get('/{id}/kelas-data', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'kelasData'])->name('kelas.data');
                Route::post('/{id}/barang', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'storeBarang'])->name('barang.store');
                Route::put('/{ruanganId}/barang/{barangId}', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'updateBarang'])->name('barang.update');
                Route::post('/{id}/import', [\App\Http\Controllers\Admin\AdminRuanganController::class, 'importBarang'])->name('barang.import');
            });

            // Manajemen Jadwal
            Route::prefix('jadwal')->name('jadwal.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminJadwalController::class, 'index'])->name('index');
                Route::get('/data', [\App\Http\Controllers\Admin\AdminJadwalController::class, 'data'])->name('data');
            });

            // Persetujuan Pendaftaran (Enrollments)
            Route::prefix('enrollments')->name('enrollments.')->group(function () {
                Route::get('/', [\App\Http\Controllers\AdminEnrollmentController::class, 'index'])->name('index');
                Route::patch('/{enrollment}/approve', [\App\Http\Controllers\AdminEnrollmentController::class, 'approve'])->name('approve');
                Route::patch('/{enrollment}/reject', [\App\Http\Controllers\AdminEnrollmentController::class, 'reject'])->name('reject');
            });

            // Pengaturan
            Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
            Route::post('/settings/pengumuman', [\App\Http\Controllers\Admin\AdminSettingController::class, 'storePengumuman'])->name('settings.pengumuman.store');

            // Laporan Kerusakan dari Laboran
            Route::prefix('laporan-laboran')->name('laporan-laboran.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminLaporanLaboranController::class, 'index'])->name('index');
                Route::patch('/{id}/review', [\App\Http\Controllers\Admin\AdminLaporanLaboranController::class, 'review'])->name('review');
            });

            // Cetak Laporan Keseluruhan (Tahap 5)
            Route::prefix('laporan')->name('laporan.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\AdminLaporanController::class, 'index'])->name('index');
                Route::get('/cetak-nilai', [\App\Http\Controllers\Admin\AdminLaporanController::class, 'cetakNilai'])->name('cetak-nilai');
                Route::get('/cetak-inventaris', [\App\Http\Controllers\Admin\AdminLaporanController::class, 'cetakInventaris'])->name('cetak-inventaris');
            });
        });

    // ── Dosen ────────────────────────────────────────────────
    Route::middleware('role:dosen')
        ->prefix('dosen')
        ->name('dosen.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'dosenDashboard'])->name('dashboard');
            
            // Kelas Dosen
            Route::get('/kelas', [\App\Http\Controllers\Dosen\DosenKelasController::class, 'index'])->name('kelas.index');
            Route::get('/kelas/{id}', [\App\Http\Controllers\Dosen\DosenKelasController::class, 'show'])->name('kelas.show');
            Route::post('/kelas/{id}/modul', [\App\Http\Controllers\Dosen\DosenKelasController::class, 'storeModul'])->name('kelas.modul.store');
            Route::delete('/kelas/{id}/modul/{modul_id}', [\App\Http\Controllers\Dosen\DosenKelasController::class, 'destroyModul'])->name('kelas.modul.destroy');

            // Jadwal Pertemuan Kelas
            Route::post('/kelas/{kelas_id}/jadwal', [\App\Http\Controllers\Dosen\DosenJadwalController::class, 'store'])->name('jadwal.store');
            Route::get('/kelas/{kelas_id}/jadwal/{jadwal_id}', [\App\Http\Controllers\Dosen\DosenJadwalController::class, 'show'])->name('jadwal.show');
            Route::post('/kelas/{kelas_id}/jadwal/{jadwal_id}/absen-laboran', [\App\Http\Controllers\Dosen\DosenJadwalController::class, 'absenLaboran'])->name('jadwal.absenLaboran');
        });

    // ── Laboran ──────────────────────────────────────────────
    Route::middleware('role:laboran')
        ->prefix('laboran')
        ->name('laboran.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'laboranDashboard'])->name('dashboard');
            
            // Kelas Laboran
            Route::get('/kelas', [\App\Http\Controllers\Laboran\LaboranKelasController::class, 'index'])->name('kelas.index');
            Route::get('/kelas/{id}', [\App\Http\Controllers\Laboran\LaboranKelasController::class, 'show'])->name('kelas.show');

            // Barang / Data Lab Laboran (Read Only)
            Route::prefix('barang')->name('barang.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Laboran\LaboranBarangController::class, 'index'])->name('index');
            });

            Route::prefix('ruangan')->name('ruangan.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Laboran\LaboranRuanganController::class, 'index'])->name('index');
                Route::post('/', [\App\Http\Controllers\Laboran\LaboranRuanganController::class, 'store'])->name('store');
            });

            Route::prefix('kategori')->name('kategori.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Laboran\LaboranKategoriController::class, 'index'])->name('index');
                Route::post('/', [\App\Http\Controllers\Laboran\LaboranKategoriController::class, 'store'])->name('store');
            });

            // Jadwal Pertemuan Kelas
            Route::post('/kelas/{kelas_id}/jadwal', [\App\Http\Controllers\Laboran\LaboranJadwalController::class, 'store'])->name('jadwal.store');
            Route::get('/kelas/{kelas_id}/jadwal/{jadwal_id}', [\App\Http\Controllers\Laboran\LaboranJadwalController::class, 'show'])->name('jadwal.show');
            Route::post('/kelas/{kelas_id}/jadwal/{jadwal_id}/absen-mahasiswa', [\App\Http\Controllers\Laboran\LaboranJadwalController::class, 'absenMahasiswaBulk'])->name('jadwal.absenMahasiswa');

            // Laporan Kerusakan Barang
            Route::get('/laporan', [\App\Http\Controllers\Laboran\LaboranLaporanController::class, 'index'])->name('laporan.index');
            Route::post('/jadwal/{jadwal_id}/laporan', [\App\Http\Controllers\Laboran\LaboranLaporanController::class, 'store'])->name('laporan.store');

            // Tugas & Laporan Kelas
            Route::get('/kelas/{kelas_id}/tugas/create', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'create'])->name('tugas.create');
            Route::post('/kelas/{kelas_id}/tugas', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'store'])->name('tugas.store');
            Route::get('/kelas/{kelas_id}/tugas/{tugas_id}/edit', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'edit'])->name('tugas.edit');
            Route::put('/kelas/{kelas_id}/tugas/{tugas_id}', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'update'])->name('tugas.update');
            
            // Penilaian Tugas
            Route::get('/kelas/{kelas_id}/tugas/{tugas_id}/submissions', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'submissions'])->name('tugas.submissions');
            Route::post('/kelas/{kelas_id}/tugas/{tugas_id}/grade/{mahasiswa_id}', [\App\Http\Controllers\Laboran\LaboranTugasController::class, 'grade'])->name('tugas.grade');
        });

    // ── Mahasiswa ────────────────────────────────────────────
    Route::middleware('role:mahasiswa')
        ->prefix('mahasiswa')
        ->name('mahasiswa.')
        ->group(function () {

            // Lengkapi Profil (tanpa middleware profile.completed agar bisa diakses)
            Route::get('/profile/complete', [MahasiswaProfileController::class, 'showCompleteForm'])->name('profile.complete');
            Route::post('/profile/complete', [MahasiswaProfileController::class, 'completeProfile'])->name('profile.complete.process');

            // Route yang memerlukan profil sudah lengkap
            Route::middleware('profile.completed')->group(function () {
                Route::get('/dashboard', [DashboardController::class, 'mahasiswaDashboard'])->name('dashboard');

                // Enrollment Kelas Praktikum
                Route::get('/kelas', [EnrollmentController::class, 'index'])->name('kelas.index');
                Route::post('/kelas/{kelasPraktikum}/apply', [EnrollmentController::class, 'apply'])->name('kelas.apply');
                Route::delete('/kelas/{kelasPraktikum}/cancel', [EnrollmentController::class, 'cancel'])->name('kelas.cancel');

                // Fitur Utama Mahasiswa
                Route::get('/myclass', [\App\Http\Controllers\Mahasiswa\MahasiswaKelasController::class, 'myClass'])->name('myclass');
                Route::get('/myclass/{id}', [\App\Http\Controllers\Mahasiswa\MahasiswaKelasController::class, 'detailClass'])->name('kelas.detail');
                Route::get('/tugas', [\App\Http\Controllers\Mahasiswa\MahasiswaTugasController::class, 'index'])->name('tugas.index');
                
                // Upload Tugas Mahasiswa
                Route::get('/kelas/{kelas_id}/tugas/{tugas_id}', [\App\Http\Controllers\Mahasiswa\MahasiswaTugasController::class, 'show'])->name('tugas.show');
                Route::post('/kelas/{kelas_id}/tugas/{tugas_id}', [\App\Http\Controllers\Mahasiswa\MahasiswaTugasController::class, 'submit'])->name('tugas.submit');
                
                Route::get('/nilai', [\App\Http\Controllers\Mahasiswa\MahasiswaNilaiController::class, 'index'])->name('nilai.index');
            });
        });
});
