# Product Requirements Document (PRD)
## Sistem Inventaris & Manajemen Lab Fisika

| | |
|---|---|
| **Nama Dokumen** | PRD Sistem Inventaris Lab Fisika |
| **Versi** | 1.0 |
| **Tanggal** | 7 Agustus 2026 |
| **Status** | Draft |

---

## 1. Latar Belakang

Laboratorium Fisika saat ini membutuhkan sistem digital terpadu untuk mengelola inventaris alat/bahan lab, penjadwalan praktikum, absensi, penilaian, distribusi modul praktikum, serta koordinasi antara Kepala Lab, Dosen, Laboran, dan Mahasiswa. Proses yang masih manual (spreadsheet, kertas, komunikasi via chat pribadi) menyebabkan data tidak terpusat, jadwal bentrok, dan sulitnya melacak kondisi barang inventaris.

## 2. Tujuan Produk

1. Menyediakan satu platform terpusat untuk manajemen inventaris, jadwal, dan akademik praktikum.
2. Memastikan setiap peran memiliki akses dan kontrol sesuai tanggung jawabnya (role-based access).
3. Mempermudah pelacakan kondisi barang lab dan riwayat maintenance.
4. Mendigitalkan proses absensi, penilaian, dan pengumpulan laporan praktikum.
5. Memberikan visibilitas jadwal dan pengumuman secara real-time ke seluruh pengguna.

## 3. Ruang Lingkup (Scope)

**Termasuk dalam scope:**
- Manajemen pengguna dan role (Admin/Kepala Lab, Dosen, Laboran, Mahasiswa)
- Manajemen jadwal lab dan kelas praktikum
- Manajemen inventaris barang lab dan alokasi barang per kelas
- Modul pengumuman
- Modul absensi (dosen, laboran, mahasiswa)
- Modul penilaian praktikum
- Modul distribusi modul praktikum (PDF) & pengumpulan laporan
- Modul pemantauan, pencatatan penggunaan barang, dan SOP maintenance
- Modul pelaporan laboran ke admin

**Di luar scope (fase 1):**
- Integrasi pembayaran/keuangan
- Aplikasi mobile native (fokus web responsive dahulu)
- Integrasi dengan sistem akademik kampus (SIAKAD) — dapat menjadi fase 2

## 4. Peran Pengguna (User Roles) & Ringkasan Hak Akses

| Peran | Ringkasan Akses Utama |
|---|---|
| **Kepala Lab (Admin)** | Kontrol penuh sistem: jadwal, inventaris, pengumuman, data pengguna, penentuan dosen & laboran per kelas |
| **Dosen** | Melihat jadwal, mengabsen laboran, memantau nilai mahasiswa |
| **Laboran** | Maintenance barang, SOP kebersihan, membuat tugas laporan, input nilai, absensi mahasiswa, absen dari dosen |
| **Mahasiswa** | Melihat modul, jadwal, nilai, submit laporan praktikum |

---

## 5. Kebutuhan Fungsional per Peran

### 5.1 Kepala Lab (Admin)

| ID | Fitur | Deskripsi |
|---|---|---|
| ADM-01 | Manajemen Jadwal Lab | Admin dapat membuat, mengedit, menghapus jadwal penggunaan lab (tanggal, jam, ruang, kelas praktikum) |
| ADM-02 | Input Barang Inventaris | Admin dapat menambah, mengedit, menghapus data barang lab (nama, kategori, jumlah, kondisi, lokasi penyimpanan, tanggal pengadaan) |
| ADM-03 | Manajemen Pengumuman | Admin dapat membuat, mengedit, menghapus, dan menjadwalkan publikasi pengumuman ke seluruh/sebagian role |
| ADM-04 | Manajemen Data Pengguna | Admin mengelola data mahasiswa, dosen, dan laboran (CRUD, impor massal via Excel/CSV) |
| ADM-05 | Peringatan Jadwal (Reminder) | Sistem mengirim notifikasi otomatis ke pihak terkait (dosen/laboran/mahasiswa) menjelang jadwal praktikum |
| ADM-06 | Manajemen Data Kelas | Admin membuat data kelas praktikum dan menentukan dosen pengampu serta laboran penanggung jawab (1 laboran = 1 kelas) |
| ADM-07 | Alokasi Barang Praktikum | Admin menentukan (mengalokasikan) daftar barang inventaris yang akan digunakan pada suatu kelas praktikum |
| ADM-08 | Tindak Lanjut Laporan Laboran | Admin menerima laporan kondisi barang & SOP dari laboran, lalu melakukan tindak lanjut evaluasi atau perbaikan |
| ADM-09 | Dashboard Admin | Ringkasan statistik: jumlah barang, kondisi barang, jadwal aktif, jumlah pengguna per role |
| ADM-10 | Laporan & Audit Log | Melihat riwayat perubahan data inventaris dan aktivitas pengguna penting |
| ADM-11 | Kontrol Status Kelas | Admin mengubah status kelas praktikum (draft/open/closed) untuk mengontrol visibility pendaftaran mahasiswa |
| ADM-12 | Approval Pendaftaran Mahasiswa | Admin menyetujui atau menolak pendaftaran mahasiswa ke kelas praktikum, dengan opsi catatan alasan penolakan |

