<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password default sama untuk seluruh user: 'password'
        $defaultPassword = Hash::make('password');

        // 1. Admin
        $users = [
            [
                'name' => 'Admin Laboratorium Fisika',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'nip_nim' => 'ADMIN001',
            ],
            [
                'name' => 'Mahasiswa Test Utama',
                'email' => 'mahasiswa@gmail.com',
                'role' => 'mahasiswa',
                'nip_nim' => 'MHS001',
                'angkatan' => '2023',
                'jurusan' => 'Pendidikan Fisika',
            ],
        ];

        // 2. Dosen Pengampu Laboratorium Fisika
        $dosens = [
            ['name' => 'Dr. M. Syukri, M.Ed.', 'email' => 'dosen1@gmail.com', 'nip_nim' => 'DOSEN001', 'jabatan' => 'Lektor Kepala'],
            ['name' => 'Dr. Suhrawardi, M.Sc.', 'email' => 'dosen2@gmail.com', 'nip_nim' => 'DOSEN002', 'jabatan' => 'Lektor'],
            ['name' => 'Dr. Fitria Herliana, M.Pd.', 'email' => 'dosen3@gmail.com', 'nip_nim' => 'DOSEN003', 'jabatan' => 'Lektor'],
            ['name' => 'Dr. Elisa, M.Si.', 'email' => 'dosen4@gmail.com', 'nip_nim' => 'DOSEN004', 'jabatan' => 'Asisten Ahli'],
            ['name' => 'Dr. Rini Safitri, M.Si.', 'email' => 'dosen5@gmail.com', 'nip_nim' => 'DOSEN005', 'jabatan' => 'Lektor Kepala'],
        ];

        foreach ($dosens as $d) {
            $users[] = [
                'name' => $d['name'],
                'email' => $d['email'],
                'role' => 'dosen',
                'nip_nim' => $d['nip_nim'],
                'jabatan_akademik' => $d['jabatan'],
            ];
        }

        // 3. Laboran per Ruangan Laboratorium Fisika
        $laborans = [
            ['name' => 'Anwar (Pengelola BMN Lab)', 'email' => 'laboran1@gmail.com', 'nip_nim' => 'LAB001', 'spesialisasi' => 'Administrasi Laboratorium'],
            ['name' => 'Laboran Lab Mekanika', 'email' => 'laboran2@gmail.com', 'nip_nim' => 'LAB002', 'spesialisasi' => 'Laboratorium Mekanika'],
            ['name' => 'Laboran Lab Listrik & Magnet', 'email' => 'laboran3@gmail.com', 'nip_nim' => 'LAB003', 'spesialisasi' => 'Laboratorium Listrik dan Magnet'],
            ['name' => 'Laboran Lab Fisika Dasar', 'email' => 'laboran4@gmail.com', 'nip_nim' => 'LAB004', 'spesialisasi' => 'Laboratorium Fisika Dasar'],
            ['name' => 'Laboran Lab Optik', 'email' => 'laboran5@gmail.com', 'nip_nim' => 'LAB005', 'spesialisasi' => 'Laboratorium Optik'],
            ['name' => 'Laboran Lab Termodinamika', 'email' => 'laboran6@gmail.com', 'nip_nim' => 'LAB006', 'spesialisasi' => 'Laboratorium Termodinamika'],
        ];

        foreach ($laborans as $l) {
            $users[] = [
                'name' => $l['name'],
                'email' => $l['email'],
                'role' => 'laboran',
                'nip_nim' => $l['nip_nim'],
                'spesialisasi_lab' => $l['spesialisasi'],
            ];
        }

        // 4. Mahasiswa per Ruangan (Minimal 15 Mahasiswa per Ruangan)
        $mahasiswaPerRuangan = [
            'Mekanika' => [
                ['name' => 'Ahmad Farhan', 'email' => 'ahmadfarhan@mhs.ar-raniry.ac.id', 'nim' => '210204001'],
                ['name' => 'Aulia Rahman', 'email' => 'auliarahman@mhs.ar-raniry.ac.id', 'nim' => '210204002'],
                ['name' => 'Budi Santoso', 'email' => 'budisantoso@mhs.ar-raniry.ac.id', 'nim' => '210204003'],
                ['name' => 'Cut Intan Permata', 'email' => 'cutintanpermata@mhs.ar-raniry.ac.id', 'nim' => '210204004'],
                ['name' => 'Dedi Kurniawan', 'email' => 'dedikurniawan@mhs.ar-raniry.ac.id', 'nim' => '210204005'],
                ['name' => 'Fajar Hidayat', 'email' => 'fajarhidayat@mhs.ar-raniry.ac.id', 'nim' => '210204006'],
                ['name' => 'Gita Gutawa', 'email' => 'gitagutawa@mhs.ar-raniry.ac.id', 'nim' => '210204007'],
                ['name' => 'Hafiz Pratama', 'email' => 'hafizpratama@mhs.ar-raniry.ac.id', 'nim' => '210204008'],
                ['name' => 'Irfan Hakim', 'email' => 'irfanhakim@mhs.ar-raniry.ac.id', 'nim' => '210204009'],
                ['name' => 'Jihan Fahira', 'email' => 'jihanfahira@mhs.ar-raniry.ac.id', 'nim' => '210204010'],
                ['name' => 'Khalid Basalamah', 'email' => 'khalidbasalamah@mhs.ar-raniry.ac.id', 'nim' => '210204011'],
                ['name' => 'Lestari Indah', 'email' => 'lestariindah@mhs.ar-raniry.ac.id', 'nim' => '210204012'],
                ['name' => 'Muhammad Rizky', 'email' => 'muhammadrizky@mhs.ar-raniry.ac.id', 'nim' => '210204013'],
                ['name' => 'Nadia Safira', 'email' => 'nadiasafira@mhs.ar-raniry.ac.id', 'nim' => '210204014'],
                ['name' => 'Oki Setiana', 'email' => 'okisetiana@mhs.ar-raniry.ac.id', 'nim' => '210204015'],
                ['name' => 'Putri Rahmawati', 'email' => 'putrirahmawati@mhs.ar-raniry.ac.id', 'nim' => '210204016'],
                ['name' => 'Rian Ardiansyah', 'email' => 'rianardiansyah@mhs.ar-raniry.ac.id', 'nim' => '210204017'],
                ['name' => 'Siti Sarah', 'email' => 'sitisarah@mhs.ar-raniry.ac.id', 'nim' => '210204018'],
            ],
            'Lismag' => [
                ['name' => 'Teuku Iskandar', 'email' => 'teukuiskandar@mhs.ar-raniry.ac.id', 'nim' => '210204019'],
                ['name' => 'Ulfa Maisarah', 'email' => 'ulfamaisarah@mhs.ar-raniry.ac.id', 'nim' => '210204020'],
                ['name' => 'Vina Panduwinata', 'email' => 'vinapanduwinata@mhs.ar-raniry.ac.id', 'nim' => '210204021'],
                ['name' => 'Wahyu Hidayat', 'email' => 'wahyuhidayat@mhs.ar-raniry.ac.id', 'nim' => '210204022'],
                ['name' => 'Yuni Shara', 'email' => 'yunishara@mhs.ar-raniry.ac.id', 'nim' => '210204023'],
                ['name' => 'Zainal Abidin', 'email' => 'zainalabidin@mhs.ar-raniry.ac.id', 'nim' => '210204024'],
                ['name' => 'Alif Akbar', 'email' => 'alifakbar@mhs.ar-raniry.ac.id', 'nim' => '210204025'],
                ['name' => 'Bunga Citra', 'email' => 'bungacitra@mhs.ar-raniry.ac.id', 'nim' => '210204026'],
                ['name' => 'Candra Wijaya', 'email' => 'candrawijaya@mhs.ar-raniry.ac.id', 'nim' => '210204027'],
                ['name' => 'Dian Sastrowardoyo', 'email' => 'diansastrowardoyo@mhs.ar-raniry.ac.id', 'nim' => '210204028'],
                ['name' => 'Eko Prasetyo', 'email' => 'ekoprasetyo@mhs.ar-raniry.ac.id', 'nim' => '210204029'],
                ['name' => 'Fina Melati', 'email' => 'finamelati@mhs.ar-raniry.ac.id', 'nim' => '210204030'],
                ['name' => 'Gilang Dirga', 'email' => 'gilangdirga@mhs.ar-raniry.ac.id', 'nim' => '210204031'],
                ['name' => 'Hesti Purwadinata', 'email' => 'hestipurwadinata@mhs.ar-raniry.ac.id', 'nim' => '210204032'],
                ['name' => 'Indra Bekti', 'email' => 'indrabekti@mhs.ar-raniry.ac.id', 'nim' => '210204033'],
                ['name' => 'Julia Perez', 'email' => 'juliaperez@mhs.ar-raniry.ac.id', 'nim' => '210204034'],
                ['name' => 'Kevin Julio', 'email' => 'kevinjulio@mhs.ar-raniry.ac.id', 'nim' => '210204035'],
                ['name' => 'Luna Maya', 'email' => 'lunamaya@mhs.ar-raniry.ac.id', 'nim' => '210204036'],
            ],
            'Fisdas' => [
                ['name' => 'Maia Estianty', 'email' => 'maiaestianty@mhs.ar-raniry.ac.id', 'nim' => '210204037'],
                ['name' => 'Nino Fernandez', 'email' => 'ninofernandez@mhs.ar-raniry.ac.id', 'nim' => '210204038'],
                ['name' => 'Olga Syahputra', 'email' => 'olgasyahputra@mhs.ar-raniry.ac.id', 'nim' => '210204039'],
                ['name' => 'Pevita Pearce', 'email' => 'pevitapearce@mhs.ar-raniry.ac.id', 'nim' => '210204040'],
                ['name' => 'Raffi Ahmad', 'email' => 'raffiahmad@mhs.ar-raniry.ac.id', 'nim' => '210204041'],
                ['name' => 'Syahrini Zaskia', 'email' => 'syahrinizaskia@mhs.ar-raniry.ac.id', 'nim' => '210204042'],
                ['name' => 'Titi Kamal', 'email' => 'titikamal@mhs.ar-raniry.ac.id', 'nim' => '210204043'],
                ['name' => 'Ussy Sulistiawaty', 'email' => 'ussysulistiawaty@mhs.ar-raniry.ac.id', 'nim' => '210204044'],
                ['name' => 'Vicky Prasetyo', 'email' => 'vickyprasetyo@mhs.ar-raniry.ac.id', 'nim' => '210204045'],
                ['name' => 'Wulan Guritno', 'email' => 'wulanguritno@mhs.ar-raniry.ac.id', 'nim' => '210204046'],
                ['name' => 'Yuki Kato', 'email' => 'yukikato@mhs.ar-raniry.ac.id', 'nim' => '210204047'],
                ['name' => 'Zaskia Gotik', 'email' => 'zaskiagotik@mhs.ar-raniry.ac.id', 'nim' => '210204048'],
                ['name' => 'Adipati Dolken', 'email' => 'adipatidolken@mhs.ar-raniry.ac.id', 'nim' => '210204049'],
                ['name' => 'Baim Wong', 'email' => 'baimwong@mhs.ar-raniry.ac.id', 'nim' => '210204050'],
                ['name' => 'Chelsea Islan', 'email' => 'chelseaislan@mhs.ar-raniry.ac.id', 'nim' => '210204051'],
                ['name' => 'Dimas Anggara', 'email' => 'dimasanggara@mhs.ar-raniry.ac.id', 'nim' => '210204052'],
                ['name' => 'Eva Celia', 'email' => 'evacelia@mhs.ar-raniry.ac.id', 'nim' => '210204053'],
                ['name' => 'Fero Walandouw', 'email' => 'ferowalandouw@mhs.ar-raniry.ac.id', 'nim' => '210204054'],
            ],
            'Optik' => [
                ['name' => 'Gading Marten', 'email' => 'gadingmarten@mhs.ar-raniry.ac.id', 'nim' => '210204055'],
                ['name' => 'Herjunot Ali', 'email' => 'herjunotali@mhs.ar-raniry.ac.id', 'nim' => '210204056'],
                ['name' => 'Iko Uwais', 'email' => 'ikouwais@mhs.ar-raniry.ac.id', 'nim' => '210204057'],
                ['name' => 'Joe Taslim', 'email' => 'joetaslim@mhs.ar-raniry.ac.id', 'nim' => '210204058'],
                ['name' => 'Kiki Fatmala', 'email' => 'kikifatmala@mhs.ar-raniry.ac.id', 'nim' => '210204059'],
                ['name' => 'Lukman Sardi', 'email' => 'lukmansardi@mhs.ar-raniry.ac.id', 'nim' => '210204060'],
                ['name' => 'Marsha Timothy', 'email' => 'marshatimothy@mhs.ar-raniry.ac.id', 'nim' => '210204061'],
                ['name' => 'Nicholas Saputra', 'email' => 'nicholassaputra@mhs.ar-raniry.ac.id', 'nim' => '210204062'],
                ['name' => 'Olla Ramlan', 'email' => 'ollaramlan@mhs.ar-raniry.ac.id', 'nim' => '210204063'],
                ['name' => 'Prilly Latuconsina', 'email' => 'prillylatuconsina@mhs.ar-raniry.ac.id', 'nim' => '210204064'],
                ['name' => 'Reza Rahadian', 'email' => 'rezarahadian@mhs.ar-raniry.ac.id', 'nim' => '210204065'],
                ['name' => 'Slamet Rahardjo', 'email' => 'slametrahardjo@mhs.ar-raniry.ac.id', 'nim' => '210204066'],
                ['name' => 'Tara Basro', 'email' => 'tarabasro@mhs.ar-raniry.ac.id', 'nim' => '210204067'],
                ['name' => 'Undang Setiawan', 'email' => 'undangsetiawan@mhs.ar-raniry.ac.id', 'nim' => '210204068'],
                ['name' => 'Vino G. Bastian', 'email' => 'vinog.bastian@mhs.ar-raniry.ac.id', 'nim' => '210204069'],
                ['name' => 'Widyawati Sofia', 'email' => 'widyawatisofia@mhs.ar-raniry.ac.id', 'nim' => '210204070'],
                ['name' => 'Yayan Ruhian', 'email' => 'yayanruhian@mhs.ar-raniry.ac.id', 'nim' => '210204071'],
                ['name' => 'Zack Lee', 'email' => 'zacklee@mhs.ar-raniry.ac.id', 'nim' => '210204072'],
            ],
            'Thermo' => [
                ['name' => 'Ari Lasso', 'email' => 'arilasso@mhs.ar-raniry.ac.id', 'nim' => '210204073'],
                ['name' => 'Bams Samsons', 'email' => 'bamssamsons@mhs.ar-raniry.ac.id', 'nim' => '210204074'],
                ['name' => 'Cakra Khan', 'email' => 'cakrakhan@mhs.ar-raniry.ac.id', 'nim' => '210204075'],
                ['name' => 'Duta Sheila', 'email' => 'dutasheila@mhs.ar-raniry.ac.id', 'nim' => '210204076'],
                ['name' => 'Ebiet G. Ade', 'email' => 'ebietg.ade@mhs.ar-raniry.ac.id', 'nim' => '210204077'],
                ['name' => 'Fiersa Besari', 'email' => 'fiersabesari@mhs.ar-raniry.ac.id', 'nim' => '210204078'],
                ['name' => 'Glenn Fredly', 'email' => 'glennfredly@mhs.ar-raniry.ac.id', 'nim' => '210204079'],
                ['name' => 'Hedi Yunus', 'email' => 'hediyunus@mhs.ar-raniry.ac.id', 'nim' => '210204080'],
                ['name' => 'Iwan Fals', 'email' => 'iwanfals@mhs.ar-raniry.ac.id', 'nim' => '210204081'],
                ['name' => 'Judika Sihotang', 'email' => 'judikasihotang@mhs.ar-raniry.ac.id', 'nim' => '210204082'],
                ['name' => 'Kunto Aji', 'email' => 'kuntoaji@mhs.ar-raniry.ac.id', 'nim' => '210204083'],
                ['name' => 'Once Mekel', 'email' => 'oncemekel@mhs.ar-raniry.ac.id', 'nim' => '210204084'],
                ['name' => 'Pasha Ungu', 'email' => 'pashaungu@mhs.ar-raniry.ac.id', 'nim' => '210204085'],
                ['name' => 'Rizky Febian', 'email' => 'rizkyfebian@mhs.ar-raniry.ac.id', 'nim' => '210204086'],
                ['name' => 'Sammy Simorangkir', 'email' => 'sammysimorangkir@mhs.ar-raniry.ac.id', 'nim' => '210204087'],
                ['name' => 'Tulus Rusydi', 'email' => 'tulusrusydi@mhs.ar-raniry.ac.id', 'nim' => '210204088'],
                ['name' => 'Virgoun Tambunan', 'email' => 'virgountambunan@mhs.ar-raniry.ac.id', 'nim' => '210204089'],
                ['name' => 'Yovie Widianto', 'email' => 'yoviewidianto@mhs.ar-raniry.ac.id', 'nim' => '210204090'],
            ],
        ];

        foreach ($mahasiswaPerRuangan as $group => $mhsList) {
            foreach ($mhsList as $m) {
                $users[] = [
                    'name' => $m['name'],
                    'email' => $m['email'],
                    'role' => 'mahasiswa',
                    'nip_nim' => $m['nim'],
                    'angkatan' => '2023',
                    'jurusan' => 'Pendidikan Fisika',
                ];
            }
        }

        // Create all Users and their Profiles
        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_profile_completed' => true,
                ]
            );

            if ($user->role === 'dosen') {
                $user->dosenProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $userData['nip_nim'],
                        'jabatan_akademik' => $userData['jabatan_akademik'] ?? 'Dosen Tetap',
                    ]
                );
            } elseif ($user->role === 'laboran') {
                $user->laboranProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $userData['nip_nim'],
                        'spesialisasi_lab' => $userData['spesialisasi_lab'] ?? 'Fisika',
                    ]
                );
            } elseif ($user->role === 'mahasiswa') {
                $user->mahasiswaProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim' => $userData['nip_nim'],
                        'angkatan' => $userData['angkatan'] ?? '2023',
                        'jurusan' => $userData['jurusan'] ?? 'Pendidikan Fisika',
                    ]
                );
            }
        }
    }
}
