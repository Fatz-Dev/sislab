# Task List Sistem Informasi Laboratorium Fisika

Berdasarkan _Activity Diagram_ dan spesifikasi kebutuhan aplikasi, berikut adalah daftar perincian fitur-fitur dan status penyelesaiannya dalam sistem.

## ✅ TAHAP 1: Data Master & Inti Sistem (Telah Selesai)

Fitur-fitur ini adalah pondasi sistem yang sudah dirancang dan dapat diakses saat ini.

- [x] **Autentikasi & Manajemen Sesi**: Login/Logout terpusat berdasarkan _Role_ (Admin, Dosen, Laboran, Mahasiswa).
- [x] **Manajemen Profil Pengguna**:
    - [x] Halaman Profil khusus Admin
    - [x] Halaman Profil khusus Dosen
    - [x] Halaman Profil khusus Laboran
    - [x] Halaman Profil khusus Mahasiswa
- [x] **Pengaturan Sistem (Global Settings)**:
    - [x] Pengaturan umum _platform_
    - [x] Form penyiaran Pengumuman oleh Admin
- [x] **Notifikasi Real-time (WebSockets)**:
    - [x] Setup _Laravel Reverb_, _Pusher JS_, dan _Laravel Echo_
    - [x] _Event Broadcasting_ `PengumumanPublished` kepada target spesifik (misal: hanya Dosen, atau semua)
    - [x] _Toast Popup_ notifikasi interaktif yang muncul instan tanpa _reload_
- [x] **Manajemen Ruangan Lab**:
    - [x] _List_ Ruangan
    - [x] Detail Ruangan dengan tampilan _Tab_ (Jadwal, Barang, dll) dengan _styling_ yang sudah disesuaikan (_active_ green-600)
- [x] **Manajemen Barang (Inventaris) Tahap Dasar**:
    - [x] _List_ Barang dan integrasi _Seeder_ (dengan penetapan `ruangan_id` & `tanggal_pengadaan`)
    - [x] Detail Barang (menampilkan riwayat barang, dll)
    - [x] Modifikasi UI Tambah Barang yang lebih masuk akal (fokus penambahan barang baru dengan status Stok Baik, bukan barang rusak)
    - [x] Upload Foto Barang (_Preview_ via modal)

---

## ✅ TAHAP 2: Fase Pengajuan & Manajemen Kelas (Telah Selesai)

Sesuai dengan Bagian A dari _Activity Diagram_.

- [x] **LMS: Katalog Kelas Praktikum**: Fitur mahasiswa dapat melihat kelas praktikum yang aktif/tersedia.
- [x] **LMS: Pengajuan Kelas (Apply)**: Mahasiswa dapat memilih dan "Apply" untuk mendaftar praktikum di suatu kelas.
- [x] **LMS: Approval Admin**:
    - [x] Halaman Admin untuk meninjau permohonan (_review_ kuota & data).
    - [x] Aksi `Terima` / `Tolak` permohonan dari mahasiswa.
    - [x] Notifikasi status penerimaan dikirimkan kepada mahasiswa (bisa via Reverb/Email).
    - [x] Toggle Buka/Tutup Pendaftaran Kelas secara Global (hanya mengizinkan _read-only_ pengajuan berstatus 'menunggu' saat pendaftaran ditutup).
- [x] **Kelola Kelas oleh Dosen**:
    - [x] Dosen melihat daftar kelas yang disetujui.
    - [x] Dosen melihat mahasiswa yang tergabung di dalam kelasnya.
    - [x] Plotting Dosen dan Laboran penanggung jawab per kelas (1 Laboran : 1 Kelas Praktikum).

---

## 🚀 TAHAP 3: Kegiatan Akademik & LMS Praktikum

Sesuai dengan Bagian B dari _Activity Diagram_.

- [x] **Modul Absensi Bertingkat**:
    - [x] Dosen mengabsen kehadiran Laboran.
    - [x] Laboran mengabsen kehadiran Mahasiswa praktikum.
- [x] **Materi & Modul Praktikum**:
    - [x] Dosen/Laboran dapat _upload_ materi/modul pembelajaran praktikum.
    - [x] Mahasiswa dapat men-_download_ modul yang diberikan.
- [x] **Penugasan & Laporan**:
    - [x] Dosen memberikan tugas/instruksi laporan praktikum.
    - [x] Mahasiswa meng-_upload_ jawaban/hasil laporan (PDF/Word).
- [x] **Penilaian Praktikum**:
    - [x] Dosen melakukan rekapitulasi penilaian/memberi nilai (_grading_).
    - [x] Mahasiswa dapat melihat hasil nilai praktikum mereka.

---

## 📦 TAHAP 4: Manajemen Inventaris Lanjutan (Akan Datang)

Sesuaikan dengan Bagian C pada _Activity Diagram_.

- [x] **Laporan Kerusakan & Kondisi Barang (Laboran)**:
    - [x] Laboran melakukan inspeksi inventaris berkala di kelasnya (berdasarkan SOP).
    - [x] Laboran mengirimkan formulir laporan kerusakan alat/barang ke Admin.
- [x] **Tindak Lanjut Admin**:
    - [x] Admin menerima laporan kerusakan dari Laboran.
    - [x] Admin mengubah status kelayakan barang (Rusak Ringan, Berat, Perlu Penggantian).
- [x] **Rekapitulasi Global Barang**:
    - [x] Halaman khusus Admin yang _men-list_ total "Semua Barang" di seluruh laboratorium Fisika dalam satu tampilan terpadu.
- [x] **Import / Export Data (Excel)**:
    - [x] Impor massal data barang dari berkas Excel (menggunakan _template_ yang telah disiapkan).
    - [x] Ekspor data barang ke Excel / PDF untuk rekapitulasi fisik.

---

## 📊 TAHAP 5: Monitoring, Laporan, & Dashboard Tingkat Tinggi (Akan Datang)

Sesuai dengan Bagian D pada _Activity Diagram_.

- [ ] **Dashboard Analytics Admin**:
    - [ ] Statistik aktivitas seluruh kelas (Jumlah mahasiswa aktif, kelas berjalan).
    - [ ] Statistik kesehatan Inventaris (Berapa % alat yang layak, berapa yang rusak).
- [x] **Cetak Laporan Keseluruhan**:
    - [x] Pembuatan Dokumen Cetak/PDF untuk rekapitulasi nilai per semester.
    - [x] Laporan akhir inventaris laboratorium sebagai bahan evaluasi tahunan.
