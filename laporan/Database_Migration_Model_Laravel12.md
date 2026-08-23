# Database Migration & Model Relasi (Laravel 12)
## Sistem Inventaris & Manajemen Lab Fisika

| | |
|---|---|
| **Framework** | Laravel 12 |
| **Database** | MySQL / MariaDB |
| **Versi Dokumen** | 1.0 |
| **Tanggal** | 7 Agustus 2026 |

---

## 1. Daftar Tabel

| No | Tabel | Deskripsi |
|---|---|---|
| 8 | `laporan_laborans` | Laporan kondisi barang & SOP dari laboran ke admin |
| 9 | `maintenance_logs` | Riwayat maintenance/kerusakan barang |
| 10 | `sop_checklists` | Checklist SOP kebersihan barang oleh laboran |
| 11 | `pengumumans` | Pengumuman dari admin |
| 12 | `modul_praktikums` | Modul praktikum berbentuk PDF |
| 13 | `tugas_laporans` | Tugas laporan praktikum dari laboran |
| 14 | `submission_laporans` | Laporan yang disubmit mahasiswa |
| 15 | `nilais` | Nilai praktikum mahasiswa |
| 16 | `absensis` | Absensi dosen, laboran, dan mahasiswa |

---

## 2. CLI: Generate Semua Model + Migration Sekaligus

Jalankan perintah berikut satu per satu dari root project Laravel 12 (setiap perintah otomatis membuat file model di `app/Models/` dan file migration di `database/migrations/`):

```bash
php artisan make:model KelasPraktikum -m
php artisan make:model Jadwal -m
php artisan make:model BarangInventaris -m
php artisan make:model MaintenanceLog -m
php artisan make:model SopChecklist -m
php artisan make:model Pengumuman -m
php artisan make:model ModulPraktikum -m
php artisan make:model TugasLaporan -m
php artisan make:model SubmissionLaporan -m
php artisan make:model Nilai -m
php artisan make:model Absensi -m
php artisan make:model PenggunaanBarang -m
php artisan make:model LaporanLaboran -m
```

Untuk tabel pivot `kelas_praktikum_mahasiswa` dan `kelas_praktikum_barang` (tanpa model, cukup migration):

```bash
php artisan make:migration create_kelas_praktikum_mahasiswa_table
php artisan make:migration create_kelas_praktikum_barang_table
```

> Catatan: tabel `users` sudah tersedia secara default di Laravel 12 (`0001_01_01_000000_create_users_table.php`). Kita hanya perlu membuat migration tambahan untuk memodifikasi kolomnya.

```bash
php artisan make:migration add_role_and_profile_fields_to_users_table --table=users
```

Setelah seluruh file migration diisi (lihat Bagian 3), jalankan:

```bash
php artisan migrate
```

