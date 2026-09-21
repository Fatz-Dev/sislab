<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
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
