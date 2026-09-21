# Dokumentasi & Ekstraksi Data Asli Laboratorium Fisika

> **Sumber Berkas:** `Data_LAB. FISIKA.xlsx`  
> **Satuan Kerja:** Program Studi Pendidikan Fisika / Fisika  
> **Fakultas / Universitas:** Fakultas Tarbiyah dan Keguruan, UIN Ar-Raniry Banda Aceh  
> **Tanggal Dokumen:** 30 Juni 2026  
> **Pejabat Pengesah:**  
> - **Pengelola Barang Persediaan:** Anwar  
> - **Kepala Bagian Tata Usaha:** Mukhtar  

---

## 1. Analisis & Struktur Berkas Excel

Berkas `Data_LAB. FISIKA.xlsx` terdiri atas **7 Lembar Kerja (Worksheets)**:
1. **`PFS (LAB.)`**: Lembar kerja induk (*master consolidation*) yang menggabungkan seluruh daftar barang dari 6 unit ruangan laboratorium beserta informasi inventaris negara (Merk, Kode Barang/BMN, Tahun Perolehan, dan Kondisi).
2. **`ADM. LAB`**: Lembar kerja khusus barang Administrasi Laboratorium.
3. **`LAB. MEKANIKA`**: Lembar kerja khusus barang Laboratorium Mekanika.
4. **`LAB. LISMAG`**: Lembar kerja khusus barang Laboratorium Listrik dan Magnet.
5. **`LAB. FISDAS`**: Lembar kerja khusus barang Laboratorium Fisika Dasar (Multi Fungsi).
6. **`LAB. OPTIK`**: Lembar kerja khusus barang Laboratorium Optik.
7. **`LAB. THERMO`**: Lembar kerja khusus barang Laboratorium Termodinamika.

### Ringkasan Statistik Inventaris Ruangan

| No | Kode Ruangan | Nama Ruangan Laboratorium | Total Item | Total Fisik (Unit/Set) | Baik | Rusak Ringan | Rusak Berat |
|:--:|:------------:|:--------------------------|:----------:|:----------------------:|:----:|:------------:|:-----------:|
| 1 | `ADM` | Administrasi Laboratorium | 26 | 41 | 37 | 2 | 2 |
| 2 | `MEK` | Laboratorium Mekanika | 48 | 157 | 152 | 2 | 3 |
| 3 | `LISMAG` | Laboratorium Listrik dan Magnet | 44 | 171 | 168 | 3 | 3 |
| 4 | `FISDAS` | Laboratorium Fisika Dasar (Multi Fungsi) | 20 | 88 | 88 | 0 | 0 |
| 5 | `OPTIK` | Laboratorium Optik | 67 | 204 | 197 | 3 | 4 |
| 6 | `THERMO` | Laboratorium Termodinamika | 57 | 179 | 179 | 0 | 0 |
| | **TOTAL** | **Seluruh Laboratorium** | **262** | **840** | **821** | **10** | **12** |

---

## 2. Pemetaan Skema Database Sistem (`sislab-fisika`)

Berikut pemetaan data Excel ke tabel-tabel database Laravel:

### A. Tabel `ruangans`
| Kolom | Tipe Data | Keterangan Sumber |
|:------|:----------|:------------------|
| `id` | `bigint unsigned` | Primary key auto increment (1 s.d. 6) |
| `nama_ruangan` | `string` | Nama ruangan laboratorium |
| `deskripsi` | `string` | Deskripsi fungsi dan peruntukan ruangan |

### B. Tabel `kategori_barangs`
| ID | Nama Kategori | Peruntukan Barang |
|:--:|:--------------|:------------------|
| 1 | `Alat Praktikum & Ukur` | Klasifikasi inventaris kategori alat praktikum & ukur |
| 2 | `Alat Praktikum Optik` | Klasifikasi inventaris kategori alat praktikum optik |
| 3 | `Alat Praktikum Elektronika` | Klasifikasi inventaris kategori alat praktikum elektronika |
| 4 | `Mebel & Furnitur Lab` | Klasifikasi inventaris kategori mebel & furnitur lab |
| 5 | `Elektronik & Komputer` | Klasifikasi inventaris kategori elektronik & komputer |
| 6 | `Perlengkapan Umum Lab` | Klasifikasi inventaris kategori perlengkapan umum lab |

### C. Tabel `barang_inventaris`
| Kolom Database | Tipe Data | Aturan Konversi dari Excel |
|:----------------|:----------|:---------------------------|
| `kode_barang` | `string(unique)` | Menggunakan Kode BMN asli bila ada; jika kosong diformat standar `[KODE_RUANG]-[NO]` (misal: `ADM-001`, `MEK-015`) |
| `nama_barang` | `string` | Diambil dari kolom *Nama Barang* |
| `merk` | `string(nullable)` | Diambil dari kolom *Merk / Identitas Barang* |
| `kategori_id` | `foreignId` | Terpetakan secara otomatis ke `kategori_barangs` |
| `ruangan_id` | `foreignId` | Mengacu pada ID ruangan tempat barang berada |
| `stok_baik` | `unsignedInteger` | Jumlah barang kondisi Baik (numerik / centang V) |
| `stok_rusak_ringan` | `unsignedInteger` | Jumlah barang kondisi Rusak Ringan |
| `stok_rusak_berat` | `unsignedInteger` | Jumlah barang kondisi Rusak Berat |
| `stok_hilang` | `unsignedInteger` | Nilai default `0` |
| `tanggal_pengadaan` | `date(nullable)` | Dikonversi dari kolom *Tahun Perolehan* (format `YYYY-01-01`) |
| `keterangan` | `text(nullable)` | Kolom *Keterangan* (lokasi penyimpanan, catatan barang) |

---

## 3. Ekstraksi Data Lengkap per Ruangan (262 Item Barang)

### Administrasi Laboratorium (`ADM`)
*Jumlah Data: 26 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `025.04.0600.423925.000.KD.2025 3.10.02.03.003. NUP: 1136` | Printer Brother DCP-T720DW | Brother | `025.04.0600.423925.000.KD.2025 3.10.02.03.003. NUP: 1136` | 2025 | 1 | 1 | 0 | 0 | Elektronik & Komputer |  |
| 2 | `ADM-002` | Air Conditioner | Panasonic |  | 2025 | 1 | 1 | 0 | 0 | Elektronik & Komputer |  |
| 3 | `ADM-003` | Komputer | Dell Intel Insside Core i3 |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 4 | `310.01.02.001.1713` | Komputer HP | HP intel CORE i5 | `310.01.02.001.1713` (NUP: 025.04.0600.423925.000.KD.2022) | 2022 | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 5 | `ADM-005` | CPU | Dell Vostro |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 6 | `ADM-006` | CPU | Intel Inside 4 G3 |  |  | 1 | 0 | 1 | 0 | Elektronik & Komputer | Lab PFS |
| 7 | `ADM-007` | PRINTER 1 | HP Deskkjet GT 5810 |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 8 | `ADM-008` | AC | SHARP |  |  | 2 | 0 | 1 | 1 | Elektronik & Komputer | Lab PFS |
| 9 | `ADM-009` | PROJEKTOR | EPSON |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 10 | `ADM-010` | KIPAS Angin | Polytron |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 11 | `ADM-011` | UPS Sistem | Emerson Liebert. It On BX |  |  | 2 | 2 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 12 | `3.06.01.01.048.1705` | UPS | Socomec | `3.06.01.01.048.1705` (NUP: 025.04.0600.423925.000.KD.2022) | 2022 | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 13 | `ADM-013` | Lemari Dokumen |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 14 | `3.05.01.04.001.390` | Lemari | Elite | `3.05.01.04.001.390` (NUP: 025.04.0600.423925.000.KD.2016) | 2016 | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 15 | `ADM-015` | Lemari Pilling | Mustang |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 16 | `ADM-016` | Lemari Pilling | Elite |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 17 | `ADM-017` | Kursi biru FTK |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 18 | `ADM-018` | Kursi Coklat | Elite |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 19 | `ADM-019` | Kursi Roda | Elite |  |  | 6 | 5 | 0 | 1 | Mebel & Furnitur Lab | Lab PFS |
| 20 | `3.05.02.01.002.2,880` | Meja |  | `3.05.02.01.002.2,880` (NUP: 025.04.0600.423925.000.KD.2016) | 2016 | 5 | 5 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 21 | `ADM-021` | Komputer | LG |  |  | 3 | 3 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 22 | `ADM-022` | Meja komputer | Macro |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 23 | `ADM-023` | Meja Televisi | safety glass |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 24 | `ADM-024` | Meja lingkaran/bulat |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 25 | `ADM-025` | Meja | Orbit trend |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 26 | `ADM-026` | Meja Biasa |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |

