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
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn(['ruangan_id', 'hari', 'jam_mulai', 'jam_selesai']);
        });
    }
};
