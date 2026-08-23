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
        Schema::create('kelas_praktikum_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->unique(['kelas_praktikum_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('kelas_praktikum_mahasiswa');

    }
};