### Laboratorium Mekanika (`MEK`)
*Jumlah Data: 48 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `MEK-001` | AC | AKIRA |  | 2014 | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 2 | `MEK-002` | AC | AUX |  | 2015 | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 3 | `3.06.02.01.001` | KIPAS Angin | Arashi Corona | `3.06.02.01.001` | 2008 | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 4 | `MEK-004` | Lemari Kaca |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 5 | `3.05.01.04.001.389` | Lemari Alat | Elite | `3.05.01.04.001.389` (NUP: 025.04.0600.423925.000.KD.2016) | 2016 | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 6 | `MEK-006` | Kursi putar | Elite |  |  | 4 | 4 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 7 | `MEK-007` | Kursi lipat | Elite |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 9 | `2.05.01.02.1,123` | Kursi Hijau IAIN |  | `2.05.01.02.1,123` | 2006 | 6 | 6 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 10 | `MEK-010` | Kursi Biru | Chitose |  | 2006 | 3 | 3 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 11 | `MEK-011` | Kursi Biru FTK |  |  | 2008 | 6 | 6 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 12 | `2.05.01.02. 1,878` | Kursi Hijau dan Biru | Futura | `2.05.01.02. 1,878` | 2006 | 5 | 5 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 14 | `3.05.01.05.010.653` | Papan Tulis Kaca |  | `3.05.01.05.010.653` | 2016 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 15 | `3.05.02.01.002.2,887` | Meja Praktek |  | `3.05.02.01.002.2,887` (NUP: 025.04.0600.423925.000.KD.2016) | 2016 | 6 | 6 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 16 | `3.05.02.01.002.2,097` | Meja Asisten |  | `3.05.02.01.002.2,097` (NUP: 025.04.0600.423925.000.KD.2016) | 2016 | 2 | 1 | 1 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 18 | `MEK-018` | Jangka sorong | TriceBrand |  |  | 18 | 18 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 19 | `MEK-019` | Mikrometer sekrop | TriceBrand |  |  | 16 | 16 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 20 | `MEK-020` | Neraca Pegas | Eus |  |  | 17 | 17 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 21 | `MEK-021` | Dynamometer 3,0 N |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 22 | `MEK-022` | Pegas Biasa/per |  |  |  | 9 | 9 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 23 | `MEK-023` | Stopwach | Diamond |  |  | 6 | 3 | 0 | 3 | Alat Praktikum & Ukur | Lab PFS |
| 24 | `MEK-024` | Stopwact digital | JapanBrand |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 25 | `MEK-025` | Garpu tala |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 26 | `MEK-026` | Katrol | Pudak |  |  | 5 | 5 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 27 | `MEK-027` | Neraca satu Lengan | Ohaus |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 28 | `MEK-028` | Neraca Ohaus 3 lengan | Ohaus |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 29 | `MEK-029` | Neraca Ohaus digital | Ohaus |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 30 | `MEK-030` | Timbangan pasar | Camry |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 31 | `MEK-031` | Tabung resonansi | Pudak Scientific |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 32 | `MEK-032` | Ticker Timer | pudak |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 33 | `3.08.01.20.999.1` | Air Spray/Track | Pudak | `3.08.01.20.999.1` | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 34 | `MEK-034` | Bandul Reversibel | Pudak Scientific |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 35 | `MEK-035` | Vibrator Generator | Pudak Scientific |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 36 | `MEK-036` | Mobil Trolley | Pudak Scientific |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 37 | `MEK-037` | Alat Resonansi | Pudak Scientific |  | 2012 | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 38 | `3.08.01.20.005.1` | Jembatan Wheatstone | Pudak Scientific | `3.08.01.20.005.1` | 2020 | 1 Set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 39 | `3.08.01.20.999.3` | Tangki Gelombang | Pudak Scientific | `3.08.01.20.999.3` | 2020 | 2 set | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 40 | `3.08.01.20.999.15` | Timer Counter AT-01 | Pudak Scientific | `3.08.01.20.999.15` | 2020 | 4 Buah | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 41 | `3.08.01.20.999.13` | Timer Counter AT-02 | Pudak Scientific | `3.08.01.20.999.13` | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 42 | `MEK-042` | Alat Momen Inersia | Pudak Scientific |  | 2020 | 1 Set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 43 | `MEK-043` | KIT Mekanika PMS-500 | Pudak Scientific |  | 2020 | 2 Kotak | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 44 | `MEK-044` | KIT Mekanika FU-02 | Pudak Scientific |  | 2020 | 2 Kotak | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 45 | `MEK-045` | KIT IPA SMA Mekanika |  |  | 2018 | 1 Kotak | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 46 | `MEK-046` | KIT Demontrasi Getaran FAL 29R | Pudak Scientific |  | 2020 | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 47 | `MEK-047` | KIT Mechanics PMS 500 | Pudak Scientific |  | 2012 | 1 Kotak | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 48 | `MEK-048` | Air Track AT-02 | Pudak Scientific |  | 2020 | 1 Set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 49 | `MEK-049` | Pompa udara Pada rel Udara | QC Passed |  | 2020 | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 50 | `MEK-050` | Generator Frekuensi Audio FAL 2T | Pudak Scientific |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 51 | `MEK-051` | Gerak Jatuh Bebas | Pudak Scientific |  | 2020 | 2 set | 1 | 1 | 0 | Mebel & Furnitur Lab | Lab PFS |