Jika ingin membuat seeder & factory sekaligus (opsional, untuk data dummy):

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder KelasPraktikumSeeder
php artisan make:factory UserFactory
php artisan make:factory KelasPraktikumFactory
```

---

## 3. Migration per Tabel

### 3.1 `users` (modifikasi tabel bawaan)

**File:** `database/migrations/xxxx_xx_xx_add_role_and_profile_fields_to_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'laboran', 'mahasiswa'])
                  ->after('email')
                  ->index();
            $table->string('nip_nim')->nullable()->unique()->after('role');
            $table->string('phone')->nullable()->after('nip_nim');
            $table->string('photo')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('photo');
            $table->boolean('is_profile_completed')->default(true)->after('is_active');
            // default true agar user yang dibuat admin tidak perlu lengkapi profil
            // mahasiswa self-register akan diset false
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nip_nim', 'phone', 'photo', 'is_active', 'is_profile_completed']);
        });
    }
};
```

---

### 3.1b `semesters`

**File:** `database/migrations/xxxx_xx_xx_create_semesters_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_semester');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_enrollment_open')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
```

---

### 3.2 `kelas_praktikums`

**File:** `database/migrations/xxxx_xx_xx_create_kelas_praktikums_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_praktikums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->unsignedInteger('kapasitas')->default(30);
            $table->enum('status', ['draft', 'open', 'closed'])->default('draft')->index();
            // draft = belum terlihat mahasiswa, open = bisa di-apply, closed = pendaftaran tutup
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('laboran_id')->unique()->constrained('users')->cascadeOnDelete();
            // unique() -> memastikan aturan bisnis 1 laboran hanya 1 kelas aktif
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_praktikums');
    }
};
```

---

### 3.3 `kelas_praktikum_mahasiswa` (pivot)

**File:** `database/migrations/xxxx_xx_xx_create_kelas_praktikum_mahasiswa_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_praktikum_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            // pending = menunggu approval admin
            // approved = diterima, mahasiswa bisa akses kelas
            // rejected = ditolak
            $table->text('catatan_admin')->nullable();
            // catatan dari admin saat approve/reject (terutama alasan penolakan)
            $table->timestamps();

            $table->unique(['kelas_praktikum_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_praktikum_mahasiswa');
    }
};
```

---

### 3.4 `jadwals`

**File:** `database/migrations/xxxx_xx_xx_create_jadwals_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang');
            $table->string('topik')->nullable();
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'jam_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
```

---

### 3.5 `barang_inventaris`

**File:** `database/migrations/xxxx_xx_xx_create_barang_inventaris_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('foto_barang')->nullable();
            $table->string('kategori'); // contoh: alat ukur, kaca, elektronik, bahan habis pakai
            $table->unsignedInteger('jumlah')->default(1);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->string('ruangan')->nullable(); // rak/lemari/ruangan penyimpanan
            $table->date('tanggal_pengadaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_inventaris');
    }
};
```

---

### 3.6 `maintenance_logs`

**File:** `database/migrations/xxxx_xx_xx_create_maintenance_logs_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_inventaris')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai'])->default('dilaporkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
```

---

### 3.7 `sop_checklists`

**File:** `database/migrations/xxxx_xx_xx_create_sop_checklists_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sop_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('item_checklist'); // contoh: "Pembersihan meja optik"
            $table->boolean('status')->default(false); // sudah/belum dikerjakan
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sop_checklists');
    }
};
```

---

### 3.8 `pengumumans`

**File:** `database/migrations/xxxx_xx_xx_create_pengumumans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->json('target_role'); // contoh: ["dosen","mahasiswa"] atau ["all"]
            $table->timestamp('tanggal_publish')->nullable();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
    }
};
```

---

### 3.9 `modul_praktikums`

**File:** `database/migrations/xxxx_xx_xx_create_modul_praktikums_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_praktikums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->string('judul');
            $table->string('file_pdf'); // path penyimpanan file
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_praktikums');
    }
};
```

---

### 3.10 `tugas_laporans`

**File:** `database/migrations/xxxx_xx_xx_create_tugas_laporans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->dateTime('deadline');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_laporans');
    }
};
```

---

### 3.11 `submission_laporans`

**File:** `database/migrations/xxxx_xx_xx_create_submission_laporans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_laporan_id')->constrained('tugas_laporans')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_laporan');
            $table->timestamp('tanggal_submit')->useCurrent();
            $table->enum('status', ['tepat_waktu', 'terlambat'])->default('tepat_waktu');
            $table->timestamps();

            $table->unique(['tugas_laporan_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_laporans');
    }
};
```

---

### 3.12 `nilais`

**File:** `database/migrations/xxxx_xx_xx_create_nilais_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('tugas_laporan_id')->nullable()->constrained('tugas_laporans')->nullOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'tugas_laporan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
```

---

### 3.13 `absensis`

**File:** `database/migrations/xxxx_xx_xx_create_absensis_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->nullOnDelete();
            $table->date('tanggal');
            $table->enum('tipe', ['dosen', 'laboran', 'mahasiswa']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('diabsen_oleh')->nullable()->constrained('users')->nullOnDelete();
            // diabsen_oleh: siapa yang menginput absensi ini (dosen mengabsen laboran, laboran mengabsen mahasiswa, dll)
            $table->enum('status_hadir', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->timestamps();

            $table->unique(['jadwal_id', 'user_id', 'tipe'], 'absensi_unique_per_jadwal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
```

---

### 3.14 `kelas_praktikum_barang` (pivot)

**File:** `database/migrations/xxxx_xx_xx_create_kelas_praktikum_barang_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_praktikum_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang_inventaris')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kelas_praktikum_id', 'barang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_praktikum_barang');
    }
};
```

---

### 3.15 `penggunaan_barangs`

**File:** `database/migrations/xxxx_xx_xx_create_penggunaan_barangs_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggunaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang_inventaris')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_digunakan')->default(1);
            $table->enum('kondisi_setelah', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggunaan_barangs');
    }
};
```

---

### 3.16 `laporan_laborans`

**File:** `database/migrations/xxxx_xx_xx_create_laporan_laborans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_laborans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status_sop', ['terpenuhi', 'sebagian', 'tidak_terpenuhi'])->default('terpenuhi');
            $table->enum('kelayakan_barang', ['layak', 'perlu_perbaikan', 'tidak_layak'])->default('layak');
            $table->text('catatan_temuan')->nullable();
            $table->enum('status_admin', ['menunggu', 'ditindaklanjuti', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_laborans');
    }
};
```

---

## 4. Model & Relasi Eloquent

### 4.1 `app/Models/User.php` (modifikasi)

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nip_nim', 'phone', 'photo', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'is_active'            => 'boolean',
            'is_profile_completed' => 'boolean',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDosen(): bool { return $this->role === 'dosen'; }
    public function isLaboran(): bool { return $this->role === 'laboran'; }
    public function isMahasiswa(): bool { return $this->role === 'mahasiswa'; }
    public function isProfileCompleted(): bool { return (bool) $this->is_profile_completed; }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin'     => 'admin.dashboard',
            'dosen'     => 'dosen.dashboard',
            'laboran'   => 'laboran.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',
            default     => 'login',
        };
    }

    // Relasi jika user adalah Dosen
    public function kelasSebagaiDosen(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'dosen_id');
    }

    // Relasi jika user adalah Laboran (1 laboran : 1 kelas)
    public function kelasSebagaiLaboran()
    {
        return $this->hasOne(KelasPraktikum::class, 'laboran_id');
    }

    // Relasi jika user adalah Mahasiswa (semua enrollment termasuk pending/rejected)
    public function kelasDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(KelasPraktikum::class, 'kelas_praktikum_mahasiswa', 'mahasiswa_id', 'kelas_praktikum_id')
            ->using(KelasPraktikumMahasiswa::class)
            ->withPivot('status', 'catatan_admin')
            ->withTimestamps();
    }

    // Kelas yang sudah di-approve
    public function approvedKelas(): BelongsToMany
    {
        return $this->kelasDiikuti()->wherePivot('status', 'approved');
    }

    // Kelas yang masih pending approval
    public function pendingEnrollments(): BelongsToMany
    {
        return $this->kelasDiikuti()->wherePivot('status', 'pending');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }

    public function submissionLaporans(): HasMany
    {
        return $this->hasMany(SubmissionLaporan::class, 'mahasiswa_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'laboran_id');
    }

    public function pengumumans(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'admin_id');
    }

    public function penggunaanBarangs(): HasMany
    {
        return $this->hasMany(PenggunaanBarang::class, 'laboran_id');
    }

    public function laporanLaborans(): HasMany
    {
        return $this->hasMany(LaporanLaboran::class, 'laboran_id');
    }
}
```

