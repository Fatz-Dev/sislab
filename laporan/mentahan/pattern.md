# Laporan Pola Arsitektur Project (devapi_idc)

Dokumen ini mencatat pola-pola utama yang digunakan dalam pengembangan project **devapi_idc** untuk memastikan konsistensi dan kemudahan pengembangan berkelanjutan.

## 1. Frontend (resources/js)

Project ini menggunakan **React** dengan **Inertia.js** sebagai jembatan komunikasi antara backend Laravel dan frontend React.

- **Pages**: Terletak di `resources/js/Pages/`. Struktur folder di sini mencerminkan peran pengguna (misal: `Admin/`, `Mahasiswa/`, `Supervisor/`).
- **Components**: Terletak di `resources/js/Components/`. Mengutamakan penggunaan komponen UI yang dapat digunakan kembali (_reusable components_) untuk menjaga konsistensi visual.
- **Styling**: Menggunakan **Tailwind CSS** untuk utility-first styling dan **Material UI (MUI)** untuk komponen UI yang lebih kompleks (seperti DataGrid atau DatePickers).
- **Standardized Form Fields (Notched Label)**: Untuk halaman detail dan edit, gunakan pola input dengan label yang berada di atas border (notched label) menggunakan Tailwind CSS (contoh: `DetailAdminSekolah.jsx`, `DetailAdminKecamatan.jsx`, `SupervisorKPM/Edit.jsx`). Ini memberikan kesan modern dan konsisten di seluruh modul admin.
- **Standardized List Pages (Layout Migration)**: Semua halaman daftar (list view) harus menggunakan `AdminLayout` dari `@/Layouts/Admin/AdminLayout`. Hindari penggunaan layout manual atau komponen sidebar/header versi lama (`@/Components/admin/`). Gunakan `PageBreadcrumb` untuk konsistensi navigasi dan bungkus konten utama dalam kartu (card) bergaya modern dengan padding `p-4 lg:p-6`.

## 2. Controller & Routing

- **Controllers**: Terletak di `app/Http/Controllers/`. Mengadopsi pemisahan berdasarkan peran pengguna (Modular Role-based). Setiap modul memiliki folder sendiri (misal: `app/Http/Controllers/Admin/`).
- **Web Routes (`routes/web.php`)**: Digunakan untuk rute utama aplikasi yang merender halaman via Inertia. Rute dikelompokkan menggunakan middleware berdasarkan peran pengguna.
- **API Routes (`routes/api.php`)**: Digunakan untuk operasi data asinkron atau integrasi sistem lain, diamankan menggunakan **Laravel Sanctum**.

## 3. Database & Model

- **Migrations**: Mengikuti penamaan tabel yang deskriptif. Terdapat skema relasi antara Mahasiswa, Lowongan (PPL/KPM), dan Sertifikat.
- **Models**: Terletak di `app/Models/`. Setiap model merepresentasikan tabel database dengan relasi Eloquent yang didefinisikan dengan jelas (misal: `User` memiliki relasi `hasOne` ke `Mahasiswa`).
- **Seeders**: Digunakan untuk menyiapkan data awal untuk pengujian. Polanya adalah menyertakan setidaknya satu akun representatif untuk setiap peran penting (Admin, Student, Supervisor).

## 4. Teknologi Utama

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: React 18, Vite, Inertia.js
- **UI Stack**: Tailwind CSS v3, Material UI (MUI), Lucide React/Heroicons
- **Laporan**: Barryvdh DomPDF, Maatwebsite Excel

## 5. Instruksi Khusus Pengembangan (AI Directives)

- **PERINGATAN KERAS UNTUK DATABASE**: AI **DILARANG KERAS** mengedit, memodifikasi, atau menghapus file apa pun yang sudah ada di dalam folder `database/` (termasuk file migrations, seeders, maupun factories).
- **KONFIRMASI WAJIB**: Jika dalam pengembangan terdapat kebutuhan mutlak untuk melakukan perubahan struktur database, penambahan tabel baru, atau penambahan seeder, AI **DIWAJIBKAN** untuk mengajukan konfirmasi dan meminta persetujuan eksplisit kepada pengguna terlebih dahulu sebelum membuat file baru atau mengeksekusi perintah apapun. File migrasi/seeder lama yang sudah ada sama sekali tidak boleh disentuh/diedit.
- **DOKUMENTASI PERUBAHAN WAJIB**: Setiap kali AI melakukan perubahan pada code apapun (frontend, backend, routing, dll), AI **WAJIB** mencatat dokumentasi perubahan tersebut beserta listkan file apa saja yang diubah dari line berapa sampai berapa dan deskripsi perubahannya ke dalam file `laporan/log_perubahan_code.md` dengan format berikut:
    ```
    tanggal/bulan/tahun
    1. [path/nama file]: [deskripsi perubahan pertama] (line: jumlah baris yang diubah)
    2. [path/nama file]: [deskripsi perubahan kedua] (line: jumlah baris yang diubah)
    3. [path/nama file]: [deskripsi perubahan ketiga] (line: jumlah baris yang diubah)
    ... dan seterusnya
    ```

- **DOKUMENTASI PERUBAHAN DATABASE (WAJIB)**: Setiap perubahan yang menyentuh aspek **Database** (Migration, Seeder, Model, Controller terkait DB, dan Routing), AI **WAJIB** mencatat dokumentasi tersebut ke dalam file `laporan/log_perubahan_database.md`. Laporan harus merinci setiap komponen yang terdampak dengan format sebagai berikut:
    ```
    Tanggal/Bulan/Tahun
    1. [Path/Nama File] (Database/Table Context):
       - [Perubahan Utama]: Deskripsi ringkas tujuan perubahan (line: X-Y)
       - [Detail Kolom]: List kolom yang ditambah/diubah/dihapus secara spesifik
       - [Controller]: Perubahan logika backend terkait database (line: X-Y)
       - [Seeder/Model]: Penambahan data dummy atau perubahan relasi (line: X-Y)
       - [Route]: Penambahan atau modifikasi endpoint database (line: X-Y)

    2. ... (dan seterusnya)
    ```
- **PROTOKOL KONFIRMASI & INTEGRITAS DATABASE**: AI **DILARANG KERAS** menyentuh atau mengedit file Migration dan Seeder lama yang sudah ada. Jika diperlukan perubahan struktur atau penambahan data, AI **WAJIB** meminta izin eksplisit dari pengguna terlebih dahulu. Setelah disetujui, AI harus membuat file Migration atau Seeder **baru** untuk menjaga integritas riwayat database.
    

- **INTEGRITAS DATA & ANTI-FALLBACK**: AI **DILARANG** menggunakan string fallback statis (seperti `user?.username ? "User"`, `"Guest"`, atau placeholder manual lainnya) untuk menutupi data yang hilang/kosong dari database. Jika data tidak muncul, AI **WAJIB** menelusuri akar permasalahannya (pada Controller, Model, atau Query) dan memberikan solusi agar data asli dari database dapat ditampilkan dengan benar tanpa manipulasi teks statis di frontend.
- **PENCEGAHAN REGRESI & ANALISIS DAMPAK**: Saat melakukan perbaikan atau penambahan fitur, AI **WAJIB** memastikan stabilitas fitur lain yang sudah ada. AI harus melakukan analisis dampak (_impact analysis_) terlebih dahulu untuk menjamin bahwa kode baru tidak merusak (_break_) fungsionalitas yang sudah berjalan. Menjaga sistem tetap berfungsi secara keseluruhan lebih diutamakan daripada sekadar menyelesaikan satu fitur baru.