### Laboratorium Listrik dan Magnet (`LISMAG`)
*Jumlah Data: 44 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `2.05.01.00.00:019` | CPU | Simbadda | `2.05.01.00.00:019` (NUP: 15.04.03.004.2005) | 2005 | 1 | 0 | 1 | 0 | Elektronik & Komputer | Lab PFS |
| 2 | `LISMAG-002` | CPU | Smasung |  |  | 1 | 0 | 1 | 0 | Elektronik & Komputer | Lab PFS |
| 3 | `LISMAG-003` | AC 3 | AUX |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 4 | `LISMAG-004` | AC 4 | LG Inverterv |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 5 | `LISMAG-005` | Lemari Kaca |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 6 | `3.05.01.04.001.388` | Lemari | Elite | `3.05.01.04.001.388` (NUP: 025.04.0600.423925.000.KD.2016) |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 7 | `LISMAG-007` | Kursi Roda | Elite |  |  | 5 | 5 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 8 | `LISMAG-008` | Kursi hijau biru |  |  |  | 30 | 30 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 9 | `3.07.01.08.162.24` | Meja Praktek listrik |  | `3.07.01.08.162.24` (NUP: 025.04.0600.423925.000.KD.2014) |  | 3 | 3 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 10 | `3.05.02.01.002.2,878` | Meja Praktek biasa |  | `3.05.02.01.002.2,878` (NUP: 025.04.0600.423925.000.KD.2016) |  | 5 | 5 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 11 | `3.05/01.05.010.654` | Papan Tulis |  | `3.05/01.05.010.654` (NUP: 025.04.0600.423925.000.KD.2016) |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 12 | `3.08.01.56.081.2` | Wastafel |  | `3.08.01.56.081.2` (NUP: 025.04.0600.423925.000.KD.2014) |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 13 | `3.05.01.04.001.389` | Audio Generator | Leg 102 | `3.05.01.04.001.389` | 2002 | 8 | 8 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 14 | `LISMAG-014` | Resistance Boxc | Pudak |  | 2020 | 6 | 6 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 15 | `LISMAG-015` | Basic Elektrometer | Pasco |  |  | 4 | 4 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 16 | `LISMAG-016` | Sliding Rheostat |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 17 | `LISMAG-017` | Reostat 3A, 10Ω |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 18 | `LISMAG-018` | Reostat 1A, 100Ω |  |  |  | 3 | 3 | 0 | 3 | Alat Praktikum Elektronika | Lab PFS |
| 19 | `LISMAG-019` | Kompas |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 20 | `LISMAG-020` | Resistor boxs |  |  |  | 9 | 9 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 21 | `LISMAG-021` | Resistor boxs 1000 Ω |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 22 | `LISMAG-022` | Basic Elektrometer |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 23 | `LISMAG-023` | Audio Generator |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 24 | `LISMAG-024` | Magnetic Filed Sensor | CMA BT52i |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 25 | `LISMAG-025` | Solenoida 50cm | PEF 300 |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 26 | `LISMAG-026` | Set Magnet dan motor listrik |  |  |  | 1 set | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 27 | `LISMAG-027` | Ampermeter | Pudak 500mA, 5A |  | 2020 | 6 | 6 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 28 | `LISMAG-028` | Multimeter | SANWA |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 29 | `LISMAG-029` | Multimeter | Winner KS-268 |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 30 | `LISMAG-030` | Multimeter | Winner YX-360 TRn |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 31 | `LISMAG-031` | Multimeter digital | DT9205A |  |  | 9 | 9 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 32 | `LISMAG-032` | Volmetere 15 Volt | Pudak |  | 2020 | 14 | 14 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 33 | `LISMAG-033` | Voltage Selector | Eisco |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 34 | `LISMAG-034` | Basic meter 90 | Kal 41 |  |  | 5 | 5 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 35 | `LISMAG-035` | Osiloskop | GW GOS-622G |  |  | 6 | 6 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 36 | `LISMAG-036` | Power Suplay | pudak |  | 2020 | 9 | 9 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 37 | `LISMAG-037` | Catu Daya |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 38 | `LISMAG-038` | Leica Geosistems/ Laser ukur jarak | Disto |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 39 | `LISMAG-039` | KIT Electricity and Magnetism | Pudak PEK 500 |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 40 | `LISMAG-040` | KIT Listrik dan Magnet | Pudak FU-04 |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 41 | `LISMAG-041` | KIT IPA SMA Listrik dan Magnet |  |  |  | 2 | 1 | 1 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 42 | `LISMAG-042` | Generator Vandegraff |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 43 | `LISMAG-043` | KIT Generator Vandegraff |  |  |  | 1 set | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 44 | `LISMAG-044` | Medan Magnet dalam Selenoida | PEF 300 Pudak |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |

### Laboratorium Fisika Dasar (Multi Fungsi) (`FISDAS`)
*Jumlah Data: 20 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `FISDAS-001` | AC | Daikin |  |  | 2 | 2 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 2 | `FISDAS-002` | Kursi | Quinnstar |  |  | 50 | 50 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 3 | `FISDAS-003` | Air Track/Suplay | pasco Scientifc |  |  | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 4 | `FISDAS-004` | Catu Daya High Voltage | pasco Scientifc |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 5 | `FISDAS-005` | Gelas Ukur 1000 ml | Gratech |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 6 | `FISDAS-006` | Gelas Ukur 25 ml | Pyrex iwaki TE-32 |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 7 | `FISDAS-007` | Rheostat | 3A, 10 ohm |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 8 | `FISDAS-008` | Resistor BOX |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 9 | `FISDAS-009` | Lamp | Pudak Scientific |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 10 | `FISDAS-010` | Volmeter 15 Volt | Pudak Scientific |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 11 | `FISDAS-011` | Galvanometer | Pudak Scientific |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 12 | `FISDAS-012` | Basic Meter | QC Passed |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 13 | `FISDAS-013` | Multimeter Digital | Sanwa |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 14 | `FISDAS-014` | Digital Multimeter | DT 9205A |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 15 | `FISDAS-015` | KIT Electrocity and Magnetism | PEK 500 |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 16 | `FISDAS-016` | Spectrocopy Experiment Set |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 17 | `FISDAS-017` | Neraca Ohauss | Ohauss |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 18 | `FISDAS-018` | Jangka Sorong | Tricle Brand |  |  | 6 | 6 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 19 | `FISDAS-019` | Project Bord |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 20 | `FISDAS-020` | Micrometer Sekrop | Tricle Brand |  |  | 7 | 7 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |

### Laboratorium Optik (`OPTIK`)
*Jumlah Data: 67 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `OPTIK-001` | Kursi Lipat/Kursi Kuliah | Chitose |  |  | 7 | 7 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 2 | `OPTIK-002` | Meja Biasa |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 3 | `OPTIK-003` | Lemari Besi | Mustang |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 4 | `OPTIK-004` | Meja Praktek Panjang |  |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 5 | `2.05.01.02.1,617` | Kursi Hijau |  | `2.05.01.02.1,617` (NUP: 15.04.03.001.2006) | 2006 | 8 | 8 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 6 | `OPTIK-006` | Kursi Kuliah merah | Chitose |  |  | 12 | 12 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 7 | `OPTIK-007` | Infokus | Sony |  |  | 1 | 0 | 0 | 1 | Elektronik & Komputer | Lab PFS |
| 8 | `OPTIK-008` | Layar Infokus | Screen |  |  | 1 | 0 | 1 | 0 | Elektronik & Komputer | Lab PFS |
| 9 | `OPTIK-009` | Papan Tulis Kaca |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 10 | `OPTIK-010` | Kipas Angin | Arashi Corona |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 11 | `OPTIK-011` | Meja Dosen |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 12 | `OPTIK-012` | Kursi Dosen |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 13 | `OPTIK-013` | AC | AUX |  |  | 1 | 0 | 0 | 1 | Elektronik & Komputer | Lab PFS |
| 14 | `OPTIK-014` | Kursi Biru | Futura,Chitose |  |  | 8 | 8 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 15 | `OPTIK-015` | Kursi Coklat | Elite |  |  | 7 | 7 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 16 | `OPTIK-016` | Kipas Angin | Maspion |  |  | 1 | 0 | 0 | 1 | Elektronik & Komputer | Lab PFS |
| 17 | `OPTIK-017` | Infokus | EPSON |  |  | 1 | 0 | 0 | 1 | Elektronik & Komputer | Lab PFS |
| 18 | `OPTIK-018` | Cermin datar |  |  |  | 12 | 12 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 19 | `OPTIK-019` | Cermin cembung f=-100 mm |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 20 | `OPTIK-020` | Cerming Cekung, f= + 100mm |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 21 | `OPTIK-021` | Kaca Plan Paralel |  |  |  | 6 | 6 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 22 | `OPTIK-022` | Basic optic | Pudak Scientific |  | 2006 | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 23 | `OPTIK-023` | Spektrometer |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 24 | `OPTIK-024` | High sensitivity light sensor |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 25 | `OPTIK-025` | Experimental Apparatus |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 26 | `OPTIK-026` | Presision interverometer |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 27 | `OPTIK-027` | Mirror Conceve |  |  |  | 3 kotak | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 28 | `OPTIK-028` | Millikan Oil Drop Apparatus |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 29 | `OPTIK-029` | Rol Skala |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 30 | `OPTIK-030` | Power Suplay/catu Daya |  |  |  | 6 | 6 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 31 | `OPTIK-031` | Prisma |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 32 | `OPTIK-032` | Diafragma celah |  |  |  | 10 | 10 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 33 | `OPTIK-033` | Diafragma Pemegang |  |  |  | 5 | 5 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 34 | `OPTIK-034` | Lensa Bikoncap |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 35 | `OPTIK-035` | Lensa Biconvec |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 36 | `OPTIK-036` | Lensa Warna |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 37 | `OPTIK-037` | Lensa cembung |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 38 | `OPTIK-038` | Lensa cekung |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 39 | `OPTIK-039` | Lensa Cembung dan Datar |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 40 | `OPTIK-040` | Lensa f+100 |  |  |  | 6 | 6 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 41 | `OPTIK-041` | Rax Box |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 42 | `OPTIK-042` | KIT Optik FU-03 | Pudak Scientific |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 43 | `OPTIK-043` | KIT Optik | Pudak Scientific |  | 2020 | 5 | 4 | 1 | 0 | Alat Praktikum Optik | Lab PFS |
| 44 | `OPTIK-044` | KIT IPA SMU Optika |  |  |  | 1 | 0 | 1 | 0 | Alat Praktikum Optik | Lab PFS |
| 45 | `OPTIK-045` | KIT OPTIK Tipe Panel |  |  |  | 2 Set | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 46 | `OPTIK-046` | KIT Optik Geometris |  |  |  | 1 Set | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 47 | `OPTIK-047` | Na Light Source |  |  |  |  | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 48 | `OPTIK-048` | Hg Light Source | Pasco |  | 2006 | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 49 | `OPTIK-049` | Rotary motion sensor |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 50 | `OPTIK-050` | Rel Optik/ Basic Optika |  |  |  | 12 | 12 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 51 | `OPTIK-051` | Lampu Fokus |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 52 | `OPTIK-052` | Lasser atau sinar lasser |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 53 | `OPTIK-053` | Kaca |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 54 | `OPTIK-054` | Layar Putih |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 55 | `3.05.08.040.1` | Teropong Bintang Celestron Omni |  | `3.05.08.040.1` (NUP: 025.04.0600.423925.000.KD.2018) | 2018 | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 56 | `OPTIK-056` | Set Lensa |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 57 | `OPTIK-057` | Ray Table |  |  |  | 5 | 5 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 58 | `OPTIK-058` | Force Table |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 59 | `OPTIK-059` | Busur Lingkaran |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 60 | `OPTIK-060` | Layar Titik Fokus Bulat |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 61 | `OPTIK-061` | Single Slet Set |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 62 | `OPTIK-062` | Filter Polarisasi |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 63 | `OPTIK-063` | Ring Launcher |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 64 | `OPTIK-064` | Light Source Besic Optik |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 65 | `OPTIK-065` | Laser Radiation (Helium Neon Gas Laser |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 66 | `OPTIK-066` | Dioda laser |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |
| 67 | `OPTIK-067` | Polarisasi Analizer |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum Optik | Lab PFS |

### Laboratorium Termodinamika (`THERMO`)
*Jumlah Data: 57 item*

| No | Kode Barang (Sistem) | Nama Barang | Merk / Spesifikasi | Kode BMN / NUP | Tahun | Qty | Baik | R. Ringan | R. Berat | Kategori | Keterangan |
|:--:|:--------------------:|:------------|:-------------------|:--------------:|:-----:|:---:|:----:|:---------:|:--------:|:---------|:-----------|
| 1 | `THERMO-001` | Kursi Putar | Ichiko |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 2 | `THERMO-002` | Meja Biasa |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 3 | `2.05.02.01.04:82` | Meja kepala |  | `2.05.02.01.04:82` (NUP: 15.04.03.004:73) | 1973 | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 4 | `THERMO-004` | Lemari Kaca |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 5 | `2.05.01.04.04` | Lemari Pilling |  | `2.05.01.04.04` (NUP: 25.04.06.305537.2009) | 2009 | 3 | 3 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 6 | `2.05.02.01.01.203` | Lemari Dokumen |  | `2.05.02.01.01.203` (NUP: 15.114.03,001.99) | 1999 | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 7 | `THERMO-007` | Meja Praktek Panjang |  |  |  | 5 | 5 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 8 | `THERMO-008` | Kursi Hijau |  |  |  | 7 | 7 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 9 | `THERMO-009` | Kursi Putar | Danati |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 10 | `THERMO-010` | Loker penitipan barang |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 11 | `THERMO-011` | Komputer | Samsung |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 12 | `THERMO-012` | Komputer | K Minfo |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 13 | `THERMO-013` | Papan Tulis |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 14 | `THERMO-014` | Lemari Skripsi dan Koloqium |  |  |  | 2 | 2 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 15 | `THERMO-015` | Lemari Buku |  |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 16 | `THERMO-016` | Kursi Putar | Indhaci |  |  | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 17 | `THERMO-017` | AC | Panasonic |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 18 | `THERMO-018` | AC | Akira |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 19 | `THERMO-019` | Printer | Canqu |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 20 | `THERMO-020` | Kursi Biru | Futura,Chitose |  |  | 22 | 22 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 21 | `THERMO-021` | Meja | Orbit Trend |  | 2013 | 1 | 1 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 22 | `THERMO-022` | Infokus | Benq |  |  | 1 | 1 | 0 | 0 | Elektronik & Komputer | Lab PFS |
| 23 | `THERMO-023` | Pemuaian Panjang |  |  |  | 7 | 7 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 24 | `THERMO-024` | Kompor listrik | Maspion |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 25 | `THERMO-025` | Termometer Air raksa |  |  |  | 8 | 8 | 0 | 0 | Mebel & Furnitur Lab | Lab PFS |
| 26 | `THERMO-026` | Termometer | DRY |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 27 | `THERMO-027` | Barometer |  |  |  | 5 | 5 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 28 | `THERMO-028` | Gelas Ukur Baker 100 ml |  |  |  | 3 | 3 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 29 | `THERMO-029` | Gelas Ukur Pyrex 100 ml |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 30 | `THERMO-030` | Gelas Ukur Pyrex 500 ml | Iwaki |  |  | 3 | 3 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 31 | `THERMO-031` | Gelas Baker 250 ml | Iwaki |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 32 | `THERMO-032` | Gelas Ukur Pyrex 10 ml |  |  |  | 9 | 9 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 33 | `THERMO-033` | Gelas berbentuk U kecil |  |  |  | 6 | 6 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 34 | `THERMO-034` | Gelas Kimia Pyrex 250 ml | Iwaki |  |  | 8 | 8 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 35 | `THERMO-035` | Gelas Kimia Pyrex 50 ml | Iwaki |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 36 | `THERMO-036` | Pembakar Spiritus |  |  |  | 11 | 11 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 37 | `THERMO-037` | Kalorimeter |  |  |  | 4 | 4 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 38 | `THERMO-038` | Steam Generator | Pasco |  | 2013 | 1 | 1 | 0 | 0 | Alat Praktikum Elektronika | Lab PFS |
| 39 | `THERMO-039` | Extech”K-Type Thermometer | Extech |  |  | 6 | 6 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 40 | `THERMO-040` | Asbes Kompor |  |  |  | 9 | 9 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 41 | `THERMO-041` | Tungku Kaki Tiga |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 42 | `THERMO-042` | Termometer C-F | Corona |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 43 | `THERMO-043` | Termometer C-F |  |  |  | 6 | 6 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 44 | `THERMO-044` | Gelas Cerek |  |  |  | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 45 | `THERMO-045` | KIT Gelombang dan Termodinamika | Pudak Scientific |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 46 | `THERMO-046` | KIT Gelombang dan Termodinamika |  |  | 2018 | 1 Set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 47 | `3.08.01.20.999.6` | Kalorimeter PHM 300 | Pudak Scientific | `3.08.01.20.999.6` | 2020 | 1 Set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 48 | `THERMO-048` | Pemanasan Listrik dan Pembangkit Uap ( Percobaan Kalorimeter) |  |  | 2020 | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 49 | `THERMO-049` | Compound Gauge | Pudak Scientific |  | 2020 | 1 set | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 50 | `3.08.01.13.999.231` | Infrared Thermometer | Tenmars TM-301 | `3.08.01.13.999.231` | 2019 | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 51 | `THERMO-051` | Gelas Cerek Panjang |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 52 | `THERMO-052` | Higrometer | Pudak Scientific |  | 2020 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 53 | `THERMO-053` | Manometer Open | Pudak Scientific |  | 2020 | 2 | 2 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 54 | `THERMO-054` | Sound Level Meter | Extech |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 55 | `THERMO-055` | Elemen Pemanas | Intra |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 56 | `THERMO-056` | Semprot Oil |  |  |  | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |
| 57 | `THERMO-057` | Thermal Coductivity Apparatus | Pasco |  | 2006 | 1 | 1 | 0 | 0 | Alat Praktikum & Ukur | Lab PFS |

---

## 4. Implementasi Database Seeder Laravel (Siap Pakai)

Berikut adalah kode seeder lengkap yang dapat langsung disalin ke berkas seeder terpisah di direktori `database/seeders/`.

### A. Seeder Ruangan (`database/seeders/LabRuanganSeeder.php`)
```php
<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class LabRuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            ['id' => 1, 'nama_ruangan' => 'Administrasi Laboratorium', 'deskripsi' => 'Ruang administrasi dan pengelolaan laboratorium Program Studi Fisika.'],
            ['id' => 2, 'nama_ruangan' => 'Laboratorium Mekanika', 'deskripsi' => 'Laboratorium praktikum mekanika dan dinamika fluida/benda tegar.'],
            ['id' => 3, 'nama_ruangan' => 'Laboratorium Listrik dan Magnet', 'deskripsi' => 'Laboratorium praktikum elektromagnetika, elektronika dasar, dan sirkuit.'],
            ['id' => 4, 'nama_ruangan' => 'Laboratorium Fisika Dasar (Multi Fungsi)', 'deskripsi' => 'Laboratorium multifungsi untuk pelaksanaan praktikum Fisika Dasar.'],
            ['id' => 5, 'nama_ruangan' => 'Laboratorium Optik', 'deskripsi' => 'Laboratorium praktikum optika geometri, gelombang, dan interferensi cahaya.'],
            ['id' => 6, 'nama_ruangan' => 'Laboratorium Termodinamika', 'deskripsi' => 'Laboratorium praktikum perpindahan kalor dan hukum-hukum termodinamika.'],
        ];

        foreach ($ruangans as $r) {
            Ruangan::updateOrCreate(['id' => $r['id']], $r);
        }
    }
}
```

### B. Seeder Kategori Barang (`database/seeders/LabKategoriBarangSeeder.php`)
```php
<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class LabKategoriBarangSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['id' => 1, 'nama_kategori' => 'Alat Praktikum & Ukur'],
            ['id' => 2, 'nama_kategori' => 'Alat Praktikum Optik'],
            ['id' => 3, 'nama_kategori' => 'Alat Praktikum Elektronika'],
            ['id' => 4, 'nama_kategori' => 'Mebel & Furnitur Lab'],
            ['id' => 5, 'nama_kategori' => 'Elektronik & Komputer'],
            ['id' => 6, 'nama_kategori' => 'Perlengkapan Umum Lab'],
        ];

        foreach ($kategoris as $k) {
            KategoriBarang::updateOrCreate(['id' => $k['id']], $k);
        }
    }
}
```

### C. Seeder Barang Inventaris Lengkap (`database/seeders/LabBarangFisikaSeeder.php`)
Seeder ini memuat **seluruh 262 item barang fisik asli**:
```php
<?php

namespace Database\Seeders;

use App\Models\BarangInventaris;
use Illuminate\Database\Seeder;

class LabBarangFisikaSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            // ========================================================
            // ADMINISTRASI LABORATORIUM (26 item)
            // ========================================================
            ['kode_barang' => '025.04.0600.423925.000.KD.2025 3.10.02.03.003. NUP: 1136', 'nama_barang' => 'Printer Brother DCP-T720DW', 'merk' => 'Brother', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2025-01-01', 'keterangan' => null],
            ['kode_barang' => 'ADM-002', 'nama_barang' => 'Air Conditioner', 'merk' => 'Panasonic', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2025-01-01', 'keterangan' => null],
            ['kode_barang' => 'ADM-003', 'nama_barang' => 'Komputer', 'merk' => 'Dell Intel Insside Core i3', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '310.01.02.001.1713', 'nama_barang' => 'Komputer HP', 'merk' => 'HP intel CORE i5', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2022-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-005', 'nama_barang' => 'CPU', 'merk' => 'Dell Vostro', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-006', 'nama_barang' => 'CPU', 'merk' => 'Intel Inside 4 G3', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-007', 'nama_barang' => 'PRINTER 1', 'merk' => 'HP Deskkjet GT 5810', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-008', 'nama_barang' => 'AC', 'merk' => 'SHARP', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-009', 'nama_barang' => 'PROJEKTOR', 'merk' => 'EPSON', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-010', 'nama_barang' => 'KIPAS Angin', 'merk' => 'Polytron', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-011', 'nama_barang' => 'UPS Sistem', 'merk' => 'Emerson Liebert. It On BX', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.06.01.01.048.1705', 'nama_barang' => 'UPS', 'merk' => 'Socomec', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2022-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-013', 'nama_barang' => 'Lemari Dokumen', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.01.04.001.390', 'nama_barang' => 'Lemari', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-015', 'nama_barang' => 'Lemari Pilling', 'merk' => 'Mustang', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-016', 'nama_barang' => 'Lemari Pilling', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-017', 'nama_barang' => 'Kursi biru FTK', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-018', 'nama_barang' => 'Kursi Coklat', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-019', 'nama_barang' => 'Kursi Roda', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.02.01.002.2,880', 'nama_barang' => 'Meja', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-021', 'nama_barang' => 'Komputer', 'merk' => 'LG', 'kategori_id' => 5, 'ruangan_id' => 1, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-022', 'nama_barang' => 'Meja komputer', 'merk' => 'Macro', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-023', 'nama_barang' => 'Meja Televisi', 'merk' => 'safety glass', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-024', 'nama_barang' => 'Meja lingkaran/bulat', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-025', 'nama_barang' => 'Meja', 'merk' => 'Orbit trend', 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'ADM-026', 'nama_barang' => 'Meja Biasa', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 1, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            // ========================================================
            // LABORATORIUM MEKANIKA (48 item)
            // ========================================================
            ['kode_barang' => 'MEK-001', 'nama_barang' => 'AC', 'merk' => 'AKIRA', 'kategori_id' => 5, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2014-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-002', 'nama_barang' => 'AC', 'merk' => 'AUX', 'kategori_id' => 5, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2015-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.06.02.01.001', 'nama_barang' => 'KIPAS Angin', 'merk' => 'Arashi Corona', 'kategori_id' => 5, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2008-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-004', 'nama_barang' => 'Lemari Kaca', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.01.04.001.389', 'nama_barang' => 'Lemari Alat', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-006', 'nama_barang' => 'Kursi putar', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-007', 'nama_barang' => 'Kursi lipat', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.01.02.1,123', 'nama_barang' => 'Kursi Hijau IAIN', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-010', 'nama_barang' => 'Kursi Biru', 'merk' => 'Chitose', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-011', 'nama_barang' => 'Kursi Biru FTK', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2008-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.01.02. 1,878', 'nama_barang' => 'Kursi Hijau dan Biru', 'merk' => 'Futura', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.01.05.010.653', 'nama_barang' => 'Papan Tulis Kaca', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.02.01.002.2,887', 'nama_barang' => 'Meja Praktek', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.02.01.002.2,097', 'nama_barang' => 'Meja Asisten', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2016-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-018', 'nama_barang' => 'Jangka sorong', 'merk' => 'TriceBrand', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 18, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-019', 'nama_barang' => 'Mikrometer sekrop', 'merk' => 'TriceBrand', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 16, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-020', 'nama_barang' => 'Neraca Pegas', 'merk' => 'Eus', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 17, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-021', 'nama_barang' => 'Dynamometer 3,0 N', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-022', 'nama_barang' => 'Pegas Biasa/per', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-023', 'nama_barang' => 'Stopwach', 'merk' => 'Diamond', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 3, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-024', 'nama_barang' => 'Stopwact digital', 'merk' => 'JapanBrand', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-025', 'nama_barang' => 'Garpu tala', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-026', 'nama_barang' => 'Katrol', 'merk' => 'Pudak', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-027', 'nama_barang' => 'Neraca satu Lengan', 'merk' => 'Ohaus', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-028', 'nama_barang' => 'Neraca Ohaus 3 lengan', 'merk' => 'Ohaus', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-029', 'nama_barang' => 'Neraca Ohaus digital', 'merk' => 'Ohaus', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-030', 'nama_barang' => 'Timbangan pasar', 'merk' => 'Camry', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-031', 'nama_barang' => 'Tabung resonansi', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-032', 'nama_barang' => 'Ticker Timer', 'merk' => 'pudak', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.999.1', 'nama_barang' => 'Air Spray/Track', 'merk' => 'Pudak', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-034', 'nama_barang' => 'Bandul Reversibel', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-035', 'nama_barang' => 'Vibrator Generator', 'merk' => 'Pudak Scientific', 'kategori_id' => 3, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-036', 'nama_barang' => 'Mobil Trolley', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-037', 'nama_barang' => 'Alat Resonansi', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2012-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.005.1', 'nama_barang' => 'Jembatan Wheatstone', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.999.3', 'nama_barang' => 'Tangki Gelombang', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.999.15', 'nama_barang' => 'Timer Counter AT-01', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.999.13', 'nama_barang' => 'Timer Counter AT-02', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-042', 'nama_barang' => 'Alat Momen Inersia', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-043', 'nama_barang' => 'KIT Mekanika PMS-500', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-044', 'nama_barang' => 'KIT Mekanika FU-02', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-045', 'nama_barang' => 'KIT IPA SMA Mekanika', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2018-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-046', 'nama_barang' => 'KIT Demontrasi Getaran FAL 29R', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-047', 'nama_barang' => 'KIT Mechanics PMS 500', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2012-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-048', 'nama_barang' => 'Air Track AT-02', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-049', 'nama_barang' => 'Pompa udara Pada rel Udara', 'merk' => 'QC Passed', 'kategori_id' => 1, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-050', 'nama_barang' => 'Generator Frekuensi Audio FAL 2T', 'merk' => 'Pudak Scientific', 'kategori_id' => 3, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'MEK-051', 'nama_barang' => 'Gerak Jatuh Bebas', 'merk' => 'Pudak Scientific', 'kategori_id' => 4, 'ruangan_id' => 2, 'stok_baik' => 1, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            // ========================================================
            // LABORATORIUM LISTRIK DAN MAGNET (44 item)
            // ========================================================
            ['kode_barang' => '2.05.01.00.00:019', 'nama_barang' => 'CPU', 'merk' => 'Simbadda', 'kategori_id' => 5, 'ruangan_id' => 3, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2005-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-002', 'nama_barang' => 'CPU', 'merk' => 'Smasung', 'kategori_id' => 5, 'ruangan_id' => 3, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-003', 'nama_barang' => 'AC 3', 'merk' => 'AUX', 'kategori_id' => 5, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-004', 'nama_barang' => 'AC 4', 'merk' => 'LG Inverterv', 'kategori_id' => 5, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-005', 'nama_barang' => 'Lemari Kaca', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.01.04.001.388', 'nama_barang' => 'Lemari', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-007', 'nama_barang' => 'Kursi Roda', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-008', 'nama_barang' => 'Kursi hijau biru', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 30, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.07.01.08.162.24', 'nama_barang' => 'Meja Praktek listrik', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.02.01.002.2,878', 'nama_barang' => 'Meja Praktek biasa', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05/01.05.010.654', 'nama_barang' => 'Papan Tulis', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.56.081.2', 'nama_barang' => 'Wastafel', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.01.04.001.389', 'nama_barang' => 'Audio Generator', 'merk' => 'Leg 102', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 8, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2002-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-014', 'nama_barang' => 'Resistance Boxc', 'merk' => 'Pudak', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-015', 'nama_barang' => 'Basic Elektrometer', 'merk' => 'Pasco', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-016', 'nama_barang' => 'Sliding Rheostat', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-017', 'nama_barang' => 'Reostat 3A, 10Ω', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-018', 'nama_barang' => 'Reostat 1A, 100Ω', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 3, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-019', 'nama_barang' => 'Kompas', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-020', 'nama_barang' => 'Resistor boxs', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-021', 'nama_barang' => 'Resistor boxs 1000 Ω', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-022', 'nama_barang' => 'Basic Elektrometer', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-023', 'nama_barang' => 'Audio Generator', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-024', 'nama_barang' => 'Magnetic Filed Sensor', 'merk' => 'CMA BT52i', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-025', 'nama_barang' => 'Solenoida 50cm', 'merk' => 'PEF 300', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-026', 'nama_barang' => 'Set Magnet dan motor listrik', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-027', 'nama_barang' => 'Ampermeter', 'merk' => 'Pudak 500mA, 5A', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-028', 'nama_barang' => 'Multimeter', 'merk' => 'SANWA', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-029', 'nama_barang' => 'Multimeter', 'merk' => 'Winner KS-268', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-030', 'nama_barang' => 'Multimeter', 'merk' => 'Winner YX-360 TRn', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-031', 'nama_barang' => 'Multimeter digital', 'merk' => 'DT9205A', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-032', 'nama_barang' => 'Volmetere 15 Volt', 'merk' => 'Pudak', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 14, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-033', 'nama_barang' => 'Voltage Selector', 'merk' => 'Eisco', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-034', 'nama_barang' => 'Basic meter 90', 'merk' => 'Kal 41', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-035', 'nama_barang' => 'Osiloskop', 'merk' => 'GW GOS-622G', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-036', 'nama_barang' => 'Power Suplay', 'merk' => 'pudak', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-037', 'nama_barang' => 'Catu Daya', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-038', 'nama_barang' => 'Leica Geosistems/ Laser ukur jarak', 'merk' => 'Disto', 'kategori_id' => 4, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-039', 'nama_barang' => 'KIT Electricity and Magnetism', 'merk' => 'Pudak PEK 500', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-040', 'nama_barang' => 'KIT Listrik dan Magnet', 'merk' => 'Pudak FU-04', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-041', 'nama_barang' => 'KIT IPA SMA Listrik dan Magnet', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-042', 'nama_barang' => 'Generator Vandegraff', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-043', 'nama_barang' => 'KIT Generator Vandegraff', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'LISMAG-044', 'nama_barang' => 'Medan Magnet dalam Selenoida', 'merk' => 'PEF 300 Pudak', 'kategori_id' => 3, 'ruangan_id' => 3, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            // ========================================================
            // LABORATORIUM FISIKA DASAR (MULTI FUNGSI) (20 item)
            // ========================================================
            ['kode_barang' => 'FISDAS-001', 'nama_barang' => 'AC', 'merk' => 'Daikin', 'kategori_id' => 5, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-002', 'nama_barang' => 'Kursi', 'merk' => 'Quinnstar', 'kategori_id' => 4, 'ruangan_id' => 4, 'stok_baik' => 50, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-003', 'nama_barang' => 'Air Track/Suplay', 'merk' => 'pasco Scientifc', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-004', 'nama_barang' => 'Catu Daya High Voltage', 'merk' => 'pasco Scientifc', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-005', 'nama_barang' => 'Gelas Ukur 1000 ml', 'merk' => 'Gratech', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-006', 'nama_barang' => 'Gelas Ukur 25 ml', 'merk' => 'Pyrex iwaki TE-32', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-007', 'nama_barang' => 'Rheostat', 'merk' => '3A, 10 ohm', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-008', 'nama_barang' => 'Resistor BOX', 'merk' => null, 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-009', 'nama_barang' => 'Lamp', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-010', 'nama_barang' => 'Volmeter 15 Volt', 'merk' => 'Pudak Scientific', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-011', 'nama_barang' => 'Galvanometer', 'merk' => 'Pudak Scientific', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-012', 'nama_barang' => 'Basic Meter', 'merk' => 'QC Passed', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-013', 'nama_barang' => 'Multimeter Digital', 'merk' => 'Sanwa', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-014', 'nama_barang' => 'Digital Multimeter', 'merk' => 'DT 9205A', 'kategori_id' => 3, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-015', 'nama_barang' => 'KIT Electrocity and Magnetism', 'merk' => 'PEK 500', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-016', 'nama_barang' => 'Spectrocopy Experiment Set', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-017', 'nama_barang' => 'Neraca Ohauss', 'merk' => 'Ohauss', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-018', 'nama_barang' => 'Jangka Sorong', 'merk' => 'Tricle Brand', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-019', 'nama_barang' => 'Project Bord', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'FISDAS-020', 'nama_barang' => 'Micrometer Sekrop', 'merk' => 'Tricle Brand', 'kategori_id' => 1, 'ruangan_id' => 4, 'stok_baik' => 7, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            // ========================================================
            // LABORATORIUM OPTIK (67 item)
            // ========================================================
            ['kode_barang' => 'OPTIK-001', 'nama_barang' => 'Kursi Lipat/Kursi Kuliah', 'merk' => 'Chitose', 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 7, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-002', 'nama_barang' => 'Meja Biasa', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-003', 'nama_barang' => 'Lemari Besi', 'merk' => 'Mustang', 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-004', 'nama_barang' => 'Meja Praktek Panjang', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.01.02.1,617', 'nama_barang' => 'Kursi Hijau', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 8, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-006', 'nama_barang' => 'Kursi Kuliah merah', 'merk' => 'Chitose', 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 12, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-007', 'nama_barang' => 'Infokus', 'merk' => 'Sony', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-008', 'nama_barang' => 'Layar Infokus', 'merk' => 'Screen', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-009', 'nama_barang' => 'Papan Tulis Kaca', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-010', 'nama_barang' => 'Kipas Angin', 'merk' => 'Arashi Corona', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-011', 'nama_barang' => 'Meja Dosen', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-012', 'nama_barang' => 'Kursi Dosen', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-013', 'nama_barang' => 'AC', 'merk' => 'AUX', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-014', 'nama_barang' => 'Kursi Biru', 'merk' => 'Futura,Chitose', 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 8, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-015', 'nama_barang' => 'Kursi Coklat', 'merk' => 'Elite', 'kategori_id' => 4, 'ruangan_id' => 5, 'stok_baik' => 7, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-016', 'nama_barang' => 'Kipas Angin', 'merk' => 'Maspion', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-017', 'nama_barang' => 'Infokus', 'merk' => 'EPSON', 'kategori_id' => 5, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-018', 'nama_barang' => 'Cermin datar', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 12, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-019', 'nama_barang' => 'Cermin cembung f=-100 mm', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-020', 'nama_barang' => 'Cerming Cekung, f= + 100mm', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-021', 'nama_barang' => 'Kaca Plan Paralel', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-022', 'nama_barang' => 'Basic optic', 'merk' => 'Pudak Scientific', 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-023', 'nama_barang' => 'Spektrometer', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-024', 'nama_barang' => 'High sensitivity light sensor', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-025', 'nama_barang' => 'Experimental Apparatus', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-026', 'nama_barang' => 'Presision interverometer', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-027', 'nama_barang' => 'Mirror Conceve', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-028', 'nama_barang' => 'Millikan Oil Drop Apparatus', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-029', 'nama_barang' => 'Rol Skala', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-030', 'nama_barang' => 'Power Suplay/catu Daya', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-031', 'nama_barang' => 'Prisma', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-032', 'nama_barang' => 'Diafragma celah', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 10, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-033', 'nama_barang' => 'Diafragma Pemegang', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-034', 'nama_barang' => 'Lensa Bikoncap', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-035', 'nama_barang' => 'Lensa Biconvec', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-036', 'nama_barang' => 'Lensa Warna', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-037', 'nama_barang' => 'Lensa cembung', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-038', 'nama_barang' => 'Lensa cekung', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-039', 'nama_barang' => 'Lensa Cembung dan Datar', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-040', 'nama_barang' => 'Lensa f+100', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-041', 'nama_barang' => 'Rax Box', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-042', 'nama_barang' => 'KIT Optik FU-03', 'merk' => 'Pudak Scientific', 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-043', 'nama_barang' => 'KIT Optik', 'merk' => 'Pudak Scientific', 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 4, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-044', 'nama_barang' => 'KIT IPA SMU Optika', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 0, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-045', 'nama_barang' => 'KIT OPTIK Tipe Panel', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-046', 'nama_barang' => 'KIT Optik Geometris', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-047', 'nama_barang' => 'Na Light Source', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-048', 'nama_barang' => 'Hg Light Source', 'merk' => 'Pasco', 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-049', 'nama_barang' => 'Rotary motion sensor', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-050', 'nama_barang' => 'Rel Optik/ Basic Optika', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 12, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-051', 'nama_barang' => 'Lampu Fokus', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-052', 'nama_barang' => 'Lasser atau sinar lasser', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-053', 'nama_barang' => 'Kaca', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-054', 'nama_barang' => 'Layar Putih', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.05.08.040.1', 'nama_barang' => 'Teropong Bintang Celestron Omni', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2018-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-056', 'nama_barang' => 'Set Lensa', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-057', 'nama_barang' => 'Ray Table', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-058', 'nama_barang' => 'Force Table', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-059', 'nama_barang' => 'Busur Lingkaran', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-060', 'nama_barang' => 'Layar Titik Fokus Bulat', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-061', 'nama_barang' => 'Single Slet Set', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-062', 'nama_barang' => 'Filter Polarisasi', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-063', 'nama_barang' => 'Ring Launcher', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-064', 'nama_barang' => 'Light Source Besic Optik', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-065', 'nama_barang' => 'Laser Radiation (Helium Neon Gas Laser', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-066', 'nama_barang' => 'Dioda laser', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'OPTIK-067', 'nama_barang' => 'Polarisasi Analizer', 'merk' => null, 'kategori_id' => 2, 'ruangan_id' => 5, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            // ========================================================
            // LABORATORIUM TERMODINAMIKA (57 item)
            // ========================================================
            ['kode_barang' => 'THERMO-001', 'nama_barang' => 'Kursi Putar', 'merk' => 'Ichiko', 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-002', 'nama_barang' => 'Meja Biasa', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.02.01.04:82', 'nama_barang' => 'Meja kepala', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-004', 'nama_barang' => 'Lemari Kaca', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.01.04.04', 'nama_barang' => 'Lemari Pilling', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2009-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '2.05.02.01.01.203', 'nama_barang' => 'Lemari Dokumen', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '1999-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-007', 'nama_barang' => 'Meja Praktek Panjang', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-008', 'nama_barang' => 'Kursi Hijau', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 7, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-009', 'nama_barang' => 'Kursi Putar', 'merk' => 'Danati', 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-010', 'nama_barang' => 'Loker penitipan barang', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-011', 'nama_barang' => 'Komputer', 'merk' => 'Samsung', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-012', 'nama_barang' => 'Komputer', 'merk' => 'K Minfo', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-013', 'nama_barang' => 'Papan Tulis', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-014', 'nama_barang' => 'Lemari Skripsi dan Koloqium', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-015', 'nama_barang' => 'Lemari Buku', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-016', 'nama_barang' => 'Kursi Putar', 'merk' => 'Indhaci', 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-017', 'nama_barang' => 'AC', 'merk' => 'Panasonic', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-018', 'nama_barang' => 'AC', 'merk' => 'Akira', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-019', 'nama_barang' => 'Printer', 'merk' => 'Canqu', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-020', 'nama_barang' => 'Kursi Biru', 'merk' => 'Futura,Chitose', 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 22, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-021', 'nama_barang' => 'Meja', 'merk' => 'Orbit Trend', 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2013-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-022', 'nama_barang' => 'Infokus', 'merk' => 'Benq', 'kategori_id' => 5, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-023', 'nama_barang' => 'Pemuaian Panjang', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 7, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-024', 'nama_barang' => 'Kompor listrik', 'merk' => 'Maspion', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-025', 'nama_barang' => 'Termometer Air raksa', 'merk' => null, 'kategori_id' => 4, 'ruangan_id' => 6, 'stok_baik' => 8, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-026', 'nama_barang' => 'Termometer', 'merk' => 'DRY', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-027', 'nama_barang' => 'Barometer', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 5, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-028', 'nama_barang' => 'Gelas Ukur Baker 100 ml', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-029', 'nama_barang' => 'Gelas Ukur Pyrex 100 ml', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-030', 'nama_barang' => 'Gelas Ukur Pyrex 500 ml', 'merk' => 'Iwaki', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 3, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-031', 'nama_barang' => 'Gelas Baker 250 ml', 'merk' => 'Iwaki', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-032', 'nama_barang' => 'Gelas Ukur Pyrex 10 ml', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-033', 'nama_barang' => 'Gelas berbentuk U kecil', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-034', 'nama_barang' => 'Gelas Kimia Pyrex 250 ml', 'merk' => 'Iwaki', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 8, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-035', 'nama_barang' => 'Gelas Kimia Pyrex 50 ml', 'merk' => 'Iwaki', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-036', 'nama_barang' => 'Pembakar Spiritus', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 11, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-037', 'nama_barang' => 'Kalorimeter', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 4, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-038', 'nama_barang' => 'Steam Generator', 'merk' => 'Pasco', 'kategori_id' => 3, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2013-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-039', 'nama_barang' => 'Extech”K-Type Thermometer', 'merk' => 'Extech', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-040', 'nama_barang' => 'Asbes Kompor', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 9, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-041', 'nama_barang' => 'Tungku Kaki Tiga', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-042', 'nama_barang' => 'Termometer C-F', 'merk' => 'Corona', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-043', 'nama_barang' => 'Termometer C-F', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 6, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-044', 'nama_barang' => 'Gelas Cerek', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-045', 'nama_barang' => 'KIT Gelombang dan Termodinamika', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-046', 'nama_barang' => 'KIT Gelombang dan Termodinamika', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2018-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.20.999.6', 'nama_barang' => 'Kalorimeter PHM 300', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-048', 'nama_barang' => 'Pemanasan Listrik dan Pembangkit Uap ( Percobaan Kalorimeter)', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-049', 'nama_barang' => 'Compound Gauge', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => '3.08.01.13.999.231', 'nama_barang' => 'Infrared Thermometer', 'merk' => 'Tenmars TM-301', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2019-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-051', 'nama_barang' => 'Gelas Cerek Panjang', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-052', 'nama_barang' => 'Higrometer', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-053', 'nama_barang' => 'Manometer Open', 'merk' => 'Pudak Scientific', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 2, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2020-01-01', 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-054', 'nama_barang' => 'Sound Level Meter', 'merk' => 'Extech', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-055', 'nama_barang' => 'Elemen Pemanas', 'merk' => 'Intra', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-056', 'nama_barang' => 'Semprot Oil', 'merk' => null, 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => null, 'keterangan' => 'Lab PFS'],
            ['kode_barang' => 'THERMO-057', 'nama_barang' => 'Thermal Coductivity Apparatus', 'merk' => 'Pasco', 'kategori_id' => 1, 'ruangan_id' => 6, 'stok_baik' => 1, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0, 'tanggal_pengadaan' => '2006-01-01', 'keterangan' => 'Lab PFS'],
        ];

        foreach ($barangs as $item) {
            BarangInventaris::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
        }
    }
}
```
