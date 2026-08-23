<?php

namespace Database\Seeders;

use App\Models\KelasPraktikum;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari kelas Fisika Dasar I - Kelas A
        $kelas = KelasPraktikum::where('nama_kelas', 'Fisika Dasar I - Kelas A')->first();
        
        if (!$kelas) {
            $this->command->error("Kelas 'Fisika Dasar I - Kelas A' tidak ditemukan. Pastikan seeder kelas sudah dijalankan.");
            return;
        }

        $this->command->info("Ditemukan Kelas: {$kelas->nama_kelas}. Memulai generate 15 mahasiswa...");

        $angkatan = date('Y');
        $prodi = 'Fisika';

        for ($i = 1; $i <= 15; $i++) {
            $nimStr = 'MHS' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $email = strtolower($nimStr) . '@test.com';

            // 2. Buat User Mahasiswa
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Mahasiswa Test ' . $i,
                    'password' => Hash::make('password123'), // Password standar
                    'role' => 'mahasiswa',
                    'is_active' => true,
                    'is_profile_completed' => true,
                ]
            );

            // 3. Buat Profil Mahasiswa
            Mahasiswa::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => '101' . $angkatan . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'jurusan' => $prodi,
                    'angkatan' => $angkatan,
                ]
            );

            // 4. Daftarkan (Enroll) ke Kelas Fisika Dasar I - Kelas A dengan status approved
            // Gunakan syncWithoutDetaching untuk menghindari duplikasi jika dijalankan ulang
            $kelas->mahasiswas()->syncWithoutDetaching([
                $user->id => [
                    'status' => 'approved',
                    'catatan_admin' => 'Otomatis di-approve oleh TestAbsensiSeeder',
                ]
            ]);
        }

        $this->command->info('Berhasil menambahkan 15 mahasiswa dan mendaftarkannya ke ' . $kelas->nama_kelas);
    }
}
