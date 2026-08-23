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
        Schema::create('submission_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_laporan_id')->constrained('tugas_laporans')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_laporan');
            $table->timestamp('tanggal_submit')->useCurrent();
            $table->enum('status', ['tepat_waktu', 'terlambat'])->default('tepat_waktu');
            $table->timestamps();

            $table->unique(['tugas_laporan_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_laporans');
    }
};