### 5.2 Dosen

| ID | Fitur | Deskripsi |
|---|---|---|
| DSN-01 | Lihat Jadwal Dosen | Dosen melihat jadwal mengajar praktikum miliknya beserta daftar barang yang dialokasikan (kalender/list) |
| DSN-02 | Absensi Laboran | Dosen mencatat kehadiran laboran yang mendampingi kelas praktikumnya |
| DSN-03 | View Nilai Mahasiswa | Dosen melihat (read-only) nilai praktikum mahasiswa yang telah diinput oleh laboran, per kelas/per pertemuan |
| DSN-04 | Notifikasi Jadwal | Dosen menerima reminder jadwal praktikum yang akan berlangsung |
| DSN-05 | Monitoring Penggunaan Barang | Dosen dapat memantau pencatatan penggunaan barang dan ringkasan SOP yang dijalankan laboran di kelasnya |

### 5.3 Laboran

| ID | Fitur | Deskripsi |
|---|---|---|
| LAB-01 | Maintenance Barang | Laboran mencatat kondisi, kerusakan, dan status maintenance barang pada kelas praktikum yang menjadi tanggung jawabnya |
| LAB-02 | SOP Kebersihan Barang | Laboran mengakses dan mencentang checklist SOP kebersihan/perawatan barang sebagai bukti kepatuhan |
| LAB-03 | Buat Tugas Laporan Praktikum | Laboran membuat dan mempublikasikan tugas/laporan praktikum untuk mahasiswa di kelasnya, lengkap dengan deadline |
| LAB-04 | Input Nilai Mahasiswa | Laboran menginput nilai praktikum mahasiswa pada kelas yang menjadi tanggung jawabnya |
| LAB-05 | Absensi Mahasiswa | Laboran mencatat kehadiran mahasiswa setiap sesi praktikum |
| LAB-06 | Mengabsen dari Dosen | Laboran melakukan konfirmasi/absen kehadiran dirinya terhadap dosen pengampu kelas |
| LAB-07 | Lihat Jadwal Kelas Sendiri | Laboran melihat jadwal kelas praktikum yang menjadi tanggung jawabnya (1 laboran : 1 kelas) |
| LAB-08 | Catat Penggunaan Barang | Laboran memantau daftar barang alokasi dan mencatat log penggunaan barang selama kegiatan praktikum |
| LAB-09 | Buat & Kirim Laporan ke Admin | Laboran menyusun laporan komprehensif (checklist SOP, kondisi/kelayakan barang, temuan lainnya) untuk diserahkan ke Admin |

### 5.4 Mahasiswa