---

### 4.1b `app/Models/Semester.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = ['nama_semester', 'is_active', 'is_enrollment_open'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_enrollment_open' => 'boolean',
    ];

    public function kelasPraktikums(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'semester_id');
    }
}
```

---

### 4.2 `app/Models/KelasPraktikum.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasPraktikum extends Model
{
    protected $fillable = ['nama_kelas', 'semester_id', 'kapasitas', 'status', 'dosen_id', 'laboran_id', 'created_by'];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    // Semua mahasiswa yang terdaftar (termasuk pending/rejected)
    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_praktikum_mahasiswa', 'kelas_praktikum_id', 'mahasiswa_id')
            ->using(KelasPraktikumMahasiswa::class)
            ->withPivot('status', 'catatan_admin')
            ->withTimestamps();
    }

    // Mahasiswa yang sudah di-approve
    public function approvedMahasiswas(): BelongsToMany
    {
        return $this->mahasiswas()->wherePivot('status', 'approved');
    }

    // Mahasiswa yang pending approval
    public function pendingMahasiswas(): BelongsToMany
    {
        return $this->mahasiswas()->wherePivot('status', 'pending');
    }

    public function barangInventaris(): BelongsToMany
    {
        return $this->belongsToMany(BarangInventaris::class, 'kelas_praktikum_barang', 'kelas_praktikum_id', 'barang_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kelas_praktikum_id');
    }

    public function modulPraktikums(): HasMany
    {
        return $this->hasMany(ModulPraktikum::class, 'kelas_praktikum_id');
    }

    public function tugasLaporans(): HasMany
    {
        return $this->hasMany(TugasLaporan::class, 'kelas_praktikum_id');
    }

    public function sopChecklists(): HasMany
    {
        return $this->hasMany(SopChecklist::class, 'kelas_praktikum_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'kelas_praktikum_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'kelas_praktikum_id');
    }

    public function laporanLaborans(): HasMany
    {
        return $this->hasMany(LaporanLaboran::class, 'kelas_praktikum_id');
    }
}
```

---

### 4.3 `app/Models/Jadwal.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $fillable = [
        'kelas_praktikum_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'ruang', 'topik', 'status', 'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }
}
```

---

### 4.4 `app/Models/BarangInventaris.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangInventaris extends Model
{
    protected $table = 'barang_inventaris';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'foto_barang', 'kategori', 'jumlah',
        'kondisi', 'ruangan', 'tanggal_pengadaan', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'barang_id');
    }

    public function kelasPraktikums(): BelongsToMany
    {
        return $this->belongsToMany(KelasPraktikum::class, 'kelas_praktikum_barang', 'barang_id', 'kelas_praktikum_id');
    }

    public function penggunaanBarangs(): HasMany
    {
        return $this->hasMany(PenggunaanBarang::class, 'barang_id');
    }
}
```

---

### 4.5 `app/Models/MaintenanceLog.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = ['barang_id', 'laboran_id', 'tanggal', 'deskripsi', 'status'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class, 'barang_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
```

---

### 4.6 `app/Models/SopChecklist.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SopChecklist extends Model
{
    protected $fillable = [
        'kelas_praktikum_id', 'laboran_id', 'tanggal',
        'item_checklist', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'boolean',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
```

---

### 4.7 `app/Models/Pengumuman.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $fillable = ['judul', 'isi', 'target_role', 'tanggal_publish', 'admin_id'];

    protected $casts = [
        'target_role' => 'array',
        'tanggal_publish' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
```

---

### 4.8 `app/Models/ModulPraktikum.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulPraktikum extends Model
{
    protected $fillable = ['kelas_praktikum_id', 'judul', 'file_pdf', 'uploaded_by', 'tanggal_upload'];

    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

---

### 4.9 `app/Models/TugasLaporan.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasLaporan extends Model
{
    protected $fillable = ['kelas_praktikum_id', 'laboran_id', 'judul', 'deskripsi', 'deadline'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    public function submissionLaporans(): HasMany
    {
        return $this->hasMany(SubmissionLaporan::class, 'tugas_laporan_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'tugas_laporan_id');
    }
}
```

---

### 4.10 `app/Models/SubmissionLaporan.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionLaporan extends Model
{
    protected $fillable = ['tugas_laporan_id', 'mahasiswa_id', 'file_laporan', 'tanggal_submit', 'status'];

    protected $casts = [
        'tanggal_submit' => 'datetime',
    ];

    public function tugasLaporan(): BelongsTo
    {
        return $this->belongsTo(TugasLaporan::class, 'tugas_laporan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
```

---

### 4.11 `app/Models/Nilai.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $table = 'nilais';

    protected $fillable = [
        'mahasiswa_id', 'kelas_praktikum_id', 'tugas_laporan_id',
        'laboran_id', 'nilai', 'keterangan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function tugasLaporan(): BelongsTo
    {
        return $this->belongsTo(TugasLaporan::class, 'tugas_laporan_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
```

---

### 4.12 `app/Models/Absensi.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'kelas_praktikum_id', 'jadwal_id', 'tanggal', 'tipe',
        'user_id', 'diabsen_oleh', 'status_hadir',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function diabsenOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diabsen_oleh');
    }
}
```

---

### 4.14 `app/Models/PenggunaanBarang.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanBarang extends Model
{
    protected $fillable = [
        'jadwal_id', 'barang_id', 'laboran_id', 
        'jumlah_digunakan', 'kondisi_setelah', 'catatan'
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class, 'barang_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
```

---

### 4.15 `app/Models/LaporanLaboran.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanLaboran extends Model
{
    protected $fillable = [
        'kelas_praktikum_id', 'laboran_id', 'tanggal', 
        'status_sop', 'kelayakan_barang', 'catatan_temuan', 'status_admin'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
```

---

## 5. Ringkasan Relasi Antar Tabel (ERD Naratif)

```
users (1) ────< kelas_praktikums (dosen_id)
users (1) ─── 1 kelas_praktikums (laboran_id)
users (M) ─── M kelas_praktikums  (melalui kelas_praktikum_mahasiswa)

kelas_praktikums (M) ─── M barang_inventaris (melalui kelas_praktikum_barang)

kelas_praktikums (1) ────< jadwals
kelas_praktikums (1) ────< modul_praktikums
kelas_praktikums (1) ────< tugas_laporans
kelas_praktikums (1) ────< sop_checklists
kelas_praktikums (1) ────< nilais
kelas_praktikums (1) ────< absensis
kelas_praktikums (1) ────< laporan_laborans >──── (1) users [laboran]

barang_inventaris (1) ────< maintenance_logs >──── (1) users [laboran]

tugas_laporans (1) ────< submission_laporans >──── (1) users [mahasiswa]
tugas_laporans (1) ────< nilais

jadwals (1) ────< absensis
jadwals (1) ────< penggunaan_barangs >──── (1) barang_inventaris
penggunaan_barangs >──── (1) users [laboran]

pengumumans >──── (1) users [admin]
```

---

## 6. Urutan Eksekusi Migration (Penting)

Agar foreign key tidak error, jalankan migration dengan urutan berikut (Laravel akan otomatis mengurutkan berdasarkan timestamp nama file, pastikan penamaan file dibuat sesuai urutan ini):

1. `users` (bawaan) + modifikasi role
2. `kelas_praktikums`
3. `kelas_praktikum_mahasiswa`
4. `jadwals`
5. `barang_inventaris`
6. `kelas_praktikum_barang`
7. `penggunaan_barangs`
8. `laporan_laborans`
9. `maintenance_logs`
10. `sop_checklists`
11. `pengumumans`
12. `modul_praktikums`
13. `tugas_laporans`
14. `submission_laporans`
15. `nilais`
16. `absensis`

Jalankan migrasi:

```bash
php artisan migrate
```

Rollback semua (jika perlu):

```bash
php artisan migrate:rollback
```

Fresh migrate (drop semua tabel lalu migrate ulang — hati-hati, data akan hilang):

```bash
php artisan migrate:fresh
```

---

## 7. Catatan Tambahan

- Kolom `role` pada tabel `users` menggunakan `enum`; alternatif lebih fleksibel adalah membuat tabel `roles` terpisah + `role_user` pivot jika ke depan dibutuhkan multi-role per user.
- Constraint `unique` pada `laboran_id` di `kelas_praktikums` merepresentasikan aturan bisnis **1 laboran hanya menangani 1 kelas**. Jika suatu saat aturan berubah (1 laboran bisa pegang beberapa kelas di semester berbeda), constraint ini perlu diubah menjadi composite unique `[laboran_id, semester]`.
- File upload (`file_pdf`, `file_laporan`, `photo`) sebaiknya disimpan menggunakan Laravel Storage (disk `public` atau cloud seperti S3), migration hanya menyimpan path/nama filenya.
- Sebaiknya tambahkan `SoftDeletes` (`$table->softDeletes()`) pada tabel penting seperti `barang_inventaris`, `nilais`, dan `kelas_praktikums` agar data tidak hilang permanen saat dihapus.

### 7.1 Kolom Baru (Update 14 Agustus 2026)

**Tabel `users` — Kolom `is_profile_completed`:**
- Tipe: `boolean`, default `true`.
- Digunakan untuk memaksa mahasiswa yang self-register menyelesaikan profil (NIM, phone, foto) sebelum bisa mengakses fitur lain.
- User yang dibuat oleh admin mendapat default `true` (profil dianggap lengkap).
- Mahasiswa yang self-register diset `false`, lalu diubah ke `true` setelah mengisi profil.

**Tabel `kelas_praktikums` — Kolom `status`:**
- Tipe: `enum('draft', 'open', 'closed')`, default `draft`.
- `draft`: kelas belum terlihat oleh mahasiswa.
- `open`: kelas bisa di-apply oleh mahasiswa.
- `closed`: pendaftaran ditutup.
- Admin mengontrol visibility kelas melalui kolom ini.

**Tabel `kelas_praktikum_mahasiswa` — Kolom `status` & `catatan_admin`:**
- `status`: `enum('pending', 'approved', 'rejected')`, default `pending`.
- `catatan_admin`: `text`, nullable. Diisi admin saat approve/reject (terutama alasan penolakan).
- Tabel ini sekarang memiliki Pivot Model: `App\Models\KelasPraktikumMahasiswa` (extends `Illuminate\Database\Eloquent\Relations\Pivot`).

### 7.2 Alur Enrollment Mahasiswa

```
Register → Lengkapi Profil → Lihat Daftar Kelas (open) → Apply → Pending
→ Admin Approve/Reject → Kelas Terbuka (jika approved)
```

- Hanya mahasiswa yang bisa self-register.
- Mahasiswa boleh apply ke lebih dari satu kelas selama kapasitas tersedia dan kelas berada di semester aktif.
- Admin melihat daftar pendaftaran pending dan bisa approve/reject dengan catatan.
