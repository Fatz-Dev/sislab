# Struktur Database Sislab Fisika Lengkap (Relasi & Skema)

## Tabel: `users`

**Deskripsi:** Menyimpan data kredensial (autentikasi) dasar untuk semua pengguna.

**Relasi Utama:**
<ul><li><b>hasOne:</b> mahasiswas, dosens, laborans</li><li><b>hasMany:</b> Berperan sebagai Foreign Key di berbagai tabel (created_by, user_id).</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `name` | varchar | Tidak |  | NULL |
| `email` | varchar | Tidak |  | NULL |
| `role` | varchar | Tidak |  | NULL |
| `phone` | varchar | Ya |  | NULL |
| `photo` | varchar | Ya |  | NULL |
| `is_active` | tinyint(1) | Tidak |  | '1' |
| `is_profile_completed` | tinyint(1) | Tidak |  | '1' |
| `email_verified_at` | datetime | Ya |  | NULL |
| `password` | varchar | Tidak |  | NULL |
| `remember_token` | varchar | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `kategori_barangs`

**Deskripsi:** Data master kategori alat/bahan praktikum (contoh: Kaca, Logam).

**Relasi Utama:**
<ul><li><b>hasMany:</b> barang_inventaris</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `nama_kategori` | varchar | Tidak |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `ruangans`

**Deskripsi:** Data master lokasi ruangan laboratorium fisik.

**Relasi Utama:**
<ul><li><b>hasMany:</b> jadwals, barang_inventaris</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `nama_ruangan` | varchar | Tidak |  | NULL |
| `deskripsi` | TEXT | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `semesters`

**Deskripsi:** Data master untuk tahun akademik dan semester berjalan.

**Relasi Utama:**
<ul><li><b>hasMany:</b> kelas_praktikums</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `nama_semester` | varchar | Tidak |  | NULL |
| `is_active` | tinyint(1) | Tidak |  | '0' |
| `is_enrollment_open` | tinyint(1) | Tidak |  | '1' |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `master_sops`

**Deskripsi:** Data master referensi poin-poin standar operasional (SOP).

**Relasi Utama:**
<ul><li><b>hasMany:</b> sop_checklists</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `item_checklist` | varchar | Tidak |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `kelas_praktikums`

**Deskripsi:** Data inti manajemen kelas praktikum yang dibuka pada semester tertentu.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> semesters, users (dosen_id, laboran_id)</li><li><b>hasMany:</b> jadwals, tugas_laporans, nilais</li><li><b>belongsToMany:</b> users (mahasiswa), barang_inventaris</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `nama_kelas` | varchar | Tidak |  | NULL |
| `semester_id` | INTEGER | Tidak |  | NULL |
| `kapasitas` | INTEGER | Tidak |  | '30' |
| `status` | varchar | Tidak |  | 'draft' |
| `dosen_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `created_by` | INTEGER | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |
| `deleted_at` | datetime | Ya |  | NULL |

---

## Tabel: `jadwals`

**Deskripsi:** Jadwal tatap muka atau sesi spesifik dari sebuah kelas praktikum.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kelas_praktikums, ruangans</li><li><b>hasOne:</b> laporan_laborans</li><li><b>hasMany:</b> absensis</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `tanggal` | date | Tidak |  | NULL |
| `jam_mulai` | time | Tidak |  | NULL |
| `jam_selesai` | time | Tidak |  | NULL |
| `ruangan_id` | INTEGER | Tidak |  | NULL |
| `topik` | varchar | Ya |  | NULL |
| `status` | varchar | Tidak |  | 'terjadwal' |
| `created_by` | INTEGER | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `barang_inventaris`

**Deskripsi:** Master stok dan inventori semua alat/barang yang ada di lab.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kategori_barangs, ruangans</li><li><b>hasMany:</b> penggunaan_barangs, maintenance_logs</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kode_barang` | varchar | Tidak |  | NULL |
| `nama_barang` | varchar | Tidak |  | NULL |
| `foto_barang` | varchar | Ya |  | NULL |
| `kategori_id` | INTEGER | Tidak |  | NULL |
| `jumlah` | INTEGER | Tidak |  | '1' |
| `kondisi` | varchar | Tidak |  | 'baik' |
| `ruangan_id` | INTEGER | Ya |  | NULL |
| `tanggal_pengadaan` | date | Ya |  | NULL |
| `keterangan` | TEXT | Ya |  | NULL |
| `created_by` | INTEGER | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |
| `deleted_at` | datetime | Ya |  | NULL |