| ID | Fitur | Deskripsi |
|---|---|---|
| MHS-00 | Registrasi Akun Mandiri | Mahasiswa dapat mendaftarkan akun sendiri (self-register) dengan email, nama, dan password |
| MHS-00a | Lengkapi Profil | Setelah registrasi, mahasiswa wajib melengkapi profil (NIM, no. HP, foto) sebelum dapat mengakses fitur lain |
| MHS-00b | Daftar Kelas Praktikum | Mahasiswa melihat daftar kelas praktikum yang terbuka (status: open) di semester aktif |
| MHS-00c | Apply Kelas Praktikum | Mahasiswa mendaftar ke satu atau lebih kelas praktikum (selama kapasitas tersedia) dan menunggu persetujuan admin |
| MHS-00d | Batal Pendaftaran | Mahasiswa dapat membatalkan pendaftaran yang masih berstatus pending |
| MHS-01 | Lihat Modul Praktikum (PDF) | Mahasiswa dapat melihat dan mengunduh modul praktikum dalam format PDF per pertemuan/topik |
| MHS-02 | Lihat Jadwal Kelas Praktikum | Mahasiswa melihat jadwal praktikum kelasnya (tanggal, jam, ruang, laboran, dosen) |
| MHS-03 | Lihat Nilai Praktikum | Mahasiswa melihat (read-only) nilai praktikum yang telah diinput laboran |
| MHS-04 | Submit Laporan Praktikum | Mahasiswa mengunggah file laporan praktikum sesuai tugas yang dibuat laboran, sebelum batas waktu (deadline) |
| MHS-05 | Notifikasi Pengumuman & Jadwal | Mahasiswa menerima notifikasi pengumuman dan reminder jadwal praktikum |

---

## 6. User Stories (Contoh Prioritas Tinggi)

1. **Sebagai Kepala Lab**, saya ingin menentukan dosen dan laboran untuk setiap kelas praktikum, agar tanggung jawab masing-masing kelas jelas.
2. **Sebagai Kepala Lab**, saya ingin menginput data barang inventaris beserta kondisinya, agar stok dan kelayakan alat lab dapat dipantau.
3. **Sebagai Dosen**, saya ingin melihat nilai mahasiswa yang diinput laboran, agar saya dapat memvalidasi hasil pembelajaran praktikum.
4. **Sebagai Laboran**, saya ingin membuat tugas laporan praktikum, agar mahasiswa di kelas saya memiliki instruksi dan deadline yang jelas.
5. **Sebagai Laboran**, saya ingin mencatat maintenance barang, agar kerusakan alat dapat segera ditindaklanjuti oleh Kepala Lab.
6. **Sebagai Mahasiswa**, saya ingin mengunduh modul PDF dan submit laporan praktikum langsung di sistem, agar prosesnya lebih praktis dan terdokumentasi.
7. **Sebagai Mahasiswa**, saya ingin melihat jadwal dan nilai praktikum saya kapan saja, agar saya dapat mempersiapkan diri dan memantau progres.

---

## 7. Alur Utama (Key Workflows)

### 7.1 Alur Penentuan Kelas Praktikum
1. Admin membuat data kelas praktikum baru.
2. Admin menentukan dosen pengampu dan 1 laboran penanggung jawab kelas tersebut.
3. Admin mengalokasikan daftar barang inventaris yang dibutuhkan untuk kelas tersebut.
4. Admin menyusun jadwal (tanggal, jam, ruang).
5. Sistem mengirim notifikasi ke dosen, laboran, dan mahasiswa terkait.

### 7.2 Alur Sesi Praktikum
1. Laboran mengonfirmasi kehadiran ke dosen (LAB-06) sebelum sesi dimulai.
2. Dosen mengabsen laboran (DSN-02).
3. Laboran mengabsen mahasiswa (LAB-05).
4. Laboran melakukan pengecekan barang alokasi, memantau penggunaan barang, dan memastikan SOP kebersihan (LAB-01, LAB-02, LAB-08).
5. Mahasiswa mengikuti praktikum menggunakan modul PDF yang telah dipublikasikan.
6. Laboran mengirimkan laporan sesi kelas (SOP & kondisi kelayakan barang) ke Admin (LAB-09).

### 7.3 Alur Tugas & Penilaian
1. Laboran membuat tugas laporan praktikum dengan deadline.
2. Mahasiswa submit laporan sebelum deadline.
3. Laboran menilai laporan dan menginput nilai ke sistem.
4. Dosen melihat nilai untuk validasi.
5. Mahasiswa melihat nilai akhir.

### 7.4 Alur Reminder Jadwal
1. Sistem memeriksa jadwal H-1 atau sesuai konfigurasi waktu reminder.
2. Sistem mengirim notifikasi (in-app/email) ke Admin, Dosen, Laboran, dan Mahasiswa terkait jadwal tersebut.

