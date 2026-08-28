# Panduan Kolaborasi GitHub (User1 & User2)

Dokumen ini berisi panduan langkah demi langkah tentang bagaimana **User1** (Owner/Pembuat Repository) dan **User2** (Kolaborator) dapat saling bekerja sama membangun fitur aplikasi Laravel (Sislab Fisika) secara terstruktur tanpa saling merusak kodingan satu sama lain.

---

## 👨‍💻 Sisi User1 (Owner Repository)

User1 adalah pihak yang saat ini sedang memegang *source code* dan telah membuat repositori di GitHub.

### 1. Inisialisasi dan Push Kode ke GitHub

Pastikan seluruh pekerjaan saat ini (hingga titik ini) sudah di-push ke branch utama (`main` atau `master`).
Jalankan perintah ini di terminal (VS Code / Git Bash) di folder project:

```bash
git add .
git commit -m "Initial commit atau status terakhir aplikasi"
git push origin main
```

*(Catatan: Pastikan file `.env`, folder `/vendor`, dan `/node_modules` **tidak** ikut ter-push, hal ini seharusnya sudah diatur otomatis di file `.gitignore` bawaan Laravel).*

### 2. Mengundang User2 Sebagai Kolaborator

Agar User2 bisa men-push kode ke repository Anda, Anda harus memberinya akses:

1. Buka Repositori GitHub Anda di browser.
2. Pergi ke tab **Settings** > **Collaborators** (di menu sebelah kiri).
3. Klik tombol **Add people**.
4. Masukkan username atau email GitHub milik **User2**.
5. Klik **Add to repository**.

---

## 👨‍💻 Sisi User2 (Kolaborator)

User2 adalah developer yang akan melanjutkan fitur dan baru saja diundang ke repositori.

### 1. Menerima Undangan (Accept Invitation)

- Buka email yang terdaftar di GitHub, lalu klik **View invitation**.
- Atau langsung login ke GitHub dan buka URL repositori User1, lalu akan muncul tombol **Accept Invitation**.

### 2. Clone Project ke Komputer Lokal

Setelah mendapat akses, User2 perlu mengunduh repositori tersebut ke komputernya.

```bash
git clone https://github.com/username_user1/sislab-fisika.git
cd sislab-fisika
```

### 3. Setup Project Laravel (Hanya Saat Pertama Kali)

Karena `.env`, `/vendor`, dan `/node_modules` tidak diikutsertakan di GitHub, User2 harus merakit (setup) aplikasinya:

1. Copy file `.env.example` menjadi `.env`.
   ```bash
   copy .env.example .env
   ```
2. Instal dependensi PHP (Composer) & Node.js (NPM).
   ```bash
   composer install
   npm install
   npm run build
   ```
3. Generate Application Key.
   ```bash
   php artisan key:generate
   ```
4. Atur koneksi database di file `.env` milik User2 (samakan nama databasenya), lalu jalankan migrasi database.
   ```bash
   php artisan migrate --seed
   ```

*(Sekarang aplikasi sudah bisa dijalankan oleh User2 menggunakan `php artisan serve`)*.

---

## 🔄 Alur Kerja Menambah Fitur Baru (User2)

**SANGAT PENTING:** Dilarang mengedit langsung di branch `main`! Setiap ingin menambah fitur baru, selalu buat **Branch Baru**.

### 1. Sinkronisasi (Wajib Sebelum Mulai Ngoding)

Pastikan branch `main` sudah paling update dengan kode terbaru dari User1:

```bash
git checkout main
git pull origin main
```

### 2. Membuat Branch Baru

Misal User2 ingin membuat fitur absensi, maka buat branch khusus:

```bash
git checkout -b fitur-absensi
```

*(Sekarang User2 sudah berpindah ke branch `fitur-absensi` dan bebas melakukan koding).*

### 3. Menyimpan dan Mengirim Perubahan (Commit & Push)

Setelah User2 selesai mengoding fitur absensi, simpan perubahan:

```bash
git add .
git commit -m "Menyelesaikan fitur absensi mahasiswa"
```

Kemudian kirim branch ini ke GitHub:

```bash
git push origin fitur-absensi
```

### 4. Membuat Pull Request (PR) di GitHub

1. Buka Repositori di GitHub.
2. Akan muncul notifikasi bar kuning dengan tombol **"Compare & pull request"**. Klik tombol tersebut.
3. Beri judul dan deskripsi tentang fitur absensi yang telah dibuat.
4. Klik **Create pull request**.

---

## 👁‍🗨 Sisi User1 (Mereview & Menggabungkan Kode)

Setelah User2 membuat *Pull Request*, User1 (sebagai pemilik kode) bertugas meninjau kodenya sebelum digabung ke `main`.

1. Buka GitHub, pergi ke tab **Pull requests**.
2. Klik *Pull Request* yang dibuat oleh User2.
3. Pergi ke tab **Files changed** untuk melihat apakah kodingan yang diubah oleh User2 sudah benar dan aman.
4. Jika kodingan dirasa sudah pas dan tidak ada konflik (No conflicts), klik tombol hijau **Merge pull request** lalu **Confirm merge**.
5. Kodingan User2 resmi menyatu ke dalam branch `main`.

---

## 🔁 Setelah Kode di-Merge (User1 & User2)

Setelah kode fitur absensi dari User2 berhasil di-merge ke branch `main` di GitHub, **kedua belah pihak** harus menarik kode gabungan tersebut ke komputer lokal masing-masing agar kembali sinkron.

**Perintah yang harus dijalankan oleh User1 & User2 di komputer masing-masing:**

```bash
# Pindah ke branch utama
git checkout main

# Tarik kodingan terbaru dari GitHub
git pull origin main
```

**Penting Khusus untuk User1:**
Terkadang, fitur baru yang dibuat oleh User2 membutuhkan penyesuaian di sisi sistem lokal Anda (seperti instalasi _library_ baru atau perubahan tabel database). **Setelah Anda berhasil melakukan `git pull`**, sangat disarankan untuk selalu menjalankan perintah-perintah berikut:

- Jika User2 menginstal dependensi PHP baru:
  ```bash
  composer install
  ```
- Jika User2 menginstal dependensi Node.js baru atau mengubah file UI/CSS/JS:
  ```bash
  npm install
  npm run build
  ```
- Jika User2 menambahkan/mengubah struktur tabel di Database:
  ```bash
  php artisan migrate
  ```

Setelah itu, jika User2 ingin mengerjakan fitur selanjutnya (misal: fitur penilaian), ulangi siklus dari membuat branch baru:
`git checkout -b fitur-penilaian` -> Ngoding -> Commit -> Push -> Pull Request -> Merge.
