<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            ['nama_ruangan' => 'Lab Fisika Dasar', 'deskripsi' => 'Laboratorium untuk praktikum fisika dasar I dan II.'],
            ['nama_ruangan' => 'Lab Elektronika', 'deskripsi' => 'Laboratorium untuk praktikum elektronika dan instrumentasi.'],
            ['nama_ruangan' => 'Lab Optika', 'deskripsi' => 'Laboratorium untuk praktikum optika dan gelombang.'],
            ['nama_ruangan' => 'Lab Mekanika', 'deskripsi' => 'Laboratorium untuk praktikum mekanika dan termodinamika.'],
            ['nama_ruangan' => 'Lab Komputer Fisika', 'deskripsi' => 'Laboratorium komputasi dan simulasi fisika.'],
        ];

        foreach ($ruangans as $r) {
            Ruangan::firstOrCreate(['nama_ruangan' => $r['nama_ruangan']], $r);
        }
    }
}
