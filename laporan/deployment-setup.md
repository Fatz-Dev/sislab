# Panduan Deployment SISLAB Fisika di Shared Hosting (cPanel)

Deploy aplikasi Laravel 12 (dengan Vite, WebSockets/Reverb, dan Queue) di lingkungan _Shared Hosting_ seringkali menantang karena keterbatasan akses root/Supervisor. Panduan ini menjelaskan langkah teknis terlengkap untuk membuat fitur _real-time_ Anda tetap berjalan di _Shared Hosting_.

---

## 1. Persiapan Build Lokal (Sebelum Upload)

Sebelum meng-_upload_ _file_ ke server, pastikan semua _assets_ Frontend telah di-_compile_ di komputer lokal Anda, karena Shared Hosting mungkin kesulitan menjalankan Node.js (`npm run build`).

1. Buka terminal di lokal, pastikan berada di folder _project_.
2. Ubah konfigurasi di `.env` (lokal) sementara agar _base URL_ menyesuaikan (opsional).
3. Jalankan perintah kompilasi Vite:
   ```bash
   npm install
   npm run build
   ```
4. Pastikan folder `public/build/` berhasil terbentuk.
5. Bersihkan _cache_ lokal:
   ```bash
   php artisan optimize:clear
   ```
6. Kompres/zip seluruh direktori _project_ menjadi `sislab-fisika.zip` (Kecuali folder `node_modules` dan `.git`).

---

## 2. Struktur Direktori di cPanel

Untuk alasan keamanan, file inti Laravel **TIDAK BOLEH** diletakkan di dalam `public_html`.

1. Login ke cPanel -> File Manager.
2. Di direktori utama (sejajar dengan `public_html`), buat folder baru bernama `sislab-app`.
3. _Upload_ dan _Extract_ `sislab-fisika.zip` ke dalam folder `sislab-app` tersebut.
4. Pindahkan **SEMUA ISI** dari folder `sislab-app/public/` ke dalam direktori `public_html` (atau folder _addon domain_ Anda).
5. Buka _file_ `public_html/index.php` yang baru saja dipindah, dan perbarui _path_ ke file inti Laravel:

   Ubah baris:

   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

   Menjadi:

   ```php
   require __DIR__.'/../sislab-app/vendor/autoload.php';
   $app = require_once __DIR__.'/../sislab-app/bootstrap/app.php';
   ```

---

## 3. Konfigurasi Database & Environment

