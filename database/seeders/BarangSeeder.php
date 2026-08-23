<?php

namespace Database\Seeders;

use App\Models\BarangInventaris;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori tersedia
        $kategoris = [
            'Alat Ukur',
            'Alat Optik',
            'Alat Elektronik',
            'Alat Mekanik',
            'Bahan Habis Pakai',
            'Perangkat Komputer',
        ];

        foreach ($kategoris as $nama) {
            KategoriBarang::firstOrCreate(['nama_kategori' => $nama]);
        }

        $katUkur = KategoriBarang::where('nama_kategori', 'Alat Ukur')->first()->id;
        $katOptik = KategoriBarang::where('nama_kategori', 'Alat Optik')->first()->id;
        $katElektronik = KategoriBarang::where('nama_kategori', 'Alat Elektronik')->first()->id;
        $katMekanik = KategoriBarang::where('nama_kategori', 'Alat Mekanik')->first()->id;
        $katBahan = KategoriBarang::where('nama_kategori', 'Bahan Habis Pakai')->first()->id;
        $katKomputer = KategoriBarang::where('nama_kategori', 'Perangkat Komputer')->first()->id;

        $ruanganFD = Ruangan::where('nama_ruangan', 'Lab Fisika Dasar')->first()->id ?? 1;
        $ruanganEL = Ruangan::where('nama_ruangan', 'Lab Elektronika')->first()->id ?? 2;
        $ruanganOP = Ruangan::where('nama_ruangan', 'Lab Optika')->first()->id ?? 3;
        $ruanganMK = Ruangan::where('nama_ruangan', 'Lab Mekanika')->first()->id ?? 4;
        $ruanganKF = Ruangan::where('nama_ruangan', 'Lab Komputer Fisika')->first()->id ?? 5;

        $tanggalPengadaan = '2026-01-15';

        $barangs = [
            // Lab Fisika Dasar
            ['kode_barang' => 'FD-001', 'nama_barang' => 'Neraca Ohaus',         'merk' => 'Ohaus',        'kategori_id' => $katUkur,      'ruangan_id' => $ruanganFD, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 10, 'stok_rusak_ringan' => 2, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'FD-002', 'nama_barang' => 'Stopwatch Digital',     'merk' => 'Casio',        'kategori_id' => $katUkur,      'ruangan_id' => $ruanganFD, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 15, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 1],
            ['kode_barang' => 'FD-003', 'nama_barang' => 'Mistar Baja 100cm',    'merk' => 'Krisbow',      'kategori_id' => $katUkur,      'ruangan_id' => $ruanganFD, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 20, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'FD-004', 'nama_barang' => 'Jangka Sorong',        'merk' => 'Mitutoyo',     'kategori_id' => $katUkur,      'ruangan_id' => $ruanganFD, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 12, 'stok_rusak_ringan' => 3, 'stok_rusak_berat' => 1, 'stok_hilang' => 0],
            ['kode_barang' => 'FD-005', 'nama_barang' => 'Mikrometer Sekrup',    'merk' => 'Mitutoyo',     'kategori_id' => $katUkur,      'ruangan_id' => $ruanganFD, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 8,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],

            // Lab Elektronika
            ['kode_barang' => 'EL-001', 'nama_barang' => 'Multimeter Digital',    'merk' => 'Sanwa',        'kategori_id' => $katElektronik, 'ruangan_id' => $ruanganEL, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 12, 'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'EL-002', 'nama_barang' => 'Osiloskop',            'merk' => 'Tektronix',    'kategori_id' => $katElektronik, 'ruangan_id' => $ruanganEL, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 5,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0],
            ['kode_barang' => 'EL-003', 'nama_barang' => 'Function Generator',   'merk' => 'GW Instek',    'kategori_id' => $katElektronik, 'ruangan_id' => $ruanganEL, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 6,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'EL-004', 'nama_barang' => 'Power Supply DC',      'merk' => 'GW Instek',    'kategori_id' => $katElektronik, 'ruangan_id' => $ruanganEL, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 8,  'stok_rusak_ringan' => 2, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'EL-005', 'nama_barang' => 'Breadboard',           'merk' => 'Generic',      'kategori_id' => $katElektronik, 'ruangan_id' => $ruanganEL, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 20, 'stok_rusak_ringan' => 5, 'stok_rusak_berat' => 0, 'stok_hilang' => 2],

            // Lab Optika
            ['kode_barang' => 'OP-001', 'nama_barang' => 'Lensa Cembung Set',    'merk' => 'Leybold',      'kategori_id' => $katOptik,     'ruangan_id' => $ruanganOP, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 6,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'OP-002', 'nama_barang' => 'Prisma Kaca',          'merk' => 'Leybold',      'kategori_id' => $katOptik,     'ruangan_id' => $ruanganOP, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 8,  'stok_rusak_ringan' => 1, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'OP-003', 'nama_barang' => 'Laser Pointer Merah',  'merk' => 'Generic',      'kategori_id' => $katOptik,     'ruangan_id' => $ruanganOP, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 10, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 3],
            ['kode_barang' => 'OP-004', 'nama_barang' => 'Spektrometer',         'merk' => 'Phywe',        'kategori_id' => $katOptik,     'ruangan_id' => $ruanganOP, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 3,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],

            // Lab Mekanika
            ['kode_barang' => 'MK-001', 'nama_barang' => 'Pegas Spiral Set',     'merk' => 'Phywe',        'kategori_id' => $katMekanik,   'ruangan_id' => $ruanganMK, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 10, 'stok_rusak_ringan' => 2, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'MK-002', 'nama_barang' => 'Bandul Ayunan',        'merk' => 'Leybold',      'kategori_id' => $katMekanik,   'ruangan_id' => $ruanganMK, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 8,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 1],
            ['kode_barang' => 'MK-003', 'nama_barang' => 'Rel Udara (Air Track)','merk' => 'Pasco',        'kategori_id' => $katMekanik,   'ruangan_id' => $ruanganMK, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 3,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 1, 'stok_hilang' => 0],
            ['kode_barang' => 'MK-004', 'nama_barang' => 'Termometer Digital',   'merk' => 'Fluke',        'kategori_id' => $katUkur,      'ruangan_id' => $ruanganMK, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 6,  'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],

            // Lab Komputer Fisika
            ['kode_barang' => 'KF-001', 'nama_barang' => 'PC Desktop',           'merk' => 'Lenovo',       'kategori_id' => $katKomputer,  'ruangan_id' => $ruanganKF, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 20, 'stok_rusak_ringan' => 2, 'stok_rusak_berat' => 1, 'stok_hilang' => 0],
            ['kode_barang' => 'KF-002', 'nama_barang' => 'Monitor LED 24"',      'merk' => 'Samsung',      'kategori_id' => $katKomputer,  'ruangan_id' => $ruanganKF, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 20, 'stok_rusak_ringan' => 0, 'stok_rusak_berat' => 0, 'stok_hilang' => 0],
            ['kode_barang' => 'KF-003', 'nama_barang' => 'Keyboard + Mouse Set', 'merk' => 'Logitech',     'kategori_id' => $katKomputer,  'ruangan_id' => $ruanganKF, 'tanggal_pengadaan' => $tanggalPengadaan, 'stok_baik' => 18, 'stok_rusak_ringan' => 4, 'stok_rusak_berat' => 0, 'stok_hilang' => 1],
        ];

        foreach ($barangs as $b) {
            BarangInventaris::firstOrCreate(
                ['kode_barang' => $b['kode_barang']],
                $b
            );
        }
    }
}
