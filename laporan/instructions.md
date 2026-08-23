AI wajid membaca, memerhatikan, serta memahami instruksi dan juga task-list yang ada di bawah ini, sebelum melakukan pengembangan. Jangan terburu-buru.
dan harus selalu mengingat instruksi ini, dan jangan mengubahnya, membantah, dan skip perarturan yang telah ditetapkan

## Panduan Pengembangan (Instructions)

1. **Prioritaskan Tailwind CSS:** 
   Gunakan _utility classes_ dari Tailwind CSS sebagai pilihan utama untuk menyusun gaya tampilan (styling). Hindari penggunaan file `.css` terpisah kecuali benar-benar diperlukan.

2. **Penanganan Data Kosong:** 
   Jangan gunakan karakter *fallback* seperti tanda hubung (`-`) untuk data yang kosong. 
   - Selalu cari cara agar data asli dapat ditampilkan dengan benar. 
   - Jika data di dalam *database* memang benar-benar kosong, biarkan sel/kolom tabel tersebut kosong (tanpa karakter apa pun), atau tampilkan pesan *placeholder* yang jelas (misalnya: "Belum diisi").

3. **Gunakan Mode Ponytail:** 
   Selalu terapkan `/ponytail` pada setiap perubahan kode. Pastikan kode yang dihasilkan ringkas, tidak menggunakan *boilerplate* atau *abstraksi* yang berlebihan, dan seefisien mungkin (*build the minimum that works*).

4. **Gunakan jQuery untuk Interaksi Asinkron (AJAX):** 
   - Manfaatkan jQuery untuk mengoptimalkan pemuatan data melalui AJAX.
   - Terapkan pendekatan ini pada halaman yang berinteraksi dengan *database*, sehingga aksi seperti filter, pencarian, atau pergantian data dapat berjalan mulus tanpa perlu memuat ulang (*reload*) seluruh halaman.

5. **Gunakan Kustom Modal Dialog & Toaster:** 
   - Hindari penggunaan modal dialog bawaan *browser* (seperti `alert()` atau `confirm()`).
   - Selalu gunakan komponen *modal dialog* kustom (HTML/Tailwind) yang konsisten dengan desain sebelumnya untuk setiap interaksi atau konfirmasi pengguna.
   - Manfaatkan *toaster* (notifikasi *pop-up*) untuk memberikan informasi, *feedback*, atau status aksi kepada pengguna secara elegan.

6. **Aturan Styling Khusus Role Mahasiswa:** 
   - Khusus untuk antarmuka *role* Mahasiswa yang ada di folder `\resources\views\pages\mahasiswa`, *styling* tidak selalu menggunakan Tailwind CSS secara eksklusif.
   - Jika diperlukan (misal untuk presisi *layout* tertentu), gabungkan *class* Tailwind CSS dengan *inline style* langsung pada tag HTML.
   - Contoh penulisan yang diperbolehkan: `<div style="display:flex; align-items:center; justify-content:space-between; gap:12px;" class="bg-white">`

7. **Berikan Penjelasan dan Saran Sebelum Modifikasi Kode:** 
   - Setiap ada pertanyaan atau instruksi baru, berikan jawaban berupa penjelasan dan saran jika diperlukan.
   - Berikan contoh-contoh yang relevan dan tambahan informasi yang mungkin berguna.
   - Jangan langsung memberikan, menambah, atau mengubah kode. Agent harus mengetahui dan mengkonfirmasi terlebih dahulu konteks dari pertanyaan tersebut agar arah perubahan atau penambahan fiturnya jelas.

8. **INTEGRITAS DATA & ANTI-FALLBACK**: AI **DILARANG** menggunakan string fallback statis (seperti `$user->email ?? '-'` atau di jquery seperti `user.nama || '-'`, atau placeholder manual lainnya) untuk menutupi data yang hilang/kosong dari database. Jika data tidak muncul, AI **WAJIB** menelusuri akar permasalahannya (pada Controller, Model, atau Query) dan memberikan solusi agar data asli dari database dapat ditampilkan dengan benar tanpa manipulasi teks statis di frontend.

9. **Jika ada task-list telah selesai** 
   AI **WAJIB** cek pada suatu penambahan di fitur tertentu apakah telah menyelesaikan dari task-list yang ada di laporan\task-list-sistem.md dan men mark task tersebut jika sudah selesai, jika belum biarkan saja. 

10. **pattern function, class, modal, utility, dan lainnya**
   AI harus selalu konsisten dalam penulisan function, class, modal, utility, dan lainnya, AI tidak boleh membuat file function, class, modal, utility, dan lainnya yang tidak konsisten dengan file function, class, modal, utility, dan lainnya yang sudah ada, dan AI juga tidak boleh membuat file function, class, modal, utility, dan lainnya yang tidak konsisten dengan file function, class, modal, utility, dan lainnya yang sudah ada.

11. **PROTOKOL KONFIRMASI & INTEGRITAS DATABASE** 
   AI **DILARANG KERAS** menyentuh atau mengedit file Migration dan Seeder lama yang sudah ada. Jika diperlukan perubahan struktur atau penambahan data, AI **WAJIB** meminta izin eksplisit dari pengguna terlebih dahulu. Setelah disetujui, AI harus membuat file Migration atau Seeder **baru** untuk menjaga integritas riwayat database.

12. **PENCEGAHAN REGRESI & ANALISIS DAMPAK**: 
   Saat melakukan perbaikan atau penambahan fitur, AI **WAJIB** memastikan stabilitas fitur lain yang sudah ada. AI harus melakukan analisis dampak (_impact analysis_) terlebih dahulu untuk menjamin bahwa kode baru tidak merusak (_break_) fungsionalitas yang sudah berjalan. Menjaga sistem tetap berfungsi secara keseluruhan lebih diutamakan daripada sekadar menyelesaikan satu fitur baru.
