<?php

namespace Database\Seeders;

use App\Models\KelasPraktikum;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada semester aktif
        $semester = Semester::firstOrCreate(
            ['is_active' => true],
            [
                'nama_semester' => 'Ganjil 2026/2027',
            ]
        );

        // Ambil beberapa dosen dan laboran, jika tidak ada, gunakan default 1 atau seeder
        $dosenIds = User::where('role', 'dosen')->pluck('id')->toArray();
        $laboranIds = User::where('role', 'laboran')->pluck('id')->toArray();

        // Data dummy Kelas Praktikum
        $kelasData = [
            [
                'nama_kelas' => 'Fisika Dasar I - Kelas A',
                'kapasitas' => 30,
                'status' => 'open',
            ],
            [
                'nama_kelas' => 'Fisika Dasar I - Kelas B',
                'kapasitas' => 30,
                'status' => 'open',
            ],
            [
                'nama_kelas' => 'Elektronika Dasar - Kelas A',
                'kapasitas' => 25,
                'status' => 'closed',
            ],
            [
                'nama_kelas' => 'Mekanika Klasik - Kelas A',
                'kapasitas' => 20,
                'status' => 'draft',
            ],
            [
                'nama_kelas' => 'Termodinamika - Kelas C',
                'kapasitas' => 40,
                'status' => 'open',
            ]
        ];


        // Ambil ID dosen dan laboran yang valid (yang memiliki profil)
        $dosenIds = User::where('role', 'dosen')->whereHas('dosenProfile')->pluck('id')->toArray();
        $laboranIds = User::where('role', 'laboran')->whereHas('laboranProfile')->pluck('id')->toArray();

        foreach ($kelasData as $idx => $data) {
            // Karena kita sudah generate 5 Dosen dan 5 Laboran di UserSeeder,
            // kita bisa langsung memetakannya berdasarkan index ke kelas.
            // Gunakan modulo atau isset fallback untuk keamanan ekstra
            $dosenId = $dosenIds[$idx % count($dosenIds)];
            $laboranId = $laboranIds[$idx % count($laboranIds)];

            KelasPraktikum::updateOrCreate(
                ['nama_kelas' => $data['nama_kelas']],
                [
                    'semester_id' => $semester->id,
                    'dosen_id' => $dosenId,
                    'laboran_id' => $laboranId,
                    'kapasitas' => $data['kapasitas'],
                    'status' => $data['status'],
                ]
            );
        }
    }
}
