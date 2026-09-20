# Panduan: Cara Menyelesaikan Jadwal dan Membuat Laporan Laboran ke Admin

Berikut adalah langkah-langkah simpel untuk laboran dalam menyelesaikan kelas dan mengirim laporan kerusakan ke admin:

### 1. Jadwal Kelas Selesai Secara Otomatis

Anda tidak perlu menekan tombol "Selesai" secara manual. Sistem akan **otomatis** mengubah status jadwal menjadi "selesai" apabila waktu jam praktikum (berdasarkan `jam_selesai`) sudah terlewati.

- **Catatan:** Pastikan absensi mahasiswa (kehadiran dan keterangan) sudah diisi karena jika waktu praktikum sudah lewat (selesai), keterangan absensi menjadi wajib diisi. [done]

### 2. Membuat Laporan ke Admin

Setelah status jadwal otomatis menjadi "selesai", barulah Anda bisa membuat laporan mengenai kondisi kelas/barang saat praktikum tersebut.

Langkah-langkah membuat laporannya:

1. Buka menu laporan atau halaman detail jadwal yang sudah selesai.
2. Isi form laporan dengan data berikut:
    - **Status SOP:** Pilih apakah SOP (Standar Operasional Prosedur) `dijalankan`, `dijalankan sebagian`, atau `tidak dijalankan`.
    - **Kelayakan Barang:** Pilih apakah `semua layak` atau `ada yang rusak`.
    - **Catatan Temuan (Opsional):** Jika Anda memilih _ada yang rusak_, Anda **wajib** mengisi kolom catatan temuan untuk menjelaskan barang apa saja yang rusak.
3. Klik tombol kirim/simpan.

Setelah berhasil disimpan, laporan akan langsung terkirim dengan status **"pending"** dan siap untuk di-review oleh Admin. Laporan untuk satu jadwal yang sama tidak dapat dibuat berulang kali (hanya bisa 1 kali per pertemuan).

> **Letak Perbaikan/Implementasi:**
>
> - **Database/Model:** Tabel `laporan_laborans` perlu diatur agar mencakup field `status_sop`, `kelayakan_barang`, `catatan_temuan`, `status` (pending/reviewed), dan `jadwal_id`.
> - **Controller:** `LaboranJadwalController` atau controller khusus `LaboranLaporanController` bertugas memvalidasi bahwa laporan hanya bisa di-_submit_ 1 kali per jadwal, dan hanya jika jadwal sudah selesai.
> - **View:** Halaman detail jadwal `resources/views/pages/laboran/jadwal/show.blade.php` ditambahkan komponen modal atau form pelaporan jika jadwal telah usai.

---

## Saran dan Masalah dari Dosen

### 1. Pembuatan quotes singkat bernuansa Islami di beberapa bagian dalam bentuk modal close [done]

- **Deskripsi:** Dosen ingin menyisipkan nilai-nilai Islami secara interaktif yang muncul dalam pop-up modal dan dapat ditutup.
- **Letak Perbaikan:**
    - **View (Komponen):** Buat komponen _blade_ baru `resources/views/components/islamic-quotes-modal.blade.php`.
    - **View (Layout):** Panggil komponen ini di `app-mahasiswa.blade.php`, `app-dosen.blade.php`, atau `app-laboran.blade.php`.
    - **Script (JS/jQuery):** Tambahkan logika menggunakan _session storage_ agar pop-up hanya muncul sesekali (misal saat baru login) dan tidak terus-terusan mengganggu ketika di-refresh.

### 2. Penfilteran Laporan Inventaris [done]

http://127.0.0.1:8000/admin/laporan

- **Deskripsi:** Butuh fitur cetak laporan inventaris yang lebih detail, serta kemampuan filter laporan berdasar: Status (pending/selesai), Tanggal, dan Ruangan.
- **Letak Perbaikan:**
    - **Controller:** Modifikasi method _index_ pada `AdminLaporanController` atau `AdminRuanganController` dengan menggunakan _query scope_ (seperti `when()`, `where()`) untuk memfilter request parameter `$request->tanggal`, `$request->ruangan`, dll. Tambahkan method untuk fungsi cetak `cetakLaporan()`.
    - **View:** Pada _blade_ daftar laporan admin, pasang _dropdown_ filter dan hubungkan dengan **AJAX/jQuery** agar data tabel ter-_update_ asinkron tanpa men-_download_ seluruh halaman ulang. Sediakan tombol cetak (mengarah ke layout PDF / cetak HTML).

### 3. Edit Profile (Semua Role) Bermasalah [done]

- **Deskripsi:** Terdapat kendala (`error` atau tidak tersimpan) saat pengguna (semua role) mengedit profil mereka di URL `/profile`.
- **Letak Perbaikan:**
    - **Route:** Cek `routes/web.php` pastikan _method_ untuk POST/PUT di `/profile` sudah benar.
    - **Controller:** Periksa `ProfileController@update`. Validasi form (seperti _unique email_, _password matching_) mungkin menyebabkan kegagalan. Pastikan instruksi _update database_ (seperti `User::find(auth()->id())->update()`) berfungsi.
    - **View:** `resources/views/pages/profile.blade.php` harus menggunakan CSRF token (`@csrf`) dan form method _spoofing_ (`@method('PUT')`). Tangkap dan tampilkan pesan _error validation_ dengan spesifik.

### 4. Laporan Keseluruhan (Dosen dan Laboran) [done]

- **Deskripsi:** Adanya kebutuhan menu untuk merangkum seluruh jalannya kegiatan praktikum (jadwal, absen, nilai) selama satu semester.
- **Letak Perbaikan:**
    - **Controller:** Tambahkan method rekapitulasi data (aggregasi `KelasPraktikum` beserta relasinya) pada `DosenKelasController` & `LaboranKelasController`, atau buat _controller_ pelaporan tersendiri.
    - **View:** Buat _blade_ _view_ baru berupa Dashboard Rekap atau Cetak Laporan (misal `laporan-keseluruhan.blade.php`)

### 5. (http://127.0.0.1:8000/laboran/rekap/1) [done]

      - laporan Rekap Absensi Mahasiswa dalam bentuk excel [done]
      - laporan Rekap Nilai Tugas mahasiswa dalam bentuk excel [done]
      - Cetak Dokumen Rekap Halaman Mandiri (Standalone Print View & Filter Tampilan) [done]
      - Unduh Berkas PDF Rekap Formal Akademik DomPDF (.pdf) [done]