---

## Tabel: `maintenance_logs`

**Deskripsi:** Log kerusakan dan riwayat perbaikan barang inventaris.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> barang_inventaris, users (laboran_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `barang_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `tanggal` | date | Tidak |  | NULL |
| `deskripsi` | TEXT | Tidak |  | NULL |
| `status` | varchar | Tidak |  | 'dilaporkan' |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `sop_checklists`

**Deskripsi:** Log detail poin SOP yang dijalankan pada suatu jadwal oleh laboran.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kelas_praktikums, users (laboran_id), master_sops</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `tanggal` | date | Tidak |  | NULL |
| `master_sop_id` | INTEGER | Tidak |  | NULL |
| `status` | tinyint(1) | Tidak |  | '0' |
| `catatan` | TEXT | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `pengumumen`

**Deskripsi:** Papan pengumuman global (broadcast) yang ditampilkan di dashboard.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> users (admin_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `judul` | varchar | Tidak |  | NULL |
| `isi` | TEXT | Tidak |  | NULL |
| `target_role` | TEXT | Tidak |  | NULL |
| `tanggal_publish` | datetime | Ya |  | NULL |
| `admin_id` | INTEGER | Tidak |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `modul_praktikums`

**Deskripsi:** Modul PDF atau panduan yang dilampirkan ke dalam suatu kelas.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kelas_praktikums, users (uploaded_by)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `judul` | varchar | Tidak |  | NULL |
| `file_pdf` | varchar | Tidak |  | NULL |
| `uploaded_by` | INTEGER | Tidak |  | NULL |
| `tanggal_upload` | datetime | Tidak |  | CURRENT_TIMESTAMP |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `tugas_laporans`

**Deskripsi:** Tugas akhir atau laporan praktikum yang ditugaskan di suatu kelas.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kelas_praktikums, users (laboran_id)</li><li><b>hasMany:</b> submission_laporans</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `judul` | varchar | Tidak |  | NULL |
| `deskripsi` | TEXT | Ya |  | NULL |
| `deadline` | datetime | Tidak |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `submission_laporans`

**Deskripsi:** Pengumpulan tugas oleh mahasiswa.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> tugas_laporans, users (mahasiswa_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `tugas_laporan_id` | INTEGER | Tidak |  | NULL |
| `mahasiswa_id` | INTEGER | Tidak |  | NULL |
| `file_laporan` | varchar | Tidak |  | NULL |
| `tanggal_submit` | datetime | Tidak |  | CURRENT_TIMESTAMP |
| `status` | varchar | Tidak |  | 'tepat_waktu' |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `nilais`

**Deskripsi:** Sistem pencatatan nilai mahasiswa pada suatu kelas/tugas.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> users (mahasiswa_id), kelas_praktikums, tugas_laporans</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `mahasiswa_id` | INTEGER | Tidak |  | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `tugas_laporan_id` | INTEGER | Ya |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `nilai` | numeric | Tidak |  | NULL |
| `keterangan` | TEXT | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |
| `deleted_at` | datetime | Ya |  | NULL |

---

## Tabel: `absensis`

**Deskripsi:** Rekapitulasi kehadiran peserta kelas per jadwal.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> kelas_praktikums, jadwals, users (user_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `jadwal_id` | INTEGER | Ya |  | NULL |
| `tanggal` | date | Tidak |  | NULL |
| `tipe` | varchar | Tidak |  | NULL |
| `user_id` | INTEGER | Tidak |  | NULL |
| `diabsen_oleh` | INTEGER | Ya |  | NULL |
| `status_hadir` | varchar | Tidak |  | 'hadir' |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `kelas_praktikum_mahasiswa`

**Deskripsi:** Tabel pivot untuk pendaftaran mahasiswa ke dalam kelas (menyimpan status approval).

**Relasi Utama:**
Menghubungkan `kelas_praktikums` (kelas_praktikum_id) dan `users` (mahasiswa_id).

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `mahasiswa_id` | INTEGER | Tidak |  | NULL |
| `status` | varchar | Tidak |  | 'pending' |
| `catatan_admin` | TEXT | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `penggunaan_barangs`

**Deskripsi:** Catatan log ketika suatu barang digunakan pada sebuah sesi kelas (jadwal).

**Relasi Utama:**
<ul><li><b>belongsTo:</b> jadwals, barang_inventaris, users (laboran_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `jadwal_id` | INTEGER | Tidak |  | NULL |
| `barang_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `jumlah_digunakan` | INTEGER | Tidak |  | '1' |
| `kondisi_setelah` | varchar | Tidak |  | 'baik' |
| `catatan` | TEXT | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `laporan_laborans`

**Deskripsi:** Laporan sesi praktikum yang disubmit oleh laboran ke admin setiap selesai jadwal.

**Relasi Utama:**
<ul><li><b>belongsTo:</b> jadwals, users (laboran_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `jadwal_id` | INTEGER | Tidak |  | NULL |
| `laboran_id` | INTEGER | Tidak |  | NULL |
| `status_sop` | varchar | Tidak |  | 'dijalankan' |
| `kelayakan_barang` | varchar | Tidak |  | 'semua_layak' |
| `catatan_temuan` | TEXT | Ya |  | NULL |
| `status_admin` | varchar | Tidak |  | 'pending' |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `kelas_praktikum_barang`

**Deskripsi:** Tabel pivot untuk mengalokasikan barang apa saja yang boleh digunakan di suatu kelas praktikum.

**Relasi Utama:**
Menghubungkan `kelas_praktikums` (kelas_praktikum_id) dan `barang_inventaris` (barang_id).

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `kelas_praktikum_id` | INTEGER | Tidak |  | NULL |
| `barang_id` | INTEGER | Tidak |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `mahasiswas`

**Deskripsi:** Tabel profil (TPT Delegation) khusus menyimpan atribut mahasiswa (NIM, jurusan).

**Relasi Utama:**
<ul><li><b>belongsTo:</b> users (via user_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `user_id` | INTEGER | Tidak |  | NULL |
| `nim` | varchar | Tidak |  | NULL |
| `angkatan` | varchar | Ya |  | NULL |
| `jurusan` | varchar | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `dosens`

**Deskripsi:** Tabel profil khusus menyimpan atribut dosen (NIP, jabatan akademik).

**Relasi Utama:**
<ul><li><b>belongsTo:</b> users (via user_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `user_id` | INTEGER | Tidak |  | NULL |
| `nip` | varchar | Tidak |  | NULL |
| `jabatan_akademik` | varchar | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

## Tabel: `laborans`

**Deskripsi:** Tabel profil khusus menyimpan atribut laboran (NIP, spesialisasi lab).

**Relasi Utama:**
<ul><li><b>belongsTo:</b> users (via user_id)</li></ul>

| Kolom | Tipe Data | Nullable | Primary Key | Default |
|---|---|---|---|---|
| `id` | INTEGER | Tidak | PK | NULL |
| `user_id` | INTEGER | Tidak |  | NULL |
| `nip` | varchar | Tidak |  | NULL |
| `spesialisasi_lab` | varchar | Ya |  | NULL |
| `created_at` | datetime | Ya |  | NULL |
| `updated_at` | datetime | Ya |  | NULL |

---

