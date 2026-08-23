# Penjelasan Activity Diagram - Inventaris dan LMS Praktikum Sederhana

Dokumen ini menguraikan diagram aktivitas sistem informasi Manajemen Inventaris dan LMS Praktikum Laboratorium Fisika berdasarkan gambar `Activity-Diagram.png`. Diagram ini terbagi menjadi 4 fase utama yang berkesinambungan dan melibatkan 4 aktor/pengguna (Mahasiswa, Admin, Dosen, Laboran) serta 1 *lane* Sistem.

## A. Registrasi & Pengajuan Kelas oleh Mahasiswa

Fase ini menjelaskan alur awal bagaimana mahasiswa dapat mengikuti praktikum di laboratorium:

1. **Setup Awal oleh Admin (Sistem)**: Admin mendaftarkan data master yang mencakup Data Barang (Inventaris), Data Kelas Praktikum, Data Dosen, Data Laboran, dan Data Mahasiswa.
2. **Mahasiswa**: Melakukan Registrasi/Daftar Akun dan Melengkapi Profil Mahasiswa.
3. **Mahasiswa**: Melihat Daftar Kelas Praktikum yang tersedia (ditampilkan oleh sistem berdasarkan data yang dibuat Admin).
4. **Mahasiswa**: Memilih & *Apply* ke Kelas yang diinginkan.
5. **Mahasiswa**: Menunggu Persetujuan dari Admin.
6. **Admin**: Menerima Permohonan (*Apply*) Mahasiswa ke Kelas.
7. **Admin**: Melakukan *Review* Data Mahasiswa & Ketersediaan kuota kelas.
8. **Keputusan Admin**:
   - Jika **Tidak Disetujui**: Admin menolak permohonan, dan notifikasi penolakan dikirimkan ke Mahasiswa. Mahasiswa akan kembali ke tahap menunggu persetujuan (atau harus mengajukan kelas lain).
   - Jika **Disetujui**: Admin menyetujui dan menambahkan Mahasiswa ke dalam kelas.
9. **Sistem/Admin**: Mengirimkan Notifikasi Hasil (*Accepted*) ke Mahasiswa.
10. **Mahasiswa**: Mendapatkan akses bahwa Kelas Terbuka (*Diaccept* Admin).
11. **Mahasiswa**: Dapat Mengikuti Kelas Praktikum & Pembelajaran.

## B. Kegiatan Kelas, Absensi & Pembelajaran

Fase ini berfokus pada aktivitas operasional di dalam kelas praktikum:

1. **Dosen**: Mengelola kelas yang diampu (*monitoring* kelas).
2. **Dosen & Laboran**: Melaksanakan kegiatan praktikum dan mengelola absensi.
   - Dosen mengabsen Laboran pendamping.
   - Laboran mendampingi & mendukung praktikum mahasiswa, serta ikut membantu dalam hal absensi mahasiswa.
3. **Dosen / Laboran**: Mengelola aktivitas praktikum seperti mengunggah (*upload*) materi, data, penugasan praktikum, dll.
4. **Mahasiswa**: Mengikuti kegiatan praktikum (Belajar, Praktikum, Mengerjakan Tugas, dll).
5. **Dosen**: Memantau aktivitas kelas & menganalisis hasil (tugas/laporan mahasiswa), lalu memberikan penilaian/nilai praktikum.
6. **Sistem**: Akan menyimpan seluruh "Data Aktivitas Kelas" yang mencakup Absensi Laboran, Absensi Mahasiswa, Aktivitas Praktikum, Materi, Tugas, dan Nilai. Data ini dapat dimonitor oleh Dosen (untuk kelasnya) dan Admin (untuk semua kelas).

## C. Monitoring Inventaris oleh Laboran & Laporan ke Admin

Fase ini memisahkan alur pengawasan kondisi laboratorium dari kegiatan akademik (LMS):

1. **Laboran**: Memantau barang (inventaris) di kelas yang ditugaskan.
2. **Laboran**: Mengecek kondisi dan kelayakan barang secara rutin. Sistem menyimpan "Data Inventaris" (Kondisi Barang, Riwayat Laporan, dan SOP Penjagaan).
3. **Laboran**: Membuat Laporan berdasarkan SOP Penjagaan Barang & Tingkat Kondisi/Kelayakan (Misal: Baik, Rusak Ringan, Rusak Berat, Hilang).
4. **Laboran**: Mengirimkan Laporan kerusakan/kondisi tersebut ke Admin.
5. **Admin**: Menerima dan meninjau laporan aktivitas barang dari Laboran.
6. **Admin**: Menindaklanjuti laporan (misal menentukan barang mana yang masih layak, perlu perbaikan, atau harus ada penggantian barang baru).

## D. Monitoring & Laporan Keseluruhan

Fase terakhir adalah perekapan data oleh Admin tingkat tinggi:

1. **Admin**: Memonitoring seluruh aktivitas kelas secara global.
2. **Admin**: Memonitoring inventaris di semua kelas/ruangan laboratorium.
3. **Admin**: Menggenerasi Laporan & Analisis Keseluruhan.
4. **Sistem**: Menyediakan "Dashboard & Laporan Keseluruhan untuk Admin" yang mencakup rekap: Aktivitas Kelas, Absensi Laboran, Absensi Mahasiswa, Nilai, Kondisi Inventaris, dan Laporan dari Laboran.

---
### Kesimpulan Alur (Ringkasan)
- **Mahasiswa**: Register -> Lengkapi Profil -> Pilih Kelas -> Apply -> Menunggu Persetujuan -> Kelas Terbuka -> Mengikuti Pembelajaran.
- **Dosen**: Absensi Laboran -> Memantau Kelas -> Penilaian & Hasil Analisis.
- **Laboran**: Absensi Mahasiswa -> Dampingi Praktikum -> Monitoring Barang -> Laporan Kondisi ke Admin.
- **Admin**: Kelola Data Master -> Setujui Mahasiswa -> Monitoring Semua Aktivitas & Inventaris -> Laporan Keseluruhan.
