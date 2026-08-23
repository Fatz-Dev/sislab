# Sislab Fisika - Sistem Informasi Inventaris & LMS Praktikum Laboratorium

Sistem Informasi Laboratorium (SISLAB) Fisika adalah aplikasi berbasis web yang dibangun untuk memanajemen inventaris laboratorium dan Sistem Manajemen Pembelajaran (LMS) sederhana untuk praktikum mahasiswa. Sistem ini memfasilitasi 4 peran utama: **Admin, Dosen, Laboran, dan Mahasiswa**.

## 🚀 Fitur Utama

- **Manajemen Inventaris**: Pengelolaan barang laboratorium (alat/bahan), alokasi ke ruang lab, pelacakan kondisi barang (Baik, Rusak Ringan, Rusak Berat, Hilang), serta laporan inventaris oleh Laboran.
- **LMS Praktikum Sederhana**: Registrasi mahasiswa ke kelas praktikum, pengelolaan modul/materi, absensi, hingga penilaian oleh Dosen dan Laboran.
- **Notifikasi Realtime**: Notifikasi pengumuman dari Admin kepada target *role* spesifik yang muncul secara *realtime* di layar pengguna (menggunakan Laravel Reverb & WebSocket).
- **Manajemen Pengguna & Hak Akses**: Profil pengguna yang disesuaikan untuk Admin, Dosen, Laboran, dan Mahasiswa.
- **Dashboard & Laporan**: Statistik aktivitas kelas, monitoring kondisi inventaris, laporan absensi, dan nilai praktikum.

## 🛠️ Stack Teknologi

Sistem ini dikembangkan menggunakan *stack* teknologi modern:

### Backend
- **Framework**: [Laravel v12.0](https://laravel.com/) (PHP ^8.2)
- **Database**: MySQL / MariaDB
- **WebSockets**: [Laravel Reverb](https://laravel.com/docs/11.x/reverb) (menggantikan Pusher untuk *realtime event broadcasting*)
- **Packages Utama**:
  - `barryvdh/laravel-dompdf`: Ekspor data ke format PDF.
  - `maatwebsite/excel`: Ekspor/Impor data ke format Excel.
  - `yajra/laravel-datatables`: Render tabel dinamis dengan pemrosesan sisi server (Server-Side Processing).
  - `opcodesio/log-viewer`: UI interaktif untuk membaca log error aplikasi.
  - `vish4395/laravel-file-viewer`: Penampil file dokumen terintegrasi.

### Frontend
- **Framework CSS**: [Tailwind CSS v4.0](https://tailwindcss.com/)
- **Build Tool**: [Vite](https://vitejs.dev/)
- **Kalender Interaktif**: `fullcalendar` (Visualisasi jadwal praktikum/kegiatan)
- **Realtime Listener**: `laravel-echo` & `pusher-js`
- **Ikon & Font**: Bootstrap Icons, Google Fonts (Lexend, Inter, Manrope).

## 📂 Struktur Direktori Utama

- `app/`: Berisi logika inti aplikasi.
  - `Http/Controllers`: Controller dibagi berdasarkan hak akses (`Admin`, `Dosen`, `Laboran`, `Mahasiswa`).
  - `Models`: Representasi struktur tabel (seperti `Pengumuman`, `Barang`, `Kelas`, dll).
  - `Events`: Event *broadcasting* (seperti `PengumumanPublished` untuk notifikasi realtime).
- `database/`:
  - `migrations/`: Skema database aplikasi.
  - `seeders/`: Data *dummy* dan data awal (seperti `BarangSeeder`).
- `resources/`:
  - `views/`: *Template* antarmuka (Blade), dibagi dalam direktori per-*role* dan *layout* terpusat.
  - `js/` & `css/`: *File* statis *frontend* yang di-*compile* oleh Vite (termasuk Tailwind v4 dan konfigurasi Echo).
- `routes/web.php`: Definisi rute *endpoint* aplikasi.
- `laporan/`: Kumpulan dokumen spesifikasi, diagram alur, *task list*, dan dokumentasi arsitektur sistem.

## 💻 Instalasi Lokal (Development)

Berikut langkah-langkah untuk menjalankan *project* ini di komputer lokal:

1. **Clone repositori**
2. **Instal dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**
   Salin `.env.example` ke `.env` dan atur koneksi database:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan pengaturan *broadcasting* dikonfigurasi untuk Reverb:
   ```env
   BROADCAST_CONNECTION=reverb
   ```
4. **Migrasi Database & Seeding**
   ```bash
   php artisan migrate:fresh --seed
   ```
5. **Jalankan Aplikasi**
   Karena aplikasi ini menggunakan Vite, Queue, dan Reverb, Anda harus menjalankan *service-service* tersebut. Terdapat script *runner* yang siap pakai:
   ```bash
   composer run dev
   ```
   Atau jalankan secara terpisah:
   - `php artisan serve` (Terminal 1)
   - `npm run dev` (Terminal 2)
   - `php artisan reverb:start` (Terminal 3 - Untuk notifikasi realtime)
   - `php artisan queue:listen` (Terminal 4 - Untuk antrean background jobs)

## 📖 Dokumen Terkait

Dokumentasi rinci terkait proses bisnis dan *deployment* dapat dilihat pada berkas-berkas berikut di direktori `laporan/`:
- `activity-diagram.md`: Penjelasan alur aktivitas sistem dari pendaftaran mahasiswa hingga monitoring admin.
- `task-list-sistem.md`: Status pengerjaan fitur aplikasi.
- `deployment-setup.md`: Panduan instalasi dan *deploy* sistem di server *Shared Hosting* lengkap dengan konfigurasi Reverb (WebSockets).

---
*Dibuat untuk kebutuhan manajemen laboratorium Fisika tingkat perguruan tinggi.*
