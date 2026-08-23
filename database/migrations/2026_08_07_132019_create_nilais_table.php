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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->foreignId('tugas_laporan_id')->nullable()->constrained('tugas_laporans')->nullOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['mahasiswa_id', 'tugas_laporan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
