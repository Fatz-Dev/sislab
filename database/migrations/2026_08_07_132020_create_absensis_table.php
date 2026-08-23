<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->nullOnDelete();
            $table->date('tanggal');
            $table->enum('tipe', ['dosen', 'laboran', 'mahasiswa']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('diabsen_oleh')->nullable()->constrained('users')->nullOnDelete();
            // diabsen_oleh: siapa yang menginput absensi ini (dosen mengabsen laboran, laboran mengabsen mahasiswa, dll)
            $table->enum('status_hadir', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->timestamps();

            $table->unique(['jadwal_id', 'user_id', 'tipe'], 'absensi_unique_per_jadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
