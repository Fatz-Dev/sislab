<?php

namespace Database\Seeders;

use App\Models\KelasPraktikum;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $semester = Semester::firstOrCreate(
            ['nama_semester' => 'Ganjil 2026/2027'],
            ['is_active' => true]
        );
        $semester->update(['is_active' => true]);

        // Pemetaan Kelas Praktikum sesuai 5 Ruangan Lab Praktikum Fisika
        // Diurutkan agar pemindahan laboran_id unik tidak bentrok di SQLite
        $kelasList = [
            [
                'id' => 5,
                'nama_kelas' => 'Praktikum Termodinamika (Kelas A)',
                'ruangan_id' => 6, // Laboratorium Termodinamika
                'dosen_email' => 'dosen5@gmail.com',
                'laboran_email' => 'laboran6@gmail.com',
                'hari' => 'Jumat',
                'jam_mulai' => '08:30:00',
                'jam_selesai' => '10:30:00',
                'group_mhs' => 'Thermo',
            ],
            [
                'id' => 4,
                'nama_kelas' => 'Praktikum Optika (Kelas A)',
                'ruangan_id' => 5, // Laboratorium Optik
                'dosen_email' => 'dosen4@gmail.com',
                'laboran_email' => 'laboran5@gmail.com',
                'hari' => 'Kamis',
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
                'group_mhs' => 'Optik',
            ],
            [
                'id' => 1,
                'nama_kelas' => 'Praktikum Fisika Dasar (Kelas A)',
                'ruangan_id' => 4, // Laboratorium Fisika Dasar (Multi Fungsi)
                'dosen_email' => 'dosen1@gmail.com',
                'laboran_email' => 'laboran4@gmail.com',
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'group_mhs' => 'Fisdas',
            ],
            [
                'id' => 2,
                'nama_kelas' => 'Praktikum Mekanika (Kelas A)',
                'ruangan_id' => 2, // Laboratorium Mekanika
                'dosen_email' => 'dosen2@gmail.com',
                'laboran_email' => 'laboran2@gmail.com',
                'hari' => 'Selasa',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
                'group_mhs' => 'Mekanika',
            ],
            [
                'id' => 3,
                'nama_kelas' => 'Praktikum Listrik dan Magnet (Kelas A)',
                'ruangan_id' => 3, // Laboratorium Listrik dan Magnet
                'dosen_email' => 'dosen3@gmail.com',
                'laboran_email' => 'laboran3@gmail.com',
                'hari' => 'Rabu',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
                'group_mhs' => 'Lismag',
            ],
        ];

        // Ambil test user mahasiswa utama
        $mainMhs = User::where('email', 'mahasiswa@gmail.com')->first();

        $groupNames = [
            'Mekanika' => ['Ahmad Farhan','Aulia Rahman','Budi Santoso','Cut Intan Permata','Dedi Kurniawan','Fajar Hidayat','Gita Gutawa','Hafiz Pratama','Irfan Hakim','Jihan Fahira','Khalid Basalamah','Lestari Indah','Muhammad Rizky','Nadia Safira','Oki Setiana','Putri Rahmawati','Rian Ardiansyah','Siti Sarah'],
            'Lismag' => ['Teuku Iskandar','Ulfa Maisarah','Vina Panduwinata','Wahyu Hidayat','Yuni Shara','Zainal Abidin','Alif Akbar','Bunga Citra','Candra Wijaya','Dian Sastrowardoyo','Eko Prasetyo','Fina Melati','Gilang Dirga','Hesti Purwadinata','Indra Bekti','Julia Perez','Kevin Julio','Luna Maya'],
            'Fisdas' => ['Maia Estianty','Nino Fernandez','Olga Syahputra','Pevita Pearce','Raffi Ahmad','Syahrini Zaskia','Titi Kamal','Ussy Sulistiawaty','Vicky Prasetyo','Wulan Guritno','Yuki Kato','Zaskia Gotik','Adipati Dolken','Baim Wong','Chelsea Islan','Dimas Anggara','Eva Celia','Fero Walandouw'],
            'Optik' => ['Gading Marten','Herjunot Ali','Iko Uwais','Joe Taslim','Kiki Fatmala','Lukman Sardi','Marsha Timothy','Nicholas Saputra','Olla Ramlan','Prilly Latuconsina','Reza Rahadian','Slamet Rahardjo','Tara Basro','Undang Setiawan','Vino G. Bastian','Widyawati Sofia','Yayan Ruhian','Zack Lee'],
            'Thermo' => ['Ari Lasso','Bams Samsons','Cakra Khan','Duta Sheila','Ebiet G. Ade','Fiersa Besari','Glenn Fredly','Hedi Yunus','Iwan Fals','Judika Sihotang','Kunto Aji','Once Mekel','Pasha Ungu','Rizky Febian','Sammy Simorangkir','Tulus Rusydi','Virgoun Tambunan','Yovie Widianto'],
        ];

        foreach ($kelasList as $k) {
            $dosen = User::where('email', $k['dosen_email'])->first() ?? User::where('role', 'dosen')->first();
            $laboran = User::where('email', $k['laboran_email'])->first() ?? User::where('role', 'laboran')->first();

            $kelas = KelasPraktikum::updateOrCreate(
                ['id' => $k['id']],
                [
                    'nama_kelas' => $k['nama_kelas'],
                    'semester_id' => $semester->id,
                    'ruangan_id' => $k['ruangan_id'],
                    'dosen_id' => $dosen->id,
                    'laboran_id' => $laboran->id,
                    'kapasitas' => 35,
                    'status' => 'open',
                    'hari' => $k['hari'],
                    'jam_mulai' => $k['jam_mulai'],
                    'jam_selesai' => $k['jam_selesai'],
                ]
            );

            // Daftarkan mahasiswa ke kelas (minimal 15 per ruangan/kelas)
            $enrollments = [];
            if ($mainMhs) {
                $enrollments[$mainMhs->id] = ['status' => 'approved', 'catatan_admin' => 'Mahasiswa Terdaftar'];
            }

            if (isset($groupNames[$k['group_mhs']])) {
                $roomStudents = User::whereIn('name', $groupNames[$k['group_mhs']])->get();
                foreach ($roomStudents as $mhs) {
                    $enrollments[$mhs->id] = ['status' => 'approved', 'catatan_admin' => 'Disetujui Laboran'];
                }
            }

            $kelas->mahasiswas()->syncWithoutDetaching($enrollments);
        }
    }
}
