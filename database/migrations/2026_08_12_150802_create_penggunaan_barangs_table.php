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
        Schema::create('penggunaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang_inventaris')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_digunakan')->default(1);
            $table->enum('kondisi_setelah', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['jadwal_id', 'barang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_barangs');
    }
};
