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
        Schema::create('laporan_laborans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status_sop', ['dijalankan', 'dijalankan_sebagian', 'tidak_dijalankan'])->default('dijalankan');
            $table->enum('kelayakan_barang', ['semua_layak', 'ada_yang_rusak'])->default('semua_layak');
            $table->text('catatan_temuan')->nullable();
            $table->enum('status_admin', ['pending', 'reviewed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_laborans');
    }
};