1. Di cPanel, buka **MySQL® Databases**.
2. Buat Database baru (Misal: `namauser_sislab`).
3. Buat User MySQL baru dan sambungkan (Add User to Database) dengan hak akses ALL PRIVILEGES.
4. Buka folder `sislab-app/` di File Manager, edit berkas `.env`.
5. Sesuaikan variabel berikut:

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=namauser_sislab
   DB_USERNAME=namauser_dbuser
   DB_PASSWORD=password_db_anda

   # PENTING UNTUK REVERB & BROADCAST
   BROADCAST_CONNECTION=reverb
   REVERB_APP_ID=bebas_isi_angka_random (Misal: 987654)
   REVERB_APP_KEY=isi_kunci_acak_yg_panjang
   REVERB_APP_SECRET=isi_rahasia_acak_yg_panjang
   REVERB_HOST="domainanda.com"
   REVERB_PORT=8080
   REVERB_SCHEME=https
   ```
6. Jika Anda punya akses SSH, masuk dan jalankan migrasi: `php artisan migrate --seed`. Jika tidak, ekspor database dari lokal via phpMyAdmin dan Impor ke phpMyAdmin server.

---

## 4. Tantangan & Solusi `php artisan reverb:start` di Shared Hosting

**Mengapa ini menjadi masalah?**
Perintah `php artisan reverb:start` adalah sebuah proses _daemon_ (berjalan terus-menerus tanpa henti) untuk membuka gerbang _WebSocket_ secara _real-time_. Di server VPS (Virtual Private Server), kita biasa menggunakan `Supervisor` agar proses ini hidup selamanya. Namun, di **Shared Hosting**, penyedia layanan biasanya **melarang** atau **mematikan otomatis** proses yang berjalan lama tanpa henti untuk menghemat RAM (_CloudLinux LVE Limits_).

Berikut adalah strategi untuk mengakalinya:

### Metode 1: Via Terminal SSH cPanel (Paling Disarankan)

Jika Shared Hosting Anda memberikan akses Terminal/SSH, Anda bisa menjalankan perintah ini di _background_.

1. Buka fitur **Terminal** di cPanel.
2. Masuk ke direktori aplikasi Anda: `cd sislab-app`
3. Jika Anda hanya mengetik `php artisan reverb:start`, perintah tersebut akan **mati** begitu Anda menutup tab browser/Terminal.
4. Oleh karena itu, gunakan `nohup` (No Hang Up) agar perintah tetap hidup meskipun terminal ditutup:
   ```bash
   nohup php artisan reverb:start --host="0.0.0.0" --port=8080 > storage/logs/reverb.log 2>&1 &
   ```

   *Catatan: Angka `8080` adalah port default. Jika hosting memblokirnya, Anda mungkin perlu minta *support hosting* untuk membukakan port tersebut, atau mencari port lain yang kosong/diizinkan.*

### Metode 2: Trik Cron Jobs (Jika tidak ada akses SSH)

Jika menu Terminal tidak tersedia, kita bisa menggunakan "Cron Jobs" untuk memicu perintah tersebut. Karena Shared Hosting sering merazia dan "membunuh" proses yang berjalan terlalu lama, kita bisa membuat Cron Job yang mengecek setiap 1 menit: _"Apakah Reverb masih hidup? Jika mati, nyalakan lagi."_

1. Masuk ke cPanel > **Cron Jobs**.
2. Tambahkan Cron baru: **Setiap Menit** (\* \* \* \* \*).
3. Masukkan skrip berikut:
   ```bash
   ps aux | grep 'reverb:start' | grep -v grep || cd /home/namausercpanel/sislab-app && /usr/local/bin/php artisan reverb:start --host="0.0.0.0" --port=8080 > /dev/null 2>&1 &
   ```

   _(Penjelasan Skrip: Ia mengecek daftar proses server (`ps aux`). Jika tidak ada tulisan `reverb:start`, maka ia akan masuk ke folder `sislab-app` dan menyalakannya. Sesuaikan `/usr/local/bin/php` dengan path eksekusi PHP di hosting Anda)._

### Metode 3: Beralih Menggunakan Pusher API (Paling Disarankan untuk Shared Hosting)

Jika penyedia _Shared Hosting_ Anda sangat ketat (misalnya langsung memblokir semua WebSocket lokal, menutup port _custom_ seperti 8080, atau membatasi batas eksekusi proses server/LVE Limits), maka **Laravel Reverb TIDAK AKAN BISA** berjalan.

Saran terbaik dan paling profesional untuk _Shared Hosting_ adalah menyerahkan beban WebSocket ke layanan pihak ketiga gratis yang khusus menangani _realtime engine_, yaitu **Pusher**.

Aplikasi ini sudah diprogram (pada berkas `resources/js/echo.js`) agar secara dinamis kompatibel 100% dengan Pusher tanpa perlu menulis ulang satu baris kode PHP/JS pun.

**Langkah-langkah Migrasi ke Pusher:**

1. **Daftar Akun Gratis di Pusher**:

   - Buka website [pusher.com](https://pusher.com/) dan buat akun (bisa _Login with GitHub/Google_).
   - Di _dashboard_, klik tombol **"Create App"** di bagian _Channels_.
   - Beri nama aplikasi (misal: `sislab-fisika`), pilih _cluster_ yang paling dekat (misal: `ap1` untuk Asia Pasifik), dan pilih _framework_ Laravel (sebagai panduan saja).
   - Klik **Create**.
2. **Dapatkan _Keys_ Kredensial**:

   - Masuk ke aplikasi yang baru dibuat, klik menu **App Keys** di bilah kiri.
   - Anda akan melihat daftar kode rahasia (`app_id`, `key`, `secret`, `cluster`).
3. **Ubah Berkas `.env` di Hosting Anda**:

   - Buka cPanel > File Manager > Edit file `.env` milik `sislab-app`.
   - Ubah `BROADCAST_CONNECTION=reverb` menjadi `pusher`.
   - Masukkan kredensial yang Anda dapatkan tadi ke pengaturan Pusher dan konfigurasikan Variabel Vite agar Frontend kita membaca kunci tersebut:

   ```env
   # GANTI INI
   BROADCAST_CONNECTION=pusher

   # MASUKKAN DATA DARI PUSHER DASHBOARD
   PUSHER_APP_ID=isi_dengan_app_id_anda
   PUSHER_APP_KEY=isi_dengan_key_anda
   PUSHER_APP_SECRET=isi_dengan_secret_anda
   PUSHER_HOST=
   PUSHER_PORT=443
   PUSHER_SCHEME=https
   PUSHER_APP_CLUSTER=ap1

   # SINKRONISASI KE FRONTEND (SANGAT PENTING)
   VITE_BROADCAST_CONNECTION="${BROADCAST_CONNECTION}"
   VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
   VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
   ```
4. **Build Ulang Frontend (Jika Menggunakan Vite)**:

   - Karena Anda mengubah kredensial `.env` untuk `VITE_...`, file _Javascript_ harus di-_compile_ ulang agar kunci Pusher yang baru disuntikkan ke dalam file `app.js`.
   - Jika Anda memiliki akses terminal lokal sebelum _upload_ ke server, jalankan `npm run build` sekali lagi, lalu _upload_ ulan folder `public/build`.
   - Jika sudah ada di server, Anda butuh akses SSH terminal untuk menjalankan `npm run build`.

Dengan langkah ini, Anda **tidak perlu lagi** memikirkan _Cron Jobs_ atau _SSH Nohup_ untuk `php artisan reverb:start`. Layanan notifikasi _realtime_ akan dilayani oleh server luar milik Pusher secara otomatis.

---

## 5. Menjalankan Queue Worker untuk Background Jobs

Untuk menjamin antrean pengiriman notifikasi/email berjalan asinkronus tanpa membuat web menjadi _lagging_, _Queue worker_ harus dihidupkan.

Sama seperti Reverb, gunakan Cron Jobs untuk menjaga _worker_ tetap hidup:

1. Masuk ke cPanel > **Cron Jobs**.
2. Tambahkan Cron baru, atur waktu ke **Setiap Menit** (\* \* \* \* \*).
3. Masukkan instruksi _Cron_:
   ```bash
   cd /home/namausercpanel/sislab-app && /usr/local/bin/php artisan queue:work --stop-when-empty
   ```

   _(Dengan `--stop-when-empty`, Cron akan memproses antrean jika ada, lalu mati. Dan menit berikutnya akan diulang lagi, menghindari penumpukan memori di Shared Hosting)._

---

## 6. Setup SSL Reverse Proxy untuk WebSocket (Opsional & Lanjutan)

Jika Anda memaksakan `REVERB_SCHEME=https` (WSS), beberapa browser modern akan memblokir koneksi ke port `8080` jika SSL tidak disajikan dengan benar.
Sebagai _workaround_ di cPanel, Anda bisa menyetting subdomain (misalnya `ws.domainanda.com`) dan mem-_proxy_ permintaannya langsung ke port 8080 secara lokal, namun hal ini seringkali bergantung pada fleksibilitas penyedia _hosting_ Anda.

Untuk Shared Hosting standar, cara tergampang agar Reverb berfungsi aman di produksi:

1. Pastikan ekstensi `pcntl` aktif di pengaturan PHP Selector cPanel.
2. Pastikan file `.env` Frontend (`VITE_REVERB_HOST`) mengarah tepat ke IP server / Domain tanpa "https://" ketika mendefinisikan port untuk Websocket.

---

**Troubleshooting Akhir:**
Setelah semua _setup_ selesai, jalankan link URL website Anda. Buka **Inspect Element > Console**. Pastikan tidak ada _error_ `WebSocket connection to 'wss://...' failed`. Jika gagal terhubung, berarti port belum dibuka oleh pihak _hosting_.