### 7.5 Alur Registrasi & Enrollment Mahasiswa
1. Mahasiswa membuka halaman web dan memilih "Daftar Akun".
2. Mahasiswa mengisi nama, email, dan password.
3. Sistem membuat akun dengan role `mahasiswa` dan `is_profile_completed = false`.
4. Mahasiswa otomatis login dan diarahkan ke halaman "Lengkapi Profil".
5. Mahasiswa mengisi NIM, nomor HP, dan foto (opsional).
6. Sistem menyimpan profil dan menandai `is_profile_completed = true`.
7. Mahasiswa diarahkan ke dashboard dan dapat melihat daftar kelas praktikum yang **open** di semester aktif.
8. Mahasiswa memilih kelas dan meng-apply (status: **pending**).
9. Admin melihat daftar pendaftaran pending di panel admin.
10. Admin menyetujui (status: **approved**) atau menolak (status: **rejected** + catatan).
11. Jika disetujui, kelas terbuka untuk mahasiswa: akses jadwal, modul, tugas, absensi.
12. Mahasiswa boleh apply ke lebih dari satu kelas selama kapasitas tersedia dan kelas berada di semester aktif.

---

## 8. Model Data Utama (High-Level Data Model)

| Entitas | Atribut Utama |
|---|---|
| **User** | id, nama, email, role (admin/dosen/laboran/mahasiswa), NIP/NIM, phone, photo, is_active, is_profile_completed, status |
| **Kelas Praktikum** | id, nama_kelas, semester_id, kapasitas, **status (draft/open/closed)**, dosen_id, laboran_id, created_by |
| **Kelas Mahasiswa (Pivot)** | id, kelas_praktikum_id, mahasiswa_id, **status (pending/approved/rejected)**, **catatan_admin** |
| **Kelas Barang (Pivot)** | kelas_praktikum_id, barang_id |
| **Jadwal** | id, kelas_id, tanggal, jam_mulai, jam_selesai, ruang, topik |
| **Barang Inventaris** | id, kode_barang, nama_barang, foto_barang, kategori, jumlah, kondisi, ruangan, tanggal_pengadaan, riwayat_maintenance, keterangan |
| **Penggunaan Barang** | id, jadwal_id, barang_id, laboran_id, jumlah_digunakan, kondisi_setelah, catatan |
| **Laporan Laboran** | id, kelas_id, laboran_id, tanggal, status_sop, kelayakan_barang, catatan_temuan, status_admin |
| **Maintenance Log** | id, barang_id, laboran_id, tanggal, deskripsi, status |
| **SOP Checklist** | id, kelas_id, laboran_id, tanggal, item_checklist, status |
| **Pengumuman** | id, judul, isi, target_role, tanggal_publish, admin_id |
| **Modul Praktikum (PDF)** | id, kelas_id, judul, file_pdf, tanggal_upload |
| **Tugas Laporan** | id, kelas_id, laboran_id, judul, deskripsi, deadline |
| **Submission Laporan** | id, tugas_id, mahasiswa_id, file_laporan, tanggal_submit, status |
| **Nilai** | id, mahasiswa_id, kelas_id, tugas_id, laboran_id, nilai, keterangan |
| **Absensi** | id, kelas_id, tanggal, tipe (dosen/laboran/mahasiswa), user_id, status_hadir |

---

## 9. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
|---|---|
| **Keamanan** | Autentikasi berbasis akun & role-based access control (RBAC); enkripsi password; hak akses ketat sesuai role |
| **Usability** | Antarmuka responsif (web desktop & mobile browser), navigasi sederhana untuk semua level pengguna |
| **Performa** | Waktu muat halaman < 3 detik untuk operasi umum; mendukung minimal 200 pengguna aktif bersamaan |
| **Ketersediaan File** | Modul PDF dan laporan mahasiswa harus dapat diunduh/diunggah dengan batas ukuran file yang wajar (misal maks 20MB) |
| **Notifikasi** | Sistem reminder otomatis (in-app minimal, email opsional) |
| **Auditability** | Pencatatan log aktivitas untuk perubahan data inventaris dan nilai |
| **Skalabilitas** | Arsitektur dapat menambah kelas, jumlah pengguna, dan barang inventaris tanpa perubahan struktural besar |
| **Backup Data** | Backup data rutin (harian/mingguan) untuk mencegah kehilangan data |

