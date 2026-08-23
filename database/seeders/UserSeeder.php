<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'nip_nim' => 'ADMIN001',
            ],
            [
                'name' => 'Mahasiswa User',
                'email' => 'mahasiswa@gmail.com',
                'role' => 'mahasiswa',
                'nip_nim' => 'MHS001',
            ],
        ];

        // Tambahkan 5 Dosen
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "Dosen Fisika $i",
                'email' => "dosen{$i}@gmail.com",
                'role' => 'dosen',
                'nip_nim' => "DOSEN00{$i}",
            ];
        }

        // Tambahkan 5 Laboran
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "Laboran Lab $i",
                'email' => "laboran{$i}@gmail.com",
                'role' => 'laboran',
                'nip_nim' => "LAB00{$i}",
            ];
        }

        foreach ($users as $userData) {
            $nipNim = $userData['nip_nim'];
            
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_profile_completed' => true,
                ]
            );

            // Create profile based on role
            if ($user->role === 'dosen') {
                $user->dosenProfile()->firstOrCreate(['nip' => $nipNim], [
                    'jabatan_akademik' => 'Dosen Tetap'
                ]);
            } elseif ($user->role === 'laboran') {
                $user->laboranProfile()->firstOrCreate(['nip' => $nipNim], [
                    'spesialisasi_lab' => 'Fisika Dasar'
                ]);
            } elseif ($user->role === 'mahasiswa') {
                $user->mahasiswaProfile()->firstOrCreate(['nim' => $nipNim], [
                    'angkatan' => '2023',
                    'jurusan' => 'Fisika'
                ]);
            }
        }
    }
}