---

## 10. Batasan & Asumsi

- 1 laboran hanya bertanggung jawab pada 1 kelas praktikum dalam satu periode.
- Nilai yang diinput laboran bersifat final kecuali direvisi oleh laboran itu sendiri; dosen hanya memiliki akses lihat (read-only).
- Modul praktikum diunggah dalam format PDF oleh Admin atau Laboran (perlu klarifikasi lebih lanjut — lihat Bagian 12).
- Jadwal lab bersifat terpusat dan hanya dapat diubah oleh Admin.

## 11. Metrik Keberhasilan (Success Metrics)

| Metrik | Target |
|---|---|
| Adopsi pengguna aktif (dosen, laboran, mahasiswa) | ≥ 90% dari total pengguna terdaftar aktif menggunakan sistem dalam 1 semester |
| Pengurangan jadwal bentrok | 0 kasus bentrok jadwal lab setelah implementasi |
| Ketepatan waktu submit laporan | ≥ 85% laporan mahasiswa disubmit sebelum deadline |
| Akurasi data inventaris | Selisih data fisik vs sistem < 5% saat audit berkala |
| Waktu respons maintenance barang | Laporan kerusakan ditindaklanjuti dalam ≤ 3 hari kerja |

## 12. Pertanyaan Terbuka (Open Questions)

1. Apakah modul PDF praktikum diunggah oleh Admin, Dosen, atau Laboran?
2. Apakah diperlukan approval Kepala Lab sebelum tugas laporan dari laboran dipublikasikan ke mahasiswa?
3. Apakah nilai yang sudah diinput laboran dapat direvisi oleh Dosen, atau murni read-only?
4. Apakah dibutuhkan fitur peminjaman barang oleh dosen/mahasiswa ke Laboran/Admin?
5. Format notifikasi: cukup in-app, atau perlu integrasi email/WhatsApp?

---

## 13. Lampiran: Ringkasan Matriks Hak Akses (RBAC)

| Fitur | Admin | Dosen | Laboran | Mahasiswa |
|---|:---:|:---:|:---:|:---:|
| Kelola Jadwal Lab | CRUD | Lihat | Lihat (kelas sendiri) | Lihat (kelas sendiri) |
| Kelola Inventaris | CRUD | Lihat | Update kondisi barang | - |
| Alokasi Barang ke Kelas | CRUD | Lihat | Lihat | - |
| Catat Penggunaan Barang | Lihat | Lihat | Input | - |
| Laporan Laboran ke Admin | Tindak Lanjut | Lihat | Buat/Kirim | - |
| Kelola Pengumuman | CRUD | Lihat | Lihat | Lihat |
| Kelola Data Pengguna | CRUD | - | - | - |
| Kelola Data Kelas & Penentuan Dosen/Laboran | CRUD | Lihat | Lihat | - |
| Kontrol Status Kelas (draft/open/closed) | Update | - | - | - |
| Approval Pendaftaran Mahasiswa | Approve/Reject | - | - | - |
| Registrasi Akun Mandiri | - | - | - | Self-Register |
| Lengkapi Profil | - | - | - | Input |
| Daftar & Apply Kelas Praktikum | - | - | - | Apply/Cancel |
| Absensi Laboran | - | Input | - | - |
| Absensi Mahasiswa | Lihat | Lihat | Input | - |
| Konfirmasi Kehadiran ke Dosen | - | Terima | Input | - |
| SOP Kebersihan Barang | Lihat | - | Input | - |
| Buat Tugas Laporan | Lihat | Lihat | CRUD | - |
| Submit Laporan | Lihat | Lihat | Lihat | Input |
| Input Nilai | Lihat | Lihat (read-only) | Input | Lihat (read-only) |
| Modul Praktikum PDF | Upload/Kelola | Lihat | Lihat/Upload* | Lihat/Unduh |

\* Tergantung jawaban Open Question #1.

---

*Dokumen ini adalah draft awal PRD dan dapat berkembang seiring diskusi lebih lanjut dengan stakeholder.*
